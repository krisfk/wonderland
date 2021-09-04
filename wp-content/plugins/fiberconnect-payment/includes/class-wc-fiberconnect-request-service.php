<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCFiberConnectRequestService' ) ) {
	/**
	 * FiberConnect Request Service class.
	 * Controls redirection handling.
	 */
	class WCFiberConnectRequestService extends WC_Payment_Gateway {

		public static $has_init = false;

		/**
		 * Variables used for params.
		 */
		private $config;
		private $str;

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {

			//	Load config and str variables from params.php.
			include 'params.php';
			$this->config	= $config;
			$this->str		= $str;

			$this->id                 = $this->config->plugin_title;
			$this->has_fields         = false;
			$this->method_title       = __( 'FiberConnect Payment', $this->config->project_title );
			$this->method_description = __( 'With FiberConnect Payment Plugin, you can collect payment in HKD via various payment methods and view all payment status auto updated in your FiberConnect account. Using other plugins may not be compatible with this plugin. <a href="https://fiberapi.com/en/contact-2/">Contact us</a> for any enquiries!', $this->config->project_title );

			//	Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			//	Define user set variables.
			$this->title        = $this->get_option( $this->str->title );
			$this->description  = $this->get_option( $this->str->description );

			//	Define actions and filters.
			//	We only want to init for once.
			if (!self::$has_init) {
				//	Show our plugin in admin options tab.
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

				//	Fields validation.
				add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'validate_admin_options' ) );

				//	Customise the message on thank you page.
				add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'order_received_text' ), 10, 2 );

				self::$has_init = true;
			}
		}

		/**
		 * Customising the order received message on confirmation page.
		 */
		public function order_received_text() {
			return esc_html__( 'Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you.', $this->config->project_title );
		}

		/**
		 * This function will validate the API Key input, and only save it if it is valid.
		 */
		public function validate_api_key_field( $key, $value ) {

			//	Throw different error message according to previous API key value.
			if ( $this->verify_api_key( $value ) ) {
				return $value;
			} else {
				return $this->get_option( $this->str->api_key );
			}

		}

		/**
		 * Initialize Gateway Settings Form Fields.
		 */
		public function init_form_fields() {

			$this->form_fields = apply_filters( 'wc_fiberconnect_gateway_settings', array(

				'customisation' => array(
					$this->str->title	=>	__( 'Customisation', $this->config->project_title ),
					'type'				=>	$this->str->title
				),

				'enabled'		=>	array(
					$this->str->title	=>	__( 'Enable/Disable', $this->config->project_title ),
					'type'				=>	'checkbox',
					'label'				=>	'Enable FiberConnect Payment',
					$this->str->default	=>	true
				),

				$this->str->title	=>	array(
					$this->str->title		=>	__( 'Title', $this->config->project_title ),
					'type'					=>	'text',
					$this->str->description	=>	__( 'This controls the title which the user sees during checkout.', $this->config->project_title ),
					$this->str->default		=>	'FiberConnect Payment (轉數快, 支付寶, 微信支付)',
					$this->str->desc_tip	=>	false,
				),

				$this->str->description	=>	array(
					$this->str->title		=>	__( 'Description', $this->config->project_title ),
					'type'					=>	'textarea',
					$this->str->description	=>	__( 'This controls the description which the user sees during checkout.', $this->config->project_title ),
					$this->str->default		=>	"Paying on desktop?\n\nScan the QR code image using your digital wallet / mobile banking app.\n\nPaying on mobile?\n\nFPS - Take a screenshot of the QR code on your phone and update to your digital wallet / mobile banking app.\nAlipay - Select \"Alipay\" as payment method and you will be directed to Alipay to complete payment.\nWeChat Pay - Take a screenshot of the QR code on your phone and upload to your WeChat Pay.",
					$this->str->desc_tip	=>	false,
					'css'					=>	'height: 150px;',
				),

				'credentials'	=>	array(
					$this->str->title		=>	__( 'API Credentials', $this->config->project_title ),
					'type'					=>	$this->str->title,
					$this->str->description	=>	__( 'Make sure you already have a FiberConnect account and you will get the API credentials. If you have any questions, please feel free to <a href="https://fiberapi.com/en/contact-2/">contact us</a>.', 'woocommerce-gateway-fiber' ),
					$this->str->desc_tip	=>	false,
				),

				$this->str->api_key	=>	array(
					$this->str->title		=>	__( 'API Key', $this->config->project_title ),
					'type'					=>	'password',
					$this->str->default		=>	''
				)
			) );

		}

		/**
		 * Validate if user is entering a valid API key.
		 */
		public function validate_admin_options() {
			$post_data	= $this->get_post_data();
			$api_key	= $post_data[ 'woocommerce_' . $this->config->plugin_title . '_' . $this->str->api_key ];
			$is_valid	= $this->verify_api_key( $api_key );

			if ( !$is_valid ) {

				$this->log( 'Attempt with invalid API Key - ' . $api_key );

				if ( $this->get_option( $this->str->api_key ) !== '' ) {
					//	Previously have correct API key -
					WC_Admin_Settings::add_error( __( 'API Key is invalid - API key will not be saved.', $this->config->project_title ) );
				} else {
					//	No API key provided previously -
					WC_Admin_Settings::add_error( __( 'API Key is invalid - Error will be prompted when processing checkout.', $this->config->project_title ) );
				}

			}

		}

		/**
		 * Writes a log message.
		 */
		public function log( $message, $level = 'info' ) {
			if ( $this->config->debug_logging ) {
				if ( empty( $this->logger ) ) {
					$this->logger = new WC_Logger();
				}
				$this->logger->add( $this->config->project_title . '-' . $level, $message );
			}
		}

		/**
		 * Get payment link path from env.php file.
		 */
		private function payment_link() {
			include 'env.php';
			return $api_url;
		}

		/**
		 * Uses to verify whether the API key is valid.
		 * Returns true / false - indicating if key is valid or not.
		 */
		private function verify_api_key( $key ) {

			$request_options = array(
				'http' => array(
					$this->str->ignore	=>	true,
					$this->str->header	=>	$this->str->x_oah_key . $key . "\r\nContent-Type: application/json",
					$this->str->method	=>	'GET'
				)
			);

			$request_url = $this->payment_link() . '/v1/payment-requests/a';

			//	And perform the query.
			$context = stream_context_create($request_options);
			$txn_response_body = file_get_contents($request_url, false, $context);

			//	Get the response code.
			preg_match('{HTTP\/\S*\s(\d{3})}', $http_response_header[0], $header_match);

			return $header_match[1] !== '401';

		}

		/**
		 * Uses to verify whether the API key is valid.
		 * Returns true / false - indicating if key is valid or not.
		 */
		private function get_activated_payment_methods() {

			//	Define a helper function for filtering not available payment methods.
			function filter_activated_methods( $item ) {
				return $item['status'] == 'activated';
			};

			//	Define a helper function for reducing an available payment method from object to simple form.
			function map_reduce_methods( $item ) {
				return $item['id'];
			};

			$request_options = array(
				'http' => array(
					$this->str->ignore	=>	true,
					$this->str->header	=>	$this->str->x_oah_key . $this->get_option( $this->str->api_key ) . "\r\nContent-Type: application/json",
					$this->str->method	=>	'GET'
				)
			);

			$request_url = $this->payment_link() . '/v1/payment-methods';

			//	And perform the query.
			$context = stream_context_create($request_options);
			$txn_response_body = file_get_contents($request_url, false, $context);

			//	Extract response data's payment methods.
			$txn_response_data = json_decode($txn_response_body, true);
			$payment_methods = $txn_response_data['payment_methods'];

			//	Convert into a simple array.
			$activated_payment_methods = array_filter( $payment_methods, 'filter_activated_methods' );
			$reduced_payment_methods = array_map( 'map_reduce_methods', $activated_payment_methods );
			$result_payment_methods = array_values( $reduced_payment_methods );

			return $result_payment_methods;

		}

		/**
		 * Build payment request body.
		 */
		private function build_payment_request( $order_id ) {

			$order = wc_get_order( $order_id );

			//	Define a helper function for converting line item.
			function item_to_line( $item ) {
				$product = wc_get_product( $item->get_product_id() );

				return array(
					"description"	=>	$product->get_short_description(),
					"name"			=>	$product->get_name(),
					"reference"		=>	$product->get_slug(),
					"quantity"		=>	$item->get_quantity(),
					"price"			=>	$product->get_price()
				);
			};

			//	Build request body.
			$request_body = array(
				"reference"	=>	'#' . strval($order_id) . ' ' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
				"payment_method_types"	=>	$this->get_activated_payment_methods(),
				"amount"	=>	array(
					"currency"		=>	$order->get_currency(),
					"value"			=>	floatval($order->get_total())
				),
				"line_items"	=>	array_map( 'item_to_line', array_values( $order->get_items() ) ),
				"payment_method_options"	=>	array(
					$this->str->gateway_url	=>	array(
						"success_callback_url"	=>	$this->get_return_url( wc_get_order($order_id) ),
						"fail_callback_url"		=>	wc_get_checkout_url()
					)
				),
				"email"		=>	$order->get_billing_email(),
				"metadata"	=>	array(
					"usage_type"	=>	"woo_commerce"
				)
			);

			//	Remove email if it is undefined.
			if ( !$_POST['billing_email'] ) {
				unset($request_body['email']);
			}

			return $request_body;

		}


		/**
		 * Get gateway_url by order_id. Also writes txn reference to order as necessary.
		 */
		private function get_gateway_url( $order_id ) {

			//	Build request body with helper function.
			$request_body = $this->build_payment_request( $order_id );

			$request_options = array(
				'http' => array(
					$this->str->ignore	=>	true,
					$this->str->header	=>	$this->str->x_oah_key . $this->get_option( $this->str->api_key ) . "\r\nContent-Type: application/json",
					$this->str->method	=>	'POST',
					'content'			=>	json_encode($request_body)
				)
			);

			$request_url = $this->payment_link() . '/v1/payment-requests';

			//	And perform the query.
			$context = stream_context_create($request_options);
			$txn_response_body = file_get_contents($request_url, false, $context);

			$txn_response_data = json_decode($txn_response_body, true);
			$txn = $txn_response_data['id'];

			//	Error handling - we will first check if the data is valid.
			//	If endpoint call has failed to generate a payment link, $txn should not be defined.
			if ( !$txn ) {

				if ( isset($txn_response_data['error_details']) && isset($txn_response_data['error_details']['body'][0]['message']) ) {
					if ( $txn_response_data['error_details']['body'][0]['message'] == '"amount.currency" must be [HKD]' ) {
						//	Case 1 - Invalid currency error.
						$this->log( 'Error occured - Currency amount must be HKD.' );
						throw new Exception( __( 'Currency amount must be HKD.', $this->config->project_title ), 1 );
					} else {
						//	Case 2 - Endpoint params generic error.
						$this->log( 'Error occured - ' . $txn_response_data['error_description'] );
						throw new Exception( $txn_response_data['error_description'], 1 );
					}
				} else if ( isset($txn_response_data['message']) ) {
					//	Case 3 - Bad authentication details.
					$this->log( 'Error occured - ' . $txn_response_data['message'] );
					throw new Exception( $txn_response_data['message'] );
				} else {
					//	Case 2 - Endpoint params generic error.
					$this->log( 'Error occured - ' . $txn_response_data['error_description'] );
					throw new Exception( $txn_response_data['error_description'], 1 );
				}

				return null;
			}

			$gw_url = $txn_response_data[$this->str->gateway_url];

			//	We will update the txn field according to assigned txn.
			update_post_meta( $order_id, 'txn', $txn );

			//	Also store gw_url.
			update_post_meta( $order_id, $this->str->gateway_url, $gw_url );

			//	May consider to remove this line later. This is only for debugging purpose.
			update_post_meta( $order_id, 'return_url', $this->get_return_url( wc_get_order($order_id) ) );

			return $gw_url;

		}

		/**
		 * Get the status of a payment, according to txn reference.
		 * If payment status changed, also update it.
		 * 
		 * We are performing a few steps here:
		 * 	1.	From the given txn reference code, get $order.
		 * 	2.	From the response body, extract "event_type" and map to corresponding WC status value:
		 * 		-	'payment_request.created'		=>	'pending'
		 * 		-	'payment_request.paid'			=>	'completed'
		 * 		-	'payment_request.charge_failed'	=>	'failed'
		 * 		-	'payment_request.cancelled'		=>	'cancelled'	[TBC]
		 * 	3.	Update payment status as necessary. Return if we successfully updated status.
		 */
		public function update_payment_status( $txn, $status ) {

			//	1.	Get order by txn.
			$orders = wc_get_orders( array(
				'status'		=>	$this->str->pending,
				'meta_key'		=>	'txn',
				'meta_value'	=>	$txn
			));

			//	There should be an exact match.
			if (sizeof($orders) === 0) {
				return false;
			}

			//	We can get $order now.
			$order = wc_get_order($orders[0]->id);

			//	2.	Update status of payment.
			$status_text = '';
			switch ($status) {
				case 'payment_request.created':
					$status_text = $this->str->pending;
					break;

				case 'payment_request.paid':
					$status_text = 'processing';
					break;

				case 'payment_request.charge_failed':
					$status_text = 'failed';
					break;

				case 'payment_request.cancelled':
					$status_text = 'cancelled';
					break;

				default:
					break;
			}

			if ($status_text !== '') {
				$order->update_status( $status_text );
			}

			//	3.	Write a log message.
			$this->log( 'Successfully updated payment status of order #' . $orders[0]->id . ' to ' . $status_text);

			//	4.	Finally, update if we have successfully changed the status.
			return true;

		}

		/**
		 * Process the payment and return the result.
		 */
		public function process_payment( $order_id ) {

			$order = wc_get_order( $order_id );

			//	Mark as on-hold (we're awaiting the payment)
			$order->update_status( $this->str->pending, __( 'Awaiting payment', $this->config->project_title ) );

			//	Reduce stock levels
			$order->reduce_order_stock();

			$gw_url = $this->get_gateway_url( $order_id );

			//	Write a log message.
			$this->log( 'Successfully created a payment link. Gateway URL - ' . $gw_url );

			//	Return thankyou redirect
			return array(
				'result' 	=> 'success',
				'redirect'	=> $gw_url
			);

		}
	}
}