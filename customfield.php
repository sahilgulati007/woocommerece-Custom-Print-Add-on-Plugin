<?php
/**
 * Plugin Name: woocommerece Custom Print Add-on Plugin
 * Description: To get custom text, print side and print side from user to print on clothes for brands or T-shirt customization.
 * Version: 1.0
 * Author: Sahil Gulati
 * Author URI: http://www.facebook.com/sahilgulati007
 */

// -----------------------------------------
// 1. Show custom input field above Add to Cart

add_action( 'woocommerce_before_add_to_cart_button', 'sg_product_add_on', 9 );

function sg_product_add_on() {
    $value = isset( $_POST['_custom_text_add_on'] ) ? sanitize_text_field( $_POST['_custom_text_add_on'] ) : '';
    echo '<div><label>Custom Text to Print <abbr class="required" title="required">*</abbr></label><p><input name="_custom_text_add_on" value="' . $value . '"></p></div>';
    echo '<div><label>Custom Text Print Side <abbr class="required" title="required">*</abbr></label><p><select name="_custom_text_print_side"> <option value="Front">Front</option> <option value="Back">Back</option> <option value="Left">Left</option> <option value="Right">Right</option></select> </p></div>';
    echo '<div><label>Custom Text Print Type <abbr class="required" title="required">*</abbr></label><p><select name="_custom_text_print_type"> <option value="Sticker">Sticker</option> <option value="Embroidery">Embroidery</option></select> </p></div>';
}

// -----------------------------------------
// 2. Throw error if custom input field empty

add_filter( 'woocommerce_add_to_cart_validation', 'sg_product_add_on_validation', 10, 3 );

function sg_product_add_on_validation( $passed, $product_id, $qty ){
    if( isset( $_POST['_custom_text_add_on'] ) && sanitize_text_field( $_POST['_custom_text_add_on'] ) == '' ) {
        wc_add_notice( 'Custom Text Add-On is a required field', 'error' );
        $passed = false;
    }
    return $passed;
}

// -----------------------------------------
// 3. Save custom input field value into cart item data

add_filter( 'woocommerce_add_cart_item_data', 'sg_product_add_on_cart_item_data', 10, 2 );

function sg_product_add_on_cart_item_data( $cart_item, $product_id ){
    if( isset( $_POST['_custom_text_add_on'] ) ) {
        $cart_item['custom_text_add_on'] = sanitize_text_field( $_POST['_custom_text_add_on'] );
        $cart_item['custom_text_print_side'] = sanitize_text_field( $_POST['_custom_text_print_side'] );
        $cart_item['custom_text_print_type'] = sanitize_text_field( $_POST['_custom_text_print_type'] );
    }
    return $cart_item;
}

// -----------------------------------------
// 4. Display custom input field value @ Cart

add_filter( 'woocommerce_get_item_data', 'sg_product_add_on_display_cart', 10, 2 );

function sg_product_add_on_display_cart( $_data, $cart_item ) {
    if ( isset( $cart_item['custom_text_add_on'] ) ){
        $data[] = array(
            'name' => 'Custom Text to Print',
            'value' => sanitize_text_field( $cart_item['custom_text_add_on'] ),
        );
        $data[] = array(
            'name' => 'Custom Text Print Side',
            'value' => sanitize_text_field( $cart_item['custom_text_print_side'] )
        );
        $data[] = array(
            'name' => 'Custom Text Print Type',
            'value' => sanitize_text_field( $cart_item['custom_text_print_type'] )
        );
    }
    return $data;
}

// -----------------------------------------
// 5. Save custom input field value into order item meta

add_action( 'woocommerce_add_order_item_meta', 'sg_product_add_on_order_item_meta', 10, 2 );

function sg_product_add_on_order_item_meta( $item_id, $values ) {
    if ( ! empty( $values['custom_text_add_on'] ) ) {
        wc_add_order_item_meta( $item_id, 'Custom Text To Print', $values['custom_text_add_on'], true );
        wc_add_order_item_meta( $item_id, 'Custom Text Print Side', $values['custom_text_print_side'], true );
        wc_add_order_item_meta( $item_id, 'Custom Text Print Type', $values['custom_text_print_type'], true );
    }
}

// -----------------------------------------
// 6. Display custom input field value into order table

add_filter( 'woocommerce_order_item_product', 'sg_product_add_on_display_order', 10, 2 );

function sg_product_add_on_display_order( $cart_item, $order_item ){
    if( isset( $order_item['custom_text_add_on'] ) ){
        $cart_item_meta['custom_text_add_on'] = $order_item['custom_text_add_on'];
        $cart_item_meta['custom_text_print_side'] = $order_item['custom_text_print_side'];
        $cart_item_meta['custom_text_print_type'] = $order_item['custom_text_print_type'];
    }
    return $cart_item;
}

// -----------------------------------------
// 7. Display custom input field value into order emails

add_filter( 'woocommerce_email_order_meta_fields', 'sg_product_add_on_display_emails' );

function sg_product_add_on_display_emails( $fields ) {
    $fields['custom_text_add_on'] = 'Custom Text To Print';
    $fields['custom_text_print_side'] = 'Custom Text Print Side';
    $fields['custom_text_print_type'] = 'Custom Text Print Type';
    return $fields;
}