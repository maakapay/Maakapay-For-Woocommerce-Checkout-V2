=== Maakapay Checkout For Woocommerce ===
Contributors: maakapay, ashwinrana
Donate link: https://maakapay.com/employee/ashwin
Tags: nabil, nabil bank, maakapay, nabil bank woocommerce
Requires at least: 5.6
Tested up to: 6.5
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrate Nabil Bank Payment Gateway to WooCommerce for directly checkout and start accepting the card Debit/Credit.

== Description ==
This plugin will help you to have the plug and play feature for Nabil Bank EPG( Electronic Payment Gateway ). By using this plugin you don't have to
hassle to create your system based on the bank provided document. You can utilize this plugin and start doing the transaction with having to waste time
for setting up the project and assigning the development team. This plugin support multiple currencies, but you have to contact to the bank before you add the currencies,
in the Maakapay Invoice Payer.

== Installation ==

First download the plugin from the WordPress plugin store. The visit (Maakapay Payment Service)[https://maakapay.com] from there they will provide you the
sandbox and live API key based on your request. Then you have to enter the given URL/wp-admin/admin.php?page=wc-settings&tab=checkout&section=maakapay_for_woocommerce_checkout page. Then after testing is completed
you can start to accept the online transactions in your website.

== If downloaded zip manually ==

1. Upload `maakapay-checkout-for-woocommerce.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

OR
1. GOTO Plugins > Add new  > Upload plugin and upload the maakapay-checkout-for-woocommerce.zip
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 1.2 =
* Fix timeout issue while creating request

= 1.1 =
* Check for Maakapay Services available and hide the extra menu if exists

= 1.0 =
* Create the new plugin
* Add the transaction feature and log viewing feature
* Add the checkout form
