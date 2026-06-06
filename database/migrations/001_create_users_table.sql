CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    old_wp_user_id BIGINT NULL,
    name VARCHAR(191) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NULL,
    phone VARCHAR(50) NULL,
    whatsapp VARCHAR(50) NULL,
    role ENUM('admin','owner','advertiser') DEFAULT 'owner',
    verification_status ENUM('unverified','pending_verification','verified','rejected','suspended') DEFAULT 'unverified',
    plan_type ENUM('free','verified','pro') DEFAULT 'free',
    plan_expires_at DATETIME NULL,
    email_verified_at DATETIME NULL,
    phone_verified_at DATETIME NULL,
    password_reset_required TINYINT(1) DEFAULT 0,
    status ENUM('active','suspended','deleted') DEFAULT 'active',
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
