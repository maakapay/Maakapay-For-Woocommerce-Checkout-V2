<?php

/**
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://maakapay.com
 * @since      1.0.0
 *
 * @package    Maakapay_Checkout_For_Woocommerce
 * @subpackage Maakapay_Checkout_For_Woocommerce/public
 */


class Maakapay_For_Woocommerce_Checkout_Payment_Page_Template_Handler {

    /**
     * Handel the Payment response from the server and update in the local database.
     *
     * @since    1.0.0
     */
    public static function mpfwc_update_the_status_of_transaction($transaction_code, $hash_value, $transaction_status ) {
        global $wpdb, $woocommerce;

        $maakapay_settings = get_option('woocommerce_maakapay_for_woocommerce_checkout_settings');

        $merchant_key = null;
        
        if( $maakapay_settings['testmode'] == "no" ) {

            $merchant_key = $maakapay_settings[ 'live_private_key' ];

        } else {

            $merchant_key = $maakapay_settings[ 'test_private_key' ];

        }

        $hash_hmac = hash_hmac( 'SHA256', $transaction_code ,  $merchant_key, false );
        $value =  strtoupper( $hash_hmac );
        $expected_value = urlencode( $value );

        if( $maakapay_settings[ 'enable_log' ] == "yes" ) {
            wc_get_logger()->debug( 'Transaction Code: ' . $transaction_code , array( 'source' => 'Maakapay Logs' ) );
        }

        if( hash_equals( $expected_value, $hash_value ) ) {

            if( $maakapay_settings[ 'enable_log' ] == "yes" ) {
                wc_get_logger()->debug( 'Hash and Validation Matched success' , array( 'source' => 'Maakapay Logs' ) );
            }

            $table_name = "{$wpdb->prefix}maakapay_transactions_logs";

            $result = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE transaction_code = '{$transaction_code}' LIMIT 1" );

            (isset($result[0])) ? $result = $result[0] : null;

            if( $maakapay_settings[ 'enable_log' ] == "yes" ) {
                wc_get_logger()->debug( 'DB Record: ' . json_encode( $result ) , array( 'source' => 'Maakapay Logs' ) );
            }

            if( ! is_null($result) && $result->status == 'pending' ) {

                $data = [ "status" => $transaction_status, "order_updated_at" =>  date("Y-m-d H:i:s") ];
                $where = [ "transaction_code" => $transaction_code ];
                $updated = $wpdb->update( $table_name, $data, $where );

                if( $updated == false ) {

                    echo "Unable to update the database. Please try again";
                    wp_die();

                }

                $order = wc_get_order ( $result->invoice_number );

                if( $maakapay_settings[ 'enable_log' ] == "yes" ) {
                    wc_get_logger()->debug('Order ID: ' . $result->invoice_number . ' Status: ' . $transaction_status , array( 'source' => 'Maakapay Logs' ) );
                }

                switch ( $transaction_status ) {
                    case "success":
                        $message = "Invoice has been paid, Transaction code: {$transaction_code} and Amount Paid: {$result->amount}";
                        $order->payment_complete();
                        $woocommerce->cart->empty_cart();
                        $url = $order->get_checkout_order_received_url();
                        break;
                    case "cancel":
                        $message = "Transaction has been canceled by the user. Transaction code: {$transaction_code}";
                        break;
                    case "decline":
                        $message = "Card has been declined by the bank. Transaction code: {$transaction_code}";
                        break;
                    default:
                        $message = "Unknown status please check Maakapay dashboard for Transaction code: {$transaction_code}";
                }

                $order->add_order_note( $message, ( $transaction_status == "success" ) ? true : false );
                if( $transaction_status == "success" ) {
                    wp_safe_redirect( $url );
                }
            }

        } else {
            if( $maakapay_settings[ 'enable_log' ] == "yes" ) {
                wc_get_logger()->debug( 'Hash and Validation Matched failed. Hash: ' .  $hash_value, array( 'source' => 'Maakapay Logs' ) );
            }
            // Return 404 page 
        }
    }

}
