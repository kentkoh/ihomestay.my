# DEPLOYMENT_NOTES.md — ihomestay.my

Last updated: Stage 1

---

## Deployment target

| Item | Value |
|------|-------|
| Development subdomain | new.ihomestay.my |
| Production domain | ihomestay.my (cutover after all 12 stages done) |
| Server IP | 111.90.134.20 |
| cPanel username | kuantan1 |
| PHP version | 8.2.31 |
| Database engine | MySQL 8.0.37 |
| OS | Linux x86_64 |

**IMPORTANT:** The current ihomestay.my (WordPress + HivePress) must NOT be touched.
All development happens on new.ihomestay.my. Domain cutover happens only as the final step.

---

## Confirmed server capabilities

| Check | Result |
|-------|--------|
| PHP version | 8.2.31 ✅ |
| pdo_mysql | confirmed ✅ |
| cPanel Terminal | available ✅ |
| Git Version Control | configured ✅ |
| MySQL database | kuantan1_ihomestay ✅ |

---

## Project paths on server

| Purpose | Path |
|---------|------|
| Project root | /home/kuantan1/public_html/new.ihomestay.my |
| Public web root | /home/kuantan1/public_html/new.ihomestay.my/public |
| .env file | /home/kuantan1/public_html/new.ihomestay.my/.env |
| Uploads | /home/kuantan1/public_html/new.ihomestay.my/uploads |
| Logs | /home/kuantan1/public_html/new.ihomestay.my/storage/logs |

---

## DNS configuration

DNS is managed via **Cloudflare** (not cPanel Zone Editor).

| Record | Type | Value |
|--------|------|-------|
| new.ihomestay.my | A | 111.90.134.20 |
| Proxy status | DNS only (grey cloud) | — |

**Important:** Always add new subdomains in Cloudflare, not cPanel.

---

## Git deployment workflow

### First-time setup (done)
1. GitHub repo: https://github.com/kentkoh/ihomestay.my (public)
2. cPanel Git Version Control clones from GitHub
3. Path: /home/kuantan1/public_html/new.ihomestay.my

### Regular deploy (every stage)
```bash
# On local PC (VS Code terminal)
git add .
git commit -m "feat: Stage X description"
git push

# On server (cPanel → Git Version Control → Manage → Update from Remote)
# OR in cPanel Terminal:
cd ~/public_html/new.ihomestay.my
git pull
```

---

## Post-deploy commands (one-time, already done)

```bash
cd ~/public_html/new.ihomestay.my
cp .env.example .env
nano .env   # fill in DB credentials
```

---

## Database credentials (in .env on server — do not commit)

```
DB_HOST=localhost
DB_DATABASE=kuantan1_ihomestay
DB_USERNAME=kuantan1_ihomestay
DB_PASSWORD=(set on server only)
```

---

## Stage 1 deploy steps (pending)

Run after pushing Stage 1 code to GitHub and pulling to server:

```bash
cd ~/public_html/new.ihomestay.my
php database/migrate.php
php database/seeders/AdminSeeder.php
```

Default admin login (change immediately):
- Email: admin@ihomestay.my
- Password: Admin@1234

---

## Folder permissions

```bash
chmod 755 ~/public_html/new.ihomestay.my/uploads
chmod 755 ~/public_html/new.ihomestay.my/storage
chmod 755 ~/public_html/new.ihomestay.my/storage/logs
```

---

## Domain cutover plan (Stage 12 only)

Only after:
1. All 12 stages complete and tested on new.ihomestay.my
2. WordPress data fully migrated and verified
3. All 301 redirects from old WordPress URLs tested
4. Full QA passed

Steps:
1. In Cloudflare: update ihomestay.my A record → 111.90.134.20
2. In cPanel: update ihomestay.my document root → /public_html/new.ihomestay.my/public
3. Update .env: APP_URL=https://www.ihomestay.my
4. Test all old WordPress URLs redirect correctly
