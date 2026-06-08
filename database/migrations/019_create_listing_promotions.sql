CREATE TABLE listing_promotions (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id   INT UNSIGNED NOT NULL,
    label        VARCHAR(100)    NOT NULL,
    discount_type ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
    discount_value DECIMAL(8,2)  NOT NULL,
    start_date   DATE            NOT NULL,
    end_date     DATE            NOT NULL,
    is_active    TINYINT(1)      NOT NULL DEFAULT 1,
    created_at   DATETIME        NOT NULL,
    updated_at   DATETIME        NOT NULL,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
);
