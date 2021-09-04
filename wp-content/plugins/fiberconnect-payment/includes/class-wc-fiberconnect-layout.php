<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WCFiberConnectLayout' ) ) {
    /**
     * FiberConnect Layout class.
     * Controls layout on checkout page
     */
    class WCFiberConnectLayout {

        /**
         * Constructor
         */
        public function __construct() {

            add_filter( 'woocommerce_payment_gateways', [ $this, 'wc_fiberconnect_add_to_gateways' ]);

        }

        /**
         * Adding this to the list of payment methods under Checkout page.
         */
        public function wc_fiberconnect_add_to_gateways( $gateways ) {

            $gateways[] = 'WCFiberConnectRequestService';
            return $gateways;

        }

    }
}