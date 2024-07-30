<?php

class Maakapay_For_Woocommerce_Chcekout_Admin {
/**
     * Register the menu for the admin area.
     *
     * @since    1.0.0
     */
    public static function maakapay_menu()
    {

        add_menu_page('Maakapay Transaction list', 'Maakapay', 'manage_options', 'maakapay-dashboard', __CLASS__ .'::maakapay_dashboard', 'dashicons-analytics' );
        add_submenu_page('maakapay-dashboard', "Maakapay Invoice Payer Dashboard", "Dashboard", "manage_options", "maakapay-dashboard", __CLASS__ .'::maakapay_dashboard' );
    }

    /**
     * Admin submenu callback.
     *
     * @since    1.0.0
     */
    public static function maakapay_dashboard()
    {

        ob_start(); //start buffer.

        $action = isset($_GET['action']) ? trim( sanitize_text_field( $_GET['action'] ) ) : "";

        if ($action == "maakapay-view") {

            $transaction_code = isset( $_GET['transaction_code'] ) ? trim( sanitize_text_field( $_GET['transaction_code'] ) ) : "";

            require_once MAAKAPAY_FOR_WOOCOMMERCE_CHECKOUT_PLUGIN_PATH . 'admin/partials/maakapay-for-woocommerce-checkout-admin-transaction-detail.php';  // include template.

        } else {

            require_once MAAKAPAY_FOR_WOOCOMMERCE_CHECKOUT_PLUGIN_PATH . 'admin/partials/maakapay-for-woocommerce-checkout-admin-display.php';  // include template.

        }


        $template = ob_get_contents(); // load content.

        ob_end_clean(); //closing and cleaning buffer.

        echo $template;

    }


}
