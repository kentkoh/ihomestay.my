# DATABASE_SCHEMA.md — ihomestay.my

Last updated: Stage 0 (schema draft only — no tables created yet)

---

## Tables Overview

| Table               | Stage | Status  |
|---------------------|-------|---------|
| users               | 1     | pending |
| owner_profiles      | 1     | pending |
| states              | 2     | pending |
| cities              | 2     | pending |
| areas               | 2     | pending |
| facilities          | 2     | pending |
| listings            | 3     | pending |
| listing_images      | 3     | pending |
| listing_facilities  | 3     | pending |
| listing_views       | 4     | pending |
| listing_clicks      | 4     | pending |
| packages            | 6     | pending |
| payments            | 6     | pending |
| articles            | 8     | pending |
| article_categories  | 8     | pending |
| ad_placements       | 9     | pending |
| ad_orders           | 9     | pending |
| ad_creatives        | 9     | pending |
| ad_impressions      | 9     | pending |
| ad_clicks           | 9     | pending |
| reports             | 11    | pending |
| redirects           | 10    | pending |

---

## Schema Definitions (Draft)

All tables use:
- Engine: InnoDB
- Charset: utf8mb4
- Collation: utf8mb4_unicode_ci

### users
```sql
CREATE TABLE users (
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
```

### owner_profiles
```sql
CREATE TABLE owner_profiles (
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
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### states
```sql
CREATE TABLE states (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(191) NOT NULL,
    slug VARCHAR(191) NOT NULL UNIQUE,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### cities
```sql
CREATE TABLE cities (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    state_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(191) NOT NULL,
    slug VARCHAR(191) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (state_id) REFERENCES states(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### areas
```sql
CREATE TABLE areas (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    city_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(191) NOT NULL,
    slug VARCHAR(191) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    FOREIGN KEY (city_id) REFERENCES cities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### listings
```sql
CREATE TABLE listings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    old_wp_listing_id BIGINT NULL,
    owner_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(191) NOT NULL,
    slug VARCHAR(191) NOT NULL UNIQUE,
    description TEXT NULL,
    state_id BIGINT UNSIGNED NOT NULL,
    city_id BIGINT UNSIGNED NOT NULL,
    area_id BIGINT UNSIGNED NULL,
    address TEXT NULL,
    latitude DECIMAL(10,7) NULL,
    longitude DECIMAL(10,7) NULL,
    price_from DECIMAL(10,2) NULL,
    max_guests INT NULL,
    bedrooms INT NULL,
    bathrooms INT NULL,
    house_rules TEXT NULL,
    status ENUM('draft','pending','published','rejected','suspended','expired') DEFAULT 'pending',
    rejection_reason TEXT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    featured_until DATETIME NULL,
    owner_verification_snapshot ENUM('unverified','verified') DEFAULT 'unverified',
    view_count BIGINT DEFAULT 0,
    whatsapp_click_count BIGINT DEFAULT 0,
    phone_click_count BIGINT DEFAULT 0,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (owner_id) REFERENCES users(id),
    FOREIGN KEY (state_id) REFERENCES states(id),
    FOREIGN KEY (city_id) REFERENCES cities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### packages
```sql
CREATE TABLE packages (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(191) NOT NULL,
    package_type ENUM('verification','featured_listing','ad','sponsored_article','pro_owner') NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration_days INT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### payments
```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    package_id BIGINT UNSIGNED NULL,
    listing_id BIGINT UNSIGNED NULL,
    ad_order_id BIGINT UNSIGNED NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'MYR',
    payment_gateway VARCHAR(50) DEFAULT 'billplz',
    billplz_bill_id VARCHAR(191) NULL,
    billplz_collection_id VARCHAR(191) NULL,
    payment_status ENUM('pending','paid','failed','cancelled','refunded') DEFAULT 'pending',
    paid_at DATETIME NULL,
    raw_response LONGTEXT NULL,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## Notes

- All migrations are stored in /database/migrations/
- Run migrations in order (Stage 1 first, then Stage 2, etc.)
- Never create tables manually without a migration file
- Use old_wp_user_id and old_wp_listing_id to preserve migration references
