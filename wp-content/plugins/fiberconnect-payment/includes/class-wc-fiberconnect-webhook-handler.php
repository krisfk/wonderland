<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCFiberConnectWebhookHandler' ) ) {
	/**
	 * FiberConnect Webhook Handler class.
	 * Controls redirection handling.
	 */
	class WCFiberConnectWebhookHandler {

		/**
		 * Managing API request service.
		 *
		 * @var WCFiberConnectRequestService
		 */
		private $api;

		/**
		 * Constructor for the gateway.
		 */
		public function __construct( $request_service ) {

			//	Assign $request_service manager.
			$this->api = $request_service;

			//	Initialize the webhook.
			//	/?wc-api=wc_fiberconnect
			add_action( 'woocommerce_api_wc_fiberconnect', [ $this, 'fiberconnect_callback_handler' ] );

		}

		public function fiberconnect_callback_handler() {

			$json = file_get_contents('php://input'); 
			$obj = json_decode($json);

			//	Get txn reference.
			$txn = $obj->event_body->id;

			//	And new status.
			$status = $obj->event_type;

			//	From txn reference - update payment status.
			$update_status = $this->api->update_payment_status( $txn, $status );

			//	Return 200 / 500 according to update status.
			if ( $update_status ) {
				return http_response_code(200);
			} else {
				return http_response_code(500);
			}
		}

	} // end class
}