CREATE TABLE IF NOT EXISTS listing_facilities (
    listing_id INT UNSIGNED NOT NULL,
    facility_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (listing_id, facility_id),
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
