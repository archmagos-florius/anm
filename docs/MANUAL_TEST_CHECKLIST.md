# Manual Test Checklist

Run this checklist before calling the MVP ready for launch.

## Setup

- Confirm `app/config.php` exists and is not committed.
- Confirm `APP_TIMEZONE` is set to the business timezone.
- Confirm SQLite database file exists in `storage/database.sqlite`.
- Confirm `storage/` is writable by the app.
- Confirm `public/uploads/menu-items/` is writable by the app.
- Run migration script successfully.
- Run admin seed script successfully.

## Auth

- Log in as seeded admin.
- Log out as admin.
- Attempt admin page access while logged out and confirm it is blocked.
- Sign up as a customer.
- Log in as a customer.
- Log out as a customer.

## Customer Account

- Edit customer name and phone.
- Add a saved address with a label.
- Edit a saved address.
- Delete a saved address.
- Confirm customer order history page loads.

## Menu Items

- Create a menu item without an image if allowed by implementation.
- Create a menu item with a JPG image.
- Create a menu item with a PNG image.
- Create a menu item with a WebP image.
- Confirm uploaded images resize successfully.
- Try uploading a file over 2MB and confirm it is rejected.
- Try uploading a disallowed file type and confirm it is rejected.
- Edit menu item name, description, price, active status, and image.

## Menus

- Create a draft menu.
- Set release date.
- Set cutoff time.
- Set delivery fee.
- Attach existing menu items.
- Set per-menu item prices.
- Mark one menu entry unavailable.
- Release the menu.
- Mark the menu as current.
- Create a second menu and mark it current.
- Confirm the first menu is no longer current.

## Public Menu

- Visit homepage with no current menu and confirm empty state.
- Visit homepage with a current menu and confirm items display.
- Confirm unavailable item displays as disabled.
- Add available item to cart.
- Try adding unavailable item and confirm it is blocked.
- Set cutoff in the past and confirm ordering banner appears.
- Confirm add-to-cart is disabled after cutoff.

## Cart

- Add one item to cart.
- Add multiple items to cart.
- Update item quantities.
- Remove item from cart.
- Confirm subtotal updates correctly.
- Confirm cart clears or blocks checkout if current menu changes.

## Guest Pickup Checkout

- Add items to cart.
- Checkout as guest with pickup.
- Submit name, email, and phone.
- Confirm address is not required for pickup.
- Confirm order is created with status `confirmed`.
- Confirm cart clears after successful checkout.
- Confirm confirmation page loads.

## Guest Delivery Checkout

- Add items to cart.
- Checkout as guest with delivery.
- Confirm name, email, phone, and address are required.
- Confirm Google Places autocomplete loads when API key is configured.
- Enter delivery address.
- Enter customer notes.
- Confirm delivery fee is applied.
- Confirm no tax is calculated.
- Confirm order is created with status `confirmed`.

## Signed-In Checkout

- Log in as customer.
- Add saved address.
- Add items to cart.
- Checkout with pickup and confirm contact info is prefilled.
- Checkout with delivery and select saved address.
- Confirm order appears in customer order history.

## Emails

- Place pickup order and confirm customer confirmation email sends.
- Place delivery order and confirm customer confirmation email includes address and notes.
- Confirm admin receives new-order email.
- Confirm admin email contains summary and admin order link.
- Temporarily break SMTP config and confirm order creation does not fail catastrophically.

## Admin Dashboard

- Confirm dashboard shows current menu.
- Confirm recent orders appear.
- Confirm prep summary counts ordered items.
- Confirm cancelled orders are excluded from prep summary.

## Admin Menu Detail

- Open menu detail page.
- Confirm order table shows orders for that menu.
- Confirm prep totals match order quantities.
- Confirm fulfilled orders still count unless business rules later say otherwise.
- Confirm cancelled orders do not count.

## Admin Order Detail

- Open pickup order detail.
- Open delivery order detail.
- Confirm delivery order has Google Maps directions link.
- Click directions link and confirm Google Maps opens with destination.
- Edit customer contact details.
- Edit fulfillment type.
- Edit delivery address.
- Edit customer notes.
- Add item from same menu.
- Change item quantity.
- Remove item.
- Confirm totals recalculate after each item edit.
- Save edit without sending customer email.
- Save edit with customer email selected.
- Confirm updated order email contains updated order details.
- Cancel order.
- Confirm cancelled order no longer counts in prep summary.
- Mark order fulfilled.

## Security And Validation

- Submit POST forms without CSRF token and confirm request is blocked.
- Try accessing admin pages as normal customer and confirm blocked.
- Try SQL-like input in forms and confirm app behaves safely.
- Confirm password fields never render stored hashes.
- Confirm guest delivery address is stored only on the order.

## Backup

- Run `scripts/backup_sqlite.php` manually.
- Confirm backup file appears in `storage/backups/`.
- Confirm backup filename includes timestamp.
- Confirm backup file size is greater than zero.
- Confirm planned cron command is documented before deployment.

## Launch Readiness

- Replace `TODO_BUSINESS_NAME`.
- Replace `TODO_ADMIN_EMAIL`.
- Replace `TODO_TIMEZONE`.
- Replace SMTP placeholders.
- Replace Google Maps API key placeholder.
- Add real pickup instructions.
- Add real delivery instructions.
- Confirm Caddy serves the site over HTTPS.
- Complete one final pickup order test.
- Complete one final delivery order test.
