<?php
/**
 *
 * @package           WC_Shipping_Additional_Days
 * @since             1.0.0
 *
 * Plugin Name:       Shipping Additional Days for WooCommerce
 * Plugin URI:        https://github.com/mariovalney/woocommerce-shipping-additional-days
 * Description:       Allows you to set additional days to your delivery date into Products and Shipping Classes.
 * Version:           1.3.0
 * Author:            Mário Valney
 * Author URI:        https://mariovalney.com
 * Text Domain:       woo-shipping-additional-days
 * Domain Path:       /languages
 *
 * WC requires at least: 4.1
 * WC tested up to:      4.8
 */

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'WC_Shipping_Additional_Days' ) ) {

    class WC_Shipping_Additional_Days {

        /**
         * The array of actions registered with WordPress.
         *
         * @since    1.0.0
         * @access   protected
         * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
         */
        protected $actions = array();

        /**
         * The array of filters registered with WordPress.
         *
         * @since    1.0.0
         * @access   protected
         * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
         */
        protected $filters = array();

        /**
         * The array of modules of plugin.
         *
         * @since    1.0.0
         * @access   protected
         * @var      array    $modules    The modules to be used in this plugin.
         */
        protected $modules = array();

        /**
         * Define the core functionality of the plugin.
         *
         * @since    1.0.0
         */
        public function __construct() {
            $this->include_functions_file();
            $this->define_hooks();
            $this->add_modules();
        }

        /**
         * Define things to run when activate plugin
         *
         * @since    1.0.0
         */
        public function on_activation() {
            flush_rewrite_rules();
        }

        /**
         * Register the hooks for Core
         *
         * @since    1.0.0
         * @access   private
         */
        private function define_hooks() {
            // Internationalization
            $this->add_action( 'init', array( $this, 'load_plugin_textdomain' ), -1 );

            // Activation Hook
            register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
        }

        /**
         * Load all the plugins modules.
         *
         * @since    1.0.0
         * @access   private
         */
        private function add_modules() {
            // Require module files:
            require_once plugin_dir_path( __FILE__ ) . 'modules/shipping-classes/class-module-shipping-classes.php';
            require_once plugin_dir_path( __FILE__ ) . 'modules/products/class-module-products.php';
            require_once plugin_dir_path( __FILE__ ) . 'modules/shipping/class-module-shipping.php';

            // Instantiate the Module's classes:
            $this->modules['shipping-classes'] = new WCSAD_Module_Shipping_Classes( $this );
            $this->modules['products'] = new WCSAD_Module_Products( $this );
            $this->modules['shipping'] = new WCSAD_Module_Shipping( $this );
        }

        /**
         * Load the core functions file
         *
         * @since    1.0.0
         * @access   private
         */
        private function include_functions_file() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/functions-core.php';
        }

        /**
         * A utility function that is used to register the actions and hooks into a single
         * collection.
         *
         * @since    1.0.0
         * @access   private
         * @param    array      $hooks              The collection of hooks that is being registered (that is, actions or filters).
         * @param    string     $hook               The name of the WordPress filter that is being registered.
         * @param    string     $callback           The callback function or a array( $obj, 'method' ) to public method of a class.
         * @param    int        $priority           The priority at which the function should be fired.
         * @param    int        $accepted_args      The number of arguments that should be passed to the $callback.
         * @return   array                          The collection of actions and filters registered with WordPress.
         */
        private function add_hook( $hooks, $hook, $callback, $priority, $accepted_args ) {
            $hooks[] = array(
                'hook'          => $hook,
                'callback'      => $callback,
                'priority'      => $priority,
                'accepted_args' => $accepted_args
            );

            return $hooks;
        }

        /**
         * Add a new action to the collection to be registered with WordPress.
         *
         * @since    1.0.0
         * @param    string     $hook             The name of the WordPress action that is being registered.
         * @param    string     $callback         The callback function or a array( $obj, 'method' ) to public method of a class.
         * @param    int        $priority         Optional. he priority at which the function should be fired. Default is 10.
         * @param    int        $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
         */
        public function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
            $this->actions = $this->add_hook( $this->actions, $hook, $callback, $priority, $accepted_args );
        }

        /**
         * Add a new filter to the collection to be registered with WordPress.
         *
         * @since    1.0.0
         * @param    string     $hook             The name of the WordPress filter that is being registered.
         * @param    string     $callback         The callback function or a array( $obj, 'method' ) to public method of a class.
         * @param    int        $priority         Optional. he priority at which the function should be fired. Default is 10.
         * @param    int        $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
         */
        public function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
            $this->filters = $this->add_hook( $this->filters, $hook, $callback, $priority, $accepted_args );
        }

        /**
         * Define the locale for this plugin for internationalization.
         *
         *
         * @since    1.0.0
         * @access   private
         */
        public function load_plugin_textdomain() {
            load_plugin_textdomain( 'woo-shipping-additional-days', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        /**
         * Run the plugin.
         *
         * @since    1.0.0
         */
        public function run() {
            // Definitions to plugin
            define( 'WCSAD_VERSION', '1.3.0' );
            define( 'WCSAD_FILE', __FILE__ );
            define( 'WCSAD_BASENAME', plugin_basename( __FILE__ ) );
            define( 'WCSAD_PATH', WP_PLUGIN_DIR . '/' . dirname( WCSAD_BASENAME ) );
            define( 'WCSAD_DIR', dirname( WCSAD_BASENAME ) );
            define( 'WCSAD_URL', plugins_url( '', __FILE__ ) );

            // Running Modules (first of all)
            foreach ( $this->modules as $module ) {
                $module->run();
            }

            // Running Filters
            foreach ( $this->filters as $hook ) {
                add_filter( $hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args'] );
            }

            // Running Actions
            foreach ( $this->actions as $hook ) {
                add_action( $hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args'] );
            }
        }
    }
}

/**
 * Making things happening
 */
global $WCSAD_core;

$WCSAD_core = new WC_Shipping_Additional_Days();
$WCSAD_core->run();
