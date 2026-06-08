# Project Handoff

## Overview

This project is a slim MVP website for a catering business. Customers visit the site, see the currently orderable menu, add food items to a cart, and check out as either guests or signed-in users. Admins create reusable menu items, build released menus, choose the current orderable menu, and manage orders.

The site should be intentionally simple: server-rendered PHP pages, regular form submissions, reusable partials, SQLite, and minimal dependencies.

## Target Users

### Customer

- Enters the website.
- Sees the current menu on the homepage.
- Adds menu items to the cart.
- Reviews the cart.
- Checks out as a guest or signed-in customer.
- Selects pickup or delivery.
- Enters required contact and address information.
- Receives an order confirmation email.

### Admin

- Logs in through a secure portal.
- Creates reusable menu items.
- Starts a new menu from existing menu items.
- Chooses release date, cutoff time, and delivery fee.
- Marks one released menu as current.
- Views a dashboard for a released/current menu.
- Sees prep totals showing how many of each item to cook.
- Opens individual orders for details.
- Edits, cancels, or fulfills orders.

## Confirmed Technology Decisions

- Plain PHP 8.3+
- SQLite database
- PDO prepared statements
- Pico.css styling
- Plain PHP templates/partials
- PHP sessions for cart and login state
- PHPMailer for SMTP email
- Google Places API for delivery address autocomplete
- Google Maps directions links for admins
- Ubuntu VPS with Caddy and PHP-FPM
- Daily SQLite backup copy using cron

## Explicit Non-Goals For MVP

- No online payments.
- No Laravel or heavy PHP framework.
- No React/Vue/SPA frontend.
- No tax calculations.
- No inventory limits.
- No complex admin roles.
- No guest order lookup page.
- No advanced analytics.

## Placeholder Values

These are approved placeholders and should live in `config.example.php` or documentation until real values are known:

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

The business timezone should be set before launch because cutoff behavior depends on it.

## Proposed File Structure

```text
public/
  index.php
  cart.php
  checkout.php
  confirmation.php
  login.php
  signup.php
  logout.php
  account.php
  assets/
    styles.css
  uploads/
    menu-items/
  admin/
    index.php
    menu-items.php
    menu-item-form.php
    menus.php
    menu-form.php
    menu-detail.php
    order-detail.php

app/
  bootstrap.php
  config.example.php
  db.php
  auth.php
  csrf.php
  mail.php
  images.php
  maps.php
  validation.php
  views/
    layout-top.php
    layout-bottom.php
    partials/
      header.php
      footer.php
      messages.php
      menu-card.php
      cart-table.php
      admin-nav.php
      order-summary.php

scripts/
  migrate.php
  seed_admin.php
  backup_sqlite.php

storage/
  database.sqlite
  backups/
```

## Database Plan

Use these core tables:

- `users`
- `customer_addresses`
- `menu_items`
- `menus`
- `menu_entries`
- `orders`
- `order_items`

### users

```text
id
email
password_hash
name
phone
is_admin
created_at
updated_at
```

### customer_addresses

```text
id
user_id
label
address
created_at
updated_at
```

### menu_items

```text
id
name
description
price_cents
image_path
active
created_at
updated_at
```

### menus

```text
id
title
release_date
cutoff_at
status
is_current
delivery_fee_cents
created_at
updated_at
```

Expected statuses:

- `draft`
- `released`
- `closed`

### menu_entries

```text
id
menu_id
menu_item_id
price_cents
available
sort_order
created_at
updated_at
```

Unavailable entries should still display publicly, but disabled with an unavailable label.

### orders

```text
id
menu_id
user_id
customer_name
customer_email
customer_phone
fulfillment_type
delivery_address
customer_notes
status
subtotal_cents
delivery_fee_cents
total_cents
created_at
updated_at
```

Expected statuses:

- `confirmed`
- `fulfilled`
- `cancelled`

### order_items

```text
id
order_id
menu_entry_id
item_name_snapshot
unit_price_cents
quantity
line_total_cents
created_at
updated_at
```

## Public Routes

```text
GET  /index.php
POST /cart.php?action=add
GET  /cart.php
POST /cart.php?action=update
POST /cart.php?action=remove
GET  /checkout.php
POST /checkout.php
GET  /confirmation.php?id={order_id}
GET  /login.php
POST /login.php
GET  /signup.php
POST /signup.php
POST /logout.php
GET  /account.php
POST /account.php
```

## Admin Routes

```text
GET  /admin/index.php
GET  /admin/menu-items.php
GET  /admin/menu-item-form.php
POST /admin/menu-item-form.php
GET  /admin/menus.php
GET  /admin/menu-form.php
POST /admin/menu-form.php
GET  /admin/menu-detail.php?id={menu_id}
POST /admin/menu-detail.php?action=release
POST /admin/menu-detail.php?action=make-current
GET  /admin/order-detail.php?id={order_id}
POST /admin/order-detail.php?action=save
POST /admin/order-detail.php?action=cancel
POST /admin/order-detail.php?action=fulfill
```

## Checkout Rules

- Guest checkout is allowed.
- Customer accounts are optional.
- Pickup requires name, email, and phone.
- Delivery requires name, email, phone, and address.
- Delivery address is a single text field.
- Google Places autocomplete should enhance the delivery address input.
- Delivery fee is read from the current menu.
- No tax calculation.
- Customer notes are allowed.
- Signed-in users can store multiple labeled addresses.
- Guests do not get saved addresses.

## Email Rules

### Customer Confirmation

Send a full order confirmation email containing:

- Customer contact details
- Fulfillment type
- Delivery address if applicable
- Customer notes if present
- Ordered items
- Delivery fee if applicable
- Total

### Admin New-Order Notification

Send admin a short summary plus a link to the admin order detail page.

### Admin Edit Email

When admin edits an order, show a checkbox asking whether to send the customer an updated order email. If selected, send the updated order details only, not a diff.

## Maps Behavior

Use Google Places API for delivery address autocomplete on checkout.

For admin directions, generate a link from the stored address:

```text
https://www.google.com/maps/dir/?api=1&destination=ENCODED_ADDRESS
```

This opens directions in Google Maps without requiring a backend Maps API call.

## Image Upload Rules

- Allow JPG, PNG, and WebP.
- Max upload size: 2MB.
- Resize images server-side to a standard width.
- Store files in `public/uploads/menu-items/`.
- Store relative paths in `menu_items.image_path`.

## Deployment Notes

- Target Ubuntu VPS.
- Use Caddy for HTTPS.
- Use PHP-FPM.
- Store SQLite at `storage/database.sqlite`.
- Ensure `storage/` and `public/uploads/` are writable by the web server user.
- Keep `config.php` out of git.
- Run daily backups through cron with `scripts/backup_sqlite.php`.
