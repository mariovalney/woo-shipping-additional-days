<?php
/**
 * WCSAD_Module_Shipping_Classes
 * Add additional time to Shipping Classes
 *
 * @package         WC_Shipping_Additional_Days
 * @subpackage      WCSAD_Module_Shipping_Classes
 * @since           1.0.0
 *
 */

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WCSAD_Module_Shipping_Classes' ) ) {
    class WCSAD_Module_Shipping_Classes {

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
        const MODULE_SLUG = "shipping-classes";

        /**
         * Define the core functionalities into plugin.
         *
         * @since    1.0.0
         * @param    WC_Shipping_Additional_Days      $core   The Core object
         */
        public function __construct( WC_Shipping_Additional_Days $core ) {
            $this->core = $core;

            if ( ! defined( 'WCSAD_SHIPPING_CLASS_ADDITIONAL_TIME' ) ) {
                define( 'WCSAD_SHIPPING_CLASS_ADDITIONAL_TIME', 'wcsad-sc-additional-time' );
            }
        }

        /**
         * Register all the hooks for this module
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_hooks() {
            $this->core->add_filter( 'woocommerce_shipping_classes_columns', array( $this, 'woocommerce_shipping_classes_columns' ) );
            $this->core->add_filter( 'woocommerce_get_shipping_classes', array( $this, 'woocommerce_get_shipping_classes' ) );

            $this->core->add_action( 'woocommerce_shipping_classes_column_wc-shipping-class-additional-time', array( $this, 'woocommerce_shipping_classes_column_additional_time' ) );
            $this->core->add_action( 'woocommerce_shipping_classes_save_class', array( $this, 'woocommerce_shipping_classes_save_class' ), 10, 2 );
        }

        /**
         * Includes files
         *
         * @since    1.0.0
         * @access   private
         */
        private function include_files() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/functions-shipping-classes.php';
        }

        /**
         * Filter: 'woocommerce_get_shipping_classes'
         * Do stuff to get_shipping_classes
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_get_shipping_classes( $shipping_classes ) {
            return array_map( function( $class ) {
                $class->additional_time = get_shipping_class_additional_time( $class->term_id, 0 );
                return $class;
            }, $shipping_classes );
        }

        /**
         * Filter: 'woocommerce_shipping_classes_columns'
         * Add the Additional Days column
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_shipping_classes_columns( $columns ) {
            $columns['wc-shipping-class-additional-time'] = __( 'Additional Days', 'woo-shipping-additional-days' );
            return $columns;
        }

        /**
         * Filter: 'woocommerce_shipping_classes_column_wc-shipping-class-additional-time'
         * Add the Additional Days column data
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_shipping_classes_column_additional_time() {
            ?>
                <div class="view">{{ data.additional_time }}</div>
                <div class="edit">
                    <input type="number" name="additional_time[{{ data.term_id }}]" data-attribute="additional_time" min="0" value="{{ data.additional_time }}" placeholder="0">
                </div>
            <?php
        }

        /**
         * Filter: 'woocommerce_shipping_classes_save_class'
         * Save the Additional Days column data
         *
         * @since    1.0.0
         * @access   private
         */
        public function woocommerce_shipping_classes_save_class( $term_id, $data ) {
            if ( ! isset( $data['additional_time'] ) ) {
                return;
            }

            $term_id = ( is_array( $term_id ) ) ? $term_id['term_id'] : $term_id;
            $additional_time = wcsad_sanitize_additional_time( $data['additional_time'] );

            update_term_meta( $term_id, WCSAD_SHIPPING_CLASS_ADDITIONAL_TIME, $additional_time );
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
