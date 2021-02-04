<p class="form-field dimensions_field">
    <?php
        global $post;

        $current = get_products_additional_time( $post->ID );

        woocommerce_wp_text_input( array(
            'id'                => WCSAD_PRODUCT_ADDITIONAL_TIME,
            'value'             => $current,
            'label'             => __( 'Additional Days', 'woo-shipping-additional-days' ),
            'placeholder'       => 0,
            'desc_tip'          => true,
            'description'       => __( 'Additional Days will be used to increase the delivery date if you need some days to have the product into stock.', 'woo-shipping-additional-days' ),
            'type'              => 'number',
            'custom_attributes' => array(
                'step'  => '1',
                'min'   => '0',
            )
        ) );
    ?>
</p>