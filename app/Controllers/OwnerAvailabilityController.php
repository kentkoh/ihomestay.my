<?php

class OwnerAvailabilityController {

    public function index(string $listingId): void {
        Auth::requireOwner();
        $listing    = $this->ownerListing((int) $listingId);
        $blocked    = ListingBlockedDate::forListing((int) $listingId, 6);
        $pageTitle  = 'Availability — ' . $listing['title'];
        ob_start();
        require APP_PATH . '/Views/owner/listings/availability.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function toggle(string $listingId): void {
        Auth::requireOwner();
        CSRF::verify();
        $this->ownerListing((int) $listingId);

        $date = trim($_POST['date'] ?? '');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date < date('Y-m-d')) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid date']);
            exit;
        }

        ob_clean();
        if (ListingBlockedDate::isBlocked((int) $listingId, $date)) {
            ListingBlockedDate::unblockDate((int) $listingId, $date);
            echo json_encode(['status' => 'unblocked']);
        } else {
            ListingBlockedDate::blockDate((int) $listingId, $date, 'manual');
            echo json_encode(['status' => 'blocked']);
        }
        exit;
    }

    public function saveIcal(string $listingId): void {
        Auth::requireOwner();
        CSRF::verify();
        $this->ownerListing((int) $listingId);

        $url = trim($_POST['ical_url'] ?? '');
        if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) {
            $_SESSION['flash']['danger'] = 'Invalid iCal URL.';
            header("Location: /owner/listings/$listingId/availability");
            exit;
        }

        Database::get()->prepare(
            "UPDATE listings SET ical_import_url = ?, updated_at = NOW() WHERE id = ?"
        )->execute([$url ?: null, (int) $listingId]);

        if ($url) {
            $ok = ListingBlockedDate::syncFromIcal((int) $listingId, $url);
            $_SESSION['flash'][$ok ? 'success' : 'danger'] = $ok
                ? 'iCal URL saved and synced successfully.'
                : 'iCal URL saved but sync failed — check the URL is publicly accessible.';
        } else {
            ListingBlockedDate::clearIcalBlocks((int) $listingId);
            $_SESSION['flash']['success'] = 'iCal URL removed.';
        }

        header("Location: /owner/listings/$listingId/availability");
        exit;
    }

    public function syncIcal(string $listingId): void {
        Auth::requireOwner();
        CSRF::verify();
        $listing = $this->ownerListing((int) $listingId);

        if (empty($listing['ical_import_url'])) {
            $_SESSION['flash']['danger'] = 'No iCal URL saved for this listing.';
            header("Location: /owner/listings/$listingId/availability");
            exit;
        }

        $ok = ListingBlockedDate::syncFromIcal((int) $listingId, $listing['ical_import_url']);
        $_SESSION['flash'][$ok ? 'success' : 'danger'] = $ok
            ? 'Calendar synced successfully.'
            : 'Sync failed — check the iCal URL is still valid.';

        header("Location: /owner/listings/$listingId/availability");
        exit;
    }

    private function ownerListing(int $id): array {
        $listing = Listing::findById($id);
        if (!$listing || (int) $listing['owner_id'] !== Auth::id()) {
            http_response_code(403);
            exit('Access denied.');
        }
        return $listing;
    }
}
