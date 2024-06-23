DragonByte eCommerce for XenForo 2.2.0+
=======================================

![Deploy](https://github.com/DragonByteTech/ecommerce/workflows/Deploy/badge.svg) ![Lint](https://github.com/DragonByteTech/ecommerce/workflows/Lint/badge.svg)  
  
Description
-----------

Sell digital downloads or physical products via your forum.

Requirements
------------

- PHP 7.3.0+

Recommendations
---------------

- PHP 7.4.0+
- DragonByte eCommerce Tickets v2.1.0+

Options
-------

#### DragonByte Tech: eCommerce

| Name | Description |
|---|---|
| Add-on product title template | Add-on product licenses can display the parent product in the title, controllable with this setting. |
| Address |  |
| Country | Your business' resident country. The native name of the country will be printed on the invoice. |
| Allow anonymous product reviews | If enabled, users can choose to make their reviews anonymous. Staff will still be able to see who made the review, but end users will not. |
| License filter field | If you are using the API to return licenses, you may wish to filter the licenses based on a URL entered in a license field. If so, please enter its field ID here.   This field will be checked against the `HTTP_X_DRAGONBYTE_BOARDURL` header when fetching the list of licenses. |
| Strict license filtering | If enabled, the value entered in the above license field must match the passed URL exactly. Otherwise, only the "host" part needs to match.   Example: (enabled) `https://www.example.com` vs. (disabled) `example.com` |
| Automatically email invoices | This system allows you to control whether invoices are automatically sent to users after they complete a transaction. |
| Address line 1 | First line of the business address. |
| Address line 2 | Second line of the business address. |
| Address line 3 | Third line of the business address. |
| Address line 4 | Fourth line of the business address. |
| Business c/o | If your business address has a c/o, you can add it here. |
| Tax ID | If your business has a tax ID such as VAT, enter it here. |
| Business title | The title of your business to be printed on the invoice. |
| Content deletion thread action | When content is deleted, take this action with any automatically created thread. |
| Coupons |  |
| Currency | This setting lets you choose the currency all prices should be displayed in. |
| Default "Customers" user group | The default "Customers" user group for new purchases. This can be overridden on a per-product basis. |
| Default address book country | When a user adds a new address, this will be the default country selected. |
| Default product owner | This setting controls who should own products created in the AdminCP. Ideally this should be set to a "bot" user that no-one logs in to. |
| Downloads per page | The number of downloads that will be listed per page in the "Releases" tab. |
| Enable API | This setting lets you toggle the API system that lets third party sites connect to your store.      An API key will be automatically generated that allows users to view products, downloads and their own licenses. |
| Enable "Checkout" button on the product page | If enabled, a "Checkout" button will appear under the Purchase button in the Pricing information if the current product is added to the cart. |
| Enable AdminCP income graph | If selected, the income graph will be added to the statistics block on the admin home page. |
| Enable product ratings | If disabled, the entire product rating system will be disabled. |
| Expired license renewal discount | This is the discount (in percent) that will be applied to purchases if the license has expired.   For instance: If a license expired Feb 1st, and it is being renewed on Feb 10th, this discount will apply. |
| Send expiry reminder for licenses that expire in less than... |  |
| Generated filename template | You can set the template to be used for generated .zip files here.   Available replacement variables: `{title}`, `{license_key}` |
| Use HTML invoices | If your system supports it, you can use a HTML template to generate invoices. To use this feature, you must have [wkhtmltopdf](https://wkhtmltopdf.org) installed on your server.      Enter the path to the wkhtmltopdf binary on this server. You can sometimes find this with the command `which wkhtmltopdf`. |
| Enable invoicing | This lets you turn off the invoicing system, preventing users from receiving or downloading invoices for their purchases. |
| Override default style | The style selected here will be used to render all invoices instead of the default style. |
| Default product list order | When viewing the product index or category overview pages, this will be the default order for products. |
| Log entries per page | The number of log entries that will be listed per page across the store. |
| MaxMind GeoIP License Key | In order to keep your geolocation database up to date, you need to [sign up for an account](https://www.maxmind.com/en/geolite2/signup) and set a password, then [generate a license key](https://www.maxmind.com/en/accounts/current/license-key) and fill that out here.   Geolocation is used for validating a customer's location if they fill out a VAT ID with their billing address. |
| Minimum product review length | This setting has no effect if a review is not required and the user does not enter a review. |
| Delete pending orders older than... |  |
| Default order list order | When viewing the account overview page, this will be the default order for orders. |
| Send email reminders for pending orders older than... |  |
| Orders per page | The number of orders that will be listed per page in a user's account. |
| Available payment profiles | You can choose the available payment profile(s) here. |
| Non-license product title template | Non-license products can display the product variation in the order item title, controllable with this setting. |
| Maximum product icon dimensions | The maximum allowed dimensions for product icon images (width x height). Use 0 or blank to use default dimensions. |
| Products per page | The number of products that will be listed per page across the store. |
| Release thread title template | You can set the template to be used for new release threads here.   Available replacement variables: `{title}`, `{category}`, `{starting_price}` |
| License renewal discount | This is the discount (in percent) that will be applied to purchases if the license is still active.   For instance: If a license expires Feb 1st, but it is being renewed on Jan 30th, this discount will apply. |
| Require account to checkout | If disabled, guests will be asked for their email address when entering their address details instead of being asked to register or login.      This setting has no effect if a digital product is in a guest's cart. Digital products always require an account. |
| Require download to rate products | If selected, users may only rate a product once they have downloaded it.   This setting is ignored for physical products. |
| Require a review when rating products | If enabled, users must submit a review when rating a product. |
| Allow review voting | This controls whether visitors can vote on whether a product review is helpful. This can allow more helpful reviews to become more visible. If enabled, you may choose to limit voting to positive responses only (upvotes). Product authors will not be able to vote on reviews for their own products. |
| Reviews per page | The number of reviews that will be listed per page across the store. |
| Sales |  |
| Sales Tax |  |
| Enable separate "Digital download refund policy" checkbox | During checkout, users will be asked to confirm they have read the Terms of Service. If your local laws require explicit consent for the refund policy regarding digital downloads, you can enable this setting.      To customise the wording of the refund policy, change the phrase `dbtech_ecommerce_i_agree_to_refund_policy` |
| Shipping alert destination | When someone purchases a physical item, the email can be sent to the seller or to the forum's contact email address. |
| Shipping weight unit | For physical items, this is the unit of measurement that will be displayed when showing the item's weight. |
| Terms of Service page | If a page is selected here, the contents of this page will be displayed in a scrollable area before purchase / download.   Updating this page in the AdminCP will force users to accept the Terms of Service again. |

#### Debug options (Debug only)

| Name | Description |
|---|---|
| Apply rounding | This feature automatically rounds prices up to the nearest 0, 2.50, 4.95 or 9.95.      Note: Only affects automatic calculations. |
| Confirmation email address | The email address DragonByte eCommerce will accept purchase confirmation requests to.   This should be an account that is not used for anything other than confirmation mail. Using a personal email account WILL lead to data loss! |
| Confirmation mailbox login info |  |
| Enable confirmation mailbox | If yes, the system will start collecting and checking confirmation emails.   Requires the below IMAP settings filled out with an account that is not used for anything other than confirmation mail. Using a personal email account WILL lead to data loss! |
| Invoice icon date | You shouldn't change this unless you need to. |
| Invoice icon path | This should not be changed unless you are sure you need to. |
| Maximum discount | The maximum discount (in percent) that can be applied to a product.   100 = No limit |

Permissions
-----------

#### DragonByte eCommerce moderator permissions

- Use inline moderation on products
- View scheduled downloads
- View deleted products
- Delete any product
- Undelete products
- Hard-delete any products
- Delete any product reviews
- Edit any products
- Release updates for any products
- Reassign products
- Manage any tags
- View unapproved products
- Approve / unapprove products
- Give warnings on products

#### DragonByte eCommerce permissions

- View products
- View product images
- Purchase products
- Use coupons
- Submit new VAT address without approval
- Download products
- React to products
- Review products
- Vote on product reviews
- Create products
- Add products without approval
- Upload images with products
- Update/edit own products
- Tag own product
- Tag any product
- Manage tags by others in own product
- Delete own products
- View own income stats

#### DragonByte eCommerce admin permissions

- View any licenses
- Edit any licenses
- Add licenses
- Delete licenses
- Download any licenses
- View download log
- Configure shopping cart for other users
- Add store credit to user
- View others' income stats

Admin Permissions
-----------------

- Manage DragonByte eCommerce: Business Profiles
- Manage DragonByte eCommerce: Categories
- Manage DragonByte eCommerce: Coupons
- Manage DragonByte eCommerce: Store Credit
- Manage DragonByte eCommerce: Discounts
- Manage DragonByte eCommerce: Downloads
- Manage DragonByte eCommerce: Licenses
- Manage DragonByte eCommerce: Products
- Manage DragonByte eCommerce: View Logs
- Manage DragonByte eCommerce: Sales
- Manage DragonByte eCommerce: Sales Tax
- Manage DragonByte eCommerce: Commissions
- Manage DragonByte eCommerce: Distributors
- Manage DragonByte eCommerce: Orders

BB Codes
--------

| Name | Tag | Description | Example |
|---|---|---|---|
| Product embed | `PRODUCT` | BB code for displaying products. | \[PRODUCT=product, X\]Product BB Code\[/PRODUCT\] |

Style Properties
----------------

#### DragonByte eCommerce

| Property | Description |
|---|---|
| Enable Infinite Scroll | Toggles whether infinite scrolling is enabled for this style. |
| Sale Ribbon | Definition of the ribbon that will be shown when a product is on sale. |
| Require click to load | Controls whether loading the next page happens automatically, or upon pressing a button. |
| Sale Ribbon: Surrounding Border | The border that gives the "wrap around" effect. |
| Only require click after X pages | If you want to require click only after a certain amount of pages have loaded, set this here.   0 = Always require click |
| Featured Ribbon | Definition of the ribbon that will be shown when a product is featured. |
| Append to browser history | If selected, new pages loaded will also update the browser's history. |
| Featured Ribbon: Surrounding Border | The border that gives the "wrap around" effect. |
| Show product owner on overview list |  |
| Invoice: Background | The background wrapper for the invoice itself. |
| Invoice: Page wrapper | The main page wrapper inside the tag. |
| Invoice: Header/logo row | The header row contains your logo and sits at the top of the first page of the invoice. |
| Product list style | This only affects the category view and the main home page overview. |
| Product rating style | Toggles how a product's rating appears in the sidebar on the product information page. |
| Product Rating Circle: Bar Width | The width of the bar in the product rating circle |
| Product Rating Circle: Background Color | The background color for the product rating circle's bar. |
| Product Rating Circle: Bar Color | The color of the rating circle bar. |
| List recent releases on license page | Toggles whether the list of recent releases is enabled in the license area for this style. |

Widget Positions
----------------

| Position | Description |
|---|---|
| Product category: Sidenav (`dbtech_ecommerce_category_sidenav`) | Displays inside the side navigation on the product category pages. Widget templates rendered in this position can use the current category entity in the `{$context.category}` param. |
| Product overview: Sidenav (`dbtech_ecommerce_overview_sidenav`) | Displays inside the side navigation on the product overview page. |
| Product page: Sidebar (`dbtech_ecommerce_product_sidebar`) | Displays inside the sidebar on the product page. Widget templates rendered in this position can use the current product entity in the `{$context.product}` param. |

Widget Definitions
------------------

| Definition | Description |
|---|---|
| DragonByte eCommerce: Latest reviews (`dbt_ecom_latest_reviews`) | Displays the latest product reviews. |
| DragonByte eCommerce: New Products (`dbt_ecom_new_products`) | Displays the most recently updated products. |
| DragonByte eCommerce: Random Products (`dbt_ecom_rnd_products`) | Displays random products. |
| DragonByte eCommerce: Top Rated Products (`dbt_ecom_top_products`) | Displays the top rated products. |

Cron Entries
------------

| Name | Run on... | Run at hours | Run at minutes |
|---|---|---|---|
| DragonByte eCommerce: Daily clean up | Any day of the month | 3AM | 0 |
| DragonByte eCommerce: Hourly clean up | Any day of the month | 12AM | 20 |
| DragonByte eCommerce: Update country list | The 1st of the month | 3AM | 0 |
| DragonByte eCommerce: Update VAT rates | The 1st of the month | 5AM | 0 |
| DragonByte eCommerce: Record daily income statistics | Any day of the month | 12AM | 30 |
| DragonByte eCommerce: Update GeoIP database | The 15th of the month | 12AM | 0 |

REST API Scopes
---------------

| Scope | Description |
|---|---|
| `dbtech_ecommerce_category:delete` | Covers deleting a product category. |
| `dbtech_ecommerce_category:read` | Covers viewing eCommerce categories or the list of categories. |
| `dbtech_ecommerce_category:write` | Covers updating or creating a product category. |
| `dbtech_ecommerce_download:delete_hard` | Covers hard-deleting downloads. |
| `dbtech_ecommerce_download:read` | Covers viewing release data |
| `dbtech_ecommerce_download:write` | Covers creating and soft-deleting downloads. |
| `dbtech_ecommerce_license:read` | Covers viewing license data |
| `dbtech_ecommerce_product:delete_hard` | Covers hard-deleting products. |
| `dbtech_ecommerce_product:read` | Covers viewing product data |
| `dbtech_ecommerce_product:write` | Covers creating and soft-deleting products. |
| `dbtech_ecommerce_rating:delete_hard` | Covers hard-deleting product ratings/reviews. |
| `dbtech_ecommerce_rating:read` | Covers viewing product ratings/reviews. |
| `dbtech_ecommerce_rating:write` | Covers creating and soft-deleting product ratings/reviews. |

CLI Commands
------------

| Command | Description |
|---|---|
| `xf-rebuild:dbtech-ecommerce-downloads` | Rebuilds download counters. |
| `xf-rebuild:dbtech-ecommerce-licenses` | Rebuilds license counters. |
| `xf-rebuild:dbtech-ecommerce-user-product-counts` | Rebuilds product related user counters. |
| `xf-rebuild:dbtech-ecommerce-products` | Rebuilds product counters. |
| `xf-rebuild:dbtech-ecommerce-categories` | Rebuilds product counters. |
| `xf-rebuild:dbtech-ecommerce-user-license-counts` | Rebuilds license related user counters. |
| `xf-rebuild:dbtech-ecommerce-amount-spent` | Rebuilds the counter for how much the user has spent. |