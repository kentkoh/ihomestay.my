ALTER TABLE users
    ADD COLUMN google_id VARCHAR(255) NULL AFTER email,
    ADD UNIQUE KEY unique_google_id (google_id);
