# Design Upgrade TODO

This plan tracks visual and UX polish after the functional MVP. Keep the site simple and server-rendered. Do not add a frontend framework or build step unless explicitly requested.

## Goals

- Make the site feel like a real catering business, not a bare admin prototype.
- Keep the layout clean, mobile-friendly, and easy to maintain in plain PHP.
- Continue using Pico.css as the base style layer.
- Add only small custom CSS where needed.

## Phase 1: Brand Basics

- Replace `TODO_BUSINESS_NAME` with the real business name.
- Choose a simple color palette.
- Choose one logo approach: text-only wordmark, uploaded logo image, or simple mark.
- Add homepage hero copy explaining what the business offers.
- Add pickup and delivery instruction copy.
- Add footer contact information.

## Phase 2: Public Homepage

- Add a stronger hero section above the menu.
- Show current menu title, release date, and cutoff time more clearly.
- Make cutoff-closed banner more prominent.
- Improve no-menu empty state.
- Improve menu card spacing, image presentation, and typography.
- Add clearer unavailable-item styling.
- Make add-to-cart controls easier to use on mobile.

## Phase 3: Cart And Checkout

- Improve cart table layout on mobile.
- Add a clearer order total summary box.
- Make pickup vs delivery choice visually obvious.
- Hide or de-emphasize delivery address fields when pickup is selected.
- Add helper text for delivery address autocomplete.
- Add pickup and delivery instruction blocks from config.
- Improve validation error placement near affected fields.

## Phase 4: Customer Account

- Improve profile and saved-address layout.
- Make saved address edit/delete actions clearer.
- Add better empty state for no saved addresses.
- Add better empty state for no order history.
- Consider adding a customer order detail page later.

## Phase 5: Admin UX

- Improve admin dashboard hierarchy.
- Make prep summary the most scannable part of the dashboard.
- Add status badges for confirmed, fulfilled, and cancelled orders.
- Add stronger visual difference between draft, released, closed, and current menus.
- Improve menu entry editing table layout.
- Add confirmation copy around cancel order actions.
- Make Google Maps directions button stand out on delivery orders.

## Phase 6: Mobile Polish

- Test homepage on phone width.
- Test cart on phone width.
- Test checkout on phone width.
- Test admin dashboard on phone width.
- Test order detail page on phone width.
- Add a simple burger menu breakpoint for medium-width navigation before header links wrap awkwardly.
- Adjust tables that overflow badly.
- Make primary actions easy to tap.

## Phase 7: Content Polish

- Write friendly homepage intro copy.
- Write short menu ordering instructions.
- Write pickup instructions.
- Write delivery instructions.
- Write confirmation page thank-you copy.
- Write better closed-menu message.
- ~~Format printed release, cutoff, and order dates into friendly readable text.~~
- Review all button labels for clarity.

## Phase 8: Final Visual QA

- Test in Chrome.
- Test in Safari.
- Test on mobile Safari if available.
- Verify images crop consistently.
- Verify color contrast is readable.
- Verify forms are usable with keyboard only.
- Verify the site still works with JavaScript disabled except Google autocomplete.

## Follow-Up: Theme And Brand Assets

These items were identified after the first rustic Peruvian customer UI pass on branch `design/rustic-peruvian-customer-ui`.

### Current Design Context

- Business name: `Akisitonoma`.
- Visual direction: homey rustic Peruvian cuisine, subtle cultural references, clay/olive/espresso/cream palette.
- CSS is split under `public/assets/styles/` with `public/assets/styles.css` acting as the import manifest.
- Current templates follow the system color scheme; the old forced `data-theme="light"` was removed from `app/views/layout-top.php`.
- Keep the site server-rendered and avoid build tooling.

### Dark And Light Modes

- ~~Add a proper light/dark theme system without adding a frontend framework.~~
- ~~Decide whether the default should follow `prefers-color-scheme` or default to light.~~
- Consider a simple theme toggle in the header if approved.
- If a toggle is added, use a small progressive-enhancement script and store preference in `localStorage`; the site should still render correctly without JavaScript.
- ~~Update `public/assets/styles/theme.css` so color tokens exist for both light and dark modes.~~
- ~~Remove or revise the hardcoded `data-theme="light"` in `app/views/layout-top.php` once dark mode exists.~~
- Verify cards, forms, tables, buttons, flash messages, and mobile cart rows in both modes.
- Check contrast for clay, olive, maize, cream, and espresso combinations.

### Homepage Banner Image

- ~~Add support for a homepage banner/hero image while preserving the current text-first hero layout.~~
- ~~Create a simple front-page hero image asset that fits the rustic Peruvian food direction.~~
- ~~Use a local static asset path such as `public/assets/images/home-hero-placeholder.*` until real photography exists.~~ Implemented as optimized WebP assets generated from an AI source image.
- ~~Prefer a warm food/kitchen banner crop that supports the rustic Peruvian direction.~~
- ~~Keep the hero usable when no image exists: CSS gradient/pattern fallback should remain.~~
- Suggested files to touch: `app/views/public/home.php`, `public/assets/styles/pages.css`, `public/assets/styles/responsive.css`, and possibly `public/assets/images/`.
- ~~Mobile behavior: image should stack below or above copy, crop cleanly, and not push the menu too far down.~~ Implemented as a decorative CSS background with a smaller mobile asset.
- ~~Include meaningful `alt` text if the image is content; use empty `alt=""` if it is decorative.~~ Implemented as a decorative CSS background, so no image markup is needed.

### Favicon And Logos

- ~~Add SVG brand assets for `Akisitonoma`: favicon and header logo/mark.~~ Raster touch icon remains deferred.
- ~~Create a simple SVG chili pepper logo/mark that can work in the header and favicon set.~~
- ~~Until final design assets exist, use a simple text/initial mark based on the current `A` brand mark.~~ Replaced with SVG pepper mark.
- Suggested static paths:
  - `public/favicon.ico`
  - `public/assets/brand/favicon.svg`
  - `public/assets/brand/apple-touch-icon.png`
  - `public/assets/brand/logo.svg`
- ~~Add SVG favicon link in `app/views/layout-top.php`.~~ Raster touch icon remains deferred.
- ~~Update `app/views/partials/header.php` if replacing the CSS-only `A` mark with an SVG logo.~~
- ~~Keep logo markup accessible with clear home-link labeling.~~
- Verify the favicon displays in Chrome/Safari and the header remains clean on mobile.

### Seed Content

- Create a simple menu-item seed script for local/demo database setup.
- Use AI-assisted seed names and descriptions for realistic Peruvian catering dishes, then review for tone and accuracy.
- Download a small set of free-to-use food images with `curl` or a similar CLI tool, saving license/source notes alongside the seed data.
- Store seeded images under `public/uploads/menu-items/` and keep the script safe to rerun without duplicating records.
- Prefer stable image URLs from clearly licensed sources; do not rely on hotlinked external images in production pages.

### Suggested Implementation Order

1. ~~Add static brand asset folders and placeholder SVG logo/favicon.~~
2. ~~Wire SVG favicon link in the layout.~~ Raster touch icon remains deferred.
3. ~~Add homepage banner image support with fallback styling.~~
4. ~~Add dark/light theme tokens.~~
5. ~~Decide and implement optional theme toggle.~~
6. Add demo menu-item seed content with local images.
7. Test desktop and mobile in both themes.

## Constraints

- Keep Pico.css.
- Keep plain PHP templates.
- Avoid custom JavaScript except small progressive enhancements.
- Avoid large icon/font/component libraries unless specifically approved.
- Do not change core ordering behavior during design polish unless needed for usability.
