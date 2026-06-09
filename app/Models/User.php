<?php

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::get();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? AND status = "active" LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare('
            INSERT INTO users (name, email, password, phone, whatsapp, role, plan_type, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, "active", ?, ?)
        ');
        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['phone'] ?? null,
            $data['whatsapp'] ?? null,
            $data['role'] ?? 'owner',
            $data['plan_type'] ?? 'free',
            $now,
            $now,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function createOwnerProfile(int $userId): void {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare('
            INSERT INTO owner_profiles (user_id, created_at, updated_at)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$userId, $now, $now]);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    public function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }

    public function updatePassword(int $id, string $newPassword): void {
        $stmt = $this->db->prepare('
            UPDATE users SET password = ?, password_reset_required = 0, updated_at = ? WHERE id = ?
        ');
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), date('Y-m-d H:i:s'), $id]);
    }

    public static function getFullProfile(int $userId): ?array {
        $stmt = Database::get()->prepare(
            "SELECT u.*, op.company_name, op.profile_photo, op.about, op.address,
                    op.facebook_url, op.instagram_url, op.website_url
             FROM users u
             LEFT JOIN owner_profiles op ON op.user_id = u.id
             WHERE u.id = ? LIMIT 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function updateUserFields(int $id, array $data): void {
        Database::get()->prepare(
            "UPDATE users SET name = ?, phone = ?, whatsapp = ?, updated_at = NOW() WHERE id = ?"
        )->execute([$data['name'], $data['phone'] ?? null, $data['whatsapp'] ?? null, $id]);
    }

    public static function updateOwnerProfile(int $userId, array $data): void {
        $pdo  = Database::get();
        $rows = $pdo->prepare("SELECT id FROM owner_profiles WHERE user_id = ?")->execute([$userId]);
        // Upsert: update if exists, insert if not
        $check = $pdo->prepare("SELECT id FROM owner_profiles WHERE user_id = ?");
        $check->execute([$userId]);
        if ($check->fetch()) {
            $pdo->prepare(
                "UPDATE owner_profiles SET company_name = ?, about = ?, address = ?,
                 facebook_url = ?, instagram_url = ?, website_url = ?,
                 profile_photo = COALESCE(?, profile_photo), updated_at = NOW()
                 WHERE user_id = ?"
            )->execute([
                $data['company_name']  ?? null,
                $data['about']         ?? null,
                $data['address']       ?? null,
                $data['facebook_url']  ?? null,
                $data['instagram_url'] ?? null,
                $data['website_url']   ?? null,
                $data['profile_photo'] ?? null,
                $userId,
            ]);
        } else {
            $pdo->prepare(
                "INSERT INTO owner_profiles
                 (user_id, company_name, about, address, facebook_url, instagram_url, website_url, profile_photo, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
            )->execute([
                $userId,
                $data['company_name']  ?? null,
                $data['about']         ?? null,
                $data['address']       ?? null,
                $data['facebook_url']  ?? null,
                $data['instagram_url'] ?? null,
                $data['website_url']   ?? null,
                $data['profile_photo'] ?? null,
            ]);
        }
    }

    public static function allOwners(): array {
        return Database::get()->query(
            "SELECT u.*, op.company_name, op.verified_at,
                    (SELECT COUNT(*) FROM listings WHERE owner_id = u.id AND status = 'published') as listing_count
             FROM users u
             LEFT JOIN owner_profiles op ON op.user_id = u.id
             WHERE u.role = 'owner'
             ORDER BY u.created_at DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function setVerification(int $id, string $status): void {
        Database::get()->prepare(
            "UPDATE users SET verification_status = ?, updated_at = NOW() WHERE id = ?"
        )->execute([$status, $id]);
    }

    public static function adminUpdate(int $id, array $data): void {
        $pdo = Database::get();
        $pdo->prepare(
            "UPDATE users SET name = ?, email = ?, phone = ?, whatsapp = ?,
             verification_status = ?, updated_at = NOW() WHERE id = ?"
        )->execute([
            $data['name'], $data['email'], $data['phone'] ?? null,
            $data['whatsapp'] ?? null, $data['verification_status'], $id,
        ]);
        $pdo->prepare(
            "UPDATE owner_profiles SET company_name = ?, about = ?, address = ?,
             facebook_url = ?, instagram_url = ?, website_url = ?, updated_at = NOW()
             WHERE user_id = ?"
        )->execute([
            $data['company_name'] ?? null, $data['about'] ?? null,
            $data['op_address']   ?? null, $data['facebook_url']  ?? null,
            $data['instagram_url'] ?? null, $data['website_url']  ?? null,
            $id,
        ]);
        if (!empty($data['new_password'])) {
            $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?")
                ->execute([password_hash($data['new_password'], PASSWORD_DEFAULT), $id]);
        }
    }

    public static function deleteOwner(int $id): void {
        $pdo = Database::get();
        // Collect listing ids to clean up child tables
        $listingIds = $pdo->prepare("SELECT id FROM listings WHERE owner_id = ?");
        $listingIds->execute([$id]);
        foreach ($listingIds->fetchAll(PDO::FETCH_COLUMN) as $lid) {
            $pdo->prepare("DELETE FROM listing_promotions   WHERE listing_id = ?")->execute([$lid]);
            $pdo->prepare("DELETE FROM listing_blocked_dates WHERE listing_id = ?")->execute([$lid]);
            $pdo->prepare("DELETE FROM listing_facilities   WHERE listing_id = ?")->execute([$lid]);
            $pdo->prepare("DELETE FROM listing_images       WHERE listing_id = ?")->execute([$lid]);
        }
        $pdo->prepare("DELETE FROM listings      WHERE owner_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM owner_profiles WHERE user_id  = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM users          WHERE id = ?")->execute([$id]);
    }

    // Normalise to Malaysian international format: 01x... → 601x..., +601x... → 601x...
    public static function normalizePhone(string $phone): string {
        $digits = preg_replace('/\D/', '', $phone); // strip spaces, dashes, +
        if ($digits === '') return '';
        if (str_starts_with($digits, '60')) return $digits;   // already 601x...
        if (str_starts_with($digits, '0'))  return '6' . $digits; // 01x → 601x
        return $digits; // unknown format — keep as-is
    }
}
