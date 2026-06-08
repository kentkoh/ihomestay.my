<?php

class Listing {
    public static function create(array $data): int {
        $pdo  = Database::get();
        $stmt = $pdo->prepare(
            "INSERT INTO listings
             (owner_id, title, slug, description, address, state_id, city_id, postcode,
              latitude, longitude, price_per_night, price_2nights, price_3nights,
              min_nights, max_guests, bedrooms, bathrooms, whatsapp, status)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $data['owner_id'],
            $data['title'],
            '',
            $data['description'],
            $data['address'],
            $data['state_id'],
            $data['city_id'],
            $data['postcode'] ?? '',
            $data['latitude']  ?: null,
            $data['longitude'] ?: null,
            $data['price_per_night'],
            $data['price_2nights'] ?: null,
            $data['price_3nights'] ?: null,
            $data['min_nights'] ?? 1,
            $data['max_guests'] ?? 1,
            $data['bedrooms']   ?? 1,
            $data['bathrooms']  ?? 1,
            $data['whatsapp']   ?? '',
            $data['status']     ?? 'pending',
        ]);
        $id   = (int) $pdo->lastInsertId();
        $slug = self::makeSlug($data['title']) . '-' . $id;
        $pdo->prepare("UPDATE listings SET slug=? WHERE id=?")->execute([$slug, $id]);
        return $id;
    }

    public static function update(int $id, array $data): void {
        $stmt = Database::get()->prepare(
            "UPDATE listings SET
             title=?, description=?, address=?, state_id=?, city_id=?, postcode=?,
             latitude=?, longitude=?, price_per_night=?, price_2nights=?, price_3nights=?,
             min_nights=?, max_guests=?, bedrooms=?, bathrooms=?,
             whatsapp=?, status=?, rejection_reason=?, updated_at=NOW()
             WHERE id=?"
        );
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['address'],
            $data['state_id'],
            $data['city_id'],
            $data['postcode'] ?? '',
            $data['latitude']  ?: null,
            $data['longitude'] ?: null,
            $data['price_per_night'],
            $data['price_2nights'] ?: null,
            $data['price_3nights'] ?: null,
            $data['min_nights'] ?? 1,
            $data['max_guests'] ?? 1,
            $data['bedrooms']   ?? 1,
            $data['bathrooms']  ?? 1,
            $data['whatsapp']   ?? '',
            $data['status'],
            $data['rejection_reason'] ?? null,
            $id,
        ]);
    }

    public static function delete(int $id): void {
        Database::get()->prepare("DELETE FROM listings WHERE id=?")->execute([$id]);
    }

    public static function findById(int $id): ?array {
        $stmt = Database::get()->prepare("SELECT * FROM listings WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findByIdWithDetails(int $id): ?array {
        $stmt = Database::get()->prepare(
            "SELECT l.*, u.name as owner_name, u.email as owner_email,
                    s.name as state_name, c.name as city_name
             FROM listings l
             JOIN users u  ON l.owner_id  = u.id
             JOIN states s ON l.state_id  = s.id
             JOIN cities c ON l.city_id   = c.id
             WHERE l.id=?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function byOwner(int $ownerId): array {
        $stmt = Database::get()->prepare(
            "SELECT l.*, s.name as state_name, c.name as city_name,
                    (SELECT filename FROM listing_images
                     WHERE listing_id=l.id AND is_primary=1 LIMIT 1) as primary_image
             FROM listings l
             JOIN states s ON l.state_id = s.id
             JOIN cities c ON l.city_id  = c.id
             WHERE l.owner_id=?
             ORDER BY l.created_at DESC"
        );
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByOwner(int $ownerId): int {
        $stmt = Database::get()->prepare(
            "SELECT COUNT(*) FROM listings WHERE owner_id=? AND status != 'suspended'"
        );
        $stmt->execute([$ownerId]);
        return (int) $stmt->fetchColumn();
    }

    public static function syncWhatsappForOwner(int $ownerId, string $whatsapp): void {
        Database::get()->prepare(
            "UPDATE listings SET whatsapp = ?, updated_at = NOW() WHERE owner_id = ?"
        )->execute([$whatsapp, $ownerId]);
    }

    public static function feature(int $id, ?string $until): void {
        Database::get()->prepare(
            "UPDATE listings SET is_featured = 1, featured_until = ?, updated_at = NOW() WHERE id = ?"
        )->execute([$until, $id]);
    }

    public static function unfeature(int $id): void {
        Database::get()->prepare(
            "UPDATE listings SET is_featured = 0, featured_until = NULL, updated_at = NOW() WHERE id = ?"
        )->execute([$id]);
    }

    public static function publishedRecent(int $limit = 6): array {
        $st = Database::get()->prepare(
            "SELECT l.*, s.name as state_name, c.name as city_name,
                    (SELECT filename FROM listing_images WHERE listing_id=l.id AND is_primary=1 LIMIT 1) as primary_image,
                    (l.is_featured = 1 AND (l.featured_until IS NULL OR l.featured_until > NOW())) as is_featured_active,
                    (u.verification_status = 'verified' OR u.role = 'admin') as owner_is_verified,
                    u.whatsapp as owner_whatsapp,
                    (SELECT JSON_OBJECT('label',label,'type',discount_type,'value',discount_value)
                     FROM listing_promotions WHERE listing_id=l.id AND is_active=1
                       AND start_date<=CURDATE() AND end_date>=CURDATE()
                     ORDER BY id DESC LIMIT 1) as active_promo
             FROM listings l
             JOIN states s ON l.state_id = s.id
             JOIN cities c ON l.city_id  = c.id
             JOIN users u  ON l.owner_id = u.id
             WHERE l.status = 'published'
             ORDER BY
                 (l.is_featured = 1 AND (l.featured_until IS NULL OR l.featured_until > NOW())) DESC,
                 (u.verification_status = 'verified' OR u.role = 'admin') DESC,
                 l.created_at DESC
             LIMIT :lim"
        );
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countPublishedByState(): array {
        $rows = Database::get()->query(
            "SELECT s.id, s.name, s.slug, COUNT(l.id) as total
             FROM states s
             LEFT JOIN listings l ON l.state_id = s.id AND l.status = 'published'
             GROUP BY s.id, s.name, s.slug
             ORDER BY s.name"
        )->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public static function allForAdmin(?string $status = null): array {
        $pdo = Database::get();
        $sql = "SELECT l.*, u.name as owner_name, s.name as state_name, c.name as city_name,
                       (SELECT filename FROM listing_images
                        WHERE listing_id=l.id AND is_primary=1 LIMIT 1) as primary_image,
                       (l.is_featured = 1 AND (l.featured_until IS NULL OR l.featured_until > NOW())) as is_featured_active
                FROM listings l
                JOIN users u  ON l.owner_id  = u.id
                JOIN states s ON l.state_id  = s.id
                JOIN cities c ON l.city_id   = c.id";
        if ($status) {
            $stmt = $pdo->prepare($sql . " WHERE l.status=? ORDER BY l.created_at DESC");
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query($sql . " ORDER BY l.created_at DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByStatus(): array {
        $rows = Database::get()
            ->query("SELECT status, COUNT(*) as cnt FROM listings GROUP BY status")
            ->fetchAll(PDO::FETCH_ASSOC);
        $counts = ['all' => 0, 'pending' => 0, 'published' => 0, 'rejected' => 0, 'suspended' => 0, 'draft' => 0];
        foreach ($rows as $row) {
            $counts[$row['status']] = (int) $row['cnt'];
            $counts['all'] += (int) $row['cnt'];
        }
        return $counts;
    }

    public static function approve(int $id): void {
        Database::get()->prepare(
            "UPDATE listings SET status='published', rejection_reason=NULL, updated_at=NOW() WHERE id=?"
        )->execute([$id]);
    }

    public static function reject(int $id, string $reason): void {
        Database::get()->prepare(
            "UPDATE listings SET status='rejected', rejection_reason=?, updated_at=NOW() WHERE id=?"
        )->execute([$reason, $id]);
    }

    public static function suspend(int $id): void {
        Database::get()->prepare(
            "UPDATE listings SET status='suspended', updated_at=NOW() WHERE id=?"
        )->execute([$id]);
    }

    public static function syncFacilities(int $listingId, array $facilityIds): void {
        $pdo = Database::get();
        $pdo->prepare("DELETE FROM listing_facilities WHERE listing_id=?")->execute([$listingId]);
        if (!empty($facilityIds)) {
            $stmt = $pdo->prepare(
                "INSERT INTO listing_facilities (listing_id, facility_id) VALUES (?,?)"
            );
            foreach ($facilityIds as $fid) {
                $stmt->execute([$listingId, (int) $fid]);
            }
        }
    }

    public static function getFacilityIds(int $listingId): array {
        $stmt = Database::get()->prepare(
            "SELECT facility_id FROM listing_facilities WHERE listing_id=?"
        );
        $stmt->execute([$listingId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function addImage(int $listingId, string $filename, bool $isPrimary): void {
        $pdo      = Database::get();
        $sortStmt = $pdo->prepare(
            "SELECT COALESCE(MAX(sort_order), -1) + 1 FROM listing_images WHERE listing_id=?"
        );
        $sortStmt->execute([$listingId]);
        $sortOrder = (int) $sortStmt->fetchColumn();

        $pdo->prepare(
            "INSERT INTO listing_images (listing_id, filename, is_primary, sort_order)
             VALUES (?, ?, ?, ?)"
        )->execute([$listingId, $filename, $isPrimary ? 1 : 0, $sortOrder]);
    }

    public static function getImages(int $listingId): array {
        $stmt = Database::get()->prepare(
            "SELECT * FROM listing_images WHERE listing_id=? ORDER BY sort_order"
        );
        $stmt->execute([$listingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function imageCount(int $listingId): int {
        $stmt = Database::get()->prepare(
            "SELECT COUNT(*) FROM listing_images WHERE listing_id=?"
        );
        $stmt->execute([$listingId]);
        return (int) $stmt->fetchColumn();
    }

    public static function deleteImage(int $imageId, int $listingId): ?string {
        $stmt = Database::get()->prepare(
            "SELECT filename FROM listing_images WHERE id=? AND listing_id=?"
        );
        $stmt->execute([$imageId, $listingId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        Database::get()->prepare("DELETE FROM listing_images WHERE id=?")->execute([$imageId]);

        $remaining = Database::get()->prepare(
            "SELECT COUNT(*) FROM listing_images WHERE listing_id=? AND is_primary=1"
        );
        $remaining->execute([$listingId]);
        if ($remaining->fetchColumn() == 0) {
            Database::get()->prepare(
                "UPDATE listing_images SET is_primary=1 WHERE listing_id=? ORDER BY sort_order LIMIT 1"
            )->execute([$listingId]);
        }

        return $row['filename'];
    }

    public static function setPrimaryImage(int $imageId, int $listingId): void {
        $pdo = Database::get();
        $pdo->prepare("UPDATE listing_images SET is_primary=0 WHERE listing_id=?")->execute([$listingId]);
        $pdo->prepare("UPDATE listing_images SET is_primary=1 WHERE id=? AND listing_id=?")->execute([$imageId, $listingId]);
    }

    public static function findBySlugPublic(string $slug): ?array {
        $stmt = Database::get()->prepare(
            "SELECT l.*, s.name as state_name, s.slug as state_slug,
                    c.name as city_name, c.slug as city_slug,
                    u.name as owner_name, u.role as owner_role, u.whatsapp as owner_whatsapp,
                    (u.verification_status = 'verified' OR u.role = 'admin') as owner_is_verified,
                    op.company_name as owner_company, op.about as owner_bio, op.profile_photo as owner_photo,
                    op.facebook_url as owner_facebook, op.instagram_url as owner_instagram, op.website_url as owner_website,
                    op.verified_at,
                    (l.is_featured = 1 AND (l.featured_until IS NULL OR l.featured_until > NOW())) as is_featured_active,
                    (SELECT JSON_OBJECT('label',label,'type',discount_type,'value',discount_value)
                     FROM listing_promotions WHERE listing_id=l.id AND is_active=1
                       AND start_date<=CURDATE() AND end_date>=CURDATE()
                     ORDER BY id DESC LIMIT 1) as active_promo
             FROM listings l
             JOIN states s  ON l.state_id  = s.id
             JOIN cities c  ON l.city_id   = c.id
             JOIN users u   ON l.owner_id  = u.id
             LEFT JOIN owner_profiles op ON op.user_id = l.owner_id
             WHERE l.slug = ? AND l.status = 'published'"
        );
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function getFacilitiesForListing(int $listingId): array {
        $stmt = Database::get()->prepare(
            "SELECT f.name, f.category
             FROM facilities f
             JOIN listing_facilities lf ON lf.facility_id = f.id
             WHERE lf.listing_id = ? AND f.is_active = 1
             ORDER BY f.category, f.sort_order, f.name"
        );
        $stmt->execute([$listingId]);
        $rows    = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['category']][] = $row['name'];
        }
        return $grouped;
    }

    public static function search(array $filters = [], int $page = 1, int $perPage = 12): array {
        $pdo = Database::get();
        [$where, $params] = self::buildSearchWhere($filters);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT l.*, s.name as state_name, c.name as city_name,
                       (SELECT filename FROM listing_images WHERE listing_id=l.id AND is_primary=1 LIMIT 1) as primary_image,
                       (l.is_featured = 1 AND (l.featured_until IS NULL OR l.featured_until > NOW())) as is_featured_active,
                       (u.verification_status = 'verified' OR u.role = 'admin') as owner_is_verified,
                       u.whatsapp as owner_whatsapp,
                       (SELECT JSON_OBJECT('label',label,'type',discount_type,'value',discount_value)
                        FROM listing_promotions WHERE listing_id=l.id AND is_active=1
                          AND start_date<=CURDATE() AND end_date>=CURDATE()
                        ORDER BY id DESC LIMIT 1) as active_promo
                FROM listings l
                JOIN states s ON l.state_id = s.id
                JOIN cities c ON l.city_id  = c.id
                JOIN users u  ON l.owner_id = u.id
                WHERE l.status = 'published'" . $where . "
                ORDER BY
                    (l.is_featured = 1 AND (l.featured_until IS NULL OR l.featured_until > NOW())) DESC,
                    (u.verification_status = 'verified' OR u.role = 'admin') DESC,
                    l.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countSearch(array $filters = []): int {
        $pdo = Database::get();
        [$where, $params] = self::buildSearchWhere($filters);

        $sql  = "SELECT COUNT(*) FROM listings l
                 JOIN states s ON l.state_id = s.id
                 JOIN cities c ON l.city_id  = c.id
                 WHERE l.status = 'published'" . $where;
        $stmt = $pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function similarListings(int $excludeId, int $cityId, int $stateId, int $limit = 6): array {
        $pdo     = Database::get();
        $baseSql = "SELECT l.*, s.name as state_name, c.name as city_name,
                           (SELECT filename FROM listing_images WHERE listing_id=l.id AND is_primary=1 LIMIT 1) as primary_image,
                           (l.is_featured = 1 AND (l.featured_until IS NULL OR l.featured_until > NOW())) as is_featured_active,
                           (u.verification_status = 'verified' OR u.role = 'admin') as owner_is_verified,
                           u.whatsapp as owner_whatsapp
                    FROM listings l
                    JOIN states s ON l.state_id = s.id
                    JOIN cities c ON l.city_id  = c.id
                    JOIN users u  ON l.owner_id = u.id
                    WHERE l.status = 'published'";

        $stmt = $pdo->prepare($baseSql . " AND l.id != ? AND l.city_id = ?
                    ORDER BY is_featured_active DESC, owner_is_verified DESC, RAND()
                    LIMIT ?");
        $stmt->bindValue(1, $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(2, $cityId,    PDO::PARAM_INT);
        $stmt->bindValue(3, $limit,     PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) < $limit) {
            $needed     = $limit - count($results);
            $excludeIds = array_map('intval', array_merge([$excludeId], array_column($results, 'id')));
            $ph         = implode(',', $excludeIds);

            $stmt2 = $pdo->prepare($baseSql . " AND l.id NOT IN ($ph) AND l.state_id = ?
                        ORDER BY is_featured_active DESC, owner_is_verified DESC, RAND()
                        LIMIT ?");
            $stmt2->bindValue(1, $stateId, PDO::PARAM_INT);
            $stmt2->bindValue(2, $needed,  PDO::PARAM_INT);
            $stmt2->execute();
            $results = array_merge($results, $stmt2->fetchAll(PDO::FETCH_ASSOC));
        }

        return $results;
    }

    private static function buildSearchWhere(array $filters): array {
        $where  = '';
        $params = [];

        if (!empty($filters['state_id'])) {
            $where .= ' AND l.state_id = :state_id';
            $params[':state_id'] = (int) $filters['state_id'];
        }
        if (!empty($filters['city_id'])) {
            $where .= ' AND l.city_id = :city_id';
            $params[':city_id'] = (int) $filters['city_id'];
        }
        if (!empty($filters['q'])) {
            $where .= ' AND (l.title LIKE :q1 OR l.description LIKE :q2)';
            $params[':q1'] = '%' . $filters['q'] . '%';
            $params[':q2'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['guests'])) {
            $where .= ' AND l.max_guests >= :guests';
            $params[':guests'] = (int) $filters['guests'];
        }
        if (!empty($filters['has_pool'])) {
            $where .= " AND EXISTS (
                SELECT 1 FROM listing_facilities lf
                JOIN facilities f ON lf.facility_id = f.id
                WHERE lf.listing_id = l.id
                  AND f.name IN ('Swimming Pool','Private Pool')
            )";
        }
        if (!empty($filters['has_bbq'])) {
            $where .= " AND EXISTS (
                SELECT 1 FROM listing_facilities lf
                JOIN facilities f ON lf.facility_id = f.id
                WHERE lf.listing_id = l.id
                  AND f.name = 'BBQ Pit / Grill'
            )";
        }

        return [$where, $params];
    }

    private static function makeSlug(string $title): string {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
    }
}
