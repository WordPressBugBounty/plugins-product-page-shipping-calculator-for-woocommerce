<!-- pi-doc-last-commit: 1a89a99334f369eb471dac0b0dc6b83029ee6925 -->

# Product Page Shipping Calculator

## Overview

Shows available shipping methods and costs directly on the WooCommerce product page.

Customers enter a delivery location and see which shipping methods apply, without adding the product to the cart or going to checkout.

Calculations run over AJAX. No page reload is needed.

## How to Use

### Step 1: Open the Settings

Go to:

```text
WooCommerce > Shipping Calculator
```

The settings page has these tabs:

- `Basic setting`
- `Design`
- `Remove fields`
- `Popup`
- `Labels`
- `Estimate date` / `Show estimate delivery date`

This document covers the product-page calculator. Popup badge settings live under the `Popup` tab. Text labels for the calculator and the address popup live under the `Labels` tab.

### Step 2: Configure the Feature

Open the `Basic setting` tab.

Set where the calculator appears, how it loads results, and which quantity is used.

Open the `Labels` tab to set button text and messages customers see.

### Step 3: Save the Settings

Click `Save Changes`.

## Configuration Options

All options below live under `WooCommerce > Shipping Calculator` unless stated otherwise.

### Position of the calculator on product page

Tab: `Basic setting`.

Controls where the calculator appears on the product page.

Allowed values:

| Value | Label |
|---|---|
| `woocommerce_after_add_to_cart_form` | After add to cart button |
| `woocommerce_before_add_to_cart_form` | Before add to cart button |
| `shortcode` | Use shortcode [pi_shipping_calculator] to insert manually |

Default: `After add to cart button`.

When `shortcode` is selected, automatic output is disabled. Add the calculator manually with:

```text
[pi_shipping_calculator]
```

Shortcode behavior, verified in `public/class-product-page.php:47-65`:

- Works only on product pages.
- Returns an error message when shortcode mode is not enabled.
- Returns nothing for virtual or out-of-stock products.

### Position of the calculator result

Tab: `Basic setting`.

Controls where the calculated shipping result appears.

Allowed values:

| Value | Label |
|---|---|
| `pi_ppscw_before_calculate_button` | Before "Calculate Shipping" button |
| `pi_ppscw_after_calculate_button` | After "Calculate Shipping" button |
| `pi_ppscw_before_calculate_form` | Before shipping form (hidden container) |
| `pi_ppscw_after_calculate_form` | After shipping form (hidden container) |

Default: `Before "Calculate Shipping" button`.

The last two positions sit inside the collapsible shipping form container.

### Shipping calculator form default state

Tab: `Basic setting`.

Allowed values:

- `Closed by default`
- `Open by default`

Default: `Closed by default`.

When closed, customers click the calculator button to expand the address form.

### Auto calculation of shipping method

Tab: `Basic setting`.

Allowed values:

- `Enabled`
- `Disabled`

Default: `Enabled`.

Behavior:

- `Enabled`: shipping methods load automatically.
- `Disabled`: automatic loading is skipped. The customer must submit the form with `Update Address`. Before any address is entered, the calculator shows the `When user hasn't entered an address` message.

### Load user location data via AJAX

Tab: `Basic setting`.

Type: switch. Default: off (`0`).

Enable this when the product page is cached. The saved customer location is then loaded through AJAX instead of being served from the cached page.

### Product Quantity Field

Tab: `Basic setting`.

Allowed values:

- `Calculate for the quantity selected (more accurate)`
- `Calculate for 1 unit until the product is in the cart`

Default: `Calculate for 1 unit until the product is in the cart`.

Stored values are unchanged: `consider-quantity` and `dont-consider-quantity-field`.

Behavior, verified in `public/class-product-page.php:216-240` and the page script:

- `Calculate for the quantity selected (more accurate)`: the quantity selected on the product page is sent with the calculation. If the same product is already in the cart, that cart quantity is also included because the test product is added on top of the cart.
- `Calculate for 1 unit until the product is in the cart`: calculation uses 1 unit when the product is not already in the cart. When the product is already in the cart, the existing cart quantity is used and no extra test product is added.

The page script reads the visible quantity field `#quantity_<product_id>` into the AJAX request.

### Show shipping cost of the individual product only

Tab: `Basic setting`.

Type: switch. Default: off (`0`).

When enabled, other products in the cart are temporarily ignored during the calculation. The result reflects only the product being viewed.

When disabled, the calculation includes the current cart contents plus the viewed product.

### When user hasn't entered an address

Tab: `Labels`, section `Product page calculator labels`.

Type: text. Default:

```text
Insert your location to get the shipping method
```

Shown when no shipping country is selected yet, or when automatic loading is disabled and no calculation has run.

### Button – Calculate Shipping

Tab: `Labels`, section `Product page calculator labels`.

Type: text. Default:

```text
Select delivery location
```

Text inside the button that opens the calculator form.

### Button – Update Address

Tab: `Labels`, section `Product page calculator labels`.

Type: text. Default:

```text
Update Address
```

Submit button inside the address form.

### Above the shipping method list

Tab: `Labels`, section `Product page calculator labels`.

Type: text. Default:

```text
Shipping methods available for your location:
```

Supports the `[country]` placeholder. It is replaced with the shipping country name selected by the customer.

### When no shipping methods are available

Tab: `Labels`, section `Product page calculator labels`.

Type: text. Default:

```text
No shipping methods available for your location
```

Shown when a location is entered but its shipping zone has no available methods.

### When no product variation is selected

Tab: `Labels`, section `Product page calculator labels`.

The popup labels (`Popup title`, `Your details are saved`, `Update address button`) also live under `Labels`, section `Address form popup labels`. They belong to the popup flow, not the product-page calculator.

Type: text. Default:

```text
Select variation
```

Used on variable products before a variation is chosen. The calculator stays hidden until an in-stock, non-virtual variation is selected. If this message is left empty, no message is shown.

### Price next to free shipping methods

Tab: `Basic setting`.

Allowed values:

- `No price is shown`
- `Show "0"`

Default: `No price is shown`.

Behavior, verified in `public/class-product-page.php:439-453`:

- `No price is shown`: free methods keep the normal WooCommerce label.
- `Show "0"`: free methods show as `<label>: <price of 0>`.

### Remove address form fields from product page calculator

Tab: `Remove fields`.

Each field is a switch. Default: off (`0`, field is shown).

- `Remove country field`
- `Remove state field`
- `Remove Town/City field`
- `Remove Postcode/Zip field`

Warning from the settings screen: if shipping zones depend on a field, do not remove it. The plugin then cannot match the zone.

Important verified constraint for the country field (`public/partials/shipping-calculator.php:43-63`):

- The country dropdown is replaced by a hidden field only when the store ships to exactly one country **and** `Remove country field` is enabled.
- When WooCommerce is set to ship to more than one country, the remove-country control is replaced with a notice and cannot be used.

The same tab has a separate section for the popup address form (`Remove country field`, `Remove state field`, `Remove Town/City field`, `Remove Postcode/Zip field` with `_add_form` option names). Those affect the popup/address form, not the product-page calculator.

### Design template

Tab: `Design`.

Setting: `Design template`. Type: select. Default: `Template 1`.

Allowed values:

- `Template 1`
- `Old classic template`

### Box showing result of shipping methods

Tab: `Design`.

- `Background color of shipping methods`: gradient. Default `#FFF4EF` to `#FFE0D3`, direction `135`.
- `Text color of message`: default `#000000`.
- `Text color of shipping methods name`: default `#000000`.
- `Text color of shipping charges`: default `#000000`.

### Button colors

Tab: `Design`.

- `Calculate shipping button background color`: gradient. Default `#EE6A47` to `#D9472B`, direction `135`.
- `Calculate shipping button text color`: default `#ffffff`.
- `Update address button background color`: gradient. Default `#EE6A47` to `#D9472B`, direction `135`.
- `Update address button text color`: default `#ffffff`.

### Disable shipping cost per product

Location:

```text
Products > Edit product > Shipping calculator
```

Setting: `Disable shipping cost`. Type: select.

Allowed values:

- Empty (`Select option`)
- `Disable calculator`
- `Enable calculator`

No global default is forced in code. Verified behavior in `public/class-product-page.php:80-82`: only the exact value `disable` hides the calculator for that product. The product tab is hidden for grouped products (`hide_if_grouped`).

The calculator also never renders for virtual products (`public/class-product-page.php:90-94`).

## Examples

### Example 1: Show methods after the Add to cart button

#### Configuration

| Option | Value |
|---|---|
| Position of the calculator on product page | After add to cart button |
| Shipping calculator form default state | Closed by default |
| Auto calculation of shipping method | Enabled |

#### Result

Customer opens a product page, clicks `Select delivery location`, enters an address, and sees the shipping methods for that address without page reload.

### Example 2: Manual placement with shortcode

#### Configuration

| Option | Value |
|---|---|
| Position of the calculator on product page | Use shortcode [pi_shipping_calculator] to insert manually |

#### Result

Add this to the product page:

```text
[pi_shipping_calculator]
```

Automatic output stops. The shortcode renders the calculator only on product pages.

### Example 3: Respect the quantity field

#### Configuration

| Option | Value |
|---|---|
| Product Quantity Field | Calculate for the quantity selected (more accurate) |

#### Result

Customer sets quantity to `3` on the product page. The shipping estimate uses `3` plus any quantity of the same product already in the cart.

### Example 4: Single-product estimate while cart has other items

#### Configuration

| Option | Value |
|---|---|
| Show shipping cost of the individual product only | Enabled |

#### Result

Cart contains product B. Customer views product A. The product-page result reflects product A only and ignores product B.

### Example 5: Show the selected country in the result heading

#### Configuration

| Option | Value |
|---|---|
| Above the shipping method list | Shipping methods available for [country]: |

#### Result

Customer selects India. The result heading reads:

```text
Shipping methods available for India:
```

## Customer-facing behavior

- The calculator submits `calc_shipping_country`, `calc_shipping_state`, `calc_shipping_city`, and `calc_shipping_postcode` through WooCommerce shipping-zone matching.
- Default selected country comes from the WooCommerce customer shipping country. When the store ships to exactly one country, the plugin can auto-select it.
- Method labels use `wc_cart_totals_shipping_method_label()`, so price and tax display follow WooCommerce tax settings.
- During its own AJAX request, the plugin bypasses WooCommerce's `Hide shipping costs until an address is entered` option so results can be returned.
- For variable products, the calculator is hidden until the customer selects a variation. Out-of-stock or virtual variations keep it hidden.
- The location popup badge and `[pi_address_form]` shortcode belong to the separate delivery-location popup flow and are configured under the `Popup` tab.

## Important Notes

- WooCommerce must be active. Without it, the plugin shows an admin notice and stops loading.
- On the plugin settings screen, an admin notice appears when `WooCommerce > Settings > Default customer location` is empty. Set it to `Shop base address` or a geolocation option for reliable results.
- Do not remove a form field that shipping zones depend on. Zone matching will fail.
- `Remove country field` works only when WooCommerce shipping countries contain exactly one country.
- The estimate-date tab requires the Estimate Delivery Date plugin (PRO `>= 4.5` or free `>= 4.11.44`). Without it, that tab shows install instructions instead of working settings.

## Troubleshooting

- Calculator does not appear on a product: check the per-product `Shipping calculator` setting is not `Disable calculator`, check the product is not virtual, and check `Position of the calculator on product page`.
- Shortcode shows an error: `Position of the calculator on product page` must be set to `Use shortcode [pi_shipping_calculator] to insert manually`, and the shortcode must be on a product page.
- Wrong location loads from cache: enable `Load user location data via AJAX`.
- Result ignores the quantity field: set `Product Quantity Field` to `Calculate for the quantity selected (more accurate)`.
- Result reflects other cart items: enable `Show shipping cost of the individual product only`.
- No methods for a valid address: check `WooCommerce > Settings > Shipping > Shipping zones` has a matching zone with at least one method, and check removed fields are not needed for zone matching.

## Frequently Asked Questions

### Where is the calculator positioned?

Under `Basic setting > Position of the calculator on product page`: before or after the Add to cart button, or manually with `[pi_shipping_calculator]`.

### Where is the result positioned?

Under `Basic setting > Position of the calculator result`. Four positions are available: before or after the `Calculate Shipping` button, or before or after the shipping form inside the hidden container.

### How is quantity handled?

See `Product Quantity Field`. Use `Calculate for the quantity selected (more accurate)` when shipping cost depends on quantity.

### Can the calculator ignore the rest of the cart?

Yes. Enable `Show shipping cost of the individual product only`.

### Can a single product opt out?

Yes. Edit the product, open the `Shipping calculator` tab, and set `Disable shipping cost` to `Disable calculator`.

### Does it work for variable products?

Yes. The customer must select a variation first. The `When no product variation is selected` message covers the unselected state.

### Why is the country removal option unavailable?

That option works only when the store ships to a single country. Configure `WooCommerce > Settings > General > Shipping location(s)` to one country first.
