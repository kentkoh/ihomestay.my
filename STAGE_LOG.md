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

---

## Stage 1 — Core Database and Auth
Date: 2026-06-07
Status: Completed (pending deployment test)

Completed:
- Migration SQL: users table
- Migration SQL: owner_profiles table
- Migration runner: database/migrate.php
- Core class: Auth.php (login, logout, role checks, redirects)
- Core class: CSRF.php (token generation and verification)
- Model: User.php (findByEmail, findById, create, createOwnerProfile, emailExists, verifyPassword)
- Controller: AuthController.php (showLogin, handleLogin, showRegister, handleRegister, logout)
- Controller: AdminController.php (dashboard)
- Controller: OwnerController.php (dashboard)
- View: layouts/main.php (Bootstrap 5 base layout)
- View: auth/login.php
- View: auth/register.php
- View: admin/dashboard.php (placeholder with stats cards)
- View: owner/dashboard.php (placeholder with listing limit progress)
- Seeder: database/seeders/AdminSeeder.php
- Updated public/index.php with all routes

Files created:
- database/migrations/001_create_users_table.sql
- database/migrations/002_create_owner_profiles_table.sql
- database/migrations/003_create_migrations_log_table.sql
- database/migrate.php
- database/seeders/AdminSeeder.php
- app/Core/Auth.php
- app/Core/CSRF.php
- app/Models/User.php
- app/Controllers/AuthController.php
- app/Controllers/AdminController.php
- app/Controllers/OwnerController.php
- app/Views/layouts/main.php
- app/Views/auth/login.php
- app/Views/auth/register.php
- app/Views/admin/dashboard.php
- app/Views/owner/dashboard.php

Files modified:
- public/index.php (added all routes and requires)

Database changes:
- users table (migration 001)
- owner_profiles table (migration 002)
- migrations_log table (auto-created by migrate.php)

Pending issues:
- Must run migrations on server: php database/migrate.php
- Must run seeder on server: php database/seeders/AdminSeeder.php
- Must test login/register/logout on new.ihomestay.my

Default admin credentials (change after first login):
- Email: admin@ihomestay.my
- Password: Admin@1234

Next recommended stage:
- Stage 2 — Location and Facility Module
