# MIGRATION_NOTES.md — ihomestay.my

Last updated: Stage 0

## Overview

The existing ihomestay.my runs on WordPress + HivePress.
All existing listings and users must be imported into the new custom PHP system.

Migration is planned for Stage 10 — after the core listing system is stable.

## WordPress tables to inspect

```
wp_posts            — listings (post_type = 'hp_listing')
wp_postmeta         — listing custom fields (phone, whatsapp, price, etc.)
wp_users            — owner accounts
wp_usermeta         — owner profile fields
wp_terms            — tags/categories
wp_term_taxonomy    — taxonomy definitions
wp_term_relationships — term assignments
wp_options          — site settings (for HivePress config)
```

## HivePress listing post type

Expected: `hp_listing`
Must verify with:
```sql
SELECT post_type, COUNT(*) FROM wp_posts GROUP BY post_type;
```

## Migration tools (to be created in Stage 10)

| Script                             | Purpose                              |
|------------------------------------|--------------------------------------|
| tools/migration/audit_wordpress.php  | Detect fields, post types, image links |
| tools/migration/export_users.php     | Export users to JSON                 |
| tools/migration/export_listings.php  | Export listings to JSON              |
| tools/migration/import_users.php     | Import users into new DB             |
| tools/migration/import_listings.php  | Import listings into new DB          |
| tools/migration/import_images.php    | Copy/link images                     |
| tools/migration/create_redirects.php | Build 301 redirect map               |

## Export paths

```
storage/migration/users_export.json
storage/migration/listings_export.json
```

## Password migration rule

Do NOT import WordPress hashed passwords as-is.
Set `password_reset_required = 1` for all migrated users.
They must reset password on first login.

## SEO redirect rule

Old URL: /listing/{slug}
New URL: /homestay/{state}/{city}/{slug}

Store all redirects in the `redirects` table.
Handle in public/index.php routing layer.

## Old ID preservation

Keep:
- users.old_wp_user_id
- listings.old_wp_listing_id

These fields allow rollback comparison and audit.

## Migration run order

1. Run audit first
2. Export users
3. Export listings
4. Import users
5. Import listings
6. Import images
7. Create redirects
8. Verify row counts match
9. Test a sample listing in the new system
