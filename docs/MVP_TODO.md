# MVP TODO

This TODO list is ordered by recommended implementation sequence. Keep the build slim and server-rendered.

## Phase 1: Foundation

- Create project folders: `public`, `app`, `app/views`, `app/views/partials`, `scripts`, and `storage`.
- Add `app/config.example.php` with placeholder config values.
- Ensure real `app/config.php` is ignored by git.
- Add `app/bootstrap.php` to start sessions, load config, set timezone, and load helpers.
- Add `app/db.php` with a PDO SQLite connection.
- Enable SQLite foreign keys on every DB connection.
- Add `app/csrf.php` for CSRF token generation and validation.
- Add shared layout partials.
- Add Pico.css from CDN or local asset.
- Add basic flash message helper.

## Phase 2: Database Scripts

- Add `scripts/migrate.php`.
- Create `users` table.
- Create `customer_addresses` table.
- Create `menu_items` table.
- Create `menus` table.
- Create `menu_entries` table.
- Create `orders` table.
- Create `order_items` table.
- Add indexes for common lookups: current menu, menu orders, user orders, email login.
- Add `scripts/seed_admin.php` to create the first admin from CLI.

## Phase 3: Auth

- Add login page.
- Add logout action.
- Add customer signup page.
- Store passwords with `password_hash()`.
- Verify passwords with `password_verify()`.
- Add `current_user()` helper.
- Add `require_login()` helper.
- Add `require_admin()` helper.
- Add single `is_admin` flag behavior.

## Phase 4: Customer Account

- Add account page for signed-in customers.
- Allow customers to edit name and phone.
- Allow customers to manage multiple saved addresses.
- Each saved address should have a label and one address text field.
- Add simple signed-in customer order history.

## Phase 5: Menu Items

- Add admin menu items list page.
- Add create/edit form for menu items.
- Support name, description, price, active flag, and image.
- Validate price and store as cents.
- Validate image type: JPG, PNG, WebP.
- Validate image size: max 2MB.
- Resize uploaded images server-side to a standard width.
- Store uploaded images in `public/uploads/menu-items/`.

## Phase 6: Menus

- Add admin menus list page.
- Add create/edit menu form.
- Support title, release date, cutoff time, status, and delivery fee.
- Allow admin to attach existing menu items.
- Allow per-menu item price snapshot.
- Allow menu entry sort order.
- Allow admin to mark entries unavailable.
- Show unavailable entries publicly as disabled.
- Allow admin to release a menu.
- Allow admin to mark one released menu as current.
- When one menu is marked current, unset all other current menus.

## Phase 7: Public Menu And Cart

- Build homepage showing the current menu.
- If no current menu exists, show a friendly empty state.
- If cutoff has passed, show a banner that ordering is closed.
- Disable add-to-cart after cutoff.
- Disable add-to-cart for unavailable entries.
- Store cart in `$_SESSION`.
- Add cart page.
- Allow quantity updates.
- Allow removing items.
- Clear cart if it references a non-current menu.

## Phase 8: Checkout

- Add guest checkout form.
- Allow signed-in customer checkout.
- Prefill signed-in customer contact details.
- Let signed-in customers select saved address for delivery.
- Pickup requires name, email, and phone.
- Delivery requires name, email, phone, and address.
- Add customer notes field.
- Add Google Places autocomplete to delivery address field.
- Apply per-menu delivery fee for delivery orders.
- Do not calculate tax.
- Create order and order item snapshots.
- Clear cart after successful checkout.
- Redirect to confirmation page.

## Phase 9: Email

- Add PHPMailer dependency or bundled setup.
- Add `app/mail.php` with SMTP config usage.
- Send customer confirmation email with full order details.
- Send admin new-order email with summary and admin order link.
- Fail gracefully if email sending fails after order creation.
- Consider logging email errors to a simple file under `storage`.

## Phase 10: Admin Dashboard And Orders

- Add admin dashboard.
- Show current menu summary.
- Show recent orders.
- Show prep summary for current menu.
- Add menu detail dashboard.
- Show prep totals by item excluding cancelled orders.
- Show orders table for the menu.
- Add order detail page.
- Add Google Maps directions link for delivery orders.
- Allow admin to edit customer details.
- Allow admin to edit fulfillment type, address, and notes.
- Allow admin to add items from the same menu.
- Allow admin to update item quantities.
- Allow admin to remove items.
- Recalculate subtotal, delivery fee, and total after edits.
- Allow admin to cancel order.
- Allow admin to mark order fulfilled.
- Add checkbox to send updated order email after edits.

## Phase 11: Backups

- Add `scripts/backup_sqlite.php`.
- Copy `storage/database.sqlite` to `storage/backups/database-YYYYMMDD-HHMMSS.sqlite`.
- Add simple retention policy if desired.
- Document cron command for daily backups.

## Phase 12: Deployment

- Document Ubuntu package install steps.
- Document Caddy config.
- Document PHP-FPM setup.
- Document writable directories.
- Document config file creation.
- Document migration and seed commands.
- Document backup cron entry.
- Run manual test checklist before launch.

## Deferred Decisions

- Real business name.
- Logo and brand colors.
- Admin email.
- SMTP provider and credentials.
- Google Maps API key.
- Pickup instructions.
- Delivery instructions.
- Business timezone.
- VPS provider.

## Related Plans

- See `docs/DESIGN_UPGRADE_TODO.md` for visual polish and UX upgrade work after the functional MVP.
