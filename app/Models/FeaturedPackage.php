<?php

class FeaturedPackage {
    private PDO $db;

    public function __construct() {
        $this->db = Database::get();
    }

    public function all(): array {
        return $this->db->query("SELECT * FROM featured_packages ORDER BY sort_order ASC")->fetchAll();
    }

    public function active(): array {
        return $this->db->query("SELECT * FROM featured_packages WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();
    }

    public function findById(int $id): ?array {
        $s = $this->db->prepare("SELECT * FROM featured_packages WHERE id = ? LIMIT 1");
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    public function update(int $id, array $data): void {
        $this->db->prepare("
            UPDATE featured_packages
            SET label = ?, days = ?, normal_price = ?, promo_price = ?, is_active = ?, sort_order = ?
            WHERE id = ?
        ")->execute([
            $data['label'],
            (int) $data['days'],
            (float) $data['normal_price'],
            isset($data['promo_price']) && $data['promo_price'] !== '' ? (float) $data['promo_price'] : null,
            (int) $data['is_active'],
            (int) $data['sort_order'],
            $id,
        ]);
    }

    public function effectivePrice(array $package): float {
        return ($package['promo_price'] !== null && $package['promo_price'] > 0)
            ? (float) $package['promo_price']
            : (float) $package['normal_price'];
    }

    public function hasPromo(array $package): bool {
        return $package['promo_price'] !== null && (float) $package['promo_price'] > 0
            && (float) $package['promo_price'] < (float) $package['normal_price'];
    }
}
