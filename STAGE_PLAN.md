# STAGE_PLAN.md — ihomestay.my Development Stages

## Current Stage: 0 (Completed)

---

## Stage 0 — Project Setup and Hosting Check ✅
**Goal:** Confirm hosting capabilities, choose framework, create project skeleton.

Tasks:
- [x] Read and summarize project instruction
- [x] Inspect existing project folder
- [x] Choose framework (Custom PHP MVC — cPanel-safe)
- [x] Create project directory structure
- [x] Create documentation files
- [x] Create .env.example
- [x] Create public/index.php entry point skeleton
- [x] Create .htaccess for URL routing
- [x] Create .gitignore

Do not build features in this stage.

---

## Stage 1 — Core Database and Auth
**Goal:** Create database foundation and login/register system.

Tasks:
- [ ] Migration: users table
- [ ] Migration: owner_profiles table
- [ ] Login page
- [ ] Register page
- [ ] Logout
- [ ] Password reset flow (email token)
- [ ] Role-based redirect after login
- [ ] Admin seed account
- [ ] Session handling and CSRF protection

Deliverables: Working auth, admin login, owner registration

---

## Stage 2 — Location and Facility Module
**Goal:** Malaysia state/city/area structure and facility management.

Tasks:
- [ ] Migration: states, cities, areas tables
- [ ] Seed all 16 Malaysian states
- [ ] Migration: facilities table
- [ ] Seed default facilities
- [ ] Admin CRUD for locations
- [ ] Admin CRUD for facilities

---

## Stage 3 — Listing Module
**Goal:** Owners and admin can create/manage homestay listings.

Tasks:
- [ ] Migration: listings, listing_images, listing_facilities tables
- [ ] Owner create listing form
- [ ] Owner edit own listing
- [ ] Image upload (with MIME check, rename, resize)
- [ ] Admin listing approval / rejection / suspension
- [ ] Listing status flow (draft → pending → published)

---

## Stage 4 — Public Search and Listing Pages
**Goal:** Build the public-facing directory.

Tasks:
- [ ] Homepage with hero search
- [ ] Search results page with filters
- [ ] State page /homestay/{state}
- [ ] City page /homestay/{state}/{city}
- [ ] Listing detail page /homestay/{state}/{city}/{slug}
- [ ] Ranking logic (scoring)
- [ ] View tracking
- [ ] WhatsApp click tracking
- [ ] Non-verified owner warning popup

---

## Stage 5 — Owner Verification and Listing Limits
**Goal:** Free vs verified owner logic.

Tasks:
- [ ] Free owner: max 3 listings enforcement
- [ ] Verification application form
- [ ] Document upload
- [ ] Admin approve/reject verification
- [ ] Verified Owner badge on listing

---

## Stage 6 — Billplz Payment Integration
**Goal:** Enable payments for verification, featured listing, ads.

Tasks:
- [ ] Migration: packages, payments tables
- [ ] Billplz API config
- [ ] Bill creation service
- [ ] Payment callback and webhook routes
- [ ] Payment verification and activation logic

---

## Stage 7 — Featured Listing Module
**Goal:** Verified owners can buy featured listing placement.

Tasks:
- [ ] Featured listing package
- [ ] Buy featured flow
- [ ] featured_until expiry logic
- [ ] Admin manual feature/unfeature
- [ ] Featured listing appears higher in search

---

## Stage 8 — Article CMS
**Goal:** Admin publishes SEO articles.

Tasks:
- [ ] Migration: articles, article_categories tables
- [ ] Admin article CRUD
- [ ] Article listing page /articles
- [ ] Article detail page /articles/{slug}
- [ ] Homepage latest articles section

---

## Stage 9 — Banner Advertising System
**Goal:** Advertisers buy banner space, admin approves.

Tasks:
- [ ] Migration: ad_placements, ad_orders, ad_creatives, ad_impressions, ad_clicks
- [ ] /advertise page
- [ ] Ad order flow + Billplz payment
- [ ] Admin approve/reject ad
- [ ] Ad display by placement with impression/click tracking

---

## Stage 10 — WordPress + HivePress Migration Tools
**Goal:** Import existing data from old system.

Tasks:
- [ ] audit_wordpress.php
- [ ] export_users.php
- [ ] export_listings.php
- [ ] import_users.php
- [ ] import_listings.php
- [ ] import_images.php
- [ ] create_redirects.php

---

## Stage 11 — Reports, Safety, and Moderation
**Goal:** Platform trust and safety features.

Tasks:
- [ ] Report listing button
- [ ] Admin report management
- [ ] Safety guidelines page
- [ ] Terms and disclaimer page

---

## Stage 12 — SEO, Sitemap, and Launch Cleanup
**Goal:** Production-ready launch.

Tasks:
- [ ] sitemap.xml generation
- [ ] robots.txt
- [ ] Schema markup
- [ ] 301 redirects
- [ ] 404 page
- [ ] Performance and mobile check
- [ ] Security audit
