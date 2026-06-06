<?php

class AdminListingController {
    public function index(): void {
        Auth::requireAdmin();
        $status  = $_GET['status'] ?? null;
        $status  = in_array($status, ['pending','published','rejected','suspended','draft'], true) ? $status : null;
        $listings = Listing::allForAdmin($status);
        $counts   = Listing::countByStatus();
        $title    = 'Listings';
        ob_start();
        require APP_PATH . '/Views/admin/listings/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function approve(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        if ($listing) {
            Listing::approve((int) $id);
            $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" approved and published.";
        }
        header('Location: /admin/listings?status=pending');
        exit;
    }

    public function reject(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        $reason  = trim($_POST['reason'] ?? '');
        if ($listing && $reason !== '') {
            Listing::reject((int) $id, $reason);
            $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" rejected.";
        }
        header('Location: /admin/listings?status=pending');
        exit;
    }

    public function suspend(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        if ($listing) {
            Listing::suspend((int) $id);
            $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" suspended.";
        }
        header('Location: /admin/listings');
        exit;
    }
}
