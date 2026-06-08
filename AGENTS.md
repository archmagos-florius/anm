# Agent Instructions

This project is an MVP website for a small catering business. The expected traffic is very low, around 50 visitors per week. Keep every implementation choice simple, readable, and easy for a PHP developer to maintain.

## Product Summary

Customers order food from a periodically released menu. Admins create reusable menu items, build a menu from those items, set a release date and cutoff time, mark one menu as current, and review orders/prep totals.

## Confirmed Stack

- Language: plain PHP 8.3+
- Database: SQLite
- Database access: PDO prepared statements
- Templates: plain PHP includes and reusable partials
- Styling: Pico.css
- Cart storage: PHP session
- Auth: custom session auth using `password_hash()` and `password_verify()`
- Email: PHPMailer over SMTP
- Hosting target: Ubuntu VPS with Caddy and PHP-FPM
- Config: `config.php`, ignored by git

## Keep It Simple

- Do not add Laravel, Symfony, React, Vue, HTMX, Node build tooling, Redis, queues, or online payments unless explicitly requested.
- Prefer regular form posts and full page reloads.
- Prefer small PHP files with shared helpers over abstract framework patterns.
- Use reusable partials for headers, forms, tables, cart summaries, order summaries, and admin navigation.
- Store money as integer cents, never floats.
- Use SQLite foreign keys and enable them on each connection.
- Use CSRF protection for every POST form.
- Use PDO prepared statements for all database input.
- Preserve historical order data with snapshots of item names and prices.
- Do not store guest addresses outside the order record.

## Placeholder Config Values

Use placeholders until real values are available:

```php
SITE_NAME = 'TODO_BUSINESS_NAME'
ADMIN_EMAIL = 'TODO_ADMIN_EMAIL'
APP_TIMEZONE = 'TODO_TIMEZONE'
SMTP_HOST = 'TODO_SMTP_HOST'
SMTP_PORT = 587
SMTP_USERNAME = 'TODO_SMTP_USERNAME'
SMTP_PASSWORD = 'TODO_SMTP_PASSWORD'
SMTP_FROM_EMAIL = 'TODO_SMTP_FROM_EMAIL'
SMTP_FROM_NAME = 'TODO_BUSINESS_NAME'
GOOGLE_MAPS_API_KEY = 'TODO_GOOGLE_MAPS_API_KEY'
PICKUP_INSTRUCTIONS = 'TODO_PICKUP_INSTRUCTIONS'
DELIVERY_INSTRUCTIONS = 'TODO_DELIVERY_INSTRUCTIONS'
```

## Core Rules

- Only one menu can be current/orderable at a time.
- A menu cutoff disables ordering and shows a customer-facing banner.
- Pickup checkout requires name, email, and phone.
- Delivery checkout requires name, email, phone, and one delivery address text field.
- Delivery fee is configured per menu.
- No tax logic in MVP.
- Customer notes are allowed.
- Guest checkout is allowed.
- Customer accounts are optional and can store multiple labeled addresses.
- Admin users are controlled by a single `is_admin` flag.
- Admins can edit, cancel, and fulfill orders.
- Order totals recalculate automatically after admin item edits.

## Documentation

Read these before implementation:

- `docs/PROJECT_HANDOFF.md`
- `docs/MVP_TODO.md`
- `docs/MANUAL_TEST_CHECKLIST.md`
