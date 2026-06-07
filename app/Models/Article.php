<?php

class Article {

    public static function all(): array {
        $db = Database::get();
        return $db->query('SELECT * FROM articles ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function published(int $limit = 10, int $offset = 0): array {
        $db = Database::get();
        $st = $db->prepare('SELECT * FROM articles WHERE is_published = 1 ORDER BY published_at DESC LIMIT :lim OFFSET :off');
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->bindValue(':off', $offset, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function latestPublished(int $limit = 3): array {
        return self::published($limit, 0);
    }

    public static function findById(int $id): ?array {
        $db = Database::get();
        $st = $db->prepare('SELECT * FROM articles WHERE id = :id');
        $st->execute([':id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $db = Database::get();
        $st = $db->prepare('SELECT * FROM articles WHERE slug = :slug AND is_published = 1');
        $st->execute([':slug' => $slug]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(array $data): int {
        $db = Database::get();
        $db->prepare('
            INSERT INTO articles (title, slug, excerpt, body, cover_image, is_published, published_at)
            VALUES (:title, :slug, :excerpt, :body, :cover_image, :is_published, :published_at)
        ')->execute([
            ':title'        => $data['title'],
            ':slug'         => $data['slug'],
            ':excerpt'      => $data['excerpt'] ?? null,
            ':body'         => $data['body'],
            ':cover_image'  => $data['cover_image'] ?? null,
            ':is_published' => $data['is_published'] ?? 0,
            ':published_at' => $data['published_at'] ?? null,
        ]);
        return (int) $db->lastInsertId();
    }

    public static function update(int $id, array $data): void {
        $db = Database::get();
        $db->prepare('
            UPDATE articles
            SET title = :title, slug = :slug, excerpt = :excerpt, body = :body,
                cover_image = :cover_image, is_published = :is_published, published_at = :published_at
            WHERE id = :id
        ')->execute([
            ':title'        => $data['title'],
            ':slug'         => $data['slug'],
            ':excerpt'      => $data['excerpt'] ?? null,
            ':body'         => $data['body'],
            ':cover_image'  => $data['cover_image'],
            ':is_published' => $data['is_published'] ?? 0,
            ':published_at' => $data['published_at'] ?? null,
            ':id'           => $id,
        ]);
    }

    public static function delete(int $id): void {
        $db = Database::get();
        $db->prepare('DELETE FROM articles WHERE id = :id')->execute([':id' => $id]);
    }

    public static function togglePublish(int $id): void {
        $db = Database::get();
        $db->prepare('
            UPDATE articles
            SET is_published = 1 - is_published,
                published_at = CASE WHEN is_published = 0 THEN NOW() ELSE published_at END
            WHERE id = :id
        ')->execute([':id' => $id]);
    }

    public static function makeSlug(string $title, int $id): string {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $title), '-'));
        return $slug . '-' . $id;
    }

    public static function countAll(): int {
        return (int) Database::get()->query('SELECT COUNT(*) FROM articles')->fetchColumn();
    }

    public static function countPublished(): int {
        return (int) Database::get()->query('SELECT COUNT(*) FROM articles WHERE is_published = 1')->fetchColumn();
    }
}
