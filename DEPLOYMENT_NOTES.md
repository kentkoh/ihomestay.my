# DEPLOYMENT_NOTES.md — ihomestay.my

Last updated: Stage 0

## Deployment target

**Subdomain:** new.ihomestay.my (development/staging)
**Production domain:** ihomestay.my (only after full project completion)

IMPORTANT: The current ihomestay.my (WordPress + HivePress) must NOT be touched.
All development happens on new.ihomestay.my until the new system is fully ready.
Domain switch (new.ihomestay.my → ihomestay.my) happens only as a final cutover step.

## Hosting environment

```
cPanel shared hosting
PHP (version to confirm via SSH — minimum 7.4 required, 8.0+ preferred)
MySQL / MariaDB
Git Version Control (cPanel Git)
SSH access
Terminal access
```

## Document root configuration

Point new.ihomestay.my document root to:
```
/public_html/new/public
```
or as configured in cPanel subdomain settings.

Only the /public folder is publicly accessible.
All other directories (app, config, database, storage, uploads) must be outside web root
or protected via .htaccess.

## Deployment steps (Git-based)

1. Create cPanel Git repository linked to this project
2. Set deploy path to the project folder on the server
3. Push from local to cPanel remote
4. SSH into server and run post-deploy setup if needed

## Required server checks (run via SSH before Stage 1)

```bash
php -v                  # Must be >= 7.4
mysql --version         # Confirm MySQL/MariaDB availability
php -m | grep pdo       # Confirm PDO and PDO_MySQL
php -m | grep mbstring  # Confirm mbstring
php -m | grep gd        # Confirm GD for image processing
```

## Post-deploy file setup on server

```bash
cp .env.example .env
# Edit .env with real credentials
chmod 755 uploads/
chmod 755 storage/
chmod 755 storage/logs/
```

## .env required keys

```
APP_NAME=ihomestay.my
APP_ENV=production
APP_DEBUG=false
APP_URL=https://new.ihomestay.my
APP_KEY=        # random 32-char string for hashing

DB_HOST=localhost
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
DB_CHARSET=utf8mb4

BILLPLZ_API_KEY=
BILLPLZ_COLLECTION_ID=
BILLPLZ_X_SIGNATURE_KEY=
BILLPLZ_SANDBOX=false

MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@ihomestay.my
MAIL_FROM_NAME=ihomestay.my

UPLOAD_MAX_SIZE_MB=5
```

## .htaccess (URL routing)

All requests routed through public/index.php.
See public/.htaccess for rules.

## Domain cutover plan (final step)

Only after:
1. All 12 stages complete
2. WordPress data fully migrated and verified
3. All redirects tested
4. Full QA on new.ihomestay.my

Then:
- Update DNS: ihomestay.my → same server
- Update document root: ihomestay.my → /public_html/new/public
- Update APP_URL in .env to https://www.ihomestay.my
- Test all redirects from old WordPress URLs
