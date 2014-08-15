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


	function add_additional_css() {
	//wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/js/example.js', array(), '1.0.0', true );
	//wp_enqueue_script( 'script-name', get_template_directory_uri() . '/css/additionalCss.css', array(), '1.0.0', true );
	//wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	wp_enqueue_style('style-name', get_template_directory_uri() . '/css/additionalCss.css' );
}

add_action( 'wp_enqueue_scripts', 'add_additional_css' );

?>