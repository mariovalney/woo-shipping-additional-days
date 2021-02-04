=== Shipping Additional Days for WooCommerce ===
Contributors: mariovalney
Donate link: https://mariovalney.com
Tags: shipping, delivery, woocommerce, woo commerce, ecommerce, e-commerce, shop, correios
Requires at least: 4.4
Tested up to: 5.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to set additional days to your delivery date into Products and Shipping Classes.

== Description ==

Shipping Additional Days for WooCommerce allows you to set additional days to your estimated delivery date into Products and Shipping Classes.

= SELL FOR FUTURE =

With WCSAD you can sell "on demand" products:

* If you need some extra time to receive a stock
* If you just can send products into weekends
* If you spend one week to make your product
* If your provider takes 2 or 3 days to send you the product

= SUPPORTED SHIPPING PLUGINS =

For all plugin we'll use the max value: additional days of all shipping classes and/or additional days of all products.

* [WooCommerce Local Pickup](https://docs.woocommerce.com/document/local-pickup/)

We will show the additional day as estimated delivery time. If no additional day is used, we'll show a message informing the product will be available after payment.

* [WooCommerce Correios 3.8](https://wordpress.org/plugins/woocommerce-correios/)

WooCommerce Correios has it's own additional days and estimated delivery time implementation. We'll add the max value to its delivery time: our additional days or its own implementation.

= COMPATIBILITY =

Tested up to WooCommerce version 4.8

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/woocommerce-shipping-additional-days` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. That's all. No configuration needed: you already can see new fields into products shipping options and shipping classes list

== Frequently Asked Questions ==

= Did you renamed the plugin? =

Yes. Due to [this](https://make.wordpress.org/plugins/2019/08/08/trademark-enforcement/).

= My stock will be managed? =

No. This plugin just work into delivery days estimate: everything else is up to WooCommerce and your shipping plugin.

= I can save Additional Days but delivery estimate hasn't changed. =

Make sure you have a supported shipping plugin activate and in use.

= Which plugins are supported? =

Please take a look in the plugin's description.

= My shipping plugin is not supported. Can you support it? =

If your shipping plugin is here into WordPress repository: yepp!
Please, open a support ticket with plugin's name and link.

If you are the developer, let's take a coffe :)

= I want to change the Local Pickup estimated delivery text. =

You can use the `wcsad_pickup_estimated_delivery_text` filter.

= I'm developer, can I help with code? =

Of course! You are welcomed to join us in [GitHub](https://github.com/mariovalney/woo-shipping-additional-days).

== Screenshots ==

1. Product configuration
2. Shipping Classes configuration

== Changelog ==

= 1.0.0 =

* You can add Additional Days to every product or to all products into a Shipping Class
* Support to "WooCommerce Correios" plugin

= 1.0.1 =

* Support to translations

= 1.1.0 =

* Fixed configurations on products
* Tested with new version of WC and WooCommerce Correios

= 1.1.1 =

* Fixed bug when no products have Shipping Classes
* Tested with new version of WordPress, WooCommerce and WooCommerce Correios

= 1.2.0 =

* Fixed saving Shipping Classes as 0
* Tested with new version of WordPress, WooCommerce and WooCommerce Correios

= 1.3.0 =

* Added support to Local Pickup shipping method.
* Bug fixes and tested with new version of WordPress, WooCommerce and WooCommerce Correios.

== Upgrade Notice ==

= 1.3 =
Bug fixes and added support to Local Pickup shipping method.
