<?php
/**
 * @package Maakapay_Checkout_For_Woocommerce
 * @version 1.2.0
 */

class Maakapay_For_Woocommerce_Checkout extends WC_Payment_Gateway
{
	public function __construct() {
		$this->id 					= "maakapay_for_woocommerce_checkout";
		$this->icon 				= apply_filters('woocommerce_offline_icon', plugin_dir_url( __DIR__ ) . 'assets/images/maakapay-logo.png');
		$this->has_fields 			= true;
		$this->method_title 		= "Maakapay";
		$this->method_description 	= "Take payments using multiple payment gateway options from Woocommerce Using Maakapay Plugin";
		$this->description 			= $this->get_option( 'description' );
		$this->supports             = array( 'products' );
		$this->init_form_fields();
		$this->init_settings();
		$this->title 				= $this->get_option( 'title' );
		$this->enabled              = $this->get_option( 'enabled' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	// Initialise Gateway Settings Form Fields
	public function init_form_fields() {
	     $this->form_fields = array(
	     	'enabled' => array(
		        'title' 	=> __( 'Enable/Disable', 'woocommerce' ),
		        'type' 		=> 'checkbox',
		        'label' 	=> __( 'Enable Maakapay For Checkout', 'woocommerce' ),
		        'default'   => 'yes'
		    ),
		     'title' => array(
		          'title' 		=> __( 'Title', 'woocommerce' ),
		          'type' 		=> 'text',
		          'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
		          'default' 	=> __( 'Maakapay', 'woocommerce' )
		    ),
		     'description' 		=> array(
		          'title' 		=> __( 'Description', 'woocommerce' ),
		          'type' 		=> 'textarea',
		          'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
		          'default' 	=> __("Maakapay is a unified payment gateway supporting various systems. For Nabil and Nic Asia transactions, 3D Secure must be enabled to ensure security.", 'woocommerce')
		    ),
		    'testmode' => array(
				'title'       => 'Test mode',
				'label'       => 'Enable Test Mode',
				'type'        => 'checkbox',
				'description' => 'Place the payment gateway in test mode using test API key provided by Maakapay.',
				'default'     => 'yes',
				'desc_tip'    =>  true,
			),
			'test_api_key' => array(
				'title'       => 'Test API Key',
				'type'        => 'text',
				'description' => 'Place The Payment Gateway Test API Key provided by Maakapay.',
				'desc_tip'    =>  true,
				'default'     => get_option('maakapay_test'),
			),
			'test_private_key' => array(
				'title'       => 'Test Private Key',
				'type'        => 'password',
				'description' => 'Place The Payment Gateway Test Private Key provided by Maakapay.',
				'desc_tip'    =>  true,
			),
			'live_api_key' => array(
				'title'       => 'Live API Key',
				'type'        => 'text',
				'description' => 'Place The Payment Gateway Live API Key provided by Maakapay.',
				'desc_tip'    =>  true,
				'default'     => get_option('maakapay_live'),
			),
			'live_private_key' => array(
				'title'       => 'Live Private Key',
				'type'        => 'password',
				'description' => 'Place The Payment Gateway Live Private Key provided by Maakapay.',
				'desc_tip'    =>  true,
			),
			'enable_nabil' => array(
		        'title' 	=> __( 'Nabil EPG', 'woocommerce' ),
		        'type' 		=> 'checkbox',
		        'label' 	=> __( 'Enable Nabil EPG in Maakapay For Checkout', 'woocommerce' ),
		        'default'   => 'no'
		    ),
			'enable_cips' => array(
		        'title' 	=> __( 'ConnectIPS', 'woocommerce' ),
		        'type' 		=> 'checkbox',
		        'label' 	=> __( 'Enable ConnectIPS in Maakapay For Checkout', 'woocommerce' ),
		        'default'   => 'no'
		    ),
			'enable_nic' => array(
		        'title' 	=> __( 'Nic Cybersource', 'woocommerce' ),
		        'type' 		=> 'checkbox',
		        'label' 	=> __( 'Enable Nic Cybersource in Maakapay For Checkout', 'woocommerce' ),
		        'default'   => 'no'
		    ),
			// 'enable_quick_nic' => array(
		    //     'title' 	=> __( 'Nic Quickpay', 'woocommerce' ),
		    //     'type' 		=> 'checkbox',
		    //     'label' 	=> __( 'Enable Nic Quickpay in Maakapay For Checkout', 'woocommerce' ),
		    //     'default'   => 'no'
		    // ),
			// 'enable_qr' => array(
		    //     'title' 	=> __( 'Connect IPS Dyanmic Qr', 'woocommerce' ),
		    //     'type' 		=> 'checkbox',
		    //     'label' 	=> __( 'Enable ConnectIPS Dynamic QR in Maakapay For Checkout', 'woocommerce' ),
		    //     'default'   => 'no'
		    // ),
			// 'enable_ws_qr' => array(
		    //     'title' 	=> __( 'Connect IPS Dynmic Qr With Web Socket', 'woocommerce' ),
		    //     'type' 		=> 'checkbox',
		    //     'label' 	=> __( 'Enable ConnectIPS Dyamic QR with Realtime update in Maakapay For Checkout using Web Socket', 'woocommerce' ),
		    //     'default'   => 'no'
		    // ),
			'enable_khalti' => array(
		        'title' 	=> __( 'Khalti', 'woocommerce' ),
		        'type' 		=> 'checkbox',
		        'label' 	=> __( 'Enable Khalti in Maakapay For Checkout', 'woocommerce' ),
		        'default'   => 'no'
		    ),
			'enable_log' => array(
		        'title' 	=> __( 'Logs', 'woocommerce' ),
		        'type' 		=> 'checkbox',
		        'label' 	=> __( 'Enable Debug log', 'woocommerce' ),
		        'default'   => 'no',
				'description' => 'Enabling debug log can help to generate the detailed and crusal log of your system to identify any issues',
		    ),
	    );
	}

	public function payment_fields(){

		global $wpdb;
		
	    if ( $this->description )
            if( $this->get_option( 'testmode' ) == 'yes' ) {
                $this->description .= ' <br><strong>TEST MODE IS ENABLED</strong>';
                $this->description  = trim( $this->description );
            }
        echo wpautop( wptexturize( $this->description ) );

		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form">';
 
	// Add this action hook if you want your custom payment gateway to support it
	do_action( 'woocommerce_credit_card_form_start', $this->id );

	$paymentOptions = "";

	if( get_woocommerce_currency() === "NPR" ) {
		if( $this->get_option( 'enable_cips' ) == "yes" ) {
			$paymentOptions .= '<div>
				<input type="radio" id="CIPS" name="agent" value="CIPS" />
				<label for="CIPS" style="font-size: 15px; font-weight: bold">ConnectIPS</label>
			</div>';
		   }
	}
	if( $this->get_option( 'enable_nic' ) == "yes" ) {
		 $paymentOptions .= '
		 <div>
			<input type="radio" id="NIC" name="agent" value="NIC" />
			<label for="NIC" style="font-size: 15px; font-weight: bold">Card payment by NIC Cybersource</label>
		</div>';
	}
	// if( $this->get_option( 'enable_quick_nic' ) == "yes" ) {
	// 	$paymentOptions .= '<div>
	// 		<input type="radio" id="NICQUICKPAY" name="agent" value="NICQUICKPAY" />
	// 		<label for="NICQUICKPAY">NIC Asia QuickPay</label>
	// 	</div>';
	// 	   }
	if( $this->get_option( 'enable_nabil' ) == "yes" ) {
		$paymentOptions .= '<div>
			<input type="radio" id="NABIL" name="agent" value="NABIL" checked />
			<label for="NABIL" style="font-size: 15px; font-weight: bold">Card payment by Nabil EPG</label>
		</div>';
   	}

	if( get_woocommerce_currency() === "NPR" ) {
	   if( $this->get_option( 'enable_khalti' ) == "yes" ) {
			$paymentOptions .= '<div>
				<input type="radio" id="KHALTI" name="agent" value="KHALTI" />
				<label for="KHALTI" style="font-size: 15px; font-weight: bold">KHALTI</label>
			</div>';
   		}
	}
	echo '
		<fieldset>
		  <legend>Select Payment Method:</legend>
		  ' . $paymentOptions . '
		</fieldset>';
 
	do_action( 'woocommerce_credit_card_form_end', $this->id );
 
	echo '<div class="clear"></div></fieldset>';
     }

	public function process_payment( $order_id ) {

	    global $woocommerce;

	    $order = new WC_Order( $order_id );

	    $order->update_status('pending-payment', __( 'Awaiting payment', 'woocommerce' ));

  	 	$response_url = $this->mpfwc_send_request_to_bank( $order );

	    return array(
				'result' 	=> 'success',
				'redirect'	=> $response_url
			);

	}

	public function mpfwc_send_request_to_bank( $order = null ) {

        global $wpdb;

        $exists = false;

        if ( !function_exists( 'wp_generate_uuid4' ) ) {

            require_once ABSPATH . WPINC . '/functions.php';
            $exists = true;

        }

        if(!is_null($order)) {

            $app_mode = ($this->get_option( 'testmode' ) == "no") ? "live" : "test";

		    $name = sanitize_text_field( $order->get_billing_first_name() ). ' ' . sanitize_text_field( $order->get_billing_last_name() );
		    $invoice_id = $order->id;
            $amount = $order->get_total();
            $email = $order->get_billing_email();
            $phone = $order->get_billing_phone();
            $client_ip = $this->user_ip_address();
            $currency = $order->get_currency();
			$agent = sanitize_text_field( $_POST['agent'] );

			if( $agent == "CIPS" ) {
				$transaction_code = strtoupper( $this->get_hex_slice( random_bytes( 32 ) ) );	
			} else {
				$transaction_code = ( $exists ) ? strtoupper( wp_generate_uuid4() ) : strtoupper( md5( time() ) );
			}

            $table_name = $wpdb->prefix . "maakapay_transactions_logs";
            $data = array('name' => $name, 'invoice_number' => $invoice_id, 'transaction_code' => $transaction_code, 'amount' => $amount, 'email'=> $email, 'contact_number' => $phone, 'status' => 'pending', 'app_mode' => $app_mode, 'currency' => $currency, 'client_ip' => $client_ip, 'order_created_at' => date("Y-m-d H:i:s"), 'agent' => $agent);
            $format = array('%s','%d');

			if( $this->get_option( 'enable_log') == "yes" ) {
				wc_get_logger()->debug( 'Request Data: ' . json_encode( $data ), array( 'source' => 'Maakapay Logs' ) );
			}

            $wpdb->insert( $table_name, $data, $format );

			$api_url = null;
    		$token = null;

			if( $this->get_option( 'testmode' ) == "no" ){

                $token = $this->get_option( 'live_api_key' );
                $api_url = 'https://api.maakapay.com/v2/request/payment/' . $agent;

            }else{

                $token = $this->get_option( 'test_api_key' );
                $api_url = 'https://devapi.maakapay.com/v2/request/payment/' . $agent;

            }

			if( $this->get_option( 'enable_log') == "yes" ) {
				wc_get_logger()->debug('Resolving Agent: ' . $agent, array( 'source' => 'Maakapay Logs' ) );
			}



			$params = [
				'fname'            => sanitize_text_field( $order->get_billing_first_name() ),
				'lname'            => sanitize_text_field( $order->get_billing_last_name() ),
				'email'            => $email,
				'phone'            => $phone,
				'address1'         => sanitize_text_field( $order->get_shipping_address_1() ),
				'city'             => sanitize_text_field( $order->get_shipping_city() ),
				'country'          => sanitize_text_field( $order->get_shipping_country() ),
				'postCode'         => sanitize_text_field( $order->get_shipping_postcode() ),
				'state'            => sanitize_text_field( $order->get_shipping_state() ),
		        'currency'         => $currency,
		        'amount'           => $amount,
                'tnxCode'          => $transaction_code,
		        'approvedUrl'      => home_url( '/maakapay-success/' ),
                'canceledUrl'      => home_url( '/maakapay-cancel/' ),
                'declinedUrl'      => home_url( '/maakapay-decline/' ),
		        'merchantKey'      => $token,
				'orderNumber'	   => $invoice_id
		    ];

		    $response = wp_safe_remote_post( esc_url_raw( $api_url ), array(

                'body'    => $params,
                'timeout' => 60,

            ));

			if( $this->get_option( 'enable_log') == "yes" ) {
				wc_get_logger()->debug( 'Response from Server: ' . json_encode( $response ), array( 'source' => 'Maakapay Logs' ) );
			}


            if ( is_wp_error( $response ) ) {

                $error_message = $response->get_error_message();
                $data = [
                    "data" => [
                        "message" => "Something went wrong: $error_message",
                        "status" => 400
                    ]
                ];

				if( $this->get_option( 'enable_log') == "yes" ) {
					wc_get_logger()->error( 'Error Response: ' . $error_message, array( 'source' => 'Maakapay Logs' ) );
				}

                return json_encode( $data );

            } else {

                $body = trim( wp_remote_retrieve_body( $response ) );
				if( $this->get_option( 'enable_log') == "yes" ) {
					wc_get_logger()->debug( json_encode( json_decode($body, true) ), array( 'source' => 'Maakapay Logs' ) );
				}

				$body = json_decode($body);
                return $body->data->url;

            }
        }
	}

    /**
     * Get User Ip address for security purpose.
     *
     * @since    1.0.0
     */
    public function user_ip_address() {

        if( !empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
            $ip = sanitize_text_field( $_SERVER[ 'HTTP_CLIENT_IP' ] );
        } elseif( ! empty( $_SERVER[ 'HTTP_X_FORWARD_FOR' ] ) ) {
            $ip = sanitize_text_field( $_SERVER[ 'HTTP_X_FORWARD_FOR' ] );
        } else {
            $ip = sanitize_text_field( $_SERVER[ 'REMOTE_ADDR' ] );
        }

        return sanitize_text_field( $ip );
    }


	/**
	 * Generate Hex value for using in ConnectIPS uuid.
	 * 
	 * @since 2.0.0
	 */
	function get_hex_slice( $binary_data ) {
		// Convert binary data to hexadecimal string
		$hex_string = bin2hex($binary_data);
		
		// Get the first 15 characters
		$hex_slice = substr($hex_string, 0, 15);
		
		return $hex_slice;
	}

}
