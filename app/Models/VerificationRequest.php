<?php

class VerificationRequest {
    private PDO $db;

    public function __construct() {
        $this->db = Database::get();
    }

    public function create(array $data): int {
        $this->db->prepare("
            INSERT INTO verification_requests
                (owner_id, request_type, document_path, selfie_path, selected_listing_id,
                 promo_eligible, bill_id, amount, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending_payment')
        ")->execute([
            $data['owner_id'],
            $data['request_type'],
            $data['document_path'],
            $data['selfie_path'] ?? null,
            $data['selected_listing_id'] ?? null,
            $data['promo_eligible'] ? 1 : 0,
            $data['bill_id'] ?? null,
            $data['amount'] ?? 49.00,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateBillId(int $id, string $billId): void {
        $this->db->prepare("UPDATE verification_requests SET bill_id = ? WHERE id = ?")
                 ->execute([$billId, $id]);
    }

    public function findByBillId(string $billId): ?array {
        $s = $this->db->prepare("SELECT * FROM verification_requests WHERE bill_id = ? LIMIT 1");
        $s->execute([$billId]);
        return $s->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM verification_requests WHERE id = ? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function markPaid(int $id, string $paidAt): void {
        $this->db->prepare("
            UPDATE verification_requests
            SET payment_status = 'paid', paid_at = ?, status = 'pending_review'
            WHERE id = ?
        ")->execute([$paidAt, $id]);
    }

    public function markFailed(int $id): void {
        $this->db->prepare("
            UPDATE verification_requests
            SET payment_status = 'failed'
            WHERE id = ?
        ")->execute([$id]);
    }

    public function pendingForOwner(int $ownerId): ?array {
        $s = $this->db->prepare("
            SELECT * FROM verification_requests
            WHERE owner_id = ? AND status NOT IN ('rejected')
            ORDER BY created_at DESC LIMIT 1
        ");
        $s->execute([$ownerId]);
        return $s->fetch() ?: null;
    }

    public function all(): array {
        return $this->db->query("
            SELECT vr.*,
                   u.name  AS owner_name,
                   u.email AS owner_email,
                   l.title AS listing_title
            FROM verification_requests vr
            JOIN users u ON u.id = vr.owner_id
            LEFT JOIN listings l ON l.id = vr.selected_listing_id
            ORDER BY vr.created_at DESC
        ")->fetchAll();
    }

    public function approve(int $id, int $adminId, string $notes = ''): void {
        $this->db->prepare("
            UPDATE verification_requests
            SET status = 'approved', admin_notes = ?, reviewed_by = ?, reviewed_at = NOW()
            WHERE id = ?
        ")->execute([$notes, $adminId, $id]);
    }

    public function reject(int $id, int $adminId, string $notes = ''): void {
        $this->db->prepare("
            UPDATE verification_requests
            SET status = 'rejected', admin_notes = ?, reviewed_by = ?, reviewed_at = NOW()
            WHERE id = ?
        ")->execute([$notes, $adminId, $id]);
    }

    public function markFeaturedActivated(int $id): void {
        $this->db->prepare("UPDATE verification_requests SET featured_activated = 1 WHERE id = ?")
                 ->execute([$id]);
    }
}
