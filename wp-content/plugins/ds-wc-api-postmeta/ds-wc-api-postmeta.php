<?php
/*
Plugin Name: WC API Custom Metafields
Plugin URI:  https://github.com/academe/TBC
Description: Allows custom meta fields to be added to products when creating or updating.
Version:     0.5
Author:      Jason Judge
Author URI:  http://academe.co.uk
*/

// Want to hook into woocommerce_api_process_product_meta_{product_type} for all product types.
add_action('woocommerce_api_process_product_meta_simple', 'academe_wc_api_custom_meta', 10, 2);
add_action('woocommerce_api_process_product_meta_variable', 'academe_wc_api_custom_meta', 10, 2);
add_action('woocommerce_api_process_product_grouped', 'academe_wc_api_custom_meta', 10, 2);
add_action('woocommerce_api_process_product_external', 'academe_wc_api_custom_meta', 10, 2);
function academe_wc_api_custom_meta($id, $data) {
    if (!empty($data['price_meta']) && is_array($data['price_meta'])) {
        foreach($data['price_meta'] as $field_name => $field_value) {
            update_post_meta($id, $field_name, wc_clean($field_value));
        }
    }
    if (!empty($data['price_meta']) && is_array($data['remove_price_meta'])) {
        foreach($data['remove_price_meta'] as $key => $value) {
            // If the key is numeric, then assume $value is the field name
            // and all entries need to be deleted. Otherwise is is a specfic value
            // of a named meta field that should be removed.
            if (is_numeric($key)) {
                delete_post_meta($id, $value);
            } else {
                delete_post_meta($id, $key, $value);
            }
        }
    }
}

// Hook into Action to supply line item meta
add_action('woocommerce_api_edit_order_data', 'ds_wc_api_order_item_meta', 10, 3);

function ds_wc_api_order_item_meta($data, $id, $instance) {

    if (!empty($data['line_items']) && is_array($data['line_items'])) { 
        
        global $wpdb;

        for ($i=0; $i < count($data['line_items']); $i++) { 
            // Update wp_routed_order_items
            $wpdb->update(
                $wpdb->prefix."routed_order_items", 
                array( 
                    'notes'    => $data['line_items'][$i]['line_note'], 
                    'in_stock' => $data['line_items'][$i]['in_stock']
                ),
                array(
                    'order_id' => $id, 
                    'item_id'  => $data['line_items'][$i]['product_id'] 
                ),
                array( 
                    '%s',
                    '%d'     
                ), 
                array( '%d', '%d' ) 
            );  
        }  
    }  
}



