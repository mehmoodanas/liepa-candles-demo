# Performance results (Lighthouse, mobile)

Measured with Lighthouse on the local site (`http://liepa-candles.local`) using `scripts/measure-lighthouse.ps1`.
The score summaries are in `docs/performance/*-summary.json`; the full reports (about 500 KB each) are kept only on the local machine. These are local-machine numbers with Lighthouse's simulated slow phone, so treat them as relative, not absolute.

## Performance score (0-100)

| Page | Before | After run 1 | After run 2 | After run 3 | After (median) |
|---|---|---|---|---|---|
| Home | 50 | 96 | 75 | 97 | **96** |
| Shop | 81 | 83 | 91 | 79 | **83** |
| Product (with 3D) | 42 | 78 | 83 | 88 | **83** |

Results vary a lot between runs on a normal computer (home page 75 to 97), so the median of three runs is the fairest number.

## Other scores (after)

| Page | Accessibility | Best practices | SEO |
|---|---|---|---|
| Home | 93 | 78 | 92 |
| Shop | 97 | 78 | 92 |
| Product | 94 | 78 | 92-100 |

SEO went from 83-85 to 92+ after adding a meta description.

## What changed and why it helped

| Change | Effect |
|---|---|
| **Page cache** (WP Super Cache) | Before, every visit rebuilt the page: server response took 1.2 s on the product page and 8.2 s on the home page. Cached, a page is served in about 0.06 s. This is the biggest gain. |
| **3D model compressed** (2.7 MB to 140 KB) | Smaller download when a visitor opens the viewer. Textures converted to WebP at 1024 px. |
| **3D library loaded on demand** | The 250 KB `model-viewer` script is requested only when the viewer scrolls near the screen, not on page load. Cut unused JavaScript and blocking time on the product page. |
| **WebP product images** (about 9 KB each) | Images were already tiny; no image-optimising plugin is needed. |
| **Meta description** | SEO score 85 to 92+. |

## Still open (honest list)

- **Largest Contentful Paint is still 3.1-3.8 s on shop and product pages.** The Storefront theme loads Google Fonts and a 78 KB icon font; hosting them locally or trimming them is the next step.
- **Best practices stays at 78** because the local site runs on HTTP (and Local's HTTPS certificate is not trusted by default). On a real host with HTTPS this would rise.
- Accessibility items left: small tap targets and one low colour-contrast spot on the product page.
- The cache is cleared with `scripts/clear-cache.ps1` after code changes; stale cached pages can hide a change.
