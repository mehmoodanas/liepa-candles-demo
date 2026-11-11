# Plugins and why each one is here

Only what the demo needs. Fewer plugins means a faster, safer store.

| Plugin | Type | Why |
|---|---|---|
| WooCommerce 11.2 | Shop engine | Products, cart, checkout, shipping, coupons |
| Polylang 3.9 (free) | Translation | English and Latvian site with a language switcher |
| WP Super Cache 3.1 | Speed | Page cache; the biggest speed gain (see `performance.md`) |
| Two Factor 0.17 | Security | Two-step login for admin accounts (each admin enrols with their own phone) |
| Liepa 3D Viewer 0.1 | Own code | Adds the 3D / AR model block to product pages (`src/plugins/liepa-3d-viewer`) |

Theme: Storefront (official WooCommerce theme) with the own child theme `Liepa Child` (`src/themes/liepa-child`).

## Deliberately not used

- **No image-optimiser plugin.** Product images are generated as small WebP files already; a plugin would only add weight.
- **No page-builder plugin.** The home page is plain blocks plus a few lines of CSS.
- **No custom login or payment code.** Login security and (later) payments use standard, well-reviewed plugins.

## Other hardening

- The dashboard's theme/plugin code editor is switched off (`DISALLOW_FILE_EDIT`).
- Visitors cannot register their own user accounts.
- Backups: `scripts/backup.ps1` saves the database and uploads; copy the folder to a second disk.
