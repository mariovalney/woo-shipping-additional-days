<?php
/**
 * WCSAD_Module_Products
 * Add additional time to Products
 *
 * @package         WC_Shipping_Additional_Days
 * @subpackage      WCSAD_Module_Products
 * @since           1.0.0
 *
 */

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WCSAD_Module_Products' ) ) {
    class WCSAD_Module_Products {

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
        const MODULE_SLUG = "products";

        /**
         * Define the core functionalities into plugin.
         *
         * @since    1.0.0
         * @param    WC_Shipping_Additional_Days      $core   The Core object
         */
        public function __construct( WC_Shipping_Additional_Days $core ) {
            $this->core = $core;

            if ( ! defined( 'WCSAD_PRODUCT_ADDITIONAL_TIME' ) ) {
                define( 'WCSAD_PRODUCT_ADDITIONAL_TIME', '_wcsad_product_additional_time' );
            }
        }

        /**
         * Register all the hooks for this module
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_hooks() {
            $this->core->add_action( 'woocommerce_product_options_shipping', array( $this, 'woocommerce_product_options_shipping' ) );
            $this->core->add_action( 'woocommerce_admin_process_product_object', array( $this, 'woocommerce_admin_process_product_object' ) );
        }

        /**
         * Includes files
         *
         * @since    1.0.0
         * @access   private
         */
        private function include_files() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/functions-products.php';
        }

        /**
         * Action: 'woocommerce_product_options_shipping'
         * Add Additional Time to shipping config into Product dashboard
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_product_options_shipping() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/views/product-shipping-additional-time.php';
        }

        /**
         * Action: 'woocommerce_admin_process_product_object'
         * Save Additional Time into Product meta data
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_admin_process_product_object( $product ) {
            if ( isset( $_POST[WCSAD_PRODUCT_ADDITIONAL_TIME] ) ) {
                $additional_time = wcsad_sanitize_additional_time( $_POST[WCSAD_PRODUCT_ADDITIONAL_TIME] );

                $product->update_meta_data( WCSAD_PRODUCT_ADDITIONAL_TIME, $additional_time );
                $product->save();
            }
        }

        /**
         * Run the module.
         *
         * @since    1.0.0
         */
        public function run() {
            $this->include_files();
            $this->define_hooks();
        }
    }
}
