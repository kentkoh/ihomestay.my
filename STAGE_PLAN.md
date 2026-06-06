# STAGE_PLAN.md — ihomestay.my Development Stages

## Current Stage: 1 (Code complete — pending DNS + deployment test)

---

## Stage 0 — Project Setup and Hosting Check ✅
**Goal:** Confirm hosting capabilities, choose framework, create project skeleton.

Tasks:
- [x] Read and summarize project instruction
- [x] Inspect existing project folder
- [x] Choose framework (Custom PHP MVC — cPanel-safe)
- [x] Create project directory structure
- [x] Create documentation files (9 files)
- [x] Create .env.example
- [x] Create public/index.php entry point
- [x] Create public/.htaccess for URL routing
- [x] Create .gitignore
- [x] Push project to GitHub (repo: kentkoh/ihomestay.my)
- [x] Set up cPanel Git Version Control (pull from GitHub)
- [x] Create subdomain new.ihomestay.my on cPanel
- [x] Verify PHP 8.2.31 and pdo_mysql on server
- [x] Create MySQL database: kuantan1_ihomestay
- [x] Create .env file on server
- [x] Add DNS A record in Cloudflare (new → 111.90.134.20)

---

## Stage 1 — Core Database and Auth ✅
**Goal:** Create database foundation and login/register system.

Tasks:
- [x] Migration: users table
- [x] Migration: owner_profiles table
- [x] Migration runner: database/migrate.php
- [x] Core class: Auth.php
- [x] Core class: CSRF.php
- [x] Model: User.php
- [x] Controller: AuthController.php (login, register, logout)
- [x] Controller: AdminController.php
- [x] Controller: OwnerController.php
- [x] View: layouts/main.php (Bootstrap 5)
- [x] View: auth/login.php
- [x] View: auth/register.php
- [x] View: admin/dashboard.php
- [x] View: owner/dashboard.php
- [x] Seeder: AdminSeeder.php
- [x] Updated router with all auth routes
- [x] Push Stage 1 code to GitHub
- [x] Pull to server via cPanel Git Version Control
- [x] Run: php database/migrate.php on server
- [x] Run: php database/seeders/AdminSeeder.php on server
- [x] Test login at https://new.ihomestay.my/login
- [x] Test register at https://new.ihomestay.my/register
- [x] Confirm admin dashboard loads
- [x] Confirm owner dashboard loads

Default admin account (change password after first login):
- Email: admin@ihomestay.my
- Password: Admin@1234

---

## Stage 2 — Location and Facility Module
**Goal:** Malaysia state/city/area structure and facility management.

Tasks:
- [ ] Migration: states, cities, areas tables
- [ ] Seed all 16 Malaysian states + major cities
- [ ] Migration: facilities table
- [ ] Seed default facilities (WiFi, Pool, BBQ, etc.)
- [ ] Admin CRUD: states, cities, areas
- [ ] Admin CRUD: facilities

Deliverables: Admin can manage Malaysia locations and facilities

---

## Stage 3 — Listing Module
**Goal:** Owners and admin can create/manage homestay listings.

Map: Leaflet.js + OpenStreetMap (free, no API key). Owner clicks map to drop pin → lat/lng saved to DB. Public page shows pinned location. Address search via Nominatim (free OSM geocoder).

Tasks:
- [ ] Migration: listings, listing_images, listing_facilities tables (include latitude, longitude columns)
- [ ] Owner create listing form (with Leaflet map pin picker)
- [ ] Owner edit own listing
- [ ] Image upload (MIME check, rename, size limit)
- [ ] Admin listing approval / rejection / suspension
- [ ] Listing status flow (draft → pending → published)
- [ ] Free owner: max 3 listings enforcement
- [ ] Map pin display on public listing detail page

Deliverables: Owner can submit listing with map pin, admin can approve/reject

---

## Stage 4 — Public Search and Listing Pages
**Goal:** Build the public-facing directory.

Tasks:
- [ ] Homepage with hero search box
- [ ] Search results page with filters
- [ ] State page: /homestay/{state}
- [ ] City page: /homestay/{state}/{city}
- [ ] Listing detail page: /homestay/{state}/{city}/{slug}
- [ ] Ranking logic (featured +100, verified +30, etc.)
- [ ] Listing view tracking
- [ ] WhatsApp click tracking
- [ ] Non-verified owner warning popup

Deliverables: Visitors can search, browse, and contact owners

---

## Stage 5 — Owner Verification and Listing Limits
**Goal:** Free vs verified owner logic.

Tasks:
- [ ] Verification application form
- [ ] Document upload
- [ ] Admin approve/reject verification
- [ ] Verified Owner badge on listing and profile
- [ ] Upgrade prompt when free owner hits 3-listing limit

---

## Stage 6 — Billplz Payment Integration
**Goal:** Enable payments for verification, featured listing, ads.

Tasks:
- [ ] Migration: packages, payments tables
- [ ] Billplz API config and service class
- [ ] Bill creation flow
- [ ] Payment callback route (redirect)
- [ ] Webhook route (server-to-server)
- [ ] Payment verification and feature activation logic

---

## Stage 7 — Featured Listing Module
**Goal:** Verified owners can buy featured listing placement.

Tasks:
- [ ] Featured listing package options (7/30/90 days)
- [ ] Buy featured flow with Billplz
- [ ] featured_until expiry automation
- [ ] Admin manual feature/unfeature
- [ ] Featured listings appear higher in search

---

## Stage 8 — Article CMS
**Goal:** Admin publishes SEO articles.

Tasks:
- [ ] Migration: articles, article_categories tables
- [ ] Admin article CRUD with rich text editor
- [ ] Cover image upload
- [ ] Meta title/description fields
- [ ] Article listing page: /articles
- [ ] Article detail page: /articles/{slug}
- [ ] Homepage latest articles section

---

## Stage 9 — Banner Advertising System
**Goal:** Advertisers buy banner space, pay with Billplz, admin approves.

Tasks:
- [ ] Migration: ad_placements, ad_orders, ad_creatives, ad_impressions, ad_clicks
- [ ] Seed 3 Version 1 placements (Home Top, City Top, Article Middle)
- [ ] /advertise page
- [ ] Ad order creation + banner upload
- [ ] Billplz payment for ad order
- [ ] Admin approve/reject ad
- [ ] Ad display with rotation and impression/click tracking
- [ ] Advertiser dashboard with stats

---

## Stage 10 — WordPress + HivePress Migration Tools
**Goal:** Import existing data from old WordPress system.

Tasks:
- [ ] tools/migration/audit_wordpress.php
- [ ] tools/migration/export_users.php
- [ ] tools/migration/export_listings.php
- [ ] tools/migration/import_users.php
- [ ] tools/migration/import_listings.php
- [ ] tools/migration/import_images.php
- [ ] tools/migration/create_redirects.php
- [ ] Verify migrated data matches original counts

Note: Run after core listing system is fully stable.

---

## Stage 11 — Reports, Safety, and Moderation
**Goal:** Platform trust and safety features.

Tasks:
- [ ] Report listing button on listing detail page
- [ ] Admin report management panel
- [ ] Safety guidelines page: /safety-guidelines
- [ ] Terms and conditions page: /terms
- [ ] Privacy policy page: /privacy-policy

---

## Stage 12 — SEO, Sitemap, and Launch Cleanup
**Goal:** Production-ready public launch.

Tasks:
- [ ] /sitemap.xml auto-generation
- [ ] /robots.txt
- [ ] Schema markup for listings and articles
- [ ] 301 redirects from old WordPress URLs
- [ ] Custom 404 error page
- [ ] Performance audit (image sizes, page speed)
- [ ] Mobile responsive check on all pages
- [ ] Security audit (OWASP checklist)
- [ ] Final QA on new.ihomestay.my
- [ ] Domain cutover: new.ihomestay.my → ihomestay.my
