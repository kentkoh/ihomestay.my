CREATE TABLE listing_blocked_dates (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id   INT UNSIGNED    NOT NULL,
    blocked_date DATE            NOT NULL,
    source       ENUM('manual','ical') NOT NULL DEFAULT 'manual',
    created_at   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_listing_date (listing_id, blocked_date),
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
);

ALTER TABLE listings
    ADD COLUMN ical_import_url    VARCHAR(512) NULL AFTER price_3nights,
    ADD COLUMN ical_last_synced_at DATETIME    NULL AFTER ical_import_url;
