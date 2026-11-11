# Progress summary (updated 3 Nov 2026)

## Done
| Area | What was done | Where |
|---|---|---|
| Local setup | LocalWP 10.2.0 installed on drive E; its data folder moved to E (junction left on C) | `tools/LocalWP`, `tools/LocalWP-data` |
| Site | WordPress site `liepa-candles.local` created in `sites/` | `sites/app/public` |
| Shop engine | WooCommerce 11.2.1 + Storefront installed; store set to Latvia, EUR, Riga time; Latvian language pack installed | WordPress |
| Code | Child theme `liepa-child` and plugin `liepa-3d-viewer` written as small files and linked into WordPress | `src/` |
| Products | 8 demo candle products with SKU, EUR price, stock (one sold out), categories "Soy candles" and "Gift sets" | `scripts/seed/products.json`, `scripts/seed-products.ps1` |
| 3D model | CC-BY candle holder model attached to "Linden Blossom Soy Candle"; the product page shows a lazy-loaded 3D viewer with an AR button | `assets/models/` (credits in `CREDITS.md`) |
| Product photos | 8 illustrated WebP images (about 9 KB each) drawn by a script, imported as featured images | `scripts/images/`, `assets/images/products/`, `scripts/import-product-images.ps1` |
| Home page | Hero with a CSS animation, 4 newest products, trust badges, demo notice; set as front page; default "Sample Page" removed | `src/content/home.html`, `scripts/create-home-page.ps1`, theme `hero.css` |
| Shipping | Zone "Latvia": parcel locker 2.99 EUR, courier 4.99 EUR, free over 35 EUR (demo rates) | `scripts/php/setup-shipping.php` |
| Coupon | `WELCOME10`, 10 percent off | `scripts/php/setup-coupon.php` |
| Variants | Linden Blossom is a variable product: Small (150 g) 14.90 EUR, Large (250 g) 22.90 EUR | `scripts/php/setup-variable-product.php` |
| Languages | Polylang (free): English (default, `/`) and Latvian (`/lv/`); Latvian copy of all 8 products, 2 category names and the home page; EN/LV switcher in the header; each language lists only its own products | `scripts/setup-languages.ps1`, `scripts/php/setup-languages.php`, `scripts/php/setup-translations.php`, `src/content/home.lv.html`, theme `inc/language-*.php` |
| Speed | WP Super Cache; 3D model 2.7 MB to 140 KB; 3D library loaded only when needed; meta description. Lighthouse home 50 to 96, product 42 to 83, shop 81 to 83 (medians) | `docs/performance.md`, `scripts/measure-lighthouse.ps1`, `scripts/php/setup-cache.php` |
| Security | Two Factor plugin installed; dashboard code editor off; no public sign-up | `scripts/setup-security.ps1` |
| Backup | Script saves database dump + uploads zip; first backup made | `scripts/backup.ps1`, `backups/` |
| Plugins | List with a reason for each | `docs/plugins.md` |
| Tests | 11 Playwright browser tests (all pass), manual checklist, README screenshots, GitHub lint workflow (not run yet) | `tests/`, `.github/workflows/lint.yml`, `docs/screenshots/` |
| Bugs found by testing | 3D model URL was `https://` (failed on HTTP); 3D block overlapped the product image; empty beige box until clicked | fixed in `src/plugins/liepa-3d-viewer` |
| README | Rewritten with purpose, honest limits, screenshots, results, setup | `README.md` |
| Store status | "Coming soon" mode switched off, so the shop is visible | WordPress option |
| Tools | `scripts/wp.ps1` runs WP-CLI against the local site | `scripts/` |

## Remaining
1. **Content and look:** fill in the privacy, returns and terms pages (WooCommerce created empty drafts for privacy and returns), a menu clean-up, nicer product pictures if wanted.
   - *Known language limits (free Polylang):* cart, checkout and account pages exist only once (English); stock is not shared between an English product and its Latvian copy; the Size labels on Linden Blossom stay in English. Full WooCommerce translation needs the paid add-on.
2. **Shop features:** a full test checkout (cart, coupon, shipping choice) and VAT settings (Latvia 21 percent) are still to be checked; shipping only covers Latvia so far.
3. **Payments (test mode):** Stripe or PayPal sandbox. *You* create the sandbox account and keys.
4. **Speed and security leftovers:** *you* turn on 2FA for your admin login (Users, Profile, Two-Factor Options, needs your phone); a staging copy (Local's "Clone site"); self-host the fonts to cut LCP from about 3.5 s; copy `backups/` to a second disk.
5. **Run the manual checklist** once payments are set up (checkout, test card, order e-mail, AR on your phone).
6. **Delivery:** GitHub push (needs your sign-in), short screen recording, and the message to WD Market. Decide whether the README should mention that an AI assistant helped build this (it currently does not).
7. **Housekeeping (later):** switch the Windows firewall rules to Private networks; set up a full computer backup.

## Notes
- Open the site at `http://liepa-candles.local` (HTTP). The HTTPS address shows a certificate warning until you click **Trust** next to SSL in Local.
- The store is a fictional brand and a learning demo; do not present it as real work experience.
- Local must be open and the site started before the scripts in `scripts/` work.
