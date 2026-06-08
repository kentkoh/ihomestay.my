ALTER TABLE owner_profiles
    ADD COLUMN facebook_url  VARCHAR(512) NULL AFTER address,
    ADD COLUMN instagram_url VARCHAR(512) NULL AFTER facebook_url,
    ADD COLUMN website_url   VARCHAR(512) NULL AFTER instagram_url;
