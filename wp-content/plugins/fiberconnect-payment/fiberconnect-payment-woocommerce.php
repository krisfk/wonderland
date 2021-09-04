<?php
/**
 * Plugin Name:			FiberConnect Payment (轉數快, 支付寶, 微信支付)
 * Plugin URI:			https://fiberapi.com/en/woocommerce-payment-plugin-2/
 * Description:			Accept QR Code payments on your online store via FiberConnect.
 * Version:				1.0.7
 * Requires at least:	5.5 or above
 * Requires PHP:		7.0 or above
 * Author:				FiberConnect
 * Author URI:			https://fiberapi.com/en/
 * License:				FiberAPI Technologies LimitedFiberAPI Technologies Limited
 * License URI:			https://fiberapi.com/en/woocommerce-payment-plugin-2/
 * Text Domain:			fiberconnect-payment-woocommerce
 * Domain Path:			/languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FIBER_PLUGIN_VERSION', '1.0.7' );
register_activation_hook( __FILE__, 'fiberconnect_activate_plugin' );
register_uninstall_hook( __FILE__, 'fiberconnect_uninstall_plugin' );

/**
 * Process when activate plugin.
 */
function fiberconnect_activate_plugin() {
	//	Add or update plugin version to database, if necessary.
	$fiberconnect_plugin_version = get_option( 'fiberconnect_plugin_version' );
	if ( ! $fiberconnect_plugin_version ) {
		add_option( 'fiberconnect_plugin_version', FIBER_PLUGIN_VERSION );
	} else {
		update_option( 'fiberconnect_plugin_version', PAYSLEY_PLUGIN_VERSION );
	}
}

/**
 * Process when delete plugin.
 */
function fiberconnect_uninstall_plugin() {
	delete_option( 'fiberconnect_plugin_version' );
	delete_option( 'woocommerce_fiberconnect_settings' );
}

/**
 * WooCommerce fallback notice.
 *
 * @since 4.1.2
 */
function woocommerce_fiberconnect_missing_wc_notice() {
	/* translators: 1. URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'FiberConnect Payment Plugin requires WooCommerce to be installed and active. You can download %s here.', 'fiberconnect-payment-woocommerce' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

add_action( 'plugins_loaded', 'woocommerce_fiberconnect_gateway_init' );

function woocommerce_fiberconnect_gateway_init() {
	// load_plugin_textdomain( 'woocommerce-gateway-stripe', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'woocommerce_fiberconnect_missing_wc_notice' );
		return;
	}

	woocommerce_fiberconnect_gateway();
}

function woocommerce_fiberconnect_gateway() {
    static $plugin;

    if ( ! isset( $plugin ) ) {
        class WCFiberConnectGateway {
            /**
			 * The *Singleton* instance of this class
			 *
			 * @var Singleton
			 */
			private static $instance;

			/**
			 * Returns the *Singleton* instance of this class.
			 *
			 * @return Singleton The *Singleton* instance.
			 */
			public static function get_instance() {
				if ( null === self::$instance ) {
					self::$instance = new self();
				}
				return self::$instance;
			}

			/**
			 * Setting layout related.
			 * 
			 * @var WCFiberConnectLayout
			 */
			private $layout;

			/**
			 * Managing API request service.
			 *
			 * @var WCFiberConnectRequestService
			 */
			private $api;

			/**
			 * Managing webhook.
			 *
			 * @var WCFiberConnectWebhookHandler
			 */
			private $webhook;

			/**
			 * Private clone method to prevent cloning of the instance of the
			 * *Singleton* instance.
			 *
			 * @return void
			 */
			public function __clone() {}

			/**
			 * Private unserialize method to prevent unserializing of the *Singleton*
			 * instance.
			 *
			 * @return void
			 */
			public function __wakeup() {}

			/**
			 * Protected constructor to prevent creating a new instance of the
			 * *Singleton* via the `new` operator from outside of this class.
			 */
			public function __construct() {
				$this->init();

				$this->layout  = new WCFiberConnectLayout();
				$this->api     = new WCFiberConnectRequestService();
				$this->webhook = new WCFiberConnectWebhookHandler( $this->api );
			}

            /**
			 * Init the plugin after plugins_loaded so environment variables are set.
			 */
			public function init() {
				//	Include all files from `includes` directory.
				require_once dirname( __FILE__ ) . '/includes/class-wc-fiberconnect-layout.php';
				require_once dirname( __FILE__ ) . '/includes/class-wc-fiberconnect-request-service.php';
				require_once dirname( __FILE__ ) . '/includes/class-wc-fiberconnect-webhook-handler.php';

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
			}

			/**
			 * Add plugin action links.
			 */
			public function plugin_action_links( $links ) {
				include 'includes/params.php';

				$plugin_links = [
					'<a href="admin.php?page=wc-settings&tab=checkout&section=' . $config->plugin_title . '">' . esc_html__( 'Settings', 'fiberconnect-payment-woocommerce' ) . '</a>',
				];
				return array_merge( $plugin_links, $links );
			}
        }

		$plugin = WCFiberConnectGateway::get_instance();
    }

    return $plugin;
}
