ALTER TABLE listings
    ADD COLUMN price_2nights DECIMAL(10,2) NULL AFTER price_per_night,
    ADD COLUMN price_3nights DECIMAL(10,2) NULL AFTER price_2nights;
