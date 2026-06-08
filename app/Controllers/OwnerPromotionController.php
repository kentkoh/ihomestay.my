<?php

class OwnerPromotionController {

    public function index(string $listingId): void {
        Auth::requireOwner();
        $listing    = $this->ownerListing((int) $listingId);
        $promotions = ListingPromotion::forListing((int) $listingId);
        $pageTitle  = 'Promotions — ' . $listing['title'];
        ob_start();
        require APP_PATH . '/Views/owner/listings/promotions.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function store(string $listingId): void {
        Auth::requireOwner();
        CSRF::verify();
        $listing = $this->ownerListing((int) $listingId);

        $label  = trim($_POST['label'] ?? '');
        $type   = $_POST['discount_type'] ?? 'percent';
        $value  = (float) ($_POST['discount_value'] ?? 0);
        $start  = trim($_POST['start_date'] ?? '');
        $end    = trim($_POST['end_date']   ?? '');

        $errors = [];
        if ($label === '')      $errors[] = 'Promotion label is required.';
        if ($value <= 0)        $errors[] = 'Discount value must be greater than 0.';
        if ($type === 'percent' && $value >= 100) $errors[] = 'Percentage discount must be less than 100%.';
        if ($start === '')      $errors[] = 'Start date is required.';
        if ($end === '')        $errors[] = 'End date is required.';
        if ($start && $end && $end < $start) $errors[] = 'End date must be after start date.';

        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            header("Location: /owner/listings/$listingId/promotions");
            exit;
        }

        ListingPromotion::create([
            'listing_id'    => (int) $listingId,
            'label'         => $label,
            'discount_type' => $type,
            'discount_value'=> $value,
            'start_date'    => $start,
            'end_date'      => $end,
        ]);

        $_SESSION['flash']['success'] = 'Promotion added.';
        header("Location: /owner/listings/$listingId/promotions");
        exit;
    }

    public function destroy(string $listingId, string $promoId): void {
        Auth::requireOwner();
        CSRF::verify();
        $this->ownerListing((int) $listingId);
        ListingPromotion::delete((int) $promoId, (int) $listingId);
        $_SESSION['flash']['success'] = 'Promotion deleted.';
        header("Location: /owner/listings/$listingId/promotions");
        exit;
    }

    public function toggle(string $listingId, string $promoId): void {
        Auth::requireOwner();
        CSRF::verify();
        $this->ownerListing((int) $listingId);
        ListingPromotion::toggleActive((int) $promoId, (int) $listingId);
        header("Location: /owner/listings/$listingId/promotions");
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
