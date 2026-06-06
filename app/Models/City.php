<?php

class City {
    public static function byState(int $stateId): array {
        $stmt = Database::get()->prepare(
            "SELECT * FROM cities WHERE state_id = ? ORDER BY name"
        );
        $stmt->execute([$stateId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array {
        $stmt = Database::get()->prepare("SELECT * FROM cities WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function all(): array {
        return Database::get()
            ->query("SELECT * FROM cities ORDER BY state_id, name")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findBySlug(int $stateId, string $slug): ?array {
        $stmt = Database::get()->prepare(
            "SELECT * FROM cities WHERE state_id = ? AND slug = ?"
        );
        $stmt->execute([$stateId, $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
