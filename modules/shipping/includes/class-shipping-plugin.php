<?php
/**
 * WCSAD_Woocommerce_Shipping_Plugin
 * Base class to add estimated data to plugins
 *
 * @package         WC_Shipping_Additional_Days
 * @subpackage      WCSAD_Module_Shipping
 * @since           1.0.0
 *
 */

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WCSAD_Woocommerce_Shipping_Plugin' ) ) {
    abstract class WCSAD_Woocommerce_Shipping_Plugin {

        /**
         * The Core object
         *
         * @since    1.0.0
         * @var      WC_Shipping_Additional_Days    $core   The core class
         */
        protected $core;

        /**
         * Define the core functionalities into plugin.
         *
         * @since    1.0.0
         * @param    WC_Shipping_Additional_Days      $core   The Core object
         */
        public function __construct( WC_Shipping_Additional_Days $core ) {
            $this->core = $core;
            $this->define_hooks();
        }

        /**
         * Register all the hooks for this module
         *
         * @since    1.0.0
         * @access   private
         */
        abstract protected function define_hooks();

        /**
         * Get the time for shipping classes from package
         *
         * @param array $package
         * @return int
         */
        protected function get_additional_time_for_package( $package ) {
            if ( empty( $package ) || empty( $package['contents'] ) ) {
                return 0;
            }

            $shipping_classes_ids = array();
            $product_days = 0;

            foreach ( $package['contents'] as $product_data ) {
                $shipping_classes_ids[] = $product_data['data']->get_shipping_class_id();
                $product_days = max( $product_days, get_products_additional_time( $product_data['product_id'] ) );
            }

            $shipping_classes_ids = array_unique( array_values( array_filter( $shipping_classes_ids ) ) );
            $shipping_class_days = $this->get_max_shipping_classes_additional_time( $shipping_classes_ids );

            return max( $shipping_class_days, $product_days );
        }

        /**
         * Retrieve shipping classes by id
         *
         * @since    1.0.0
         * @access   private
         * @param    array      $shipping_classes_ids   Array of term_ids
         */
        private function get_max_shipping_classes_additional_time( $shipping_classes_ids = array() ) {
            if ( ! is_array( $shipping_classes_ids ) ) {
                return 0;
            }

            $shipping_classes_ids = array_filter( $shipping_classes_ids, function( $id ) {
                return ! is_nan( intval( $id ) );
            } );

            if ( empty( $shipping_classes_ids ) ) {
                return 0;
            }

            $shipping_class = get_terms( array(
                'taxonomy'      => 'product_shipping_class',
                'hide_empty'    => true,
                'include'       => (array) $shipping_classes_ids,
                'number'        => 1,
                'order'         => 'DESC',
                'orderby'       => 'meta_value_num',
                'meta_key'      => WCSAD_SHIPPING_CLASS_ADDITIONAL_TIME,
            ) );

            if ( is_wp_error( $shipping_class ) || empty( $shipping_class ) ) {
                return 0;
            }

            return get_shipping_class_additional_time( $shipping_class[0]->term_id );
        }

    }
}
