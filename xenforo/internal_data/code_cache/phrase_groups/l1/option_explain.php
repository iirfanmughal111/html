<?php
return array (
  'option_explain.Advertiser_manaul_approval' => '',
  'option_explain.DC_LinkProxy_AutoRedirection' => 'You can disable automatic redirection if you want your visitor to click "Continue" on the redirection page to visit the page, instead of automatically being redirected.',
  'option_explain.DC_LinkProxy_AutoRedirection__time' => 'Enter default auto redirection time in seconds.',
  'option_explain.DC_LinkProxy_DomainWhiteList' => 'Enter the domain names/hostnames that you do not want to anonymize/redirect. <b>Only one domain per line.</b><br/>
If you want to white list an entire domain including its sub-domains, <b>only add domain.com.</b><br/>
If you want to white list a domain\'s sub-domains and not the top level one, <b>add .domain.com.</b>',
  'option_explain.acpSearchExclude' => 'When using the quick search facility in the control panel, content from the following types will be searched. Disabling content types here may speed-up searching slightly.',
  'option_explain.activitySummaryEmail' => 'If enabled, users who have not visited for a while will receive an email to keep them updated about recent content. The content of the email can be configured <a href="admin.php?activity-summary/">here</a>.<br />
<br />
Note: Users can decide to opt-in/opt-out of receiving the activity summary email in their account preferences.',
  'option_explain.activitySummaryEmailBatchLimit' => 'The activity summary emails are sent daily by default and this value controls how many emails are sent  at a time.',
  'option_explain.addBanUserGroup' => 'When a user is banned, they can be added to a specific user group while the ban is active. This allows overrides to their user group styling, for example.',
  'option_explain.adminRequireTfa' => 'If enabled, admins will not be able to access the control panel until they have activated two-step verification. This will not affect users currently logged into the control panel until their next login.',
  'option_explain.adsDisallowedTemplates' => 'You may want to prevent all ads from showing within certain templates, such as errors or other pages that may be against your ad provider\'s ToS. List them above.',
  'option_explain.akismetKey' => '<a href="https://akismet.com/signup/" target="_blank">Akismet</a> is a service that scans comments and determines if they are spam. If you enter an Akismet API key here, user messages will be checked for spam. You can sign up for an API key via their site. If Akismet determines a message is spam, it will have to be manually approved before being displayed.',
  'option_explain.alertExpiryDays' => 'User alerts will disappear from the alerts list this many days after being viewed. Unviewed alerts will automatically expire after 30 days.',
  'option_explain.alertsPerPage' => 'Controls how many alerts are shown on each page of users\' full alert list.',
  'option_explain.alertsPopupExpiryDays' => 'The alert popup will show all unviewed alerts, plus any viewed alerts that were viewed within the time frame (in days) before now. Note that the total amount shown in this popup is limited to no more than 25.',
  'option_explain.allowGuestRte' => 'If a text editor is shown to guests, this controls whether they will be given the option to use the formatting controls provided by the rich text editor. Disabling this can increase performance for guests if you allow guest posting or use the "write before registering" feature.',
  'option_explain.allowVideoUploads' => 'Use this option to globally enable or disable video/audio uploads. If enabled, you must give specific users/groups the relevant permissions to upload video/audio.<br />
<br />
The following additional extensions will be available for upload: {allowedVideoExtensions}.<br />
<br />
You should also provide the maximum video/audio file size in kilobytes (KB). Video/audio over this size will be rejected. The configuration of this server limits this value to <b>{serverMaxFileSize}</b> KB.',
  'option_explain.allowedCodeLanguages' => 'The languages defined here will be available to use inside code BB code with the format of [CODE=xxxx].',
  'option_explain.approveSharedBannedRejectedIp' => 'This option allows the IP address of a new registration to be checked against IPs recently used by banned or rejected users. If one or more matches is found, the registration will need to be manually approved by an administrator.',
  'option_explain.attachmentExtensions' => 'List the file extensions that are allowed to be uploaded. Use spaces or line break between extensions. If video/audio uploads are enabled, the related extensions will be automatically allowed.',
  'option_explain.attachmentMaxDimensions' => 'The maximum allowed dimensions for attached images (width x height). Use 0 or blank to not restrict dimensions.',
  'option_explain.attachmentMaxFileSize' => 'Provide the maximum attachment file size in kilobytes (KB). Attachments over this size will be rejected. The configuration of this server limits this value to <b>{serverMaxFileSize}</b> KB.',
  'option_explain.attachmentMaxPerMessage' => 'Use 0 to allow an unlimited number of attachments per message. To disable message attachments, use the permissions system.',
  'option_explain.attachmentThumbnailDimensions' => 'Controls the length of the shortest edge of attachment thumbnail images. The longer edge of the image may be longer than the length entered here. A value of at least 150px is recommended. Note that if this value is changed, you should rebuild attachment thumbnails via the "Rebuild caches" system.',
  'option_explain.attendanceEmployeeGroup' => '',
  'option_explain.auction_thread_prefix_id' => '',
  'option_explain.autoEmbedMedia' => 'Enable this option to have the system attempt to automatically embed media from URLs posted by visitors that point to registered media sites. If you choose to include a link to the content as well, the media embedding may be duplicated when the message is quoted.',
  'option_explain.boardActive' => '',
  'option_explain.boardDescription' => 'Enter a description for your board. This will be placed inside the meta description tag for the "Forums default page", so avoid using HTML.',
  'option_explain.boardInactiveMessage' => 'When the board is inactive / closed, this message will be shown to site visitors. You may use HTML.',
  'option_explain.boardShortTitle' => 'The short title of your board. This should ideally be no more than 12 characters. This may be displayed when the full title is too long, such as when a user adds your app to their mobile home screen.',
  'option_explain.boardTitle' => 'The title of your board. This will be displayed at the top of pages and used in emails.',
  'option_explain.boardUrl' => 'The primary URL to your board should not include a trailing "/", a query string, hash fragment or a filename such as "index.php". The suggested URL is <b>{suggestedUrl}</b>',
  'option_explain.boardUrlCanonical' => 'If enabled, the board URL setting will be treated as the canonical installation URL. If visitors access your site through a different URL, they will be redirected to the appropriate canonical URL. When enabled, you must ensure that your board URL setting is correct. If it is not correct, areas outside the admin control panel will not be accessible!',
  'option_explain.bounceEmailAddress' => 'If an email cannot be delivered, the bounce notification will be returned to this email address. If left blank, bounced messages will be returned to the default email address. A value is required here if automated bounce handling is to be enabled. Note that this option may not work unless the return path parameter is enabled or mail is sent via SMTP using the Email Transport option.',
  'option_explain.captcha' => 'CAPTCHAs help prevent spammers from registering or posting.',
  'option_explain.categoryOwnPage' => 'When enabled, clicking on a link to a category will take you to a dedicated page that only shows the children of that category. When disabled, users will be redirected to the full forum list and scrolled to the correct category.',
  'option_explain.censorCharacter' => 'This character will be repeated for each character in a censored word that does not otherwise have a replacement specified. For example, if "dog" is censored, it may be replaced with "***".',
  'option_explain.censorWords' => 'This is a list of words or phrases that are to be censored.  If a replacement word or phrase is entered, the censored text will be rewritten to this (for example, replacing "dog" with "cat"). If no replacement is entered, the censored text will be replaced with censor characters (for example, "***").<br />
<br />
If you wish to match a wildcard at the beginning or end of the matched word, add a "*" in the correct position. For example, "dog" will not censor "dogs" but "dog*" will.',
  'option_explain.changeLogLength' => 'Content change log data will be kept for this many days. Use 0 to keep change log data indefinitely.',
  'option_explain.collectServerStats' => 'XenForo would like to collect some anonymous statistics including information about PHP, MySQL and your XenForo installation.<br />
<br />
If enabled, any data collected will be stored anonymously and will not include any user data.',
  'option_explain.contactEmailAddress' => 'Email address where board-related messages will be sent.',
  'option_explain.contactEmailSenderHeader' => 'If enabled, emails sent via the "Contact us" form will be sent with the sender\'s info in the "From" header rather than the "Reply-To" header. Enabling this may help with situations where replying to a contact message does not go to the correct address, but it may not be compatible with all SMTP servers.',
  'option_explain.contactUrl' => 'This is the URL to the page where users will be able to contact you. Please note that the overlay option will only work with XenForo URLs, so if you specify an external URL, it most likely will not work with an overlay.',
  'option_explain.conversationPopupExpiryHours' => 'The conversations popup will show all conversations with unread replies, plus any read conversations whose most recent replies fall within the time frame (in hours) before now.',
  'option_explain.convertMarkdownToBbCode' => 'If enabled, some Markdown styling will automatically be converted to BB code when saved. Markdown is a simple method for adding formatting by using common patterns such as changing *example* into italics. This can make adding formatting to messages easier, but it can sometimes cause unexpected formatting changes.',
  'option_explain.currentVersionId' => '',
  'option_explain.dbtechCreditsSmartNegate' => 'If enabled, a negation event trigger (f.ex. a post being deleted) will delete the existing transaction instead of inserting a new transaction. This does not work for all event triggers.<br />
If multiple transactions are found, it will negate the event using a new transaction instead.<br />
You can also override this feature by setting the "Negation amount" to a value other than 0.',
  'option_explain.dbtechEcommerceAddonProductTitle' => 'Add-on product licenses can display the parent product in the title, controllable with this setting.',
  'option_explain.dbtechEcommerceAddress' => '',
  'option_explain.dbtechEcommerceAddressCountry' => 'Your business\' resident country. The native name of the country will be printed on the invoice.',
  'option_explain.dbtechEcommerceAllowAnonReview' => 'If enabled, users can choose to make their reviews anonymous. Staff will still be able to see who made the review, but end users will not.',
  'option_explain.dbtechEcommerceApiLicenseFilterField' => 'If you are using the API to return licenses, you may wish to filter the licenses based on a URL entered in a license field. If so, please enter its field ID here.<br />
This field will be checked against the <code>HTTP_X_DRAGONBYTE_BOARDURL</code> header when fetching the list of licenses.',
  'option_explain.dbtechEcommerceApiLicenseFilterStrict' => 'If enabled, the value entered in the above license field must match the passed URL <b>exactly</b>. Otherwise, only the "host" part needs to match.<br />
Example: (enabled) <code>https://www.example.com</code> vs. (disabled) <code>example.com</code>',
  'option_explain.dbtechEcommerceApplyRounding' => 'This feature automatically rounds prices up to the nearest 0, 2.50, 4.95 or 9.95.<br />
<br />
<b>Note:</b> Only affects automatic calculations.',
  'option_explain.dbtechEcommerceAutomaticInvoices' => 'This system allows you to control whether invoices are automatically sent to users after they complete a transaction.',
  'option_explain.dbtechEcommerceBusinessAddress1' => 'First line of the business address.',
  'option_explain.dbtechEcommerceBusinessAddress2' => 'Second line of the business address.',
  'option_explain.dbtechEcommerceBusinessAddress3' => 'Third line of the business address.',
  'option_explain.dbtechEcommerceBusinessAddress4' => 'Fourth line of the business address.',
  'option_explain.dbtechEcommerceBusinessCo' => 'If your business address has a c/o, you can add it here.',
  'option_explain.dbtechEcommerceBusinessTaxId' => 'If your business has a tax ID such as VAT, enter it here.',
  'option_explain.dbtechEcommerceBusinessTitle' => 'The title of your business to be printed on the invoice.',
  'option_explain.dbtechEcommerceConfirmationEmail' => 'The email address DragonByte eCommerce will accept purchase confirmation requests to.<br />
<b>This should be an account that is not used for anything other than confirmation mail. Using a personal email account WILL lead to data loss!</b>',
  'option_explain.dbtechEcommerceConfirmationMailboxLogin' => '',
  'option_explain.dbtechEcommerceContentDeleteThreadAction' => 'When content is deleted, take this action with any automatically created thread.',
  'option_explain.dbtechEcommerceCoupons' => '',
  'option_explain.dbtechEcommerceCurrency' => 'This setting lets you choose the currency all prices should be displayed in.',
  'option_explain.dbtechEcommerceCustomerGroup' => 'The default "Customers" user group for new purchases. This can be overridden on a per-product basis.',
  'option_explain.dbtechEcommerceDefaultAddressCountry' => 'When a user adds a new address, this will be the default country selected.',
  'option_explain.dbtechEcommerceDefaultProductOwner' => 'This setting controls who should own products created in the AdminCP. Ideally this should be set to a "bot" user that no-one logs in to.',
  'option_explain.dbtechEcommerceDownloadsPerPage' => 'The number of downloads that will be listed per page in the "Releases" tab.',
  'option_explain.dbtechEcommerceEnableApi' => 'This setting lets you toggle the API system that lets third party sites connect to your store.<br />
<br />
An API key will be automatically generated that allows users to view products, downloads and their own licenses.',
  'option_explain.dbtechEcommerceEnableCheckoutProductPage' => 'If enabled, a "Checkout" button will appear under the Purchase button in the Pricing information if the current product is added to the cart.',
  'option_explain.dbtechEcommerceEnableConfirmationMailbox' => 'If yes, the system will start collecting and checking confirmation emails.<br />
<b>Requires the below IMAP settings filled out with an account that is not used for anything other than confirmation mail. Using a personal email account WILL lead to data loss!</b>',
  'option_explain.dbtechEcommerceEnableIncomeGraph' => 'If selected, the income graph will be added to the statistics block on the admin home page.',
  'option_explain.dbtechEcommerceEnableRate' => 'If disabled, the entire product rating system will be disabled.',
  'option_explain.dbtechEcommerceExpiredRenewDiscount' => 'This is the discount (in percent) that will be applied to purchases if the license has expired.<br />
For instance: If a license expired Feb 1st, and it is being renewed on Feb 10th, this discount will apply.',
  'option_explain.dbtechEcommerceExpiryReminder' => '',
  'option_explain.dbtechEcommerceGeneratedFilename' => 'You can set the template to be used for generated .zip files here.<br />
Available replacement variables: <code>{title}</code>, <code>{license_key}</code>',
  'option_explain.dbtechEcommerceHtmlInvoice' => 'If your system supports it, you can use a HTML template to generate invoices. To use this feature, you must have <a href="https://wkhtmltopdf.org" target="_blank">wkhtmltopdf</a> installed on your server.<br />
<br />
Enter the path to the wkhtmltopdf binary on this server. You can sometimes find this with the command <code>which wkhtmltopdf</code>.',
  'option_explain.dbtechEcommerceInvoiceActive' => 'This lets you turn off the invoicing system, preventing users from receiving or downloading invoices for their purchases.',
  'option_explain.dbtechEcommerceInvoiceIconDate' => 'You shouldn\'t change this unless you need to.',
  'option_explain.dbtechEcommerceInvoiceIconPath' => 'This should not be changed unless you are sure you need to.',
  'option_explain.dbtechEcommerceInvoiceOverrideStyle' => 'The style selected here will be used to render all invoices instead of the default style.',
  'option_explain.dbtechEcommerceListDefaultOrder' => 'When viewing the product index or category overview pages, this will be the default order for products.',
  'option_explain.dbtechEcommerceLogEntriesPerPage' => 'The number of log entries that will be listed per page across the store.',
  'option_explain.dbtechEcommerceMaxDiscount' => 'The maximum discount (in percent) that can be applied to a product.<br />
100 = No limit',
  'option_explain.dbtechEcommerceMaxMindApiKey' => 'In order to keep your geolocation database up to date, you need to <a href="https://www.maxmind.com/en/geolite2/signup" target="_blank">sign up for an account</a> and set a password, then <a href="https://www.maxmind.com/en/accounts/current/license-key" target="_blank">generate a license key</a> and fill that out here.<br />
Geolocation is used for validating a customer\'s location if they fill out a VAT ID with their billing address.',
  'option_explain.dbtechEcommerceMinimumReviewLength' => 'This setting has no effect if a review is not required and the user does not enter a review.',
  'option_explain.dbtechEcommerceOrderCleanUp' => '',
  'option_explain.dbtechEcommerceOrderDefaultOrder' => 'When viewing the account overview page, this will be the default order for orders.',
  'option_explain.dbtechEcommerceOrderReminder' => '',
  'option_explain.dbtechEcommerceOrdersPerPage' => 'The number of orders that will be listed per page in a user\'s account.',
  'option_explain.dbtechEcommercePaymentProfileIds' => 'You can choose the available payment profile(s) here.',
  'option_explain.dbtechEcommercePhysicalProductTitle' => 'Non-license products can display the product variation in the order item title, controllable with this setting.',
  'option_explain.dbtechEcommerceProductIconMaxDimensions' => 'The maximum allowed dimensions for product icon images (width x height). Use 0 or blank to use default dimensions.',
  'option_explain.dbtechEcommerceProductsPerPage' => 'The number of products that will be listed per page across the store.',
  'option_explain.dbtechEcommerceReleaseThreadTitle' => 'You can set the template to be used for new release threads here.<br />
Available replacement variables: <code>{title}</code>, <code>{category}</code>, <code>{starting_price}</code>',
  'option_explain.dbtechEcommerceRenewDiscount' => 'This is the discount (in percent) that will be applied to purchases if the license is still active.<br />
For instance: If a license expires Feb 1st, but it is being renewed on Jan 30th, this discount will apply.',
  'option_explain.dbtechEcommerceRequireAccount' => 'If disabled, guests will be asked for their email address when entering their address details instead of being asked to register or login.<br />
<br />
This setting has no effect if a digital product is in a guest\'s cart. Digital products always require an account.',
  'option_explain.dbtechEcommerceRequireDownloadToRate' => 'If selected, users may only rate a product once they have downloaded it.<br />
This setting is ignored for physical products.',
  'option_explain.dbtechEcommerceReviewRequired' => 'If enabled, users must submit a review when rating a product.',
  'option_explain.dbtechEcommerceReviewVoting' => 'This controls whether visitors can vote on whether a product review is helpful. This can allow more helpful reviews to become more visible. If enabled, you may choose to limit voting to positive responses only (upvotes). Product authors will not be able to vote on reviews for their own products.',
  'option_explain.dbtechEcommerceReviewsPerPage' => 'The number of reviews that will be listed per page across the store.',
  'option_explain.dbtechEcommerceSales' => '',
  'option_explain.dbtechEcommerceSalesTax' => '',
  'option_explain.dbtechEcommerceSeparateRefundPolicy' => 'During checkout, users will be asked to confirm they have read the Terms of Service. If your local laws require explicit consent for the refund policy regarding digital downloads, you can enable this setting.<br />
<br />
To customise the wording of the refund policy, change the phrase <code>dbtech_ecommerce_i_agree_to_refund_policy</code>',
  'option_explain.dbtechEcommerceShippingAlert' => 'When someone purchases a physical item, the email can be sent to the seller or to the forum\'s contact email address.',
  'option_explain.dbtechEcommerceShippingWeightUnit' => 'For physical items, this is the unit of measurement that will be displayed when showing the item\'s weight.',
  'option_explain.dbtechEcommerceTermsPageId' => 'If a page is selected here, the contents of this page will be displayed in a scrollable area before purchase / download.<br />
Updating this page in the AdminCP will force users to accept the Terms of Service again.',
  'option_explain.dbtech_credits_best_event' => 'If more than one event per currency applies to a situation, choose whether the highest amount or all of them will be triggered.',
  'option_explain.dbtech_credits_enable_events' => 'Master toggle for all events should you want to temporarily disable the whole system. Does not disable Displays.',
  'option_explain.dbtech_credits_eventtrigger_content_bbcode' => 'This BBCode will be used for designating charged content. Change this to something else to take over the tags from other content hiding plugins. Blank out this setting to disable the charged content functionality, which would expose such content for free.',
  'option_explain.dbtech_credits_eventtrigger_content_currency' => 'The currency that will be used for charged content transactions. Make sure corresponding events are set to use this currency as well.',
  'option_explain.dbtech_credits_eventtrigger_donate_whole' => 'To ease rounding issues, you can set a number of decimals that the target currency will be rounded up to that adjusts the source currency amounts accordingly when transferring or donating currency.<br />
Use 0 for whole number amounts.',
  'option_explain.dbtech_credits_eventtrigger_interest_interval' => 'Number of days between each interest event trigger.',
  'option_explain.dbtech_credits_eventtrigger_message_multiple' => 'If unchecked, the event will only occur once no matter how many recipients are attached to a single message.',
  'option_explain.dbtech_credits_eventtrigger_paycheck_interval' => 'Number of days between each paycheck event trigger.',
  'option_explain.dbtech_credits_eventtrigger_purchase_currency' => 'The currency you\'re selling your credits in.',
  'option_explain.dbtech_credits_eventtrigger_revival_threshold' => 'Time (in seconds) since the last reply before a thread is considered "old".',
  'option_explain.dbtech_credits_eventtrigger_taxation_interval' => 'Number of days between each taxation event trigger.',
  'option_explain.dbtech_credits_eventtrigger_taxation_user' => 'If you wish to send the taxed amount to another user, enter the user name here.',
  'option_explain.dbtech_credits_licensekey' => 'This is the license key associated with your product. Removing this will affect your ability to use the free "Product Manager" product to check for updates.',
  'option_explain.dbtech_credits_navbar' => '',
  'option_explain.dbtech_credits_size_words' => 'Choose whether it\'s the number of words or number of characters in a message that will be used for size multipliers. This only affects certain event triggers.',
  'option_explain.dbtech_credits_transactions' => 'The currency page lists the latest transactions for each user. This is the number of transactions to show before splitting to the next page.',
  'option_explain.defaultEmailAddress' => 'This is the default email address that emails will be sent from.',
  'option_explain.defaultEmailStyleId' => '',
  'option_explain.defaultLanguageId' => '',
  'option_explain.defaultStyleId' => '',
  'option_explain.disallowedCustomTitles' => 'Enter the words or phrases that are disallowed in custom user titles. All censored words are automatically disallowed. Place each word or phrase on separate lines.',
  'option_explain.discourageBlankChance' => 'You may present discouraged users with a blank page from time to time. Enter the percentage chance of this happening.',
  'option_explain.discourageDelay' => 'Discouraged users will be subjected to a page loading delay of a random period between the two values provided here.',
  'option_explain.discourageFloodMultiplier' => 'The standard minimum time between messages can be multiplied to make discouraged users wait longer between posting. Enter a multiplier here.',
  'option_explain.discourageRedirectChance' => 'Enter the percentage chance that a discouraged user will be redirected to the redirection page.',
  'option_explain.discourageRedirectUrl' => 'You may randomly redirect discouraged users to a different page. Leave this blank to redirect to the forum home page.',
  'option_explain.discourageSearchChance' => 'When discouraged users attempt to search, this option defines the percentage chance that they will find it disabled. (0 = never, 100 = always)',
  'option_explain.discussionPreview' => 'If enabled, a discussion/thread preview will appear when hovering over the title.',
  'option_explain.discussionRssContentLength' => 'The maximum number of characters of content to include in RSS feeds. Note that this includes any BB code mark up used in the message. 0 will disable content from being included in RSS feeds.',
  'option_explain.discussionsPerPage' => 'This controls the maximum number of discussions (such as threads) that will be shown on one page.',
  'option_explain.displayVisitorCount' => 'When a logged in user has unread conversations or unviewed alerts, the total count can be displayed in the user\'s browser tab before the title, or an indicator displayed on the favicon, or both.',
  'option_explain.dynamicAvatarEnable' => 'If enabled, an avatar will be dynamically created for users without a custom avatar. This will include a letter and a color based on their username. If disabled, all users without an avatar will receive a default placeholder.',
  'option_explain.editHistory' => 'If enabled, moderators will be able to see historical versions of messages and compare changes between them. Historical data will be pruned after the specified number of days. Use 0 to keep the history forever.',
  'option_explain.editLogDisplay' => 'If enabled, any edit after the delay will cause a "last edited" message to be displayed at the end of the message.',
  'option_explain.editorDropdownConfig' => 'This option can\'t be edited manually. It is edited only via the "BB code button manager" page.',
  'option_explain.editorToolbarConfig' => 'This option can\'t be edited manually. It is edited only via the "BB code button manager" page.',
  'option_explain.emailBounceHandler' => 'This option allows the "Bounced email address" account to be automatically read and processed for bounced email reports. This will detect if emails sent to a user bounce, forcing the user to update their email address and preventing the system from emailing them until this happens. This can help reduce the chance of email sent from your board from being considered spam.<br />
<br />
This option will read and remove emails from the specified account when processing. It MUST be directed to an account whose sole purpose is collecting bounce emails from this XenForo installation. A value must be entered for the "Bounced email address" option.',
  'option_explain.emailConversationIncludeMessage' => 'With this option enabled, notification emails sent to conversation recipients will contain the full text of the message about which they are being notified. With this option disabled, they will need to visit the forum to read the message.',
  'option_explain.emailDkim' => 'DomainKeys Identified Mail (DKIM) is a method of email authentication to detect forged sender addresses which are often used in phishing and email spam. DKIM allows the receiver to verify that an email claimed to have originated from a particular domain was authorised by the owner of that domain.<br />
<br />
<strong>Note:</strong> You can only enable this option if you have the <a href="https://secure.php.net/manual/en/book.openssl.php" target="_blank"><code>openssl</code></a> extension installed and enabled.',
  'option_explain.emailFileCheckWarning' => 'If enabled, and no email address is provided, the warning emails will be sent to the <a href="admin.php?options/contactEmailAddress/view">contact email address</a>.',
  'option_explain.emailSenderName' => 'If specified, emails sent by XenForo will default to being from this name. If no value is entered, the board name will be used.',
  'option_explain.emailShare' => 'If a user clicks this button the user will be prompted to create a new email using their default email client.',
  'option_explain.emailSoftBounceThreshold' => 'If automated bounce processing is enabled, this criteria will be used to determine when multiple soft bounce failures will be considered permanent and emails will no longer be sent to the user.<br />
<br />
All threshold values are limited to bounces generated in the last 30 days.',
  'option_explain.emailTransport' => '',
  'option_explain.emailUnsubscribeHandler' => 'This option allows the "Unsubscribe email address" account to be automatically read and processed for unsubscribe email requests. Requests to this address will disable the "Receive news and update emails" option for users.<br />
<br />
This option will read and remove emails from the specified account when processing. It MUST be directed to an account whose sole purpose is collecting unsubscribe request emails from this XenForo installation. A value must be entered for the "Unsubscribe email address" option.',
  'option_explain.emailWatchedThreadIncludeMessage' => 'With this option enabled, notification emails sent to users watching threads/forums will contain the full text of the message about which they are being notified. With this option disabled, they will need to visit the forum to read the message.',
  'option_explain.emojiSource' => 'If you have chosen a value above other than "native" (which will always be served from the device, if available) then you may choose from which source to serve the emoji artwork from.<br />
<br />
By default, we will always serve the graphics from the preferred CDN, though if you wanted to download the artwork and host it yourself, or use a different CDN, you can specify the path here.',
  'option_explain.emojiStyle' => 'Emojis can look vastly different depending on which device you are using. Older devices may not support emoji at all. We can replace native device emoji (or missing emoji) with the artwork sets above.<br />
<br />
<b>Note:</b> Image emojis will only be displayed in areas which support rich text input.',
  'option_explain.enableMemberList' => 'If enabled, people will be able to browse an alphabetical list of users. This can have performance implications with a large number of users.',
  'option_explain.enableNewsFeed' => 'With this option disabled, viewing the news feed will be completely disabled.',
  'option_explain.enableNotices' => 'If you don\'t use the notices system, you can completely disable it and save a query on session creation.',
  'option_explain.enablePush' => 'If enabled, a user will be able to subscribe to receive their alerts via devices which are compatible with the Push API. Users will only be able to enable push notifications if they are using a compatible device. This is supported by most major browsers except Safari on macOS and any browser on iOS.<br />
<br />
<strong>Note:</strong> You can only enable this option if you are using PHP 7.1 or above, with the <a href="https://secure.php.net/manual/en/book.gmp.php" target="_blank"><code>gmp</code></a>, <a href="https://secure.php.net/manual/en/book.mbstring.php" target="_blank"><code>mbstring</code></a> and <a href="https://secure.php.net/manual/en/book.openssl.php" target="_blank"><code>openssl</code></a> extensions enabled and have HTTPS enabled.',
  'option_explain.enableSearch' => 'With this option disabled, the search engine will not function.',
  'option_explain.enableTagging' => 'Tagging is a system that allows keywords to be applied to content to aid searching and content browsing.',
  'option_explain.enableTrophies' => 'If enabled, your users can be awarded <a href="admin.php?trophies/">trophies</a> for completing certain actions or reaching certain milestones. If disabled, the <a href="admin.php?user-title-ladder/">user title ladder</a> will no longer be able to use trophy points.',
  'option_explain.enableVerp' => 'If enabled, sent emails will include the recipient email address in the bounce/unsubscribe address field. This enables more accurate and more secure automated email handling. If using automated bounce/unsubscribe processing, enabling this option is strongly recommended.<br />
<br />
This option requires that the specified account is a catch-all account or supports a "+" as a wildcard separator (such as in Gmail). For example, if this option is enabled with a bounce address of bounce@example.com, the email might be returned to bounce+123abc+user=domain.com@example.com.',
  'option_explain.extraCaptchaKeys' => 'This option can\'t be edited manually. It is edited only via the "captcha" option.',
  'option_explain.facebookLike' => 'If this feature is enabled, a Facebook button will be displayed on various pages including the thread view page, allowing Facebook users to share it with their Facebook friends.',
  'option_explain.floodCheckLength' => 'Users will have to wait this many seconds between posting messages. Users with the permission "Can bypass flood check" will be exempt from this option.',
  'option_explain.floodCheckLengthDiscussion' => 'Users will have to wait this many seconds between posting new discussions (threads, conversations etc.). If this option is set to 0, the value for \'minimum time between messages\' will be used.',
  'option_explain.forumsDefaultPage' => 'When entering the forums section, this will be the default page users will be taken to. They will be able to access the alternative page via sub-navigation options.',
  'option_explain.fs_auction_applicable_forum' => '',
  'option_explain.fs_auction_bumping_allowed' => 'how many times user can bump there auction...!',
  'option_explain.fs_auction_dropDown_list_limit' => '',
  'option_explain.fs_auction_per_page' => 'how many auctions display on per page you can set from here..!',
  'option_explain.fs_escrow_admin_Id' => 'transfer percentage',
  'option_explain.fs_escrow_admin_percentage' => 'Admin Percentage',
  'option_explain.fs_escrow_api' => 'Central Account api key',
  'option_explain.fs_escrow_applicable_forum' => 'This forum is used for managing Escrow. DO NOT DELETE THIS FORUM.',
  'option_explain.fs_escrow_approval_que_option' => 'Allow withdrawal without admin apprvel.',
  'option_explain.fs_escrow_bit_base_url' => '',
  'option_explain.fs_escrow_callback_link' => 'Site base URL is required to generate a callback URL.<br> Example: https//:www.abc.com OR https//:www.abc.com:2526',
  'option_explain.fs_escrow_conversion_api' => 'This API is used for the conversion of USD to BTC to perform transactions in BTC\'',
  'option_explain.fs_escrow_currency' => '',
  'option_explain.fs_escrow_pgp_option' => 'Submit a request without PGP encryption.',
  'option_explain.fs_forum_gorups_per_row' => 'how many groups display on per "Row" you can set from here..!',
  'option_explain.fs_forum_groups_applicable_forum' => 'Forum Id',
  'option_explain.fs_forum_groups_approval' => 'Allow withdrawal without admin apprvel.',
  'option_explain.fs_includeLanguages' => 'select the languages which you want to show in translate language list',
  'option_explain.fs_link_group_apply_settings' => 'If a user has multiple groups tell either the time of group with high-style priority is applied or with low priority.',
  'option_explain.fs_link_html_position' => '',
  'option_explain.fs_register_Reviews_ids' => 'Enter the FORUM_IDS Comma separated like 25,26.',
  'option_explain.fs_register_alert_ids' => 'Enter the FORUM_IDS Comma separated like 25,26.',
  'option_explain.fs_register_compainion_ids' => 'Enter the FORUM_IDS Comma separated like 25,26.',
  'option_explain.fs_register_discussion_ids' => 'Enter the FORUM_IDS Comma separated like 25,26.',
  'option_explain.fs_register_hobbyiest_fields' => 'Enter the custom user FIELD_ID  <b>Comma separated like: filed1,field2.</b>',
  'option_explain.fs_register_provider_fields' => 'Enter the custom user FIELD_ID  <b>Comma separated like: filed1,field2.</b>',
  'option_explain.fs_scheduled_ban_user_timezone' => 'According to this Time Zone User will be banned.',
  'option_explain.fs_t_FlagStyle' => 'Language Switcher Flag style SVG or PNG select which you want.',
  'option_explain.fs_t_NativeLanguage' => 'If checked then Language names will be display in Native language names',
  'option_explain.fs_t_alternativeFlags' => 'If you want Alternative flags of English and Spanish Language',
  'option_explain.fs_t_autoSwitch' => 'If checked then Detect browser language and switch to that language automatically.',
  'option_explain.fs_t_defaultLanguage' => 'Default Language of the Site.',
  'option_explain.fs_t_openDirection' => 'Language Switcher open direction',
  'option_explain.fs_t_showLanguage' => 'Select the position of language selector',
  'option_explain.fs_t_widgetLook' => 'Different Widget look options available (Language Switcher Look)',
  'option_explain.fs_threadHash_cron_active' => '',
  'option_explain.fs_threadHash_runRules_hours' => '',
  'option_explain.fs_threadHash_runRules_mins' => '',
  'option_explain.fs_threadHash_type_of_day' => '',
  'option_explain.geoLocationUrl' => 'The URL specified here will be used to give information (such as a map) about a physical location. The URL must include a <strong>{location}</strong> token.',
  'option_explain.giphy' => 'If enabled, users will be able to search for GIFs while composing messages using the rich-text editor. Powered by <a href="https://giphy.com/" target="_blank">GIPHY</a>.',
  'option_explain.googleAnalyticsAnonymize' => 'If Google Analytics is enabled above and you wish to anonymize IP addresses, you can enable this option.',
  'option_explain.googleAnalyticsWebPropertyId' => 'You may enter your <a href="https://www.google.com/analytics/" target="_blank">Google Analytics</a> web property ID here to have the Analytics HTML automatically added to your public-facing pages.',
  'option_explain.gravatarEnable' => 'If enabled, your users may source their avatars from <a href="https://www.gravatar.com" target="_blank">Gravatar</a>. When a new user registers, XenForo will automatically search for a Gravatar associated with their email address. If disabled, this will not remove Gravatars from users that already have them.',
  'option_explain.guestShowSignatures' => 'In order to maximise your \'signal to noise\' ratio when displaying threads to guests, you may hide your members\' signatures.',
  'option_explain.guestTimeZone' => 'All dates and times will be displayed to guests in this time zone.',
  'option_explain.headerImageDimensions' => 'Default 1920 × 460 px',
  'option_explain.homePageUrl' => 'This is the URL to your home page, outside of the board. If this is left blank, \'Home\' will not appear in the navigation.',
  'option_explain.imageCacheRefresh' => 'If a value greater than 0 is entered, images cached by the image proxy will be refreshed after this many days have passed. This can be used in conjunction with a long cache lifetime to allow images to be updated periodically while retaining resilience against the image being removed. If a value of 0 is entered, images will only be updated when the cache entry expires.',
  'option_explain.imageCacheTTL' => 'Enter the number of days that proxied images should be retained for, before they are removed from your system. If the image is re-requested after this time, it will automatically be fetched again. Use 0 to retain the images indefinitely.',
  'option_explain.imageLibrary' => 'XenForo can make use of various different image processing libraries to produce image thumbnails etc. Select your preferred library from the list above.',
  'option_explain.imageLinkProxy' => 'By enabling these options, you may proxy and cache images and links posted in messages through your own server, allowing tracking of clicks etc. Proxying of images is especially important if you are running your site through SSL (HTTPS).',
  'option_explain.imageLinkProxyKey' => 'If you have enabled the image or link proxy, this secret key will ensure that images and links are only proxied if the requests originated at your forum. If you find that links are being accessed via third-party sites, you can change this secret key to expire these links. All links stored on the forum will be automatically updated to use the new secret key.',
  'option_explain.imageLinkProxyLogLength' => 'This option controls the length of time for which the proxy logs will be maintained after the last request made to an image or a link. If an entry is not requested for this amount of time, its data (including first request time and total accesses) will be removed. Image proxy logs will never be removed unless the image data has been removed (Image cache lifetime). Use 0 to disable pruning of the logs.',
  'option_explain.imageLinkProxyReferrer' => 'If enabled, whenever a proxied image or link is accessed, referrer information will be maintained. This can be viewed in the logs to determine where the image or link has been mentioned. Use 0 to keep the referrer data forever.',
  'option_explain.imageProxyBypass' => 'By default, all images are proxied. Alternatively, you can choose to bypass it for all HTTPS requests or allow specific domains to bypass the image proxy.<br />
<br />
<b>Note:</b> Images not requested with HTTPS will always be proxied.',
  'option_explain.imageProxyMaxSize' => 'This is the maximum file size for images that are displayed through the image proxy system. An image larger than this will return a placeholder image instead. You may use 0 to disable the limit.',
  'option_explain.includeCaptchaPrivacyPolicy' => 'Some CAPTCHA providers may provide their own privacy policy that will be appended to the end of your site\'s privacy policy. You may wish to disable this if your privacy policy already covers the use of CAPTCHA.',
  'option_explain.includeTitleInUrls' => 'With this disabled, a URL such as /threads/my-thread.128/ would exclude the title and be output as /threads/128/',
  'option_explain.indexRoute' => 'If you wish to change the default index page of the forums, you may enter the route path here. The route path is the section of the URL to a page after your main forum directory URL, such as forums/ or pages/page-name/. Do not reference a route filter here.',
  'option_explain.ipInfoUrl' => 'Specify a URL to be used for requesting more information about an IP address. The URL must include <strong>{ip}</strong>, which will be replaced with the actual IP address.',
  'option_explain.ipLogCleanUp' => 'Old IP logs are rarely useful and simply take up space. They can be pruned after a specified amount of time if desired.',
  'option_explain.jQuerySource' => 'Controls the source of the jQuery core JavaScript library. You may host this yourself (Local) or use one of the recommended CDN sources. All CDN options support HTTPS/SSL.',
  'option_explain.jobRunTrigger' => 'Long-running and scheduled tasks are deferred to the job system. By default, activity on the forum triggers these jobs to run. This can be changed to trigger independently of forum activity but additional setup is required.<br />
<br />
<strong>Note:</strong> If you select "Server based trigger" you are required to configure your server (such as with <code>crontab</code> or <code>cron.d</code>) manually to execute the following command once per minute: <code>php /path/to/xf/cmd.php xf:run-jobs</code>',
  'option_explain.jsLastUpdate' => 'The Unix time stamp of the last JS update. This can be changed to force a JS recache even if the XF version doesn\'t change.<!-- <span class="js-updateJsLastUpdate">update</span>-->

<script>
/*$(function()
{
	$(\'.js-updateJsLastUpdate\').click(function()
	{
		var d = new Date();
		$(this).closest(\'dd\').find(\'input\').val(d.getTime() / 1000|0);
	});
});*/
</script>',
  'option_explain.lastPageLinks' => 'If a discussion spans multiple pages, the last few pages are displayed on the discussion list. Set the maximum number of pages to show here. Set the number to 0 to disable this feature.',
  'option_explain.lightBoxUniversal' => 'If enabled, the lightbox overlay will show images from all messages on the current page, rather than only the current message. Note that the lightbox will only include images that do not appear in full size in the message body.
',
  'option_explain.linkShare' => 'When clicked, the current page link will be copied to the clipboard.',
  'option_explain.loginLimit' => 'If a user fails to log in 4 or more times in a 15 minute period, this method will be used to prevent brute force attacks.',
  'option_explain.logoLink' => 'If a home URL is provided, select this option to link the main page logo to that URL. If this option is not selected, the logo will always link to the Index page route.',
  'option_explain.lostPasswordCaptcha' => 'To prevent robots flooding your lost password form, you may add CAPTCHA protection to it.',
  'option_explain.lostPasswordTimeLimit' => 'To prevent flooding, you may require a delay between lost password requests. Enter a length of time in seconds that users must wait.',
  'option_explain.maxContentSpamMessages' => 'Users will only have their messages checked as spam until they have successfully posted this many messages. Use 0 to disable all spam checks.',
  'option_explain.maxContentTags' => 'This controls the maximum number of tags that can be applied to a piece of content. Use 0 to disable this limit.',
  'option_explain.maxContentTagsPerUser' => 'Beyond controlling the maximum number of tags on a piece of content, you can limit the number of tags each user may apply to prevent a single user from abusing the system. Use 0 to disable this limit.',
  'option_explain.maximumSearchResults' => 'This number reflects the maximum number of search or find new results that will be found, before permissions are taken into account. Setting this too high may cause performance problems.',
  'option_explain.membersPerPage' => 'Limit the number of members to show on each page of the registered member list, and online members list.',
  'option_explain.messageMaxImages' => 'Use 0 to allow an unlimited amount of images per message.',
  'option_explain.messageMaxLength' => 'The maximum number of characters that can be in a message. This includes BB code. Setting this value too large or disabling it entirely may cause performance issues and is not recommended.',
  'option_explain.messageMaxMedia' => 'Use 0 to disable this limit. Disabling the limit or setting it too high is not recommended, as numerous media embeds can cause browser performance problems.',
  'option_explain.messagesPerPage' => 'When there are more messages to display than this number, they will be separated into page 2, page 3 etc.',
  'option_explain.moderatorLogLength' => 'The number of days that moderator log records will be kept for. Use 0 to keep the records permanently.',
  'option_explain.multiQuote' => 'Enabling this system allows multiple messages across multiple pages to be selected and quoted in a single reply.',
  'option_explain.newsFeedMaxItems' => 'The maximum number of news feed items to fetch when a user views their news feed. Also controls how many will be cached. Higher numbers require more resources both in terms of storage and processing time.',
  'option_explain.newsFeedMessageSnippetLength' => 'When the text of messages is displayed in news feed items, it will be trimmed to the length specified here.',
  'option_explain.oEmbedCacheRefresh' => 'If a value greater than 0 is entered, oEmbed data cached by the system will be refreshed after this many days have passed. If a value of 0 is entered, oEmbed data will only be updated when the cache entry expires.',
  'option_explain.oEmbedCacheTTL' => 'Enter the number of days for which fetched oEmbed data should be retained, before they are removed from your system. If the oEmbed data is re-requested after this time, it will automatically be fetched again. Use 0 to retain oEmbed data indefinitely.',
  'option_explain.oEmbedLogLength' => 'Controls how long oEmbed logs are retained after the most recent request for the referenced oEmbed data. Logs are only removed if the oEmbed data has expired and been pruned. Set this to 0 to disable log pruning.',
  'option_explain.oEmbedRequestReferrer' => 'If enabled, whenever oEmbed data is accessed, referrer information will be maintained. This can be viewed in the logs to determine where the embedded media has been mentioned. Use 0 to keep the referrer data forever.',
  'option_explain.onlineStatusTimeout' => 'After a user interacts with the system (by clicking a link etc.) they will be considered \'online\'. They will be considered to be offline if they do not interact with the system again within the time specified here.',
  'option_explain.pinterestShare' => 'This button will let your users pin your content to any Pinterest board.',
  'option_explain.pollMaximumResponses' => 'This will limit the number of choices that can be given as responses to a poll.',
  'option_explain.preRegAction' => 'If enabled, guests will be able to write supported content but asked to register before it is submitted and publicly viewable. In most circumstances, the permissions should be inherited from the group or groups that a newly registered user would be placed into. By default, this is the "Registered" group.',
  'option_explain.preventDiscouragedRegistration' => 'You may prevent any visitors browsing from <a href="{link}">discouraged IP addresses</a> from registering new accounts. They will be informed that registration is currently disabled.',
  'option_explain.privacyPolicyForceWhitelist' => 'If you decide to <a href="admin.php?force-agreement/privacy-policy" target="_blank">Force privacy policy agreement</a> then the routes listed here will bypass being redirected to the force agreement page. The route path is the section of the URL to a page after your main forum directory URL, such as forums/ or pages/page-name/. Do not reference a route filter here.',
  'option_explain.privacyPolicyLastUpdate' => 'The Unix time stamp of the last privacy policy update.',
  'option_explain.privacyPolicyUrl' => 'This link will be shown in the footer and users will have to agree to the policy during registration.',
  'option_explain.profilePostMaxLength' => 'The maximum number of characters that can be in a profile post or comment. Setting this value too large or disabling it entirely may cause performance issues and is not recommended.',
  'option_explain.pushKeysVAPID' => 'This option can\'t be edited manually. It is edited only via the "enablePush" option.',
  'option_explain.readMarkingDataLifetime' => 'This is the number of days to maintain read marking data (such as for threads and forums). Data older than this will always be seen as read.',
  'option_explain.redditShare' => 'The Reddit share button allows your users to share your content quickly and easily directly to Reddit.',
  'option_explain.registrationCheckDnsBl' => 'Check IP addresses in DNS block lists when a new user registers to help prevent spam. If the StopForumSpam integration is not enabled, the Tornevall DNSBL will be checked.',
  'option_explain.registrationDefaults' => 'In order to keep the registration form short, many preferences are not shown. This option allows you to set the default values for newly registered users.',
  'option_explain.registrationSetup' => 'These basic options set the foundation for new registrations to your forum.',
  'option_explain.registrationTimer' => 'Use this option to set a minimum number of seconds that a registration must take before being submitted. This can help prevent spam registrations. Use 0 to disable this option.',
  'option_explain.registrationWelcome' => '',
  'option_explain.reportIntoForumId' => 'If a forum is selected here, the report center will be disabled and a thread will be posted whenever content is reported.',
  'option_explain.romanizeUrls' => 'If selected, accented and non-Latin characters in a URL will be converted to Latin equivalents if possible.',
  'option_explain.rootBreadcrumb' => 'Specify the navigation item that will serve as the \'root\' of your breadcrumb list.',
  'option_explain.saveDrafts' => 'If enabled, drafts will be periodically sent to the server and stored to allow users to resume working on their messages later. Disabling this will also disable the automatic checking for new messages when composing a reply.',
  'option_explain.searchMinWordLength' => 'This is the minimum length of a word that can be searched by the index. With the default search system, this should correspond with the MySQL full text minimum word length (normally 4).',
  'option_explain.searchResultsPerPage' => '',
  'option_explain.selectQuotable' => 'This feature enables users to quote snippets of messages by using their browser\'s text-selection tools.',
  'option_explain.sharedIpsCheckLimit' => 'When checking for other users having used the same IP addresses, this control limits the search to the last X days.',
  'option_explain.shortcodeToEmoji' => 'If enabled, common <code>:short_code:</code> will be converted to emoji and, where supported, we will display suggestions as you type. If an emoji and smilie share the same short code, the smilie will be used.',
  'option_explain.showEmojiInSmilieMenu' => 'If enabled, the smilie menu will display a categorised list of all emoji in addition to your custom smilies.',
  'option_explain.showFirstCookieNotice' => 'When a guest views a page, if enabled, the guest will see a notice stating that cookies are used by this site and continued use consents to the cookies. This notice must be accepted or it will continue to display. This option is designed to make compliance with EU cookie regulations and GDPR easier.',
  'option_explain.showMessageOnlineStatus' => 'If enabled, messages will display an icon if the author is currently online.',
  'option_explain.sitemapAutoRebuild' => 'If this option is enabled, the sitemap will be rebuilt automatically periodically. If this option is disabled, the sitemap will only be updated when it is rebuilt manually through <i>Tools &gt; Rebuild caches</i>. The current sitemap can be accessed via <a href="sitemap.php">sitemap.php</a>.',
  'option_explain.sitemapAutoSubmit' => 'Once a sitemap is built, if this option is enabled, the updated version will be automatically submitted to the search engines specified. {$url} is replaced with your sitemap URL automatically. If this option is not enabled, search engines will only know about the sitemap if it is listed in robots.txt or if you manually submit it to them.',
  'option_explain.sitemapExclude' => 'If you wish to exclude certain content types from the sitemap, that can be done here. Note that content must be guest accessible to be included in the sitemap, regardless of this setting.',
  'option_explain.sitemapExtraUrls' => 'If desired, you may include additional URLs that would not otherwise be included in the sitemap. Place each URL on separate lines. Note that these URLs must match your board URL or they will not be included. Partial URLs will be converted to absolute URLs automatically.',
  'option_explain.spamDefaultOptions' => 'These are the default options that will be checked when running the spam cleaner. The individual who actually runs the spam cleaner will have the opportunity to alter these options.',
  'option_explain.spamMessageAction' => 'This controls what happens to messages made by spammers when the spam cleaner is applied against them. Note that if content does not support removal from view, it will be permanently deleted regardless of this setting.',
  'option_explain.spamPhrases' => 'When any of these phrases are entered in a message, the action below will be taken. Enter one phrase per line. You may use a * as a wild card to match any words. If you start the line with /, the line will be treated as a regular expression (example: /test/i).',
  'option_explain.spamThreadAction' => 'This controls what happens to threads started by spammers when the spam cleaner is applied against them.',
  'option_explain.spamUserCriteria' => 'The spam cleaner will only be available to act against users who meet these criteria. If any of these criteria are set to 0 (zero) they will be ignored.',
  'option_explain.stopForumSpam' => '',
  'option_explain.tagCloud' => 'If enabled, a tag cloud showing the most popular tags will be shown on the tag search page.',
  'option_explain.tagCloudMinUses' => 'Tags will not be shown in the tag cloud unless they have been used at least this many times.',
  'option_explain.tagLength' => 'This controls the minimum and maximum length of tags. Use 0 to disable a limit. Tags may never be longer than 100 characters. These limits only apply when a tag is created. Existing tags may always be used.',
  'option_explain.tagValidation' => '',
  'option_explain.templateHistoryLength' => 'The number of days to maintain template edit history records. Use 0 to never remove history.',
  'option_explain.termsLastUpdate' => 'The Unix time stamp of the last terms and rules update.',
  'option_explain.thpostcomments_enableMultiQuote' => 'Disabling this option will disable the multi-quote functionality for comments when the multi-quote system is enabled.',
  'option_explain.thpostcomments_hide_comment_depth' => 'This option regulates how deep comments on posts can be shown on page load. Any depth below this level will initially be hidden and can only be revealed by clicking on a link. Set to 0 to hide all comments.',
  'option_explain.thpostcomments_max_comment_depth' => 'This option regulates, how deep comments on posts can be nested. For example with a nesting depth of 1, only regular posts can be commented on, with a nesting depth of 2, their comments can also be commented on, and so forth.',
  'option_explain.thpostcomments_replaceReplyButton' => 'If this option is enabled, a single reply link will appear and will be able to be used to post comments. Multi-quote will be disabled. If this option is disabled, a separate comment link will be shown alongside the reply and multi-quote links.',
  'option_explain.thumbnailImageDimensions' => 'Default 100 × 100 px',
  'option_explain.tosForceWhitelist' => 'If you decide to <a href="admin.php?force-agreement/terms" target="_blank">Force terms and rules agreement</a> then the routes listed here will bypass being redirected to the force agreement page. The route path is the section of the URL to a page after your main forum directory URL, such as forums/ or pages/page-name/. Do not reference a route filter here.',
  'option_explain.tosUrl' => 'This link will be shown in the footer and users will have to agree to the terms and rules during registration.',
  'option_explain.tumblrShare' => 'The Tumblr share button lets your users share pages to Tumblr.',
  'option_explain.tweet' => 'Enabling this button will allow your visitors to share pages easily using their Twitter account.<br />
<br />
You may also specify up to two Twitter accounts to recommend to visitors after they use the Tweet button. <a href="https://developer.twitter.com/en/docs/twitter-for-websites/web-intents/overview" target="_blank">More info...</a>',
  'option_explain.unsubscribeEmailAddress' => 'Some email clients support reading a <code>List-Unsubscribe</code> header within emails which enables them to display a prominent option to allow a user to unsubscribe from mailing lists. The mechanism for notifying you about a user\'s request to unsubscribe is an email sent to the address specified here.<br />
<br />
<b>Note:</b> Unless you enable "Automated unsubscribe email handler" below, it will be entirely your responsibility to manually check and process such emails, unless you are using a third party service that will do it for you. A value is required here if you enable the automated option.',
  'option_explain.upgradeCheckStableOnly' => 'When checking for upgrades, by default, we will only look for stable upgrades. Uncheck this box to include "Unsupported" upgrades too.',
  'option_explain.urlToPageTitle' => 'With this enabled, if a URL is used inside a message and is not given a title by the author, where possible the linked page\'s title will be fetched and used instead.<br />
<br />
Use the textbox above to specify a format. <b>{title}</b> will be replaced with the fetched page title and <b>{url}</b> will be replaced with the original URL. If no format is entered, the title itself will be displayed.',
  'option_explain.urlToRichPreview' => 'If a URL is inserted into a post it can automatically be "unfurled" to display a rich preview of the link contents, such as title, description and image.',
  'option_explain.useFriendlyUrls' => 'If you enable this option, the links generated by the system will not include "index.php?". However, to enable this, mod_rewrite must be available and an appropriate .htaccess file (or the equivalent for your web server) must be in place.',
  'option_explain.userBanners' => '',
  'option_explain.userMentionKeepAt' => 'The @ character is used to initiate user mentions. If this option is disabled, successful user mentions will remove this character.',
  'option_explain.userTitleLadderField' => 'The <a href="admin.php?user-title-ladder/">user title ladder</a> will use this field to determine how people move up the ladder.',
  'option_explain.usernameChangeRecentLimit' => 'When a username is changed, the change will be indicated on the user\'s profile and the previous username will be visible until the change is no longer "recent" based on this option. Note that moderators will be able to see full username change histories. Users will be able to see their own full username change history. Set this to 0 to disable displaying username changes publicly.',
  'option_explain.usernameChangeRequireReason' => 'If enabled, the user will be required to provide a reason when requesting a username change.',
  'option_explain.usernameChangeTimeLimit' => 'Users will need to wait this number of days between username changes. If this is set to 0 users can change their username as frequently as they like.',
  'option_explain.usernameLength' => 'This controls the minimum and maximum length of usernames. Use 0 to disable a limit. Usernames may never be longer than 50 characters.',
  'option_explain.usernameReuseTimeLimit' => 'This controls how long a user must wait before they are able to pick a username that was recently used by another user. Set this to 0 to disable this feature.',
  'option_explain.usernameValidation' => '',
  'option_explain.watchAlertActiveOnly' => 'If enabled, watched content alerts and emails will only be sent to users that have visited within the specified number of days. This can improve performance on large or very active installations.',
  'option_explain.webShare' => 'On supported devices, this button will open the browser\'s web share prompt, allowing users to share the current page to other applications.',
  'option_explain.whatsAppShare' => 'If a user clicks this button WhatsApp will open a list of users with whom to share the current page URL and title.',
  'option_explain.xaScAllowedFileExtensions' => 'List the file extensions that are allowed to be uploaded to items. Use spaces or line break between extensions.',
  'option_explain.xaScAuthorOtherItemsCount' => 'The maximum number of items to display in the "More from [author name]" sidebar block. Use 0 to disable this block.',
  'option_explain.xaScAutoFeatureCategories' => 'Select the categories from which showcase items will be featured automatically.',
  'option_explain.xaScAutoFeatureComments' => 'Items that have at least X comments.
<br>
<b>use -1 to ignore</b>',
  'option_explain.xaScAutoFeatureCreated' => 'Items that have been created within the last x days',
  'option_explain.xaScAutoFeatureExclusive' => 'If enabled, the criteria above will be exclusive (OR), rather than inclusive (AND). 
<br>
<b>Exclusive</b>: 300 views OR a reaction score of 50 OR an average rating of 4 OR 25 comments
<br>
<b>Inclusive</b>: 300 views AND a reaction score of 50 AND an average rating of 4 AND 25 comments',
  'option_explain.xaScAutoFeatureItems' => 'When enabled, showcase items will be automatically featured based on selected categories and criteria.',
  'option_explain.xaScAutoFeatureRating' => 'Items that have a rating average of at least X.  
<br>
<b>use -1 to ignore</b>',
  'option_explain.xaScAutoFeatureReactionScore' => 'Items that have a reaction score of at least X.  
<br>
<b>use -1 to ignore</b>',
  'option_explain.xaScAutoFeatureUpdated' => 'Items that have been updated within the last x days',
  'option_explain.xaScAutoFeatureUserGroups' => 'Select the user groups from which showcase authors items will be featured automatically
<br><br>
<b>Note:</b> Select (none) to disable this criteria.  If (none) is selected, even with other user groups selected, the system will consider this criteria disabled!',
  'option_explain.xaScAutoFeatureViews' => 'Items that have at least X views.
<br>
<b>use -1 to ignore</b>',
  'option_explain.xaScAutoUnfeatureItems' => 'If enabled, will automatically unfeature any featured items that are older than x days (from the date the item was featured).',
  'option_explain.xaScCategoryOtherItemsCount' => 'The maximum number of items to display in the "More in [category]" sidebar block. Use 0 to disable this block.',
  'option_explain.xaScCommentAllowedFileExtensions' => 'List the file extensions that are allowed to be uploaded to comments. Use spaces or line break between extensions.',
  'option_explain.xaScCommentAttachmentMaxFileSize' => 'Provide the maximum comment attachment file size in kilobytes (KB). Attachments over this size will be rejected.',
  'option_explain.xaScCommentMaxLength' => 'The maximum number of characters that can be in an item comment. This includes BB code. Use 0 to disable the limit.',
  'option_explain.xaScCommentsPerPage' => '',
  'option_explain.xaScDefaultFeaturedMapMarkerIconUrl' => '',
  'option_explain.xaScDefaultMapMarkerIconUrl' => '',
  'option_explain.xaScDisplayCoverImageHeader' => 'If enabled, will display the cover image (or the category image) in the header area.  This will also launch the gallery to view image attachments.',
  'option_explain.xaScDisplayCoverImageSidebar' => 'If enabled, will display the cover image in a sidebar block above the item details block.',
  'option_explain.xaScDisplayDescriptionHeader' => 'If enabled, will display the brief description in the header area.',
  'option_explain.xaScDisplayDescriptionItemDetails' => 'If enabled, will display the brief description in the item details area.',
  'option_explain.xaScDisplaySectionsOnThread' => 'When enabled, will display sections 1-6 content (rich text and custom fields) on the associated discussion thread above the messages.
<br><br>
<b>Note</b>: If you have the option to display sections as Tabs, only Section 1 will be displayed,',
  'option_explain.xaScEnableAuthorList' => 'If enabled, members will be able to browse an alphabetical list of authors. This can have performance implications with a large number of authors.',
  'option_explain.xaScExcludeFeaturedItemsFromListing' => 'When enabled, will remove duplicate items from the Showcase Index and Category pages that are already being fetched for the featured items carousel or grid block.',
  'option_explain.xaScExcludeFeaturedSeriesFromListing' => 'When enabled, will remove duplicate series from the Series Index page that are already being fetched for the featured series carousel.',
  'option_explain.xaScExpandCategoryNav' => 'When enabled, the Category navigation block will expand the entire category tree.
<br><br>
<b>Note:</b> If you wish to control expanding category navigation on a per category level, <b>do not set this option</b> as this option overrides the per category option.  Instead, use the per category option instead!',
  'option_explain.xaScFeaturedItemsDisplayType' => 'Choose the display type you want to use to display featured items on showcase index, category pages and other various pages with item listings.',
  'option_explain.xaScFeaturedItemsLimit' => 'The number of featured items you want to fetch for display in the featured items carousel on Showcase index and Showcase category pages.  Use 0 to disable the featured items carousel or grid block.  <b>Note:</b> The featured grid block is limited to displaying 3 items.',
  'option_explain.xaScFeaturedSeriesLimit' => 'The number of featured series you want to fetch for display in the featured series carousel on the Series index page.  Use 0 to disable the featured series carousel.',
  'option_explain.xaScFilesLocation' => 'Controls where the collection of uploaded file attachments will be displayed',
  'option_explain.xaScGalleryDisplayType' => 'Controls the display type (single block or multiple blocks) of the image gallery when set to "Own tab".',
  'option_explain.xaScGalleryLocation' => 'Controls where the collection of uploaded images will be displayed',
  'option_explain.xaScGoogleMapsEmbedApiKey' => 'Input your Google Maps Embed API Key here. Instructions on generating and retrieving your Google Maps Embed API key can be found <a href="https://developers.google.com/maps/documentation/embed/guide#api_key">here</a>.',
  'option_explain.xaScGoogleMapsGeocodingApiKey' => 'Input your Google Maps Geocoding API Key here. Instructions on generating and retrieving your Google Maps Geocoding API key can be found <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key">here</a>.',
  'option_explain.xaScGoogleMapsJavaScriptApiKey' => 'Input your Google Maps JavaScript API Key here. Instructions on generating and retrieving your Google Maps JavaScript API key can be found <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">here</a>.',
  'option_explain.xaScIncludeCommentsImagesInGallery' => 'This option allows you to include comments image attachments from the comments section in the image gallery. This option is only available when the \'Image gallery location\' option is set to "Own tab".',
  'option_explain.xaScIncludePostsImagesInGallery' => 'This option allows you to include posts image attachments from the associated discussion thread, in the  showcase item image gallery. This option is only available when the \'Image gallery location\' option is set to "Own tab".',
  'option_explain.xaScIncludeReviewsImages' => 'This option allows you to include reviews image attachments in the showcase item image gallery. This option is only available when the \'Image gallery location\' option is set to "Own tab".',
  'option_explain.xaScIndexFullPageMapOptions' => 'When enabled, will display a full page Google Map with map markers of items with location data.',
  'option_explain.xaScIndexMapOptions' => 'When enabled, will display a Google Map with map markers of items with location data on the Showcase Index page above or below the items listing.',
  'option_explain.xaScIndexPageType' => 'Choose the type of Home Page that you want to use for Showcase.  
<br>
<ul>
<li>Standard = Index of all items in a filterable listing.</li>
<li>Modular = Blank page with Main and Sidebar Widget Locations</li>
</ul>',
  'option_explain.xaScItemAttachmentMaxFileSize' => 'Provide the maximum item attachment file size in kilobytes (KB). Attachments over this size will be rejected.
<br><br>
<b>Important Note:</b> The option "Maximum attachments per items" has been removed and is now set via Showcase Permissions!',
  'option_explain.xaScItemDeleteThreadAction' => 'When an item is deleted, take this action with any automatically created thread associated with the item.',
  'option_explain.xaScItemListLayoutType' => 'Choose the layout type you want to use to display items on showcase index, category pages and other various pages with item listings.  <b>Note</b>:  Individual category pages can override this setting and use a different layout type.',
  'option_explain.xaScItemMaxLength' => 'The maximum number of characters that can be in an item. This includes BB code. <b>Note</b>: Use 0 to disable the limit.',
  'option_explain.xaScItemsPerPage' => 'The number of items that will be listed per page on various pages with item listings that use the list view layout type.',
  'option_explain.xaScItemsPerPageGridView' => 'The number of items that will be listed per page on various pages with item listings that use the grid view layout type.',
  'option_explain.xaScItemsPerPageItemView' => 'The number of items that will be listed per page on various pages with item listings that use the item view layout type.',
  'option_explain.xaScItemsPerPageTileView' => 'The number of items that will be listed per page on various pages with item listings that use the tile view layout type.',
  'option_explain.xaScLatestReviewsCutOffDays' => 'This is the number of days old that a review can be in order for it to be fetched.  Reviews that are older than the cutoff date will not be displayed.  Leave this option set to 0 to bypass the cut off date.',
  'option_explain.xaScLatestUpdatesCutOffDays' => 'This is the number of days old that an update can be in order for it to be fetched.  Updates that are older than the cutoff date will not be displayed.  Leave this option set to 0 to bypass the cut off date.',
  'option_explain.xaScLimitedViewItemLength' => 'Users that do not have the permission to view full items will only see this number of characters of the Section 1 message for the item.',
  'option_explain.xaScListDefaultOrder' => 'When viewing various pages with item listings, this will be the default sort order that is set.',
  'option_explain.xaScLocalizeGoogleMaps' => 'When enabled, will localize various google maps (both Embed API generated and JS API generated) throughout Showcase, based on XF language settings for the viewing user.',
  'option_explain.xaScLocationDisplayType' => 'Controls how the location data will be displayed when viewing an item',
  'option_explain.xaScMarkerClustering' => 'When enabled, the MarkerClustererPlus library used in combination with the Maps JavaScript API, will combine map markers of close proximity to one another into clusters to simplify the display of markers on the map at various zoom levels.',
  'option_explain.xaScMetaDescription' => 'Enter a meta description for your Showcase Home Page. This will be placed inside the meta description tag on the SC Index page, so avoid using HTML.',
  'option_explain.xaScMinimumReviewLength' => 'If a review is entered, it will not be accepted unless it is at least this long. If a review is not required, this setting will be ignored if no review is entered.',
  'option_explain.xaScMoreFromAuthorLayoutType' => 'When the "Display location" above is set to "Below item", you can choose the layout type you want to use to display items in the "More from [author]" block.',
  'option_explain.xaScMoreFromAuthorLocation' => 'Controls where the "More from [author name]" block will be displayed',
  'option_explain.xaScMoreInCategoryLayoutType' => 'When the "Display location" above is set to "Below item", you can choose the layout type you want to use to display items in the "More in [category name]" block.',
  'option_explain.xaScMoreInCategoryLocation' => 'Controls where the More in [category name] block will be displayed',
  'option_explain.xaScOverrideStyle' => 'The style selected here will be displayed on all showcase pages instead of the default style.',
  'option_explain.xaScRecentReviewsCount' => 'The maximum number of recent reviews to display on the main item view page. 
<br />
<b>Note</b>: Use 0 to disable this block.',
  'option_explain.xaScRecentUpdatesCount' => 'The maximum number of recent item updates to display on the main item overview page. 
<br />
<b>Note</b>: Use 0 to disable this block.',
  'option_explain.xaScRequireReviewTitle' => 'If enabled, a review title must be included when posting new reviews or editing existing reviews.',
  'option_explain.xaScReviewAllowedFileExtensions' => 'List the file extensions that are allowed to be uploaded to reviews. Use spaces or line break between extensions.',
  'option_explain.xaScReviewAttachmentMaxFileSize' => 'Provide the maximum review attachment file size in kilobytes (KB). Attachments over this size will be rejected.',
  'option_explain.xaScReviewMaxLength' => 'The maximum number of characters that can be in an item review. This includes BB code. Use 0 to disable the limit.',
  'option_explain.xaScReviewReplyMaxLength' => 'The maximum number of characters that can be in a review reply. Use 0 to disable the limit.',
  'option_explain.xaScReviewsPerPage' => '',
  'option_explain.xaScSectionsDisplayType' => 'Choose how you want sections 2-6 to display on an item.
<br>
<ul>
<li>Stacked = Sections 2-6 will be displayed on the overview page with Section 1 stacked from top to bottom in order</li>
<li>Tabbed = Sections 2-6 will be displayed in their own individual tab (its own page)</li>
</ul>',
  'option_explain.xaScSeriesDetailsMaxLength' => 'The maximum number of characters that can be in the series details. This includes BB code. <b>Note</b>: Use 0 to disable the limit.',
  'option_explain.xaScSeriesItemsPerPage' => 'The number of items that will be listed per page on a series.',
  'option_explain.xaScSeriesMinTags' => '',
  'option_explain.xaScSeriesPerPage' => 'The number of series that will be listed per page on series index and other various pages with series listings.',
  'option_explain.xaScShowPointsOfInterest' => 'When enabled, will include points of interest on Category maps (this is default standard with google maps).  Disable this option if you want to "clean up" the map from looking cluttered.
<br>
<b>Note</b>:  This does not apply to individual item maps.',
  'option_explain.xaScShowRatingFilters' => 'If enabled, the ave rating filter will be enabled on item listing pages',
  'option_explain.xaScShowTitleFilters' => 'If enabled, the title filter will be enabled on item listing pages',
  'option_explain.xaScShowcaseSearchFormTabOrder' => 'Allows you to set the display order of the [Search showcase] tab.',
  'option_explain.xaScSignatureOnComments' => 'When enabled, signatures will display on comments just like they do on posts.',
  'option_explain.xaScSnippetLengthFC' => 'The number of characters of the item preview to display on the featured items carousel.',
  'option_explain.xaScSnippetLengthGV' => 'The number of characters of the item preview to display on the grid view layout type.',
  'option_explain.xaScSnippetLengthIV' => 'The number of characters of the item preview to display on the item view layout type.',
  'option_explain.xaScSnippetLengthLV' => 'The number of characters of the item preview to display on the list view layout type.',
  'option_explain.xaScUnreadCounter' => 'If enabled, the count of unread items will display above the "Showcase" navigation tab.',
  'option_explain.xaScUpdateMaxLength' => 'The maximum number of characters that can be in an item update. This includes BB code. Use 0 to disable the limit.',
  'option_explain.xaScUpdateReplyMaxLength' => 'The maximum number of characters that can be in an item update reply. Use 0 to disable the limit.',
  'option_explain.xaScUpdatesPerPage' => '',
  'option_explain.xaScViewFullItem' => 'When enabled, will add "View full item" links to various navigation elements on a multi page item, allowing the viewing user to view the full item on a single page.',
  'option_explain.xfrmAllowAnonReview' => 'If enabled, users can choose to make their reviews anonymous. Staff will still be able to see who made the review, but end users will not.',
  'option_explain.xfrmAllowIcons' => 'If enabled, resource authors will have the option of specifying a small (96x96) icon. This icon will be displayed in resource lists and when viewing a resource.',
  'option_explain.xfrmAuthorOtherResourcesCount' => 'The maximum number of other resources to display from a resource\'s author. Use 0 to disable this block.',
  'option_explain.xfrmEnableTeams' => 'If enabled, resource owners will be able to add up to 10 other users as team members for each resource. Team members will be able to take actions against a resource as if it were their own, with the exception of deletions which may only be performed by the owner.',
  'option_explain.xfrmFilelessViewFull' => 'If selected, users that cannot download resources will only see this number of characters from the descriptions of resources without a file.',
  'option_explain.xfrmListDefaultOrder' => 'When viewing the resource index or category overview pages, this will be the default order for resources.',
  'option_explain.xfrmMinimumReviewLength' => 'If a review is entered, it will not be accepted unless it is at least this long. If a review is not required, this setting will be ignored if no review is entered.',
  'option_explain.xfrmPaidResourceThreadTitleTemplate' => 'Normally, when threads are automatically created for resources, the thread title is just the resource\'s name. This option lets you specify a format for paid Resources. Use {title} where the resource title should appear.',
  'option_explain.xfrmRecentReviewsCount' => 'The maximum number of recent reviews to display on the main resource view page. Use 0 to disable this block.',
  'option_explain.xfrmRecentUpdatesCount' => 'The maximum number of recent updates to display on the main resource view page. Use 0 to disable this block.',
  'option_explain.xfrmRequireDownloadToRate' => 'If selected, users may only rate a resource once they have downloaded it.',
  'option_explain.xfrmResourceCurrencies' => 'Controls the currencies that users can select to apply to their resources. Add one currency per line. Each currency can only be 3 characters (USD, GBP, etc). A symbol will be used to display the currency if possible.',
  'option_explain.xfrmResourceDeleteThreadAction' => 'When a resource is deleted, take this action with any automatically created thread.',
  'option_explain.xfrmResourceExtensions' => 'List the file extensions that are allowed to be uploaded. Use spaces or line break between extensions.',
  'option_explain.xfrmResourceMaxFileSize' => 'Provide the maximum resource file size in kilobytes (KB). Resources over this size will be rejected.',
  'option_explain.xfrmResourceMaxFiles' => 'This is the maximum number of files that can be attached per resource version.',
  'option_explain.xfrmResourcesPerPage' => 'The number of resources that will be listed per page, including on the resource and category overview pages.',
  'option_explain.xfrmReviewRequired' => 'If enabled, users must submit a review when rating a resource.',
  'option_explain.xfrmReviewVoting' => 'This controls whether visitors can vote on whether a resource review is helpful. This can allow more helpful reviews to become more visible. If enabled, you may choose to limit voting to positive responses only (upvotes). Resource authors will not be able to vote on reviews for their own resources.',
  'option_explain.xfrmReviewsPerPage' => '',
  'option_explain.xfrmUpdateMaxLength' => 'The maximum number of characters that can be in a resource update or description. This includes BB code. Setting this value too large or disabling it entirely may cause performance issues and is not recommended.',
  'option_explain.xfrmUpdatesPerPage' => '',
);