<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! yit_get_option('shop-products-details-nav') ) return;

?>
<div class="product-nav">
    <?php previous_post_link( '<div class="prev">%link</div>', __( 'Prev', 'yit' ) ); ?>&nbsp;&nbsp;/&nbsp;
    <?php next_post_link( '<div class="next">%link</div>', __( 'Next', 'yit' ) ); ?>
</div>