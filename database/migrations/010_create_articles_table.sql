CREATE TABLE IF NOT EXISTS articles (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(255)   NOT NULL,
    slug          VARCHAR(300)   NOT NULL UNIQUE,
    excerpt       TEXT           NULL,
    body          LONGTEXT       NOT NULL,
    cover_image   VARCHAR(255)   NULL,
    is_published  TINYINT(1)     NOT NULL DEFAULT 0,
    published_at  DATETIME       NULL,
    created_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_published (is_published, published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
