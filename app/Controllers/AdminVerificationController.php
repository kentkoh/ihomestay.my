<?php

class AdminVerificationController {
    private VerificationRequest $vrModel;

    public function __construct() {
        $this->vrModel = new VerificationRequest();
    }

    // GET /admin/verifications
    public function index(): void {
        Auth::requireAdmin();

        $requests = $this->vrModel->all();

        $counts = [
            'pending_review' => 0,
            'approved'       => 0,
            'rejected'       => 0,
            'pending_payment'=> 0,
        ];
        foreach ($requests as $r) {
            if (isset($counts[$r['status']])) $counts[$r['status']]++;
        }

        $title = 'Verifications';
        ob_start();
        require APP_PATH . '/Views/admin/verifications/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    // GET /admin/verifications/{id}/document
    public function streamDocument(int $id): void {
        Auth::requireAdmin();
        $this->streamFile($id, 'document_path');
    }

    // GET /admin/verifications/{id}/selfie
    public function streamSelfie(int $id): void {
        Auth::requireAdmin();
        $this->streamFile($id, 'selfie_path');
    }

    // POST /admin/verifications/{id}/approve
    public function approve(int $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $vr = $this->vrModel->findById($id);

        if (!$vr || $vr['status'] !== 'pending_review') {
            $_SESSION['flash']['danger'] = 'Cannot approve this application.';
            header('Location: /admin/verifications');
            exit;
        }

        $notes   = trim($_POST['admin_notes'] ?? '');
        $adminId = (int) Auth::user()['id'];

        $this->vrModel->approve($id, $adminId, $notes);

        // Update user verification_status
        $db = Database::get();
        $db->prepare("
            UPDATE users SET verification_status = 'verified', updated_at = NOW()
            WHERE id = ?
        ")->execute([$vr['owner_id']]);

        // If promo eligible and a listing was selected — activate 30-day featured
        if ($vr['promo_eligible'] && $vr['selected_listing_id'] && !$vr['featured_activated']) {
            $db->prepare("
                UPDATE listings
                SET is_featured = 1,
                    featured_until = DATE_ADD(NOW(), INTERVAL 30 DAY)
                WHERE id = ? AND owner_id = ?
            ")->execute([$vr['selected_listing_id'], $vr['owner_id']]);

            $this->vrModel->markFeaturedActivated($id);
        }

        $_SESSION['flash']['success'] = 'Verification approved. Owner is now a Verified Host.';
        header('Location: /admin/verifications');
        exit;
    }

    // POST /admin/verifications/{id}/reject
    public function reject(int $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $vr = $this->vrModel->findById($id);

        if (!$vr || !in_array($vr['status'], ['pending_review','pending_payment'])) {
            $_SESSION['flash']['danger'] = 'Cannot reject this application.';
            header('Location: /admin/verifications');
            exit;
        }

        $notes   = trim($_POST['admin_notes'] ?? '');
        $adminId = (int) Auth::user()['id'];

        $this->vrModel->reject($id, $adminId, $notes);

        $_SESSION['flash']['success'] = 'Application rejected.';
        header('Location: /admin/verifications');
        exit;
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function streamFile(int $id, string $field): void {
        $vr = $this->vrModel->findById($id);

        if (!$vr || empty($vr[$field])) {
            http_response_code(404);
            echo 'File not found';
            exit;
        }

        $path = STORAGE_PATH . '/verifications/' . $vr[$field];

        if (!file_exists($path)) {
            http_response_code(404);
            echo 'File not found';
            exit;
        }

        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'pdf'        => 'application/pdf',
            'jpg','jpeg' => 'image/jpeg',
            'png'        => 'image/png',
            'webp'       => 'image/webp',
            default      => 'application/octet-stream',
        };

        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
