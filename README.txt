=== Maakapay Checkout For Woocommerce V2 ===
Contributors: maakapay, ashwinrana
Tags: Nabil EPG, Connect IPS, Khalti, NIC Cybersource, Maakapay
Requires at least: 5.6
Requires PHP: 7.0
Tested up to: 6.6
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Maakapay: One Platform, Endless Payment Options.

== Description ==
Elevate your business with Maakapay, the ultimate unified payment gateway. Effortlessly integrate with top systems like ConnectIPS, Khalti, Nic Asia Cybersource, and Nabil EPG to start accepting payments instantly. 
Streamline your transactions and enhance your financial efficiency with Maakapay’s cutting-edge, professional solution.

Please note that [WooCommerce](https://wordpress.org/plugins/woocommerce/) must be installed and active.

== Installation ==
* Download the Plugin: Go to the WordPress plugin store and download the Maakapay plugin.

* Get API Keys: Visit [Maakapay](https://maakapay.com/) . They’ll provide you with a sandbox and live API key and secret key based on your request.

* Configure the Plugin: Log in to your WordPress admin panel and go to wp-admin/admin.php?page=wc-settings&tab=checkout&section=maakapay_for_woocommerce_checkout to enter your API keys.

* Start Accepting Payments: After testing the setup, you can begin accepting online transactions on your website.

== If downloaded zip manually ==
1. Upload `maakapay-checkout-for-woocommerce.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

OR

1. GOTO Plugins > Add new  > Upload plugin and upload the maakapay-checkout-for-woocommerce.zip
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Which currencies does Maakapay accepts payment from? =
Maakapay support all the currencies as per your merchant bank.

ConncetIPS, Khalti only accept Nepali Rupee, but NIC and Nabil support multiple currencies.

= Can I contribute to this plugin? =
Join in on our [GitHub repository](https://github.com/maakapay/Maakapay-For-Woocommerce-Checkout-V2)


== Maakapay Team ==
+977 9822791936 / [merchant@maakapay.com](merchant@maakapay.com)

== Support ==
Mobile(Viber/Whatsapp) : 9822791936/9845688436
Email : [merchantcare@maakapay.com](merchantcare@maakapay.com)

== Upgrade Notice ==
All the updates are properly testing. But please make sure you have your database backup before upgrading.

== Changelog ==
= 2.0 =
* Add new multiple payment channel (ConnectIPS, Nabil EPG, Khalti, Nic Cybersource)
* Debug Log generator
* Add Secret key for transaction validation

= 1.2 =
* Fix timeout issue while creating request

= 1.1 =
* Check for Maakapay Services available and hide the extra menu if exists

= 1.0 =
* Create the new plugin
* Add the transaction feature and log viewing feature
* Add the checkout form
