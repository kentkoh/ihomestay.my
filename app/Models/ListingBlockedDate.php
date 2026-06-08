<?php

class ListingBlockedDate {

    public static function forListing(int $listingId, int $months = 12): array {
        $stmt = Database::get()->prepare(
            "SELECT blocked_date, source FROM listing_blocked_dates
             WHERE listing_id = ?
               AND blocked_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? MONTH)
             ORDER BY blocked_date ASC"
        );
        $stmt->execute([$listingId, $months]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function blockedDatesArray(int $listingId, int $months = 12): array {
        $rows = self::forListing($listingId, $months);
        return array_column($rows, 'blocked_date');
    }

    public static function blockDate(int $listingId, string $date, string $source = 'manual'): void {
        try {
            Database::get()->prepare(
                "INSERT INTO listing_blocked_dates (listing_id, blocked_date, source)
                 VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE source = VALUES(source)"
            )->execute([$listingId, $date, $source]);
        } catch (PDOException $e) {
            // Ignore duplicate key
        }
    }

    public static function unblockDate(int $listingId, string $date): void {
        Database::get()->prepare(
            "DELETE FROM listing_blocked_dates WHERE listing_id = ? AND blocked_date = ? AND source = 'manual'"
        )->execute([$listingId, $date]);
    }

    public static function isBlocked(int $listingId, string $date): bool {
        $stmt = Database::get()->prepare(
            "SELECT 1 FROM listing_blocked_dates WHERE listing_id = ? AND blocked_date = ? LIMIT 1"
        );
        $stmt->execute([$listingId, $date]);
        return (bool) $stmt->fetchColumn();
    }

    public static function clearIcalBlocks(int $listingId): void {
        Database::get()->prepare(
            "DELETE FROM listing_blocked_dates WHERE listing_id = ? AND source = 'ical'"
        )->execute([$listingId]);
    }

    public static function syncFromIcal(int $listingId, string $url): bool {
        $ctx = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'timeout' => 15,
                'header'  => "User-Agent: ihomestay.my/1.0\r\n",
            ],
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
        ]);

        $content = @file_get_contents($url, false, $ctx);
        if ($content === false) return false;

        $dates = self::parseIcal($content);
        self::clearIcalBlocks($listingId);

        foreach ($dates as $date) {
            self::blockDate($listingId, $date, 'ical');
        }

        Database::get()->prepare(
            "UPDATE listings SET ical_last_synced_at = NOW() WHERE id = ?"
        )->execute([$listingId]);

        return true;
    }

    private static function parseIcal(string $content): array {
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        $dates   = [];

        preg_match_all('/BEGIN:VEVENT.*?END:VEVENT/s', $content, $events);
        foreach ($events[0] as $event) {
            preg_match('/DTSTART[^:\n]*:(\d{8})/', $event, $startM);
            preg_match('/DTEND[^:\n]*:(\d{8})/',   $event, $endM);
            if (empty($startM[1]) || empty($endM[1])) continue;

            $start = DateTime::createFromFormat('Ymd', $startM[1]);
            $end   = DateTime::createFromFormat('Ymd', $endM[1]);
            if (!$start || !$end) continue;

            $end->modify('-1 day'); // DTEND is exclusive in iCal spec
            for ($d = clone $start; $d <= $end; $d->modify('+1 day')) {
                $dates[] = $d->format('Y-m-d');
            }
        }

        return array_unique($dates);
    }
}
