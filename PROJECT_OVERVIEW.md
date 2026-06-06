# PROJECT_OVERVIEW.md — ihomestay.my

## What this project is

ihomestay.my is a Malaysia-only homestay directory website built as a custom PHP + MySQL application.
It replaces an existing WordPress + HivePress setup.

This is NOT a booking platform in Version 1. It is a direct-contact directory.

## Core business model

- Free owner listings (up to 3 listings)
- Verified Owner upgrade (paid, via Billplz)
- Featured Listing sales (verified owners only)
- Banner advertising (admin approval required)
- Sponsored article / listing placement
- Direct WhatsApp contact between visitor and owner

## Tech stack

| Layer      | Choice                          |
|------------|---------------------------------|
| Backend    | Custom PHP MVC (cPanel-safe)    |
| Database   | MySQL / MariaDB (UTF8MB4 InnoDB)|
| Frontend   | HTML + CSS + Bootstrap + JS     |
| Payment    | Billplz                         |
| Hosting    | cPanel + PHP + MySQL            |
| Deploy     | Git Version Control + SSH       |

## Framework choice: Custom PHP MVC

Laravel was evaluated but Custom PHP MVC was chosen for:
- Maximum cPanel shared hosting compatibility
- No Composer/artisan dependency at runtime
- Full control over routing and file structure
- Easier Git deployment without vendor folder issues

## User roles

| Role        | Auth required |
|-------------|--------------|
| admin       | Yes          |
| owner       | Yes          |
| advertiser  | Yes          |
| visitor     | No           |

## Key business rules

- Only Malaysia listings allowed
- Free owners: max 3 listings, non-verified badge, warning popup before WhatsApp
- Verified owners: unlimited listings (fair use), badge, higher ranking
- Featured listing: verified owners only
- Ad payment does NOT auto-activate — admin must approve
- No online booking, no guest payment in Version 1

## Version 1 scope

Directory + contact + verification + featured listing + ads + articles + migration + SEO

## Out of scope for Version 1

Online booking, guest payment, commission, calendar, mobile app, chat, review dispute
