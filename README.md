# Learning Management System

A Senior High School Learning Management System built on Laravel 12, with
server-rendered Blade views, Alpine.js, ApexCharts and Tailwind CSS v4 (via
Vite). See [`modules/README.md`](modules/README.md) for a per-module doc
index and real implementation status.

## Prerequisites

- PHP 8.2+ with Composer
- Node.js 20.19+ (or 22.12+) and npm
- MySQL (e.g. via XAMPP) — or SQLite if you'd rather skip installing a DB server

## Quick setup

```bash
git clone <repo-url>
cd LearningManagementSystem
composer run setup
```

`composer run setup` runs `composer install`, copies `.env.example` to
`.env`, generates the app key, runs migrations, then `npm install` and
`npm run build`. It expects the target database to already exist — see
"Database setup" below before running it, or use the manual steps instead.

## Manual setup

1. **Install PHP dependencies**
   ```bash
   composer install
   ```
2. **Install JS dependencies**
   ```bash
   npm install
   ```
3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Database setup**
   - Default is MySQL, database name `enrollment_management_system` (matches
     `.env.example`). Create it first, e.g. in phpMyAdmin ("New" → name it
     `enrollment_management_system`) or:
     ```bash
     mysql -u root -e "CREATE DATABASE enrollment_management_system"
     ```
   - Prefer not to run MySQL? Set `DB_CONNECTION=sqlite` in `.env` and create
     an empty `database/database.sqlite` file instead — no server needed.
   - Then run migrations and seed the initial admin account (self-service
     registration is disabled, so seeding is the only way to get a login):
     ```bash
     php artisan migrate
     php artisan db:seed
     ```
     Seeded login: `admin123@example.com` / `Password123` — you'll be
     required to change this password on first login.
5. **Build frontend assets**
   ```bash
   npm run build   # production build
   # or
   npm run dev      # dev server with hot reload
   ```

## Running the app

- **Via XAMPP/Apache**: point a vhost (or `http://localhost/LearningManagementSystem/public`)
  at the `public/` folder.
- **Via Artisan's built-in server**:
  ```bash
  php artisan serve
  ```
- **All-in-one dev workflow** (server + queue listener + log tailing + Vite,
  concurrently):
  ```bash
  composer run dev
  ```

## Running tests

```bash
composer test
# or
php artisan test
```

Tests run against an in-memory SQLite database with array session/cache and
a sync queue (see `phpunit.xml`), so no extra setup is required.

## Documentation

Deeper module-by-module docs (data model, file map, implementation status)
live in [`modules/`](modules/), starting with
[`modules/README.md`](modules/README.md).

