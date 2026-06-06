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
            INSERT INTO users (name, email, password, phone, whatsapp, role, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, "active", ?, ?)
        ');
        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['phone'] ?? null,
            $data['whatsapp'] ?? null,
            $data['role'] ?? 'owner',
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
}
