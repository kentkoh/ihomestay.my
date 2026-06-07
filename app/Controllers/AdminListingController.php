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

    public function feature(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $duration = $_POST['duration'] ?? 'forever';
        $until    = null;
        if ($duration === '7')  $until = date('Y-m-d H:i:s', strtotime('+7 days'));
        if ($duration === '14') $until = date('Y-m-d H:i:s', strtotime('+14 days'));
        if ($duration === '30') $until = date('Y-m-d H:i:s', strtotime('+30 days'));
        if ($duration === 'custom' && !empty($_POST['custom_date'])) {
            $until = date('Y-m-d 23:59:59', strtotime($_POST['custom_date']));
        }
        Listing::feature((int) $id, $until);
        $_SESSION['flash']['success'] = 'Listing marked as featured.';
        header('Location: /admin/listings?status=published');
        exit;
    }

    public function unfeature(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        Listing::unfeature((int) $id);
        $_SESSION['flash']['success'] = 'Listing removed from featured.';
        header('Location: /admin/listings?status=published');
        exit;
    }
}
