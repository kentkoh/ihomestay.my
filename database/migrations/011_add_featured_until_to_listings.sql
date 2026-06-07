ALTER TABLE listings
ADD COLUMN IF NOT EXISTS featured_until DATETIME NULL AFTER is_featured;
