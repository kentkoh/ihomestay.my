<?php

class VerificationController {
    private VerificationRequest $vrModel;
    private Listing $listingModel;

    private string $apiKey;
    private string $collectionId;
    private string $xSigKey;
    private string $baseUrl;

    const YEARLY_PRICE = 49.00;
    const PROMO_WINDOW = 7200; // 2 hours in seconds

    public function __construct() {
        $this->vrModel      = new VerificationRequest();
        $this->listingModel = new Listing();

        $this->apiKey       = env('BILLPLZ_API_KEY', '');
        $this->collectionId = env('BILLPLZ_COLLECTION_ID', '');
        $this->xSigKey      = env('BILLPLZ_X_SIGNATURE', '');
        $this->baseUrl      = rtrim(env('APP_URL', 'https://new.ihomestay.my'), '/');
    }

    // GET /get-verified
    public function showPage(): void {
        // Start promo timer for this session on first visit
        if (!isset($_SESSION['verify_promo_start'])) {
            $_SESSION['verify_promo_start'] = time();
        }
        $promoEndsAt = $_SESSION['verify_promo_start'] + self::PROMO_WINDOW;
        $promoActive = time() < $promoEndsAt;

        $yearlyPrice = self::YEARLY_PRICE;
        $pageTitle   = 'Become a Verified Host';

        ob_start();
        require APP_PATH . '/Views/public/get-verified.php';
        $content = ob_get_clean();

        // Mobile sticky CTA
        ob_start();
        require APP_PATH . '/Views/public/get-verified-sticky.php';
        $stickyBar = ob_get_clean();

        require APP_PATH . '/Views/layouts/main.php';
    }

    // GET /owner/verify
    public function showApplyForm(): void {
        Auth::requireOwner();

        $owner = Auth::user();

        if ($owner['verification_status'] === 'verified') {
            $_SESSION['flash']['info'] = 'You are already a Verified Host!';
            header('Location: /owner/dashboard');
            exit;
        }

        // Check if there's already a pending/approved application
        $existing = $this->vrModel->pendingForOwner($owner['id']);
        if ($existing && in_array($existing['status'], ['pending_payment','pending_review','approved'])) {
            $existingApp = $existing;
            $pageTitle   = 'Verification Application';
            ob_start();
            require APP_PATH . '/Views/owner/verify-status.php';
            $content = ob_get_clean();
            require APP_PATH . '/Views/layouts/main.php';
            return;
        }

        // Start promo timer if not started
        if (!isset($_SESSION['verify_promo_start'])) {
            $_SESSION['verify_promo_start'] = time();
        }
        $promoEndsAt = $_SESSION['verify_promo_start'] + self::PROMO_WINDOW;
        $promoActive = time() < $promoEndsAt;

        // Get published listings for free featured selection
        $allListings = Listing::byOwner($owner['id']);
        $publishedListings = array_filter($allListings, fn($l) => $l['status'] === 'published');

        $yearlyPrice = self::YEARLY_PRICE;
        $pageTitle   = 'Apply for Verification';

        ob_start();
        require APP_PATH . '/Views/owner/verify-apply.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    // POST /owner/verify
    public function submitApplication(): void {
        Auth::requireOwner();
        CSRF::verify();

        $owner = Auth::user();

        if ($owner['verification_status'] === 'verified') {
            header('Location: /owner/dashboard');
            exit;
        }

        $requestType = in_array($_POST['request_type'] ?? '', ['individual','company'])
            ? $_POST['request_type']
            : 'individual';

        $selectedListingId = (int) ($_POST['selected_listing_id'] ?? 0) ?: null;

        // Validate listing ownership if one was selected
        if ($selectedListingId) {
            $listing = $this->listingModel->findById($selectedListingId);
            if (!$listing || (int) $listing['owner_id'] !== (int) $owner['id'] || $listing['status'] !== 'published') {
                $selectedListingId = null;
            }
        }

        // Handle document upload (required)
        if (empty($_FILES['document']['name'])) {
            $_SESSION['flash']['danger'] = 'Please upload your document (IC or SSM).';
            header('Location: /owner/verify');
            exit;
        }

        $docPath = $this->uploadFile($_FILES['document'], $owner['id'], 'document');
        if (!$docPath) {
            $_SESSION['flash']['danger'] = 'Invalid file. Please upload a JPG, PNG, or PDF under 5 MB.';
            header('Location: /owner/verify');
            exit;
        }

        // Handle optional selfie upload
        $selfiePath = null;
        if (!empty($_FILES['selfie']['name'])) {
            $selfiePath = $this->uploadFile($_FILES['selfie'], $owner['id'], 'selfie');
        }

        // Determine promo eligibility
        $promoEligible = isset($_SESSION['verify_promo_start'])
            && (time() - $_SESSION['verify_promo_start']) < self::PROMO_WINDOW;

        // Create verification_request record
        $vrId = $this->vrModel->create([
            'owner_id'            => $owner['id'],
            'request_type'        => $requestType,
            'document_path'       => $docPath,
            'selfie_path'         => $selfiePath,
            'selected_listing_id' => $selectedListingId,
            'promo_eligible'      => $promoEligible,
            'amount'              => self::YEARLY_PRICE,
        ]);

        // Create BillPlz bill
        $amountCents = (int) round(self::YEARLY_PRICE * 100);
        $callbackUrl = $this->baseUrl . '/payment/verify-callback';
        $redirectUrl = $this->baseUrl . '/payment/verify-return';

        $fields = [
            'collection_id'   => $this->collectionId,
            'email'           => $owner['email'],
            'name'            => $owner['name'],
            'amount'          => $amountCents,
            'description'     => 'iHomestay Verified Host — Annual Membership',
            'callback_url'    => $callbackUrl,
            'redirect_url'    => $redirectUrl,
            'reference_1_label' => 'Verification ID',
            'reference_1'     => (string) $vrId,
            'reference_2_label' => 'Type',
            'reference_2'     => $requestType,
        ];

        $result = $this->createBillplzBill($fields);

        if (!$result || empty($result['url'])) {
            $this->vrModel->markFailed($vrId);
            $_SESSION['flash']['danger'] = 'Payment gateway error. Please try again.';
            header('Location: /owner/verify');
            exit;
        }

        $this->vrModel->updateBillId($vrId, $result['id']);

        header('Location: ' . $result['url']);
        exit;
    }

    // POST /payment/verify-callback — server-to-server
    public function callback(): void {
        $billplz = $_POST['billplz'] ?? [];

        if (!$this->verifySignature($billplz)) {
            http_response_code(400);
            echo 'Invalid signature';
            exit;
        }

        $billId = $billplz['id'] ?? '';
        $paid   = ($billplz['paid'] ?? '') === 'true';
        $paidAt = $billplz['paid_at'] ?? date('Y-m-d H:i:s');

        $vr = $this->vrModel->findByBillId($billId);

        if (!$vr) {
            http_response_code(404);
            echo 'Not found';
            exit;
        }

        if ($vr['status'] !== 'pending_payment') {
            http_response_code(200);
            echo 'OK';
            exit;
        }

        if ($paid) {
            $this->vrModel->markPaid($vr['id'], $paidAt);
        } else {
            $this->vrModel->markFailed($vr['id']);
        }

        http_response_code(200);
        echo 'OK';
        exit;
    }

    // GET /payment/verify-return — user redirect after BillPlz
    public function returnPage(): void {
        $billplz = $_GET['billplz'] ?? [];
        $paid    = false;
        $vr      = null;

        if (!empty($billplz) && $this->verifySignature($billplz)) {
            $paid   = ($billplz['paid'] ?? '') === 'true';
            $billId = $billplz['id'] ?? '';
            $vr     = $this->vrModel->findByBillId($billId);

            if ($paid && $vr && $vr['status'] === 'pending_payment') {
                $paidAt = $billplz['paid_at'] ?? date('Y-m-d H:i:s');
                $this->vrModel->markPaid($vr['id'], $paidAt);
                $vr['status'] = 'pending_review';
            }
        }

        $pageTitle = $paid ? 'Payment Successful' : 'Payment Unsuccessful';

        ob_start();
        if ($paid) {
            require APP_PATH . '/Views/payment/verify-success.php';
        } else {
            require APP_PATH . '/Views/payment/verify-failed.php';
        }
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function uploadFile(array $file, int $ownerId, string $prefix): ?string {
        $allowed  = ['image/jpeg','image/png','image/webp','application/pdf'];
        $maxBytes = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowed, true) || $file['size'] > $maxBytes || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $prefix . '_' . uniqid() . '.' . $ext;
        $dir      = STORAGE_PATH . '/verifications/' . $ownerId;

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        move_uploaded_file($file['tmp_name'], $dir . '/' . $filename);
        return $ownerId . '/' . $filename;
    }

    private function createBillplzBill(array $fields): ?array {
        $postBody = http_build_query($fields);
        $auth     = base64_encode($this->apiKey . ':');

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => implode("\r\n", [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . $auth,
                    'Content-Length: ' . strlen($postBody),
                ]),
                'content'       => $postBody,
                'timeout'       => 30,
                'ignore_errors' => true,
            ],
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
        ]);

        $response = @file_get_contents('https://www.billplz.com/api/v3/bills', false, $context);

        if ($response === false) {
            error_log('BillPlz verify: request failed');
            return null;
        }

        $statusLine = $http_response_header[0] ?? '';
        preg_match('/HTTP\/\S+\s+(\d+)/', $statusLine, $m);
        $httpCode = (int) ($m[1] ?? 0);

        if ($httpCode !== 200) {
            error_log("BillPlz verify HTTP $httpCode: $response");
            return null;
        }

        $data = json_decode($response, true);
        return $data ?: null;
    }

    private function verifySignature(array $billplz): bool {
        $signature = $billplz['x_signature'] ?? '';
        if (!$signature) return false;

        $data = $billplz;
        unset($data['x_signature']);
        ksort($data);

        $parts = [];
        foreach ($data as $key => $val) {
            $parts[] = 'billplz[' . $key . ']|' . $val;
        }

        $computed = hash_hmac('sha256', implode('&', $parts), $this->xSigKey);
        return hash_equals($computed, $signature);
    }
}
