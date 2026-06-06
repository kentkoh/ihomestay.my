# STAGE_LOG.md — ihomestay.my

---

## Stage 0 — Project Setup and Hosting Check
Date: 2026-06-07
Status: Completed

Completed:
- Read and summarized project instruction file
- Inspected existing project folder (only images and instruction MD existed)
- Chose framework: Custom PHP MVC (cPanel-safe, no Composer dependency)
- Created full project directory structure
- Created .env.example with all required config keys
- Created public/index.php (entry point skeleton)
- Created public/.htaccess (URL routing)
- Created .gitignore
- Created all 9 required documentation files

Files created:
- PROJECT_OVERVIEW.md
- STAGE_PLAN.md
- STAGE_LOG.md
- DATABASE_SCHEMA.md
- MIGRATION_NOTES.md
- SECURITY_NOTES.md
- DEPLOYMENT_NOTES.md
- BILLPLZ_INTEGRATION.md
- AD_SYSTEM_NOTES.md
- .env.example
- .gitignore
- public/index.php
- public/.htaccess
- app/Core/Router.php (basic routing skeleton)
- app/Core/Database.php (PDO connection skeleton)
- config/app.php

Directories created:
- public/
- app/Controllers/
- app/Models/
- app/Views/
- app/Core/
- config/
- database/migrations/
- database/seeders/
- uploads/
- storage/logs/
- resources/views/
- tools/migration/

Database changes:
- None (Stage 0 does not create tables)

Server/cPanel limitations found:
- Cannot verify server PHP version from local environment
- Must verify PHP >= 7.4 on cPanel before Stage 1
- Composer availability unknown — not required for Custom PHP MVC choice
- Upload folder writability must be confirmed on server

Pending issues:
- Confirm PHP version on cPanel server (need SSH access)
- Confirm MySQL credentials and database name on cPanel
- Confirm git deployment is configured on cPanel
- Test that .htaccess mod_rewrite works on the server

Next recommended stage:
- Stage 1 — Core Database and Auth
- But first: deploy skeleton to cPanel and confirm server works
