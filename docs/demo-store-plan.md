# WD Market Portfolio Demo Store: Idea and Prompt

Status: **plan only, nothing has been built.**

## Part 1: The idea

### Goal
A small, fast, working online store that shows WD Market I can build and maintain a WooCommerce shop and do the speed and stability work their ad stresses. It is a portfolio demo with a **fictional Latvian brand**, not a copy of their store. I have not verified how their real store works, so the demo must not pretend to be it.

### Concept
- **Brand:** small fictional Latvian shop. Product is still open (candles or mugs suit the 3D viewer best; tea or socks also work).
- **Platform:** WooCommerce first, on a free local setup (LocalWP). A Shopify version can come later, after I create a Shopify Partner account myself.
- **Languages / currency:** Latvian + English, prices in EUR.
- **Payments:** test/sandbox mode only (Stripe or PayPal sandbox, published test cards). No real money.

### What the store includes
| Area | Content |
|---|---|
| Home | Hero with a light CSS or GSAP scroll effect, featured products, trust badges |
| Shop | 8-10 products, filters, search, sorting |
| Product page | Image gallery, variants (size/scent), stock status, ONE 3D model with Google `<model-viewer>` (GLB under ~2 MB, lazy-loaded, AR button on phones) |
| Cart / checkout | Simple checkout, demo parcel-locker or courier shipping rates, test-mode payment |
| Account | Standard WooCommerce login + a well-reviewed 2FA plugin (no custom login or payment code) |
| E-mail | Standard order confirmations (optional later: small Gmail order-summary script) |
| Legal | Privacy, terms and returns placeholders |

### Speed and stability (what sets it apart)
- Budget: 3D model under ~2 MB, loaded only when opened. Images in WebP, lazy-loaded.
- Few plugins, each justified in the README.
- One caching plugin and one image plugin, configured and documented.
- Run PageSpeed/Lighthouse **before and after**; put both scores in the README.
- Stability: staging copy, backup routine, short update checklist.

### Tests and documentation
- Manual checklist: add to cart, apply coupon, test checkout, order e-mail, mobile view.
- Optional: Playwright or Pytest end-to-end tests run in GitHub Actions.
- GitHub repo (https://github.com/mehmoodanas) with README: purpose, setup, screenshots, plugin list with reasons, speed results.
- Link the repo and a short screen recording in the message to WD Market.

### Plan (about 4 days)
| Day | Work |
|---|---|
| 1 | LocalWP, WordPress, WooCommerce. Theme, products, pages, currency, languages |
| 2 | Product page with 3D viewer, hero animation, shipping, test payment |
| 3 | Speed work (caching, images), security (2FA, updates), backup |
| 4 | Tests, README, screenshots, recording, GitHub upload |

### Honest limits
- I have no WooCommerce/Shopify job experience. Say so and present this as a demo built to learn and prove skill.
- Do not claim experience with 1C or any tool I haven't used.
- For a Shopify demo I must create the Partner account myself (sign-ups and passwords are mine).

---

## Part 2: Prompt to give Claude when ready

```
I am Anas Mehmood, a Computer Systems graduate applying to WD Market in Latvia for a WooCommerce/Shopify developer role. I want a small portfolio demo store to show them. Do NOT copy their real store; build a fictional Latvian brand.

Build a WooCommerce demo store on LocalWP with these requirements:
- Fictional brand: [PRODUCT IDEA, e.g. handmade candles]. 8-10 products, EUR prices, Latvian + English.
- Pages: home (light CSS/GSAP scroll hero), shop with filters/search, product page with image gallery, variants, stock status and ONE 3D model using Google <model-viewer> (GLB under 2 MB, lazy-loaded, AR button on mobile), cart, checkout with demo parcel-locker/courier shipping, account page, privacy/terms/returns placeholders.
- Payments in sandbox/test mode only. Use standard, well-reviewed plugins for login security (2FA) and payments. Never write custom payment or login code. Never ask for or enter real card data or my passwords.
- Performance: WebP images, lazy loading, one caching plugin, minimal plugin list. Run Lighthouse before and after and record the scores.
- Stability: staging copy, backup routine, update checklist.
- Tests: a manual test checklist (add to cart, coupon, test checkout, order e-mail, mobile view). Optionally add Playwright tests and a GitHub Actions workflow.
- Deliverables: the project in a GitHub repo (https://github.com/mehmoodanas) with a README (purpose, setup steps, screenshots, plugin list with a reason for each, speed results), plus a short list of what I should say in my message to WD Market.

Work in small steps. After each step, tell me what you did and what I need to do myself (account sign-ups, passwords, consents, anything involving real money). Be honest in the README about what is a demo and what I have and have not done before.
```

## Open items
- [ ] Pick the product (candles recommended for the 3D viewer).
- [ ] Short message to WD Market to send with the demo link (can be drafted later).
