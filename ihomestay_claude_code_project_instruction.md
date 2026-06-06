# ihomestay.my Custom Homestay Directory — Claude Code Project Instruction

## 0. Project Summary

Build a custom Malaysia-only homestay directory website for **ihomestay.my** to replace the current WordPress + HivePress directory.

The existing website is using:

- WordPress
- HivePress
- MySQL database
- cPanel hosting

The new system must be developed as a custom PHP + MySQL web application and deployed using:

- cPanel
- PHP
- MySQL / MariaDB
- Git Version Control
- SSH access
- Terminal
- Billplz payment gateway
- ECC already installed in Claude Code

The website is a **profit-oriented homestay directory**, not an online booking platform in Version 1.

Core business model:

- Free owner listings
- Verified owner upgrade
- Featured listing sales
- Banner advertising
- Sponsored article / article ad placement
- Direct WhatsApp contact between visitor and homestay owner

Important: This is a big project. Build it stage by stage. Do not attempt to generate or edit the whole system in one pass.

---

# 1. Critical Development Rule for Claude Code

## 1.1 This is a large project

Do not build everything in a single prompt or single huge code generation.

Claude Code must split development into small, testable, linkable, and editable stages.

Each stage must:

1. Have a clear scope.
2. Create or modify only related files.
3. Update documentation after completion.
4. Avoid unnecessary rewrites of existing working code.
5. Keep database migrations organized.
6. Keep features modular.
7. Avoid hitting API/context errors such as 1M context overload.

## 1.2 Use ECC

ECC is already installed in Claude Code. Use ECC where helpful for:

- Project exploration
- Code navigation
- Multi-file editing
- Refactoring
- Stage planning
- Testing flow
- Keeping project memory organized
- Avoiding context overflow

Do not assume the whole codebase needs to be loaded into context. Use ECC to inspect only the relevant files for each stage.

## 1.3 Required project management files

Create and maintain these files:

```text
PROJECT_OVERVIEW.md
STAGE_PLAN.md
STAGE_LOG.md
DATABASE_SCHEMA.md
MIGRATION_NOTES.md
SECURITY_NOTES.md
DEPLOYMENT_NOTES.md
BILLPLZ_INTEGRATION.md
AD_SYSTEM_NOTES.md
```

After each development stage, update:

```text
STAGE_LOG.md
DATABASE_SCHEMA.md if schema changed
DEPLOYMENT_NOTES.md if deployment requirement changed
```

## 1.4 How to avoid 1M context error

Claude Code must follow this rule:

```text
One stage = one focused development session.
Do not open all files.
Do not rewrite unrelated modules.
Do not paste full database dumps into context.
Do not inspect vendor folders unless required.
Use summaries and project notes for continuity.
```

For every new stage, first read:

```text
PROJECT_OVERVIEW.md
STAGE_PLAN.md
STAGE_LOG.md
DATABASE_SCHEMA.md
```

Then inspect only the required files for that stage.

---

# 2. Product Direction

## 2.1 Website positioning

ihomestay.my should be positioned as:

```text
Malaysia Homestay Directory — Find and contact homestay owners directly.
```

This is not Airbnb.
This is not Agoda.
This is not a booking engine in Version 1.
This is a direct-contact homestay directory.

## 2.2 Main website goals

The new ihomestay.my must allow:

1. Users to register as homestay owners.
2. Homestay owners to publish homestay listings.
3. Free owners to submit up to 3 listings.
4. Verified owners to submit unlimited listings, subject to fair use.
5. Normal visitors to search homestays across Malaysia.
6. Visitors to contact homestay owners directly.
7. Admin to add, edit, modify, approve, reject, suspend, and delete homestays.
8. Admin to publish articles.
9. Advertisers to buy banner ad space automatically and wait for admin approval.
10. Payments to be handled using Billplz.
11. Existing WordPress + HivePress listings and users to be migrated into the new system.

## 2.3 Malaysia-only rule

Only Malaysia homestay listings are allowed.

The system should support:

- State
- City
- Area

Do not allow overseas listings.

Rejected examples:

```text
Singapore apartment
Thailand villa
Indonesia resort
Casino room agent
Hotel room reseller
Fake travel package
```

---

# 3. Business Model

## 3.1 Free owner model

Free owner rules:

```text
- Can submit up to 3 listings
- Listings require admin approval
- Listing shows Non-Verified Owner label
- WhatsApp contact is allowed, but visitor must see warning popup first
- Lower search ranking than verified owners
- Cannot buy Featured Listing
- Cannot buy sponsored article placement
- Limited image upload
```

Recommended free owner image limit:

```text
5 images per listing
```

## 3.2 Verified owner model

Verified owner rules:

```text
- Can submit unlimited listings, subject to fair use policy
- Gets Verified Owner badge
- Higher ranking
- No non-verified warning popup
- Can buy Featured Listing
- Can buy sponsored article/listing placement
- Can view listing performance statistics
- More image upload allowance
```

Recommended verified owner image limit:

```text
15 images per listing
```

## 3.3 Verification wording

Use the term:

```text
Verified Owner
```

Do not use:

```text
Verified Customer
```

Because the user is listing a homestay, not buying from the website.

## 3.4 Verification status

Use these statuses:

```text
unverified
pending_verification
verified
rejected
suspended
```

## 3.5 Featured listing

Featured Listing is only available to Verified Owners.

Reason:

```text
Do not promote unverified or risky listings.
```

Featured Listing benefits:

```text
- Appears above normal listings
- Featured badge
- Homepage exposure
- State/city page exposure
- Better search ranking
```

Suggested pricing:

```text
RM19 / 7 days
RM49 / 30 days
RM129 / 90 days
```

## 3.6 Banner ad income

Advertisers can buy ad banner spaces.

Rules:

```text
- Customer selects ad package
- Customer uploads banner
- Customer enters destination URL or WhatsApp link
- Customer pays using Billplz
- Payment success does not publish ad immediately
- Admin must approve before ad goes live
- Ad duration starts only after admin approval
- All ads must be marked Sponsored
```

## 3.7 Article income

Admin can publish SEO articles.

Article can later be monetized through:

```text
- Sponsored article
- Sponsored listing placement inside article
- Article banner ad
```

---

# 4. Tech Stack

## 4.1 Hosting environment

Target deployment:

```text
cPanel + PHP + MySQL + Git + SSH + Terminal
```

## 4.2 Preferred backend approach

Use one of the following:

### Option A: Laravel

Use Laravel if the server supports:

```bash
php -v
composer -V
php artisan
```

Laravel is preferred if Composer and terminal access work properly.

### Option B: Custom PHP MVC

Use custom PHP MVC if shared hosting has limited Laravel support.

Recommended custom structure:

```text
/public
/app
/app/Controllers
/app/Models
/app/Views
/app/Core
/config
/database
/database/migrations
/database/seeders
/uploads
/storage
/storage/logs
/resources
/resources/views
```

Claude Code must check the hosting capability before finalizing framework choice.

For safest cPanel compatibility, a clean Custom PHP MVC is acceptable.

## 4.3 Frontend

Use:

```text
HTML
CSS
JavaScript
Bootstrap or Tailwind
Responsive design
```

Do not overbuild frontend SPA in Version 1.

## 4.4 Database

Use:

```text
MySQL or MariaDB
UTF8MB4
InnoDB
```

---

# 5. User Roles

## 5.1 Roles

Required roles:

```text
admin
owner
advertiser
visitor
```

Visitor does not need login.

Owner and advertiser can use the same user table with different role or capabilities.

## 5.2 Admin permissions

Admin can:

```text
- Manage users
- Manage owners
- Manage listings
- Approve/reject listings
- Manage verification
- Manage featured listings
- Manage articles
- Manage ad packages
- Approve/reject ads
- View payments
- View reports
- Manage locations
- Manage facilities
- View click statistics
```

## 5.3 Owner permissions

Owner can:

```text
- Register
- Login
- Edit profile
- Submit listing
- Edit own listing
- Upload listing images
- View listing status
- View listing performance
- Apply for verification
- Buy featured listing if verified
```

## 5.4 Advertiser permissions

Advertiser can:

```text
- Register/login
- Choose ad package
- Upload ad creative
- Pay using Billplz
- View approval status
- View ad performance
```

---

# 6. Frontend Pages

## 6.1 Public pages

Required public pages:

```text
/
/homestay
/homestay/{state}
/homestay/{state}/{city}
/homestay/{state}/{city}/{slug}
/search
/articles
/articles/{slug}
/advertise
/login
/register
/forgot-password
/privacy-policy
/terms
/safety-guidelines
/contact
```

## 6.2 Homepage structure

Homepage sections:

```text
1. Hero search box
2. Home top banner ad
3. Popular states
4. Featured homestays
5. Latest homestays
6. Home middle banner ad
7. Latest articles
8. Home bottom banner ad
9. CTA: Publish your homestay
10. CTA: Advertise with us
```

## 6.3 Hero search

Fields:

```text
Destination keyword
State
City
Guests
Private pool optional filter
Search button
```

## 6.4 Search/listing result page

Filters:

```text
State
City
Area
Price range
Max guests
Bedrooms
Bathrooms
Private pool
Near beach
Muslim friendly
Pet friendly
WiFi
Parking
Aircond
Featured first
Latest
```

Ad positions:

```text
Search Top Banner
Inline Banner after every 6 listings
Sidebar Banner on desktop only
```

## 6.5 Listing detail page

Required sections:

```text
Gallery
Title
Location
Price from
Owner verification badge or warning
Short description
Facilities
Room info
Guest capacity
House rules
Map area
Owner profile
WhatsApp button
Phone button
Inquiry form optional
Safety reminder
Similar homestays
Report listing button
Sponsored banner area
```

## 6.6 Non-verified listing warning

For Non-Verified Owner listing, show label:

```text
Non-Verified Owner
This owner has not completed ihomestay.my verification. Please deal carefully.
```

Before WhatsApp opens, show popup:

```text
You are contacting a Non-Verified Owner.
Please confirm the property details, owner identity, price, and booking terms before making any payment.
ihomestay.my is a directory platform and does not collect booking payment or guarantee private transactions.

[Continue to WhatsApp]
[Cancel]
```

## 6.7 WhatsApp button logic

Do not remove WhatsApp button from non-verified listings.

Logic:

```text
If owner is verified:
    WhatsApp button opens directly and records click.
If owner is unverified:
    Show warning popup first.
    If visitor confirms, open WhatsApp and record click.
```

## 6.8 Article pages

Article listing page:

```text
/articles
```

Article detail page:

```text
/articles/{slug}
```

Article page ad positions:

```text
Article Top Banner
Article Middle Banner
Article Bottom Banner
```

Homepage should show latest 3 to 6 articles.

## 6.9 Advertise page

Page URL:

```text
/advertise
```

Purpose:

```text
Allow customers to buy banner ads automatically.
```

Flow:

```text
Choose package
Choose targeting
Upload banner
Enter destination URL / WhatsApp link
Preview ad
Pay with Billplz
Wait for admin approval
Track performance
```

---

# 7. Admin Backend

## 7.1 Admin dashboard

Show:

```text
Total listings
Pending listings
Published listings
Total owners
Pending verification
Verified owners
Total payments
Total ad orders
Pending ad approvals
Active ads
Total article count
WhatsApp clicks
Listing views
```

## 7.2 Listing management

Admin can:

```text
Add listing
Edit listing
Delete listing
Approve listing
Reject listing
Suspend listing
Mark as featured
Set featured expiry
Assign owner
Upload images
Change location
View contact clicks
View reports
```

## 7.3 Owner management

Admin can:

```text
View owner
Edit owner
Suspend owner
Verify owner
Reject verification
Reset password
View owner listings
View owner payments
View owner click stats
```

## 7.4 Article management

Admin can:

```text
Add article
Edit article
Delete article
Publish/draft article
Upload cover image
Set category
Set meta title
Set meta description
Set slug
Add listing blocks inside article
Add sponsored listing placement
```

## 7.5 Ad management

Admin can:

```text
Create ad packages
Edit ad packages
View ad orders
Approve ad
Reject ad with reason
Request changes
Suspend active ad
Extend ad duration
View payment status
View impression/click statistics
```

## 7.6 Location management

Admin can manage:

```text
States
Cities
Areas
```

Malaysia-only structure:

```text
Country = Malaysia
State > City > Area
```

## 7.7 Facility management

Admin can manage facility tags:

```text
Private Pool
BBQ Area
Karaoke
Near Beach
Near Town
Muslim Friendly
Pet Friendly
WiFi
Kitchen
Parking
Aircond
Washing Machine
```

Do not hard-code all facility filters. Use database-driven facilities.

---

# 8. Owner Dashboard

## 8.1 Owner dashboard pages

Required pages:

```text
/owner/dashboard
/owner/listings
/owner/listings/create
/owner/listings/{id}/edit
/owner/profile
/owner/verification
/owner/payments
/owner/statistics
```

## 8.2 Owner listing limit logic

Free owner:

```text
Max 3 listings
```

Verified owner:

```text
Unlimited, subject to fair use
```

Pseudo logic:

```php
if ($owner->verification_status !== 'verified') {
    if ($owner->published_listing_count + $owner->pending_listing_count >= 3) {
        block submission and show upgrade message;
    }
}
```

## 8.3 Owner upgrade prompt

When free owner reaches limit:

```text
You have reached the free listing limit of 3 listings. Upgrade to Verified Owner to submit more listings, receive a Verified Owner badge, and improve your listing ranking.
```

---

# 9. Advertiser Dashboard

## 9.1 Advertiser pages

Required pages:

```text
/advertiser/dashboard
/advertiser/ads
/advertiser/ads/create
/advertiser/ads/{id}
/advertiser/payments
```

## 9.2 Advertiser dashboard stats

Show:

```text
Ad status
Start date
End date
Impressions
Clicks
CTR
Payment status
Review status
```

---

# 10. Database Schema Draft

Important: Use migrations. Do not create tables manually without recording schema.

## 10.1 users

```sql
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
```

## 10.2 owner_profiles

```sql
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
updated_at DATETIME
```

## 10.3 states

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(191) NOT NULL,
slug VARCHAR(191) NOT NULL UNIQUE,
is_active TINYINT(1) DEFAULT 1
```

## 10.4 cities

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
state_id BIGINT UNSIGNED NOT NULL,
name VARCHAR(191) NOT NULL,
slug VARCHAR(191) NOT NULL,
is_active TINYINT(1) DEFAULT 1
```

## 10.5 areas

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
city_id BIGINT UNSIGNED NOT NULL,
name VARCHAR(191) NOT NULL,
slug VARCHAR(191) NOT NULL,
is_active TINYINT(1) DEFAULT 1
```

## 10.6 listings

```sql
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
updated_at DATETIME
```

## 10.7 listing_images

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
listing_id BIGINT UNSIGNED NOT NULL,
image_path VARCHAR(255) NOT NULL,
sort_order INT DEFAULT 0,
is_cover TINYINT(1) DEFAULT 0,
created_at DATETIME
```

## 10.8 facilities

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(191) NOT NULL,
slug VARCHAR(191) NOT NULL UNIQUE,
is_active TINYINT(1) DEFAULT 1
```

## 10.9 listing_facilities

```sql
listing_id BIGINT UNSIGNED NOT NULL,
facility_id BIGINT UNSIGNED NOT NULL
```

## 10.10 listing_views

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
listing_id BIGINT UNSIGNED NOT NULL,
visitor_ip_hash VARCHAR(191) NULL,
user_agent TEXT NULL,
page_url TEXT NULL,
created_at DATETIME
```

## 10.11 listing_clicks

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
listing_id BIGINT UNSIGNED NOT NULL,
owner_id BIGINT UNSIGNED NOT NULL,
click_type ENUM('whatsapp','phone','email','inquiry') NOT NULL,
visitor_ip_hash VARCHAR(191) NULL,
user_agent TEXT NULL,
page_url TEXT NULL,
created_at DATETIME
```

## 10.12 packages

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(191) NOT NULL,
package_type ENUM('verification','featured_listing','ad','sponsored_article','pro_owner') NOT NULL,
price DECIMAL(10,2) NOT NULL,
duration_days INT NULL,
is_active TINYINT(1) DEFAULT 1,
created_at DATETIME,
updated_at DATETIME
```

## 10.13 payments

```sql
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
updated_at DATETIME
```

## 10.14 articles

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
author_id BIGINT UNSIGNED NOT NULL,
title VARCHAR(191) NOT NULL,
slug VARCHAR(191) NOT NULL UNIQUE,
excerpt TEXT NULL,
content LONGTEXT NOT NULL,
cover_image VARCHAR(255) NULL,
category_id BIGINT UNSIGNED NULL,
status ENUM('draft','published','scheduled') DEFAULT 'draft',
meta_title VARCHAR(191) NULL,
meta_description TEXT NULL,
published_at DATETIME NULL,
created_at DATETIME,
updated_at DATETIME
```

## 10.15 article_categories

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(191) NOT NULL,
slug VARCHAR(191) NOT NULL UNIQUE,
is_active TINYINT(1) DEFAULT 1
```

## 10.16 ad_placements

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(191) NOT NULL,
slug VARCHAR(191) NOT NULL UNIQUE,
page_type ENUM('home','search','state','city','listing_detail','article','global') NOT NULL,
position VARCHAR(100) NOT NULL,
allowed_desktop_size VARCHAR(50) NULL,
allowed_mobile_size VARCHAR(50) NULL,
base_price DECIMAL(10,2) NOT NULL,
duration_days INT DEFAULT 30,
is_active TINYINT(1) DEFAULT 1,
created_at DATETIME,
updated_at DATETIME
```

## 10.17 ad_orders

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
user_id BIGINT UNSIGNED NOT NULL,
ad_placement_id BIGINT UNSIGNED NOT NULL,
target_state_id BIGINT UNSIGNED NULL,
target_city_id BIGINT UNSIGNED NULL,
target_article_category_id BIGINT UNSIGNED NULL,
amount DECIMAL(10,2) NOT NULL,
duration_days INT NOT NULL,
payment_status ENUM('pending','paid','failed','cancelled','refunded') DEFAULT 'pending',
review_status ENUM('draft','pending_payment','paid_pending_review','approved_scheduled','active','rejected','expired','cancelled','refunded') DEFAULT 'draft',
start_at DATETIME NULL,
end_at DATETIME NULL,
created_at DATETIME,
updated_at DATETIME
```

## 10.18 ad_creatives

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
ad_order_id BIGINT UNSIGNED NOT NULL,
title VARCHAR(191) NOT NULL,
image_desktop VARCHAR(255) NOT NULL,
image_mobile VARCHAR(255) NULL,
destination_url TEXT NULL,
whatsapp_number VARCHAR(50) NULL,
status ENUM('pending','approved','rejected','suspended') DEFAULT 'pending',
rejection_reason TEXT NULL,
created_at DATETIME,
updated_at DATETIME
```

## 10.19 ad_impressions

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
ad_order_id BIGINT UNSIGNED NOT NULL,
ad_placement_id BIGINT UNSIGNED NOT NULL,
page_url TEXT NULL,
visitor_ip_hash VARCHAR(191) NULL,
user_agent TEXT NULL,
created_at DATETIME
```

## 10.20 ad_clicks

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
ad_order_id BIGINT UNSIGNED NOT NULL,
ad_placement_id BIGINT UNSIGNED NOT NULL,
page_url TEXT NULL,
visitor_ip_hash VARCHAR(191) NULL,
created_at DATETIME
```

## 10.21 reports

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
listing_id BIGINT UNSIGNED NULL,
reported_by_name VARCHAR(191) NULL,
reported_by_email VARCHAR(191) NULL,
reason VARCHAR(191) NOT NULL,
message TEXT NULL,
status ENUM('pending','reviewed','resolved','dismissed') DEFAULT 'pending',
created_at DATETIME,
updated_at DATETIME
```

## 10.22 redirects

```sql
id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
old_path VARCHAR(255) NOT NULL UNIQUE,
new_path VARCHAR(255) NOT NULL,
redirect_type INT DEFAULT 301,
is_active TINYINT(1) DEFAULT 1,
created_at DATETIME,
updated_at DATETIME
```

---

# 11. WordPress + HivePress Migration

## 11.1 Migration goal

The new system must import:

```text
Existing users
Existing homestay listings
Listing owner relationship
Listing images
Listing categories/facilities if available
Listing state/city/location data
Listing slug/URL for SEO
```

## 11.2 WordPress tables to inspect

Inspect:

```text
wp_posts
wp_postmeta
wp_users
wp_usermeta
wp_terms
wp_term_taxonomy
wp_term_relationships
wp_options
```

HivePress listing post type may be:

```text
hp_listing
```

But Claude Code must verify from the database.

Run:

```sql
SELECT post_type, COUNT(*)
FROM wp_posts
GROUP BY post_type;
```

Find post meta keys:

```sql
SELECT meta_key, COUNT(*)
FROM wp_postmeta
GROUP BY meta_key
ORDER BY COUNT(*) DESC;
```

Find user meta keys:

```sql
SELECT meta_key, COUNT(*)
FROM wp_usermeta
GROUP BY meta_key
ORDER BY COUNT(*) DESC;
```

## 11.3 Migration staging

### Migration Stage A — Audit

Create script:

```text
/tools/migration/audit_wordpress.php
```

Purpose:

```text
- Detect listing post type
- Detect custom listing fields
- Detect phone/WhatsApp field
- Detect image attachment relationship
- Export sample report
```

### Migration Stage B — Export users

Create:

```text
/tools/migration/export_users.php
```

Export to:

```text
/storage/migration/users_export.json
```

Fields:

```text
old_wp_user_id
name
email
phone
whatsapp
registered_at
```

Do not migrate WordPress password as normal password.
Set:

```text
password_reset_required = 1
```

### Migration Stage C — Export listings

Create:

```text
/tools/migration/export_listings.php
```

Export to:

```text
/storage/migration/listings_export.json
```

Fields:

```text
old_wp_listing_id
title
slug
description
owner_old_wp_user_id
state
city
area
address
price
max_guests
bedrooms
bathrooms
facilities
image_ids
image_urls
created_at
updated_at
status
```

### Migration Stage D — Import to new database

Create:

```text
/tools/migration/import_users.php
/tools/migration/import_listings.php
/tools/migration/import_images.php
/tools/migration/create_redirects.php
```

Keep old IDs:

```text
users.old_wp_user_id
listings.old_wp_listing_id
```

### Migration Stage E — Redirects

Preserve SEO.

Old URLs should redirect to new URLs.

Example:

```text
/listing/old-homestay-slug -> /homestay/pahang/kuantan/old-homestay-slug
```

Store in redirects table.

---

# 12. Billplz Integration

## 12.1 Purpose

Use Billplz for:

```text
Verified Owner payment
Featured Listing payment
Ad banner payment
Sponsored article payment
Pro owner package payment
```

## 12.2 Payment rule

Never activate paid feature only from browser redirect.

Activation must be based on:

```text
Billplz webhook or verified payment status
```

## 12.3 Billplz flow

```text
User selects package
System creates payment record
System creates Billplz bill
User pays on Billplz
Billplz redirects user back
Billplz sends webhook
System verifies payment
System marks payment as paid
System activates package/order
```

## 12.4 Payment activation rules

Verification package:

```text
Payment paid -> verification_status = pending_verification
Admin still needs to verify documents
```

Featured listing:

```text
Payment paid -> if owner verified -> activate featured listing
```

Ad order:

```text
Payment paid -> review_status = paid_pending_review
Admin approval required
Duration starts after admin approval
```

Sponsored article:

```text
Payment paid -> status = paid_pending_review
Admin approval required
```

---

# 13. Banner Ad System

## 13.1 Version 1 ad placements

Start with only these placements:

```text
Home Top Banner
City Page Top Banner
Article Middle Banner
```

Do not create too many ad products in Version 1.

## 13.2 Suggested prices

```text
Home Top Banner — RM199 / 30 days
City Page Top Banner — RM49 / 30 days
Article Middle Banner — RM49 / 30 days
```

## 13.3 Future placements

Can add later:

```text
Home Middle Banner
Home Bottom Banner
State Page Banner
Search Result Inline Banner
Listing Detail Banner
Article Top Banner
Article Bottom Banner
```

## 13.4 Banner sizes

Version 1:

```text
Desktop banner: 1200 x 250
Mobile banner: 800 x 800
```

Allowed file types:

```text
jpg
jpeg
png
webp
```

Reject:

```text
php
svg
html
js
gif in Version 1
video in Version 1
```

Max file size:

```text
2MB
```

## 13.5 Ad approval rules

Admin must reject ads related to:

```text
Gambling
Ah long / illegal loan
Adult content
Fake investment
Illegal products
Vape / tobacco
Political hate content
Misleading medical claims
Fake homestay
Scam travel package
```

Allowed advertiser categories:

```text
Homestay supplies
Cleaning service
Laundry service
Furniture
Renovation
Aircond service
Catering
BBQ rental
Event decoration
Travel service
Car rental
Photography
Local attractions
Insurance
```

## 13.6 Ad display rules

All ads must show small label:

```text
Sponsored
```

If multiple ads are active for same placement and target:

```text
Rotate fairly
Prefer ads with fewer impressions
Record impression
Record click
```

## 13.7 Ad duration rule

Ad duration starts only after admin approval.

Example:

```text
Customer pays on 1 July.
Admin approves on 3 July.
30-day ad runs from 3 July to 2 August.
```

---

# 14. Ranking Logic

Search result ranking should not be only latest.

Suggested scoring:

```text
Featured listing: +100
Verified owner: +30
Has 5+ images: +20
Has price: +10
Updated within 30 days: +10
Non-verified owner: -20
Incomplete listing: -20
```

Sort by:

```text
ranking_score DESC
updated_at DESC
```

Free non-verified listings can still appear, but lower.

---

# 15. SEO Requirements

## 15.1 SEO-friendly URLs

Use:

```text
/homestay/{state}
/homestay/{state}/{city}
/homestay/{state}/{city}/{slug}
/articles/{slug}
```

## 15.2 Meta fields

Listings should have auto-generated meta title and description.

Example title:

```text
{Listing Title} Homestay in {City}, {State} | ihomestay.my
```

Example description:

```text
Find {Listing Title} in {City}, {State}. View facilities, price, photos and contact homestay owner directly on ihomestay.my.
```

Articles must allow custom:

```text
meta_title
meta_description
```

## 15.3 Sitemap

Generate:

```text
/sitemap.xml
```

Include:

```text
Homepage
State pages
City pages
Published listings
Published articles
```

## 15.4 Redirects

Support 301 redirects from old WordPress/HivePress URLs.

---

# 16. Security Requirements

Mandatory:

```text
Password hashing using password_hash/password_verify or framework equivalent
Prepared statements / ORM protection
CSRF protection
XSS protection
Input validation
Admin middleware
File upload validation
Image MIME checking
Login rate limiting
Email verification
Password reset
Secure session handling
Do not expose .env
Do not expose storage/logs publicly
Hash visitor IP before storing analytics
```

Image upload security:

```text
Check extension
Check MIME type
Rename file
Store outside executable folder if possible
Never allow PHP/SVG/HTML/JS upload
Limit file size
Resize images when possible
```

---

# 17. Deployment Requirements

## 17.1 cPanel deployment

The application must be deployable using:

```text
Git Version Control
SSH
Terminal
```

## 17.2 Environment file

Use environment configuration:

```text
APP_NAME=ihomestay.my
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.ihomestay.my
DB_HOST=localhost
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
BILLPLZ_API_KEY=
BILLPLZ_COLLECTION_ID=
BILLPLZ_X_SIGNATURE_KEY=
```

Never commit real secrets.

## 17.3 Public folder rule

If using Laravel, point domain document root to:

```text
/public
```

If using custom PHP MVC, keep only public entry files in:

```text
/public
```

Do not expose app, config, storage, database, or vendor directly.

---

# 18. Development Stages

## Stage 0 — Project Setup and Hosting Check

Goal:

```text
Confirm hosting capabilities and choose framework.
```

Tasks:

```text
Check PHP version
Check Composer
Check MySQL/MariaDB
Check Git deployment
Check writable upload folder
Create project structure
Create environment config
Create initial documentation files
```

Do not build features yet.

Deliverables:

```text
PROJECT_OVERVIEW.md
STAGE_PLAN.md
DEPLOYMENT_NOTES.md
Basic project skeleton
```

---

## Stage 1 — Core Database and Auth

Goal:

```text
Create database foundation and login/register system.
```

Tasks:

```text
Create users table
Create owner_profiles table
Create password reset flow
Create login
Create register
Create logout
Create role handling
Create admin seed account
```

Deliverables:

```text
Working authentication
Admin login
Owner registration
Basic dashboard redirect by role
```

---

## Stage 2 — Location and Facility Module

Goal:

```text
Create Malaysia location structure and facility management.
```

Tasks:

```text
Create states table
Create cities table
Create areas table
Seed Malaysia states
Create facilities table
Create admin CRUD for locations
Create admin CRUD for facilities
```

Deliverables:

```text
Admin can manage states/cities/areas
Admin can manage facilities
```

---

## Stage 3 — Listing Module

Goal:

```text
Allow owners and admin to create homestay listings.
```

Tasks:

```text
Create listings table
Create listing_images table
Create listing_facilities table
Owner create listing
Owner edit own listing
Image upload
Admin listing approval
Admin listing rejection
Admin listing suspension
Listing status flow
```

Deliverables:

```text
Owner can submit listing
Admin can approve/reject
Published listing can be viewed publicly
```

---

## Stage 4 — Public Search and Listing Pages

Goal:

```text
Build public directory pages.
```

Tasks:

```text
Homepage
Search page
State page
City page
Listing detail page
Filters
Ranking logic
SEO URL routing
Listing view tracking
WhatsApp click tracking
Non-verified warning popup
```

Deliverables:

```text
Visitors can search and view listings
Visitors can contact owner directly
Clicks and views are recorded
```

---

## Stage 5 — Owner Verification and Listing Limits

Goal:

```text
Implement free vs verified owner logic.
```

Tasks:

```text
Free owner max 3 listings
Verified owner unlimited fair use
Verification application form
Document upload
Admin approve/reject verification
Verified Owner badge
Upgrade prompts
```

Deliverables:

```text
Free owners limited to 3 listings
Verified owners get badge and higher ranking
```

---

## Stage 6 — Billplz Payment Integration

Goal:

```text
Enable payment for verification, featured listing, and ads.
```

Tasks:

```text
Create packages table
Create payments table
Create Billplz config
Create bill creation service
Create payment callback route
Create webhook route
Verify payment status
Store raw response
Activate correct product after payment rules
```

Deliverables:

```text
System can create Billplz payment
System can receive webhook
System can mark payment as paid
```

---

## Stage 7 — Featured Listing Module

Goal:

```text
Allow verified owners to buy featured listing.
```

Tasks:

```text
Create featured listing package
Allow verified owner to select listing
Create Billplz bill
After payment, activate featured listing
Set featured_until
Expire featured automatically
Admin can manually feature/unfeature
```

Deliverables:

```text
Featured listing works with expiry
Featured listing appears higher in search
```

---

## Stage 8 — Article CMS

Goal:

```text
Allow admin to publish articles for SEO.
```

Tasks:

```text
Create articles table
Create article_categories table
Admin article CRUD
Cover image upload
Meta title/description
Article listing page
Article detail page
Latest article section on homepage
```

Deliverables:

```text
Admin can publish articles
Articles appear on homepage and article page
```

---

## Stage 9 — Banner Advertising System

Goal:

```text
Allow customers to buy banner ad space automatically, pay with Billplz, and wait for admin approval.
```

Tasks:

```text
Create ad_placements table
Create ad_orders table
Create ad_creatives table
Create ad_impressions table
Create ad_clicks table
Create /advertise page
Advertiser create ad order
Banner upload
Billplz payment
Ad status paid_pending_review
Admin approve/reject ad
Ad duration starts after approval
Display active ads by placement and target
Track impressions
Track clicks
Advertiser dashboard stats
```

Deliverables:

```text
Advertiser can buy banner ad
Admin can approve ad
Approved ad displays on selected placement
System tracks impressions and clicks
```

---

## Stage 10 — WordPress + HivePress Migration Tools

Goal:

```text
Import existing WordPress/HivePress data.
```

Tasks:

```text
Create audit_wordpress.php
Detect HivePress listing post type
Detect custom fields
Export users
Export listings
Export images
Import users
Import listings
Import images
Create redirects
Generate migration report
```

Deliverables:

```text
Old users imported
Old listings imported
Images migrated
Old URLs redirected
```

Important:

```text
Do migration after core listing system is stable.
```

---

## Stage 11 — Reports, Safety, and Moderation

Goal:

```text
Improve platform trust and safety.
```

Tasks:

```text
Report listing button
Admin report management
Suspicious listing flag
Duplicate phone/address detection
Safety guidelines page
Terms and disclaimer page
```

Deliverables:

```text
Visitors can report listings
Admin can review reports
Safety pages are live
```

---

## Stage 12 — SEO, Sitemap, and Launch Cleanup

Goal:

```text
Prepare for public launch.
```

Tasks:

```text
Sitemap.xml
Robots.txt
Meta titles
Meta descriptions
Schema markup for listings/articles
301 redirects
404 page
Performance check
Mobile responsive check
Security check
Backup process
```

Deliverables:

```text
Site ready for production launch
```

---

# 19. Important Rules for Version 1

Do not build these in Version 1:

```text
Online booking
Guest payment to owner
Commission system
Refund system
Calendar availability
Mobile app
Complex chat system
Review dispute system
```

Reason:

```text
These features create operational burden and legal/customer-service risk.
```

Version 1 must focus on:

```text
Directory
Direct contact
Owner verification
Featured listing
Banner ads
Articles
Migration
SEO
```

---

# 20. Claude Code Working Method

For every stage, Claude Code should follow:

```text
1. Read PROJECT_OVERVIEW.md
2. Read STAGE_PLAN.md
3. Read STAGE_LOG.md
4. Read DATABASE_SCHEMA.md
5. Identify files needed for current stage only
6. Create or edit files
7. Run syntax checks/tests if possible
8. Update STAGE_LOG.md
9. Update relevant documentation
10. Stop and summarize what was completed
```

Do not proceed to next stage automatically unless instructed.

## 20.1 Stage completion format

At the end of each stage, update STAGE_LOG.md like this:

```text
## Stage X — Stage Name
Date:
Completed:
- item 1
- item 2
Files created:
- path/file.php
Files modified:
- path/file.php
Database changes:
- table_name
Pending issues:
- issue 1
Next recommended stage:
- Stage Y
```

---

# 21. First Task for Claude Code

Start with Stage 0 only.

Do not build the full project yet.

Stage 0 instruction:

```text
Please inspect the current project folder and hosting capability.
Check whether Laravel is suitable or whether Custom PHP MVC is safer for this cPanel environment.
Create the initial project structure and documentation files:

PROJECT_OVERVIEW.md
STAGE_PLAN.md
STAGE_LOG.md
DATABASE_SCHEMA.md
MIGRATION_NOTES.md
SECURITY_NOTES.md
DEPLOYMENT_NOTES.md
BILLPLZ_INTEGRATION.md
AD_SYSTEM_NOTES.md

Do not implement full features yet.
Do not create unnecessary files.
Use ECC to inspect and manage the project without loading unnecessary context.
After completing Stage 0, summarize the recommended framework choice and next step.
```

---

# 22. Final Business Logic Summary

The final ihomestay.my system should work like this:

```text
Visitor searches homestay in Malaysia
Visitor views listing
If owner is verified, contact button opens directly
If owner is non-verified, warning popup appears before WhatsApp
Owner can list free up to 3 listings
Owner can upgrade to Verified Owner through Billplz
Verified Owner can list unlimited homestays under fair use
Verified Owner can buy Featured Listing
Advertiser can buy banner ad space through Billplz
Paid ad waits for admin approval
Admin publishes articles for SEO
Articles and city pages bring Google traffic
Traffic creates value for featured listing and banner ads
```

Main monetization paths:

```text
Verified Owner fee
Featured Listing fee
Banner Advertising fee
Sponsored Article fee
Sponsored Listing Placement fee
Pro Owner package later
```

Main trust controls:

```text
Admin approval
Verified Owner badge
Non-verified warning popup
Report listing button
Ad approval
Malaysia-only listing rules
No booking payment in Version 1
```

