CREATE TABLE IF NOT EXISTS owner_profiles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    company_name VARCHAR(191) NULL,
    profile_photo VARCHAR(255) NULL,
    about TEXT NULL,
    address TEXT NULL,
    verification_document_path VARCHAR(255) NULL,
    verification_note TEXT NULL,
    verified_at DATETIME NULL,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
