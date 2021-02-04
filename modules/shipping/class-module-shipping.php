<?php
/**
 * WCSAD_Module_Shipping
 * Make changes to calc of delivery days
 *
 * @package         WC_Shipping_Additional_Days
 * @subpackage      WCSAD_Module_Shipping
 * @since           1.0.0
 *
 */

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WCSAD_Module_Shipping' ) ) {
    class WCSAD_Module_Shipping {

        /**
         * The Core object
         *
         * @since    1.0.0
         * @var      WC_Shipping_Additional_Days    $core   The core class
         */
        private $core;

        /**
         * The Module Indentify
         *
         * @since    1.0.0
         */
        const MODULE_SLUG = "shipping";

        /**
         * Define the core functionalities into plugin.
         *
         * @since    1.0.0
         * @param    WC_Shipping_Additional_Days      $core   The Core object
         */
        public function __construct( WC_Shipping_Additional_Days $core ) {
            $this->core = $core;
        }

        /**
         * Register filters to delivery days of all supported plugins
         *
         * @since    1.0.0
         * @access   private
         */
        private function support_shipping_plugins() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-shipping-plugin.php';
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-correios.php';
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-local-pickup.php';

            new WCSAD_Woocommerce_Correios( $this->core );
            new WCSAD_Woocommerce_Local_Pickup( $this->core );
        }

        /**
         * Check if woocommerce-correios is active
         *
         * @since    1.0.0
         * @access   private
         */
        private function check_supported_plugins() {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            $supported_plugins = array(
                'woocommerce-correios/woocommerce-correios.php',
            );

            foreach ( $supported_plugins as $plugin ) {
                if ( is_plugin_active( $plugin ) ) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Show the dependence admin note
         *
         * @since    1.0.0
         * @access   private
         */
        private function show_supported_plugins_error() {
            $this->core->add_action( 'woocommerce_system_status_report', array( $this, 'render_status_warning' ) );
        }

        /**
         * Render the dependence admin note
         *
         * @since    1.0.0
         * @access   private
         */
        public function render_status_warning() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/views/status-report-errors.php';
        }

        /**
         * Run the module.
         *
         * @since    1.0.0
         */
        public function run() {
            if ( $this->check_supported_plugins() ) {
                $this->support_shipping_plugins();
            } else {
                $this->show_supported_plugins_error();
            }
        }
    }
}
