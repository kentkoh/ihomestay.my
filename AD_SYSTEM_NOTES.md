# AD_SYSTEM_NOTES.md — ihomestay.my

Last updated: Stage 0

## Version 1 ad placements (start with these only)

| Placement           | Page type  | Size (desktop)  | Size (mobile) | Price       |
|---------------------|------------|-----------------|---------------|-------------|
| Home Top Banner     | home       | 1200 x 250      | 800 x 800     | RM199/30d   |
| City Page Top Banner| city       | 1200 x 250      | 800 x 800     | RM49/30d    |
| Article Middle Banner| article   | 1200 x 250      | 800 x 800     | RM49/30d    |

Do not create too many placements in Version 1.

## Ad approval flow

```
Advertiser creates ad order
→ Uploads banner (desktop + mobile)
→ Enters destination URL or WhatsApp link
→ Pays via Billplz
→ review_status = 'paid_pending_review'
→ Admin reviews banner content
→ Admin approves → review_status = 'active', start_at = NOW()
   OR
→ Admin rejects → review_status = 'rejected', rejection_reason saved
→ Ad runs for duration_days starting from approval date
→ After duration expires → review_status = 'expired'
```

## Ad duration rule

**Duration starts AFTER admin approval, not after payment.**

Example: Customer pays on July 1 → Admin approves July 3 → 30-day ad runs July 3 to August 2.

## Banned ad categories

```
Gambling / casino
Ah long / illegal loan / unlicensed financial
Adult / 18+ content
Fake investment / MLM scheme
Illegal products or services
Vape / tobacco
Political hate content
Misleading medical / health claims
Fake homestay listings
Scam travel packages
```

## Allowed ad categories

```
Homestay supplies and equipment
Cleaning and laundry services
Furniture and home decor
Renovation and construction
Aircond service and maintenance
Catering and food services
BBQ and party equipment rental
Event decoration
Travel services
Car rental
Photography and videography
Local tourist attractions
Insurance
```

## Ad display logic

1. Query active ads for the current page's placement
2. If multiple ads match, rotate — prefer ads with fewer impressions
3. Record one impression per page load per ad
4. Record one click per click event per ad
5. Always show "Sponsored" label

## Banner file rules

Allowed types: jpg, jpeg, png, webp
Rejected types: php, svg, html, js, gif (V1), video (V1)
Max size: 2MB per file
Always rename file on upload (never use original filename)
Always check MIME type, not just extension

## Implementation stage

Banner ad system is built in Stage 9.
Ad placements table is seeded with the 3 Version 1 placements.
