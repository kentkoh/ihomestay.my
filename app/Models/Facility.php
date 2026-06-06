<?php

class Facility {
    public static function all(): array {
        return Database::get()
            ->query("SELECT * FROM facilities ORDER BY category, sort_order, name")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allGrouped(): array {
        $rows = self::all();
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['category']][] = $row;
        }
        return $grouped;
    }

    public static function activeGrouped(): array {
        $rows = Database::get()
            ->query("SELECT * FROM facilities WHERE is_active = 1 ORDER BY category, sort_order, name")
            ->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['category']][] = $row;
        }
        return $grouped;
    }

    public static function findById(int $id): ?array {
        $stmt = Database::get()->prepare("SELECT * FROM facilities WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function categories(): array {
        return Database::get()
            ->query("SELECT DISTINCT category FROM facilities ORDER BY category")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function create(array $data): void {
        $stmt = Database::get()->prepare(
            "INSERT INTO facilities (name, category, sort_order, is_active) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['name'],
            $data['category'],
            (int) ($data['sort_order'] ?? 0),
            isset($data['is_active']) ? 1 : 0,
        ]);
    }

    public static function update(int $id, array $data): void {
        $stmt = Database::get()->prepare(
            "UPDATE facilities SET name=?, category=?, sort_order=?, is_active=?, updated_at=NOW() WHERE id=?"
        );
        $stmt->execute([
            $data['name'],
            $data['category'],
            (int) ($data['sort_order'] ?? 0),
            isset($data['is_active']) ? 1 : 0,
            $id,
        ]);
    }

    public static function delete(int $id): void {
        $stmt = Database::get()->prepare("DELETE FROM facilities WHERE id=?");
        $stmt->execute([$id]);
    }

    public static function toggle(int $id): void {
        $stmt = Database::get()->prepare(
            "UPDATE facilities SET is_active = 1 - is_active, updated_at=NOW() WHERE id=?"
        );
        $stmt->execute([$id]);
    }

    public static function countByStatus(): array {
        $row = Database::get()
            ->query("SELECT SUM(is_active) as active, SUM(1 - is_active) as inactive FROM facilities")
            ->fetch(PDO::FETCH_ASSOC);
        return [
            'active'   => (int) ($row['active'] ?? 0),
            'inactive' => (int) ($row['inactive'] ?? 0),
        ];
    }
}
