<?php

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'get_shipping_class_additional_time' ) ) {
    function get_shipping_class_additional_time( $term_id, $default = 0 ) {
        $additional_time = get_term_meta( $term_id, WCSAD_SHIPPING_CLASS_ADDITIONAL_TIME, true );

        return ( $additional_time !== false && is_numeric( $additional_time ) ) ? $additional_time : $default;
    }
}