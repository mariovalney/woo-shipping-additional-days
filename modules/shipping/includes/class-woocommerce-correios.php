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
    class WCSAD_Woocommerce_Correios extends WCSAD_Woocommerce_Shipping_Plugin {

        /**
         * Register all the hooks for this module
         *
         * @since    1.0.0
         * @access   private
         */
        protected function define_hooks() {
            $this->core->add_filter( 'woocommerce_correios_shipping_additional_time', array( $this, 'woocommerce_correios_shipping_additional_time' ), 20, 2 );
        }

        /**
         * Filter: 'woocommerce_correios_shipping_additional_time'
         * Change the additional time value
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_correios_shipping_additional_time( $global_additional_time, $package ) {
            $total = $this->get_additional_time_for_package( $package );

            return max( $global_additional_time, $total );
        }
    }
}
