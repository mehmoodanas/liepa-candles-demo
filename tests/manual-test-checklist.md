# Manual test checklist

Run through this before showing the store. Tick each line and note the date. Most items are also covered by the automatic tests (`tests/e2e`); the items marked **manual** need a person.

Date tested: ____________   Tester: ____________   Browser: ____________

## Shopping flow

- [ ] Home page loads, hero visible, 4 products shown (auto)
- [ ] Open a product, choose **Large (250 g)**: price changes to 22.90 EUR (auto)
- [ ] Add to cart: message appears, cart count goes up (auto)
- [ ] Cart shows the product, the chosen size and the price (auto)
- [ ] Apply coupon `WELCOME10`: 10 percent discount shown (auto)
- [ ] Shipping: parcel locker 2.99 EUR is preselected; courier 4.99 EUR can be chosen (**manual** for choosing courier)
- [ ] Order of 35 EUR or more: free shipping appears (**manual**)
- [ ] Sold-out product (Winter Spice) cannot be added to the cart (auto)

## Checkout and payment (needs the test-mode payment setup)

- [ ] Checkout form opens from the cart (**manual**)
- [ ] Pay with a published **test** card in sandbox mode; order is created (**manual**)
- [ ] Order confirmation e-mail arrives (view it in Local's mail catcher, Mailpit) (**manual**)
- [ ] Order appears under WooCommerce, Orders, with status Processing (**manual**)
- [ ] Never use a real card in this demo

## Languages

- [ ] Click **LV**: Latvian home page and Latvian product names (auto)
- [ ] Click **EN**: back to English (auto)
- [ ] Latvian product page has the 3D viewer too (**manual**)
- [ ] Known limit: cart and checkout stay English

## 3D and AR

- [ ] Product page: the 3D model appears under "View in 3D" and can be rotated (auto + **manual**)
- [ ] On a phone: the AR button appears and opens the camera view (**manual**, needs a phone with AR support)
- [ ] The 3D library is not requested before the viewer is near the screen (auto)

## Mobile view

- [ ] Open the site on a phone (or Chrome DevTools, device mode): no sideways scrolling (**manual**)
- [ ] Menu, cart and the language switcher can be used with a finger (**manual**)

## Security and admin

- [ ] Log in to WordPress admin; turn on two-factor login in your profile (**manual**, needs your phone)
- [ ] Appearance has no "Theme File Editor" (code editing is switched off)
- [ ] `scripts\backup.ps1` creates a database dump and uploads zip
