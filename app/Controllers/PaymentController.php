<?php

class PaymentController {
    private FeaturedPackage $packageModel;
    private Payment         $paymentModel;
    private Listing         $listingModel;

    private string $apiKey;
    private string $collectionId;
    private string $xSigKey;
    private string $baseUrl;

    public function __construct() {
        $this->packageModel = new FeaturedPackage();
        $this->paymentModel = new Payment();
        $this->listingModel = new Listing();

        $this->apiKey       = env('BILLPLZ_API_KEY', '');
        $this->collectionId = env('BILLPLZ_COLLECTION_ID', '');
        $this->xSigKey      = env('BILLPLZ_X_SIGNATURE', '');
        $this->baseUrl      = rtrim(env('APP_URL', 'https://new.ihomestay.my'), '/');
    }

    // GET /feature/{listingId}
    public function showFeaturePage(int $listingId): void {
        Auth::requireLogin();

        $listing = $this->listingModel->findById($listingId);

        if (!$listing || (int) $listing['owner_id'] !== (int) Auth::user()['id']) {
            header('Location: /owner/listings');
            exit;
        }

        if ($listing['status'] !== 'published') {
            $_SESSION['flash']['danger'] = 'Only published listings can be featured.';
            header('Location: /owner/listings');
            exit;
        }

        $owner = Auth::user();
        if (($owner['verification_status'] ?? '') !== 'verified') {
            $_SESSION['flash']['warning'] = 'Featured listings are available to Verified Hosts only. Get verified first to unlock this feature.';
            header('Location: /owner/profile');
            exit;
        }

        $packages     = $this->packageModel->active();
        $packageModel = $this->packageModel;
        $pageTitle    = 'Feature Your Listing';

        ob_start();
        require APP_PATH . '/Views/public/feature-listing.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    // POST /feature/{listingId}/checkout
    public function checkout(int $listingId): void {
        Auth::requireLogin();
        CSRF::verify();

        $listing = $this->listingModel->findById($listingId);

        if (!$listing || (int) $listing['owner_id'] !== (int) Auth::user()['id']) {
            header('Location: /owner/listings');
            exit;
        }

        if ($listing['status'] !== 'published') {
            $_SESSION['flash']['danger'] = 'Only published listings can be featured.';
            header('Location: /owner/listings');
            exit;
        }

        if ((Auth::user()['verification_status'] ?? '') !== 'verified') {
            $_SESSION['flash']['warning'] = 'Featured listings are available to Verified Hosts only.';
            header('Location: /owner/profile');
            exit;
        }

        $packageId = (int) ($_POST['package_id'] ?? 0);
        $package   = $this->packageModel->findById($packageId);

        if (!$package || !$package['is_active']) {
            $_SESSION['flash']['danger'] = 'Invalid package selected.';
            header('Location: /feature/' . $listingId);
            exit;
        }

        $owner       = Auth::user();
        $price       = $this->packageModel->effectivePrice($package);
        $amountCents = (int) round($price * 100);

        // Create payment record first (without bill_id)
        $paymentId = $this->paymentModel->create([
            'owner_id'     => $owner['id'],
            'listing_id'   => $listingId,
            'package_id'   => $packageId,
            'amount'       => $price,
            'duration_days'=> $package['days'],
        ]);

        // Create BillPlz bill
        $callbackUrl = $this->baseUrl . '/payment/callback';
        $redirectUrl = $this->baseUrl . '/payment/return';

        $fields = [
            'collection_id'   => $this->collectionId,
            'email'           => $owner['email'],
            'name'            => $owner['name'],
            'amount'          => $amountCents,
            'description'     => 'Featured Listing: ' . mb_substr($listing['title'], 0, 100),
            'callback_url'    => $callbackUrl,
            'redirect_url'    => $redirectUrl,
            'reference_1_label' => 'Payment ID',
            'reference_1'     => (string) $paymentId,
            'reference_2_label' => 'Package',
            'reference_2'     => $package['label'],
        ];

        $result = $this->createBillplzBill($fields);

        if (!$result || empty($result['url'])) {
            $this->paymentModel->markFailed($paymentId);
            $_SESSION['flash']['danger'] = 'Payment gateway error. Please try again or contact support.';
            header('Location: /feature/' . $listingId);
            exit;
        }

        // Save bill_id
        $this->paymentModel->updateBillId($paymentId, $result['id']);

        // Redirect to BillPlz payment page
        header('Location: ' . $result['url']);
        exit;
    }

    // POST /payment/callback  — BillPlz server-to-server callback
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

        $payment = $this->paymentModel->findByBillId($billId);

        if (!$payment) {
            http_response_code(404);
            echo 'Payment not found';
            exit;
        }

        if ($payment['status'] === 'paid') {
            http_response_code(200);
            echo 'OK';
            exit;
        }

        if ($paid) {
            $this->paymentModel->markPaid($payment['id'], $paidAt);
            $this->activateFeaturedListing($payment);
        } else {
            $this->paymentModel->markFailed($payment['id']);
        }

        http_response_code(200);
        echo 'OK';
        exit;
    }

    // GET /payment/return  — redirect after BillPlz payment (user-facing)
    public function returnPage(): void {
        $billplz = $_GET['billplz'] ?? [];
        $paid    = false;
        $payment = null;

        if (!empty($billplz) && $this->verifySignature($billplz)) {
            $paid    = ($billplz['paid'] ?? '') === 'true';
            $billId  = $billplz['id'] ?? '';
            $payment = $this->paymentModel->findByBillId($billId);

            // If callback hasn't fired yet but redirect says paid, activate now
            if ($paid && $payment && $payment['status'] !== 'paid') {
                $paidAt = $billplz['paid_at'] ?? date('Y-m-d H:i:s');
                $this->paymentModel->markPaid($payment['id'], $paidAt);
                $this->activateFeaturedListing($payment);
                $payment['status'] = 'paid';
            }
        }

        $listing = $payment ? $this->listingModel->findById($payment['listing_id']) : null;
        $pageTitle = $paid ? 'Payment Successful' : 'Payment Unsuccessful';

        ob_start();
        if ($paid) {
            require APP_PATH . '/Views/payment/success.php';
        } else {
            require APP_PATH . '/Views/payment/failed.php';
        }
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function activateFeaturedListing(array $payment): void {
        $db = Database::get();
        $db->prepare("
            UPDATE listings
            SET is_featured = 1,
                featured_until = DATE_ADD(NOW(), INTERVAL ? DAY)
            WHERE id = ?
        ")->execute([$payment['duration_days'], $payment['listing_id']]);
    }

    private function createBillplzBill(array $fields): ?array {
        $postBody = http_build_query($fields);
        $auth     = base64_encode($this->apiKey . ':');

        $context = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => implode("\r\n", [
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . $auth,
                    'Content-Length: ' . strlen($postBody),
                ]),
                'content'       => $postBody,
                'timeout'       => 30,
                'ignore_errors' => true,
            ],
            'ssl' => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ],
        ]);

        $response = @file_get_contents('https://www.billplz.com/api/v3/bills', false, $context);

        if ($response === false) {
            error_log('BillPlz: request failed');
            return null;
        }

        // $http_response_header is set by file_get_contents automatically
        $statusLine = $http_response_header[0] ?? '';
        preg_match('/HTTP\/\S+\s+(\d+)/', $statusLine, $m);
        $httpCode = (int) ($m[1] ?? 0);

        if ($httpCode !== 200) {
            error_log("BillPlz HTTP $httpCode: $response");
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
