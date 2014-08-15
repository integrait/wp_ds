<?php 
 
add_action( 'yit_footer', 'yit_copyright_DRUGSTOC');

function yit_copyright_DRUGSTOC(){ 
	$args = array(
	    'post_type' => 'product',
	    'posts_per_page' => -1
	);
	query_posts($args);
	while (have_posts()) : the_post();
	    print_r($product);
	    echo get_the_ID();
	endwhile;
	} 

?>