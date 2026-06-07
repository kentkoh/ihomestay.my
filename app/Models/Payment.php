<?php

class Payment {
    private PDO $db;

    public function __construct() {
        $this->db = Database::get();
    }

    public function create(array $data): int {
        $this->db->prepare("
            INSERT INTO payments (owner_id, listing_id, package_id, bill_id, amount, duration_days, status)
            VALUES (?, ?, ?, ?, ?, ?, 'pending')
        ")->execute([
            $data['owner_id'],
            $data['listing_id'],
            $data['package_id'],
            $data['bill_id'] ?? '',
            $data['amount'],
            $data['duration_days'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateBillId(int $id, string $billId): void {
        $this->db->prepare("UPDATE payments SET bill_id = ? WHERE id = ?")
                 ->execute([$billId, $id]);
    }

    public function findByBillId(string $billId): ?array {
        $s = $this->db->prepare("SELECT * FROM payments WHERE bill_id = ? LIMIT 1");
        $s->execute([$billId]);
        return $s->fetch() ?: null;
    }

    public function markPaid(int $id, string $paidAt): void {
        $this->db->prepare("
            UPDATE payments SET status = 'paid', paid_at = ? WHERE id = ?
        ")->execute([$paidAt, $id]);
    }

    public function markFailed(int $id): void {
        $this->db->prepare("UPDATE payments SET status = 'failed' WHERE id = ?")
                 ->execute([$id]);
    }

    public function forOwner(int $ownerId): array {
        $s = $this->db->prepare("
            SELECT p.*, l.title AS listing_title, fp.label AS package_label
            FROM payments p
            JOIN listings l ON l.id = p.listing_id
            JOIN featured_packages fp ON fp.id = p.package_id
            WHERE p.owner_id = ?
            ORDER BY p.created_at DESC
        ");
        $s->execute([$ownerId]);
        return $s->fetchAll();
    }
}
