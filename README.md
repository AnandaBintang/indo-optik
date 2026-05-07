# IndoOptik

IndoOptik is a Laravel-based optical shop catalog. Customers browse products, configure lens options, add items to cart, and complete checkout through WhatsApp. There is no internal order transaction dashboard by design.

## Tech Stack

- PHP 8.2+
- Laravel 12
- MySQL, MariaDB, PostgreSQL, or SQLite
- Vite
- Tailwind CSS 4
- Alpine.js
- Font Awesome
- Pest / PHPUnit

## Main Features

- Public storefront homepage, catalog, product detail, cart, about, and services pages.
- WhatsApp-only checkout from cart and appointment booking.
- Admin panel for products, categories, promo codes, testimonials, users, and settings.
- Session-based cart.
- Security headers middleware for CSP, frame protection, MIME sniffing, referrer policy, and permissions policy.
- Responsive public and admin layouts.

## Requirements

Install these before setup:

```bash
php -v        # 8.2 or newer
composer -V
node -v       # 20+ recommended
npm -v
```

## Installation

1. Install PHP dependencies:

```bash
composer install
```

2. Install JavaScript dependencies:

```bash
npm install
```

3. Create your environment file:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=indo_optik
DB_USERNAME=root
DB_PASSWORD=
```

For local SQLite development:

```bash
touch database/database.sqlite
```

Then set:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

5. Run migrations and seeders:

```bash
php artisan migrate --seed
```

6. Link public storage:

```bash
php artisan storage:link
```

7. Start the local development server:

```bash
npm run dev
```

This runs both Vite and `php artisan serve` through `concurrently`.

## Production Build

Build frontend assets:

```bash
npm run build
```

Recommended Laravel production commands:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Clear caches when changing routes, config, or Blade views:

```bash
php artisan optimize:clear
```

## Docker (VPS Deployment)

This project now includes a production-ready Docker setup:

- `Dockerfile` for Laravel app build (PHP-FPM + Nginx + compiled Vite assets)
- `docker-compose.yml` for app + MySQL services

### 1) Prepare environment

Create your production env file on VPS:

```bash
cp .env.example .env
```

Set at least these values in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=indo_optik
DB_USERNAME=root
DB_PASSWORD=change_this_password
```

Optional compose variables:

```env
APP_PORT=80
RUN_MIGRATIONS=true
```

### 2) Build and run containers

```bash
docker compose up -d --build
```

### 3) Check logs

```bash
docker compose logs -f app
docker compose logs -f db
```

### 4) Stop containers

```bash
docker compose down
```

To remove data volumes too (destructive):

```bash
docker compose down -v
```

## VPS Auto Deploy Script

Use this script to auto-deploy latest code from GitHub, rebuild containers, and run migrations:

`scripts/vps-autodeploy.sh`

What it does:

- Pull latest commit from your GitHub branch
- Run `docker compose up -d --build`
- Run `php artisan migrate --force`
- Rebuild Laravel caches (`config`, `route`, `view`)

### One-time setup on VPS

```bash
cd /var/www/indo-optik
chmod +x scripts/vps-autodeploy.sh
```

Optional environment overrides:

```bash
export APP_DIR=/var/www/indo-optik
export BRANCH=main
export REMOTE=origin
export COMPOSE_FILE=docker-compose.yml
export APP_SERVICE=app
```

### Manual run

```bash
./scripts/vps-autodeploy.sh
```

### Auto run with cron (every 5 minutes)

```bash
crontab -e
```

Add:

```cron
*/5 * * * * /bin/bash /var/www/indo-optik/scripts/vps-autodeploy.sh >> /var/log/indooptik-deploy.log 2>&1
```

This allows your VPS to auto pull updates from GitHub and auto migrate on each deploy cycle.

## Admin Access

Admin and staff users can access `/admin`.

If seeders are enabled, check `database/seeders/UserSeeder.php` for the seeded admin account. If you need to create one manually, update an existing user role in Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'admin@example.com')->first();
$user->update(['role' => App\Models\User::ROLE_ADMIN]);
```

## WhatsApp Checkout Flow

The store is intentionally one-way:

1. Customer adds products to cart.
2. Customer clicks checkout via WhatsApp.
3. The cart builds a formatted WhatsApp message.
4. Customer confirms order directly with the shop through WhatsApp.

Because of this, admin order routes and order dashboard screens are not part of the active admin navigation.

Configure the WhatsApp number from admin settings or the `settings` table using the `whatsapp_number` key. Use international format without `+`, for example:

```text
6281234567890
```

## Testing

Run all tests:

```bash
php artisan test
```

Run only unit tests:

```bash
php artisan test --testsuite=Unit
```

Run only feature tests:

```bash
php artisan test --testsuite=Feature
```

The test environment uses in-memory SQLite as configured in `phpunit.xml`.

## Code Quality Checklist

Before handing off changes:

```bash
php artisan test
npm run build
php artisan route:list --except-vendor
```

Also verify these manually in a browser:

- Homepage at desktop, tablet, and mobile widths.
- Mobile/tablet navbar drawer opens and closes.
- Cart checkout opens WhatsApp with a generated message.
- Admin index pages do not force horizontal page scrolling on mobile.
- `/admin` dashboard shows catalog/content metrics, not transaction metrics.

## Useful Paths

- Public routes: `routes/web.php`
- Public layout: `resources/views/layouts/app.blade.php`
- Admin layout: `resources/views/layouts/admin.blade.php`
- Homepage: `resources/views/pages/home.blade.php`
- Cart: `resources/views/pages/cart/index.blade.php`
- Admin dashboard: `resources/views/admin/dashboard.blade.php`
- CSS entry: `resources/css/app.css`
- JS entry: `resources/js/app.js`
- Security headers: `app/Http/Middleware/SecurityHeaders.php`

## Troubleshooting

If assets do not update:

```bash
npm run build
php artisan view:clear
```

If routes behave unexpectedly:

```bash
php artisan route:clear
php artisan route:list --except-vendor
```

If uploaded images are not visible:

```bash
php artisan storage:link
```

If tests fail on database tables:

```bash
php artisan migrate:fresh --seed
php artisan test
```
