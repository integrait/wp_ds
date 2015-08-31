<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product, $wpdb;

$current_user = wp_get_current_user();
$primary_distributor = get_user_meta($current_user->ID, 'primary_distributor', true);

//if we have a primary distributor
$distributors = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM `wp_postmeta` WHERE `meta_key` LIKE '".((strlen($primary_distributor) > 0)? $primary_distributor : '%_price')."' and meta_key NOT IN ('_price','_regular_price','_sale_price') group by meta_key");

if(count($distributors) > 0){
foreach ($distributors as $key => $dist) {
    # code...
    $dist_price = get_post_meta($product->id, $dist->meta_key, true);

    if( $dist_price != "" && $dist->meta_value == "+=+" ){ 
    // if( $dist_price != "" && $dist->meta_value != "" ){ 
    	// echo "$product->id > $dist->meta_key $dist->meta_value";

    	$user_args = array( 
			'role' 		   => 'shop_manager',
			'meta_key'     => 'primary_distributor',
		    'meta_value'   => $dist->meta_key,
		    'meta_compare' => '=',
		    'number'	   => '1'
		);
		$user = current(get_users($user_args)); ?>
    	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer"> 
			<input type="radio" name="supplier_price" class="supplier_price" value="<?php echo $dist_price;?>" data-pdtid="<?php echo $dist->post_id;?>" data-supplier="<?php echo $dist->meta_key;?>"/>
			<?php 
			if(is_user_logged_in()){ ?>
				<p itemprop="price" class="price">
					<span class="amount">₦<?php echo $dist_price;?></span>
				</p>
			<?php 
			}else{
				echo '<p style="color:'. get_option('yith_hide_price_change_color') .'"><a style="display:inline; color:'. get_option('yith_hide_price_change_color') .'" href="' .get_permalink(function_exists( 'wc_get_page_id' ) ? wc_get_page_id('myaccount') : woocommerce_get_page_id('myaccount')). '">'. get_option('yith_hide_price_link_text') .'</a> '. get_option('yith_hide_price_text').'</p>';
			}?> 
			<span>&nbsp;<?php echo (is_user_logged_in())? get_user_meta($user->ID, 'institution', true):'';?></span>
			<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
			<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
		</div>  
    <?php
    }   
}
}?> 





