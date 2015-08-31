<?php
/**
 * Template Name: Distributor 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  

// get the style
global $woocommerce_loop, $blog_id, $wp_query, $wpdb;

$cookie_shop_view = 'yit_' . get_template() . ( is_multisite() ? '_' . $blog_id : '' ) . '_shop_view';
$woocommerce_loop['view'] = isset( $_COOKIE[ $cookie_shop_view ] ) ? $_COOKIE[ $cookie_shop_view ] : yit_get_option( 'shop-view', 'grid' );

$distributor = $wp_query->query_vars['distributor'];

$dist_category   = isset($_GET['category'])? esc_attr($_GET['category']):"";
$pa_composition  = isset($_GET['composition'])? esc_attr($_GET['composition']):"";
$pa_manufacturer = isset($_GET['manufacturer'])? esc_attr($_GET['manufacturer']):"";

$meta_key = "{$distributor}_price"; //custom_color for example
$paged = explode("/", $_SERVER['REQUEST_URI']); // ( get_query_var('page') ) ? get_query_var('page') : 1;
$page_num = is_numeric($paged[count($paged)-2])? $paged[count($paged)-2]:1 ;

$args = array( 
	'post_type'  => 'product',
	'meta_key'   => $meta_key,  
	'orderby'	 => 'date', 
	'posts_per_page' => 24,
	'paged'		 => $page_num, 
	// 'ignore_sticky_posts' => 1,
	'meta_query'     => array(
	    array(
	        'key'           => $meta_key,
	        'value'         => 0,
	        'compare'       => '>',
	        'type'          => 'NUMERIC'
	    )
	)
);

if($dist_category != "" && $pa_composition != "" && $pa_manufacturer != ""){
	$args['tax_query'] =  array(
		"relation" => "AND",
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $dist_category 
		), 
		array(
			"relation" => "AND",
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $pa_composition 
			),
			array(
				'taxonomy' => 'pa_composition',
				'field'    => 'slug',
				'terms'    => $pa_manufacturer 
			)
		) 
	);  
}else if($dist_category != "" && $pa_composition != "" && $pa_manufacturer == ""){
	$args['tax_query'] =  array(
		"relation" => "AND",
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $dist_category 
		),
		array(
			'taxonomy' => 'pa_composition',
			'field'    => 'slug',
			'terms'    => $pa_composition 
		)
	);
}
else if($dist_category != "" && $pa_manufacturer != "" && $pa_composition == ""){
	$args['tax_query'] =  array(
			"relation" => "AND",
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $dist_category 
			),
			array(
				'taxonomy' => 'pa_manufacturer',
				'field'    => 'slug',
				'terms'    => $pa_manufacturer 
			)
		);
}
else if($dist_category != "" && $pa_manufacturer == "" && $pa_composition == ""){
	$args['tax_query'][] =  
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $dist_category 
	);
}
else if($pa_composition != "" && $pa_manufacturer == "" && $dist_category == ""){
	$args['tax_query'][] =  
		array(
			'taxonomy' => 'pa_composition',
			'field'    => 'slug',
			'terms'    => $pa_composition 
	);
}
else if($pa_manufacturer != "" && $pa_composition == "" && $dist_category == ""){
	$args['tax_query'][] =  
		array(
			'taxonomy' => 'pa_manufacturer',
			'field'    => 'slug',
			'terms'    => $pa_manufacturer 
	);
}

query_posts( $args );   

// Product Categories
$pdt = $wpdb->get_results("SELECT t.name, t.slug, count(t.term_id) as no_of_pdts FROM wp_terms as t 
  INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id 
  INNER JOIN wp_term_relationships as wtr on wtr.term_taxonomy_id = p.term_taxonomy_id
  WHERE p.taxonomy LIKE 'product_cat' and wtr.object_id IN (
  	SELECT p.ID FROM wp_posts as p INNER JOIN wp_postmeta as w on p.ID = w.post_id WHERE p.post_status='publish' AND w.meta_key LIKE '$meta_key' and w.meta_value != ''
  ) GROUP BY t.name ORDER BY t.name ASC ");

// Product Composition
$pdt_comp = $wpdb->get_results("SELECT t.name, t.slug, count(t.term_id) as no_of_pdts FROM wp_terms as t 
  INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id 
  INNER JOIN wp_term_relationships as wtr on wtr.term_taxonomy_id = p.term_taxonomy_id
  WHERE p.taxonomy LIKE 'pa_composition' and wtr.object_id IN (
  	SELECT p.ID FROM wp_posts as p INNER JOIN wp_postmeta as w on p.ID = w.post_id WHERE p.post_status='publish' AND w.meta_key LIKE '$meta_key' and w.meta_value != ''
  ) GROUP BY t.name ORDER BY t.name ASC ");

// Product Manufacturer
$pdt_manuf = $wpdb->get_results("SELECT t.name, t.slug, count(t.term_id) as no_of_pdts FROM wp_terms as t 
  INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id 
  INNER JOIN wp_term_relationships as wtr on wtr.term_taxonomy_id = p.term_taxonomy_id
  WHERE p.taxonomy LIKE 'pa_manufacturer' and wtr.object_id IN (
  	SELECT p.ID FROM wp_posts as p INNER JOIN wp_postmeta as w on p.ID = w.post_id WHERE p.post_status='publish' AND w.meta_key LIKE '$meta_key' and w.meta_value != ''
  ) GROUP BY t.name ORDER BY t.name ASC ");
?>

<div id="dist_category" style="display:none">
	<h3>Filter by Category</h3>
	<select onChange="window.location.href=this.value" class="distCategory"> 
		<option></option>
		<?php 
		foreach ($pdt as $key => $category) { ?>
		    <option value='<?php echo home_url("/vendor/$distributor/?category={$category->slug}&composition=$pa_composition&manufacturer=$pa_manufacturer")?>' <?php echo ($dist_category == $category->slug)? 'selected':'' ?> > <?php echo "{$category->name} ({$category->no_of_pdts})"; ?> </option>
		<?php 
		}?>
	</select><br/>
	<h3>Filter by Composition</h3>
	<select onChange="window.location.href=this.value" class="distComposition"> 
		<option></option>
		<?php 
		foreach ($pdt_comp as $key => $composition) { ?>
		    <option value='<?php echo home_url("/vendor/$distributor/?category=$dist_category&composition={$composition->slug}&manufacturer=$pa_manufacturer")?>' <?php echo ($pa_composition == $composition->slug)? 'selected':'' ?> > <?php echo "{$composition->name} ({$composition->no_of_pdts})"; ?> </option>
		<?php 
		}?>
	</select><br/>
	<h3>Filter by Manufacturer</h3>
	<select onChange="window.location.href=this.value" class="distManufacturer">
		<option></option> 
		<?php 
		foreach ($pdt_manuf as $key => $manufacturer) { ?>
		    <option value='<?php echo home_url("/vendor/$distributor/?category=$dist_category&composition=$pa_composition&manufacturer={$manufacturer->slug}")?>' <?php echo ($pa_manufacturer == $manufacturer->slug)? 'selected':'' ?> > <?php echo "{$manufacturer->name} ({$manufacturer->no_of_pdts})"; ?> </option>
		<?php 
		}?>
	</select>
<?php if($dist_category != "" || $pa_composition!="" || $pa_manufacturer!=""){?>
		<p align="center"><a href='<?php echo home_url("/vendor/$distributor/");?>' > Reset Filters </a></p>
	<?php }?></div>

<?php
get_header('shop');  
/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
$user_args = array( 
    'role'	   => 'shop_manager',
    'meta_key'     => 'primary_distributor',
    'meta_value'   => $meta_key,
    'meta_compare' => '=',
    'number'	   => '1'
);
$user = current(get_users($user_args));  


// Create Variables for info box
$user_img_url = get_cupp_meta(20, 'thumbnail'); 
$address = get_user_meta($user->ID, 'billing_address_1', true).",".get_user_meta($user->ID, 'billing_address_2', true).",".get_user_meta($user->ID, 'billing_city', true).",".get_user_meta($user->ID, 'billing_state', true);
$institution = get_user_meta($user->ID, 'institution', true);
$user_img_url = get_cupp_meta($user->ID, 'thumbnail');?>

<div style="text-align:center;"><h2>Welcome to <?php echo $institution;?></h2></div>

<?php
flexmap_show_map(array( 
  'center' => get_user_meta($user->ID, 'gmap_coords', true), // Lat and Long
  'address' => $address,
  'title' => $institution, 
  'icon' => 'http://epilogger.com/Public/images/icons/icon-verified.png',
  'html' => '<div style="width:300px"><img style="float:right;" src="'.$user_img_url.'" width="100px"/><div><b style="color:#2883f9">(Drugstoc Verified)</b><br/>'.str_replace(",",",<br/>",$address).'<b><br/>email</b>: '.$user->user_email.'<br/></div></div>',
  'zoom' => '14',
  'width' => "100%",
  'height' => "500" 
));
?> 

<!--
<div class="row1" style="width: 80%; margin-left: auto; margin-right: auto;padding-bottom: 50px;border-bottom: 2px solid #2883F9;"> 
	<h2><?php echo get_user_meta($user->ID, 'institution', true);?></h2>
	<br> 
	<img src="http://drugstoc.biz/wp-content/themes/sistina/images/nhc_logo.png" width="200px"/>
	<span id="distributor_header">
		<br> 
		<p><b>Address: </b> <br>2nd Floor Acme House,<br>23 Acme Road Ogba <br> Ikeja, Lagos</p>   
		<h5 id="distributor_header_verify">
			Verified Distributor &nbsp;
			<img src="http://drugstoc.biz/wp-content/themes/sistina/images/dsverified1.png" width="60px">
		</h5> 
	</span> 
</div>//-->
 <?php
do_action('woocommerce_before_main_content');  ?> 

	<?php do_action( 'woocommerce_archive_description' ); 
  
    if ( have_posts() ) :

			/**
			 * woocommerce_before_shop_loop hook
			 *
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			do_action( 'woocommerce_before_shop_loop' );
		

			woocommerce_product_loop_start(); 

			woocommerce_product_subcategories();

			while ( have_posts() ) : the_post(); 

				wc_get_template_part( 'content', 'product' ); 

			endwhile; // end of the loop. 

			woocommerce_product_loop_end();

			/**
			 * woocommerce_after_shop_loop hook
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			do_action( 'woocommerce_after_shop_loop' );
		 
		 elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

        <p><?php _e( 'No products found which match your selection.', 'yit' ); ?></p>
        
         <?php do_shortcode('[gdym_didyoumean]');
         

	endif;
 
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

get_footer('shop'); ?>
<style>
span#distributor_header{
	float: right; 
	width: 75%;
	margin-top: -89px;
}

h5#distributor_header_verify {
  	float: right; 
  	color: #2883F9; 
  	margin-top: -30px; 
  	margin-right: 12%; 
  	font-size: 24px; 
} 

/*@media only screen and (min-device-width : 325px) and (max-device-width : 667px) { /* STYLES GO HERE */}*/ 

#dist_category{
	width: 90%;
} 
.distComposition, .distCategory, .distManufacturer{
	width: 100%;
}

@media only screen and (min-device-width : 320px) and (max-device-width : 768px) {
  /* Styles */
  span#distributor_header {
	width: 100%;  
	padding-top: 120px;
  }  
  h5#distributor_header_verify { 
  	margin-top: 0px; 
  	margin-right: 0px;   
  }  
} 

</style>
<script type="text/javascript">  
	jQuery(".page-title").html("All Products by <?php echo strtoupper($distributor)?>");
	//jQuery("#sidebar-shop-sidebar").after('<br><a href="http://www.drugstoc.biz/product-category/anti-bacterials/">Anti Bacterials</a>');
	jQuery("#sidebar-shop-sidebar").append(jQuery("#dist_category").show());
	// jQuery("#sidebar-shop-sidebar").hide();
	jQuery(".distCategory, .distComposition, .distManufacturer").chosen({no_results_text: "Oops, nothing found!"});  
</script>