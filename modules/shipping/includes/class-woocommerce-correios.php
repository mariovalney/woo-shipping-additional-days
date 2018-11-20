<?php
/**
 * WCSAD_Woocommerce_Correios
 * Support to WooCommerce Correios 3.2.2
 * Plugin: https://br.wordpress.org/plugins/woocommerce-correios
 *
 * @package         WC_Shipping_Additional_Days
 * @subpackage      WCSAD_Module_Shipping
 * @since           1.0.0
 *
 */

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WCSAD_Woocommerce_Correios' ) ) {
    class WCSAD_Woocommerce_Correios {

        /**
         * The Core object
         *
         * @since    1.0.0
         * @var      WC_Shipping_Additional_Days    $core   The core class
         */
        private $core;

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
        private function define_hooks() {
            $this->core->add_filter( 'woocommerce_correios_shipping_additional_time', array( $this, 'woocommerce_correios_shipping_additional_time' ), 20, 2 );
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

        /**
         * Filter: 'woocommerce_correios_shipping_additional_time'
         * Change the additional time value
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_correios_shipping_additional_time( $global_additional_time, $package ) {
            if ( empty( $package['contents'] ) ) {
                return $global_additional_time;
            }

            $shipping_classes_ids = array();
            $product_additional_days = array();

            foreach ( $package['contents'] as $product_data ) {
                $shipping_classes_ids[] = $product_data['data']->get_shipping_class_id();
                $product_additional_days = get_products_additional_time( $product_data['product_id'] );
            }


            $shipping_classes_ids = array_unique( array_values( array_filter( $shipping_classes_ids ) ) );
            $max_additional_time = $this->get_max_shipping_classes_additional_time( $shipping_classes_ids );

            return max( $global_additional_time, $max_additional_time, $product_additional_days );
        }
    }
}
