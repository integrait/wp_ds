<?php
/**
 * Content Wrappers
 *
 * @version 1.6.4
 */ 

// Generate slider here <<<<
function show_featured_pdts ($dist, $tab) {  // For Distributors and Pharmacy page - Featured(1) or Bestseller(2) tabs
	global $wpdb, $woocommerce, $woocommerce_loop; 

    $query_args = array(
        'posts_per_page'=> 16, 
        'no_found_rows' => 1, 
        'post_status' 	=> 'publish',
        'post_type' 	=> 'product', 
    );    

    if($tab == 1){ // Featured by Distributors
    	$query_args['orderby'] 	  = 'date';
    	$query_args['order'] 	  = 'desc'; 
    	$query_args['meta_query'] = array(
		    array(
		        'key'           => $dist,
		        'value'         => 0,
		        'compare'       => '>',
		        'type'          => 'NUMERIC'
		    )
		); 
		$query_args['post__in'] = DrugstocPriceModel::get_featured_pdts($dist);
  
    } else if ($tab == 2) {  // Best Sellers 
		$query_args['orderby']  = 'meta_value';
		$query_args['order'] 	= 'desc'; 

		$query_args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'     => $dist,
		        'value'   => 0,
		        'compare' => '>',
		        'type'    => 'NUMERIC'
			),
			array(
				'key'     => 'total_sales',
				'value'   => 0,
				'compare' => '>',
				'type'    => 'NUMERIC',
			)
		);
    } 

    $woocommerce_loop['setLast'] = true;

	$products = new WP_Query( $query_args );
	
	$woocommerce_loop['view'] = 'grid';

    $i = 0;

	if ( $products->have_posts() ) : 

		echo '<div class="woocommerce">';
		echo '<div class="products-slider-wrapper"><div class="products-slider">';
		echo '<h4>Major Products we carry : {$dist}</h4>';
		echo '<ul class="products row">';    
		while ( $products->have_posts() ) : $products->the_post();
	        if ( function_exists( 'wc_get_template_part')){
	            wc_get_template_part('content', 'product');
	        }else{
	            woocommerce_get_template_part( 'content', 'product' );
	        }
	        $i++;
		endwhile; // end of the loop.
		echo '</ul>';
		echo '<div class="es-nav"><span class="es-nav-prev">Previous</span><span class="es-nav-next">Next</span></div>';
		echo '</div></div><div class="es-carousel-clear"></div>';
		echo '</div>';  

	endif;

    echo do_shortcode('[clear]'); 
	                         
	$woocommerce_loop['loop'] = 0;        
	unset( $woocommerce_loop['setLast'] ); 
}

/** 
 * Make visible only on distributor page
 */
$url_frag = explode('/', $_SERVER['REQUEST_URI']);

if (in_array('vendor', $url_frag)) { 

	wp_enqueue_script( 'caroufredsel' );
	wp_enqueue_script( 'touch-swipe' );
	wp_enqueue_script( 'mousewheel' );

	global $yit_products_tabs_index;
	if ( ! isset( $yit_products_tabs_index )  ) $yit_products_tabs_index = 0;

	$url = explode("/", $_SERVER['REQUEST_URI']);  
	$dist = trim($url[array_search('vendor', $url) + 1]);
	$meta_key = "{$dist}_price";  

	$categories = array('Featured','Best Seller');

	global $woocommerce;?>

	<br/><?php //var_dump(DrugstocPriceModel::get_featured_pdts('nhc_price'));?><br/>
	<div class="tabs-container products_tabs">
	    <ul class="tabs">
	    <?php 
	    foreach ($categories as $key => $category): // <<< Loop through all categories selected by distributor 
	        echo '<li><h4><a href="#" data-tab="tab-' . $yit_products_tabs_index . '" title="' . $title . '">' . $category . '</a></h4></li>';
	        $yit_products_tabs_index++;
	    endforeach ?>
	    </ul>
	    <div class="border-box group"> 
            <div id="tab-0" class="panel group">
            <?php if(count(DrugstocPriceModel::get_featured_pdts($meta_key)) > 0 ){
            	echo show_featured_pdts( $meta_key, 1 ); 
            }else{
            	echo "<h3 style='text-align:center'> No Featured Products. </h3>";
            }?>
            </div>
         
            <div id="tab-1" class="panel group"><?php echo show_featured_pdts( $meta_key, 2 ); ?></div>
	    </div>
	</div>
	<script type="text/javascript" charset="utf-8">
	    <?php global $woocommerce_loop; ?>
	    jQuery(function($){
	        var carouFredSel;
	        var carouFredSelOptions = {
	            responsive: false,
	            auto: true,
	            items: 3,
	            // items: <?php echo empty( $woocommerce_loop['columns'] ) ? 0 : $woocommerce_loop['columns'] ?>,
	            circular: true,
	            infinite: true,
	            debug: false,
	            prev: '.section-portfolio-slider .prev',
	            next: '.section-portfolio-slider .next',
	            swipe: {
	                onTouch: false
	            },
	            scroll : {
	                items     : 1,
	                pauseOnHover: true
	            }
	        };

	        $('.products_tabs .panel').on('yit_tabopened', function(){

	            var t = $(this),
		            prev = $(this).find('.es-nav-prev'),
	            	next = $(this).find('.es-nav-next');

	            carouFredSelOptions.prev = prev;
	            carouFredSelOptions.next = next;

	            if( $('body').outerWidth() <= 767 ) {
	                t.find('li').each(function(){
	                    $(this).width( t.width() );
	                });

	                carouFredSelOptions.items = 1;
	            } else {
	                t.find('li').each(function(){
	                    $(this).attr('style', '');
	                });

	                carouFredSelOptions.items = <?php echo 3;//empty( $woocommerce_loop['columns'] ) ? 0 : $woocommerce_loop['columns'] ?>;
	            }
	            carouFredSel = t.find('.products').carouFredSel(carouFredSelOptions);
	            t.find('.es-nav-prev, .es-nav-next').removeClass('hidden').show();
	        });

	        $(window).resize(function(){
	            var t = carouFredSel.parents('.panel');
	            carouFredSel.trigger('destroy', false).attr('style','');

	            if( $('body').outerWidth() <= 767 ) {
	                t.find('li').each(function(){
	                    $(this).width( t.width() );
	                });

	                carouFredSelOptions.items = 1;
	            } else {
	                t.find('li').each(function(){
	                    $(this).attr('style', '');
	                });

	                carouFredSelOptions.items = <?php echo 3;//empty( $woocommerce_loop['columns'] ) ? 0 : $woocommerce_loop['columns'] ?>;
	            }

	            carouFredSel.carouFredSel(carouFredSelOptions);
	        }); 

	        $(document).on('feature_tab_opened', function(){ $(window).trigger('resize') } );

	        // create slider when page is loaded
	        $(window).load(function(){
	            $(window).trigger('resize');
	            $('.es-nav-prev, .es-nav-next').removeClass('hidden').show();
	        });
	    });
	</script>
	<?php   
}?>
</div>