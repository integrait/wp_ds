<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  

// get the style
global $woocommerce_loop, $blog_id, $wpdb;

$cookie_shop_view = 'yit_' . get_template() . ( is_multisite() ? '_' . $blog_id : '' ) . '_shop_view';
$woocommerce_loop['view'] = isset( $_COOKIE[ $cookie_shop_view ] ) ? $_COOKIE[ $cookie_shop_view ] : yit_get_option( 'shop-view', 'grid' );

get_header('shop'); 

$url = explode("/", $_SERVER['REQUEST_URI']);
$manu_slug = in_array('manufacturer', $url)? $url[array_search('manufacturer', $url) + 1]: '';
$manu_slug = trim($manu_slug);

if($manu_slug != ''){ 
 
  	$user = current(get_users(
  		array(  
			'role'        => 'manufacturer',
			'meta_key'    => 'manufacturer_slug',
			'meta_value'  => $manu_slug,
			'meta_compare'=> '=',
			'number' 	  => '1'
		)
  	));   

	// Create Variables for info box
	$address = get_user_meta($user->ID, 'billing_address_1', true).",".get_user_meta($user->ID, 'billing_address_2', true).",".get_user_meta($user->ID, 'billing_city', true).",".get_user_meta($user->ID, 'billing_state', true);
	$institution = get_user_meta($user->ID, 'institution', true); 
	$user_img_url = get_cupp_meta($user->ID, 'thumbnail');

	// Map Coords
	$gmap  = get_user_meta($user->ID, 'gmap_coords', true); 
	$map_coords = explode(",", $gmap);
	$gmap_lat = $map_coords[0];
	$gmap_lon = $map_coords[1]; 

	//do_shortcode('[show_homepage_map]'); 
	//echo "<script> draw_single_map('$institution', '".get_user_meta($user->ID, 'billing_address_1', true)."','".get_user_meta($user->ID, 'billing_state', true)."','{$user->user_email}' ,'$gmap_lat', '$gmap_lon', 'Manufacturer'); </script>";  

}?> 

	<?php if(is_search()){?>
		<!-- Search Map -->
		<div class="map-container" id="map-container" style="height:400px; width:100%; margin-top:-110px; margin-bottom: 29px;"></div>
		<!-- Search Summary 
		<div id="group_search_result" style="width:300px; height:200px; background-color:pink;z-index: 10000;margin-top: -319px;position: absolute;float: right;margin-left: 80%;">
		</div>-->
	<?php } ?>

	<?php
		/**
		 * woocommerce_before_main_content hook 
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */ 

		do_action('woocommerce_before_main_content');

		global $wpdb;

		$args = $args = array(
			'show_option_all'    => '',
			'show_option_none'   => '',
			'option_none_value'  => '-1',
			'orderby'            => 'NAME', 
			'order'              => 'ASC',
			'show_count'         => 1,
			'hide_empty'         => 1, 
			'child_of'           => 0,
			'exclude'            => '',
			'include'			 => '',
			'echo'               => 1,
			'selected'           => 0,
			'hierarchical'       => 0, 
			'name'               => 'dist_category',
			'id'                 => 'dist_category',
			'class'              => 'postform',
			'depth'              => 0,
			'tab_index'          => 0,
			'taxonomy'           => 'category',
			'hide_if_empty'      => true,
			'value_field'	     => 'term_id',	
		);
		wp_dropdown_categories( $args ); 

	?>

		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

            <p><?php _e( 'No products found which match your selection.', 'yit' ); ?></p>
            
             <?php do_shortcode('[gdym_didyoumean]');?>
             
		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>                                

    <script type='text/javascript'>
    /* <![CDATA[ */
    var yit_shop_view_cookie = '<?php echo $cookie_shop_view; ?>';
    /* ]]> */
    </script>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action('woocommerce_sidebar');

		// global $wpdb;

		// if($manu_slug = ''){
		if(1==2){
			
			$manufacturers_dist = $wpdb->get_results("SELECT w.ID FROM `wp_users` as w INNER JOIN wp_usermeta as m on w.ID = m.user_id WHERE m.meta_key = 'primary_distributor' and m.meta_value!=''"); ?>

			<p align="center" style="padding-top:20px; cursor: pointer; margin-left:auto; margin-right:auto"><a class="button" onclick="jQuery('#show_all_cat').toggle('slow',function(){})" >View All Distributors by <?php echo strtoupper($manu_slug);?></a></p>
			<div class="dist_manufacturer responsive" style="display:block;"> 
			    <ul style="width:80%; height:auto; list-style: none; overflow-y:scroll; display:block; margin-top: 30px; margin-bottom: 30px; margin-left:auto; margin-right:auto; list-decoration:none" class="responsive" id="show_all_cat">
			    <?php 
			      foreach ($manufacturers_dist as $key => $_user) { 
			        $user = get_user_by('id', $_user->ID);
			        if(in_array('shop_manager', $user->roles ) ) { 
			          $dist = substr(get_user_meta($_user->ID,'primary_distributor',true), 0, -6);
			        ?>   
			        <li>
				        <a target="_blank" href="<?php echo home_url('/vendor/'.$dist.'/?manufacturer='.$manu_slug);?>" >
				          <span style="border: 2px solid rgb(242, 242, 242); padding: 5px; margin: 5px; float: left">
				            <?php echo get_user_meta($_user->ID,'institution',true);?>
				          </span>  
				        </a>
				    </li>
			    <?php  
			        }
			      }?> 
			    </ul> 
			</div>
		<?php
		}  
get_footer('shop'); ?>