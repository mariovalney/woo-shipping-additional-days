<?php
/**
 * WCSAD_Woocommerce_Local_Pickup
 * Support to WooCommerce Local Pickup
 * Plugin: https://docs.woocommerce.com/document/local-pickup/
 *
 * @package         WC_Shipping_Additional_Days
 * @subpackage      WCSAD_Module_Shipping
 * @since           1.0.0
 *
 */

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WCSAD_Woocommerce_Local_Pickup' ) ) {
    class WCSAD_Woocommerce_Local_Pickup extends WCSAD_Woocommerce_Shipping_Plugin {

        /**
         * Register all the hooks for this module
         *
         * @since    1.0.0
         * @access   private
         */
        protected function define_hooks() {
            $this->core->add_action( 'woocommerce_after_shipping_rate', array( $this, 'woocommerce_after_shipping_rate' ), 99 );
        }

        /**
         * Action: 'woocommerce_after_shipping_rate'
         * Add info about additional days to local pickup
         *
         * @param WC_Shipping_Rate $method Shipping method data.
         * @return void
         */
        public function woocommerce_after_shipping_rate( $method ) {
            if ( $method->get_method_id() != 'local_pickup' ) {
                return;
            }

            $packages = WC()->shipping->get_packages();
            foreach ( $packages as $package ) {
                $rates = $package['rates'];

                foreach ( $rates as $rate ) {
                    if ( $rate->get_id() !== $method->get_id() ) {
                        continue;
                    }

                    $total = $this->get_additional_time_for_package( $package );
                    break 2;
                }
            }

            $message = __( 'Available after payment', 'woo-shipping-additional-days' );
            if ( ! empty( $total ) ) {
                $message = sprintf( _n( 'Available in %d working day', 'Available in %d working days', $total, 'woo-shipping-additional-days' ), $total );
            }

            /**
             * Filter: wcsad_pickup_estimated_delivery_text
             * @var string
             *
             * You can change the text of estimated days to pickup.
             * Return a empty value to remove the delivery text.
             *
             * @param string $message Original message.
             * @param int $total Total of additional days.
             * @param WC_Shipping_Rate $method Shipping method data.
             */
            $message = apply_filters( 'wcsad_pickup_estimated_delivery_text', $message, $total, $method );

            if ( empty( $message ) ) {
                return;
            }

            echo '<p><small>' . esc_html( $message ) . '</small></p>';
        }
    }
}
