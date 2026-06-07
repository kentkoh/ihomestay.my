ALTER TABLE listings
ADD COLUMN featured_until DATETIME NULL AFTER is_featured;
