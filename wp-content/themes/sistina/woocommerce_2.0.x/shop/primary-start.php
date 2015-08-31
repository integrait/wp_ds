<?php
/**
 * Content Wrappers
 */
?>

<div id="primary" class="<?php yit_sidebar_layout() ?>">
    <div class="container group">
	    <div class="row">
	        <?php

                if ( is_single() ) {
                    echo '<div class="span12 margin-bottom2">';
                    if ( yit_get_option('shop-single-show-breadcrumb') ) woocommerce_breadcrumb();
                    yit_woocommerce_prev_next_nav();
                    echo '</div>';
                }

                do_action( 'yit_before_content' ); ?>