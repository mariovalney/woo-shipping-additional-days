<?php

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! function_exists( 'get_products_additional_time' ) ) {
    function get_products_additional_time( $product_id, $default = 0 ) {
        $product = wc_get_product( $product_id );
        if ( $product ) {
            $additional_time = $product->get_meta( WCSAD_PRODUCT_ADDITIONAL_TIME, true );
        }

        return ( $additional_time !== false && is_numeric( $additional_time ) ) ? $additional_time : $default;
    }
}