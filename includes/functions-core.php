<?php

// If this file is called directly, call the cops.
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function wcsad_log( $message, $file = 'errors.log' ) {
    error_log( $message . "\n", 3, WC_Shipping_Additional_Days_PATH . '/' . $file );
}

function wcsad_sanitize_additional_time( $number ) {
    $number = intval( $number );
    if ( is_nan( $number ) || $number < 0 ) {
        return 0;
    }

    return $number;
}