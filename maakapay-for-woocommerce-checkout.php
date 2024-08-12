<?php

/**
 * @package Maakapay_Checkout_For_Woocommerce
 * @author    Maakapay
 * @category  Admin
 * @copyright Copyright (c) 2015-2016, Maakapay
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @version   2.0.0
 */

/*
 * Plugin Name: Maakapay Checkout For Woocommerce
 * Plugin URI:https://maakapay.com/plugins/maakapay-checkout-for-woocommerce-v2
 * Description:  Maakapay: One Gateway, Endless Payment Possibilities.
 * Author: Maakapay
 * Author URI: https://maakapay.com
 * Version: 2.0.0
 * Requires Plugins: woocommerce
 * 
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Make sure WooCommerce is Installed And Active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    die('Woocommerce not found. Please install woocommerce before installing this plugin');
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('MAAKAPAY_FOR_WOOCOMMERCE_CHECKOUT', '2.0.0');
define('MAAKAPAY_FOR_WOOCOMMERCE_CHECKOUT_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-maakapay-for-woocommerce-checkout-activator.php
 */
function activate_maakapay_for_woocommerce_checkout()
{
    require_once plugin_dir_path(__FILE__) . '/includes/class-maakapay-for-woocommerce-checkout-activator.php';
    Maakapay_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-maakapay-for-woocommerce-checkout-deactivator.php
 */
function deactivate_maakapay_for_woocommerce_checkout()
{
    require_once plugin_dir_path(__FILE__) . '/includes/class-maakapay-for-woocommerce-checkout-deactivator.php';
    Maakapay_For_Woocommerce_Checkout_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_maakapay_for_woocommerce_checkout');
register_deactivation_hook(__FILE__, 'deactivate_maakapay_for_woocommerce_checkout');

// Add the gateway to WC Available Gateways
function add_maakapay_for_woocommerce_checkout_class($methods)
{
    $methods[] = 'Maakapay_For_Woocommerce_Checkout';
    return $methods;
}


add_action('plugins_loaded', 'init_maakapay_for_woocommerce_checkout_class');
add_action('plugins_loaded', 'mpfwc_check_for_checkout_page');

// Plugin load and Ask for Payment Class
function init_maakapay_for_woocommerce_checkout_class()
{
    require_once dirname(__FILE__) . '/includes/class-maakapay-for-woocommerce-checkout-payment-gateway.php';
}

add_filter('woocommerce_payment_gateways', 'add_maakapay_for_woocommerce_checkout_class');

/**
 * The code that runs during plugin template redirects.
 * This action is documented in public/class-maakapay-for-woocommerce-checkout-public.php
 */

function mpfwc_check_for_checkout_page()
{
	$url = home_url(sanitize_text_field($_SERVER['REQUEST_URI']));

    $cancel = strpos( $url, 'maakapay-cancel');

    $success = strpos( $url, 'maakapay-success');

    $decline = strpos( $url, 'maakapay-decline');

    if ( $cancel ||  $decline || $success ) {
        add_filter('page_template', 'mpfwc_payment_page_template_handler');
    }
}

function mpfwc_payment_page_template_handler()
{

    require_once dirname(__FILE__) . '/public/class-maakapay-for-woocommerce-checkout-public.php';

    global $post;

    $transaction_code = null;
    $hash_value = null;

    if (isset($_GET['transaction_code']) && !empty ( $_GET['transaction_code']) ) {

        $params = explode("?", sanitize_text_field($_GET['transaction_code']));

        if (isset($params[0]) && isset($params[1])) {

            $transaction_code = sanitize_text_field($params[0]);
            $hash_value = sanitize_text_field(str_replace("validator=", "", $params[1]));

        }

    }

    if ($post->post_name == "maakapay-success") {

        wp_enqueue_style('maakapay-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), '1.0.0', 'all');

        if (isset($transaction_code) && !empty($transaction_code && isset($hash_value) && !empty($hash_value))) {

            Maakapay_For_Woocommerce_Checkout_Payment_Page_Template_Handler::mpfwc_update_the_status_of_transaction($transaction_code, $hash_value, "success");

        }

        $page_template = MAAKAPAY_FOR_WOOCOMMERCE_CHECKOUT_PLUGIN_PATH . "public/partials/maakapay-for-woocommerce-checkout-public-payment-success.php";

    }

    if ($post->post_name == "maakapay-cancel") {

        wp_enqueue_style('maakapay-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), '1.0.0', 'all');

        if (isset($transaction_code) && !empty($transaction_code && isset($hash_value) && !empty($hash_value))) {

            Maakapay_For_Woocommerce_Checkout_Payment_Page_Template_Handler::mpfwc_update_the_status_of_transaction($transaction_code, $hash_value, "cancel");

        }

        $page_template = MAAKAPAY_FOR_WOOCOMMERCE_CHECKOUT_PLUGIN_PATH . "public/partials//maakapay-for-woocommerce-checkout-public-payment-cancel.php";

    }

    if ($post->post_name == "maakapay-decline") {

        wp_enqueue_style('maakapay-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), '1.0.0', 'all');

        if (isset($transaction_code) && !empty($transaction_code && isset($hash_value) && !empty($hash_value))) {

            Maakapay_For_Woocommerce_Checkout_Payment_Page_Template_Handler::mpfwc_update_the_status_of_transaction($transaction_code, $hash_value, "decline");

        }

        $page_template = MAAKAPAY_FOR_WOOCOMMERCE_CHECKOUT_PLUGIN_PATH . "public/partials//maakapay-for-woocommerce-checkout-public-payment-decline.php";

    }

    return $page_template;
}

add_action('admin_menu', 'mpfwc_load_admin_menu');

function mpfwc_load_admin_menu()
{
    require_once dirname(__FILE__) . '/admin/class-maakapay-for-woocommerce-checkout-admin.php';

    // Check if Maakapay Other plugin exists
    if( ! in_array( 'maakapay-invoice-payer/maakapay-wordpress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && ! in_array( 'maakapay-invoice-payer-for-woocommerce/maakapay-invoice-payer-for-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        Maakapay_For_Woocommerce_Chcekout_Admin::maakapay_menu();
    }
}
