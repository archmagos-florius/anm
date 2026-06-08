# Catering MVP

Plain PHP and SQLite MVP for a small catering business. Customers order from the current released menu, and admins manage menu items, menus, orders, and prep totals.

## Stack

- PHP 8.3+
- SQLite through PDO
- Plain PHP templates and includes
- Pico.css
- PHP sessions for login and cart
- PHPMailer over SMTP
- Google Places autocomplete for delivery addresses
- Ubuntu VPS with Caddy and PHP-FPM for production

## Local Setup

1. Install PHP with these extensions enabled: `pdo_sqlite`, `sqlite3`, `gd`, `mbstring`, `openssl`.
2. Install Composer.
3. Install dependencies:

```bash
composer install
```

Or use Make:

```bash
make install
```

4. Create local config:

```bash
cp app/config.example.php app/config.php
```

5. Edit `app/config.php` when real values are available. Placeholders work for local testing, but real SMTP and Google Maps credentials are needed for those features.
6. Run migrations:

```bash
php scripts/migrate.php
```

Or:

```bash
make migrate
```

7. Create the first admin:

```bash
php scripts/seed_admin.php admin@example.com "Admin Name" "change-this-password"
```

Or:

```bash
make seed-admin EMAIL=admin@example.com NAME="Admin Name" PASSWORD="change-this-password"
```

8. Start the local PHP server:

```bash
php -S localhost:8000 -t public
```

Or:

```bash
make serve
```

9. Open `http://localhost:8000`.

## How To Use The App

### Admin Setup Flow

1. Visit `/login.php`.
2. Log in with the seeded admin account.
3. Open `/admin/index.php`.
4. Create reusable menu items from `Menu Items`.
5. Create a menu from `Menus`.
6. Add existing menu items to that menu.
7. Set release date, cutoff time, and delivery fee.
8. Mark the menu as released.
9. Mark it as current.

Only one menu can be current at a time. Marking a menu current unsets every other current menu.

### Customer Ordering Flow

1. Visit the homepage.
2. Add available items from the current menu to the cart.
3. Open the cart and adjust quantities if needed.
4. Continue to checkout.
5. Choose pickup or delivery.
6. Enter required contact details.
7. For delivery, enter the delivery address.
8. Submit the order.
9. Review the confirmation page.

Guest checkout is allowed. Signed-in customers can save multiple labeled delivery addresses.

### Admin Order Flow

1. Open `/admin/index.php`.
2. Review current menu prep totals.
3. Open a menu dashboard to see order totals for that menu.
4. Open individual orders for details.
5. Edit customer details, fulfillment details, notes, or items.
6. Cancel or fulfill orders as needed.

Cancelled orders do not count toward prep totals.

## Config Values

Local secrets live in `app/config.php`, which is ignored by git. Use `app/config.example.php` as the template.

Important placeholders:

- `SITE_NAME`
- `ADMIN_EMAIL`
- `APP_TIMEZONE`
- `SMTP_HOST`
- `SMTP_USERNAME`
- `SMTP_PASSWORD`
- `SMTP_FROM_EMAIL`
- `GOOGLE_MAPS_API_KEY`
- `PICKUP_INSTRUCTIONS`
- `DELIVERY_INSTRUCTIONS`

## Backups

Create a manual SQLite backup:

```bash
php scripts/backup_sqlite.php
```

Or:

```bash
make backup
```

## Make Targets

```bash
make help
make install
make setup
make migrate
make seed-admin EMAIL=admin@example.com NAME="Admin Name" PASSWORD="change-this-password"
make backup
make serve
make lint
```

Production should run this daily with cron.

## Production Notes

- Serve `public/` as the web root.
- Keep `app/config.php` outside version control.
- Ensure `storage/` is writable by PHP.
- Ensure `public/uploads/menu-items/` is writable by PHP.
- Use HTTPS through Caddy.
- Restrict the Google Maps API key by domain.
