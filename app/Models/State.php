<?php

class State {
    public static function all(): array {
        return Database::get()
            ->query("SELECT * FROM states ORDER BY name")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(int $id): ?array {
        $stmt = Database::get()->prepare("SELECT * FROM states WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $stmt = Database::get()->prepare("SELECT * FROM states WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
