<?php

class ListingPromotion {

    public static function forListing(int $listingId): array {
        $stmt = Database::get()->prepare(
            "SELECT * FROM listing_promotions WHERE listing_id = ? ORDER BY start_date DESC, id DESC"
        );
        $stmt->execute([$listingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): void {
        Database::get()->prepare(
            "INSERT INTO listing_promotions
             (listing_id, label, discount_type, discount_value, start_date, end_date, is_active, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), NOW())"
        )->execute([
            $data['listing_id'],
            $data['label'],
            $data['discount_type'],
            $data['discount_value'],
            $data['start_date'],
            $data['end_date'],
        ]);
    }

    public static function delete(int $id, int $listingId): void {
        Database::get()->prepare(
            "DELETE FROM listing_promotions WHERE id = ? AND listing_id = ?"
        )->execute([$id, $listingId]);
    }

    public static function toggleActive(int $id, int $listingId): void {
        Database::get()->prepare(
            "UPDATE listing_promotions SET is_active = 1 - is_active, updated_at = NOW()
             WHERE id = ? AND listing_id = ?"
        )->execute([$id, $listingId]);
    }
}
