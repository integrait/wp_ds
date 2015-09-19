<?php
/**
 * Template Name: Manufacturer Profile Page
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// get the style
global $woocommerce_loop, $blog_id, $wp_query, $wpdb;

$cookie_shop_view = 'yit_' . get_template() . ( is_multisite() ? '_' . $blog_id : '' ) . '_shop_view';
$woocommerce_loop['view'] = isset( $_COOKIE[ $cookie_shop_view ] ) ? $_COOKIE[ $cookie_shop_view ] : yit_get_option( 'shop-view', 'grid' );

$manu_slug = $wp_query->query_vars['maker'];

$url = explode("/", $_SERVER['REQUEST_URI']);
$manu_slug = in_array('m', $url)? $url[array_search('m', $url) + 1]: '';
$manu_slug = trim($manu_slug);

// Filters
$dist_category   = isset($_GET['category'])? esc_attr(sanitize_text_field($_GET['category'])):"";
$pa_composition  = isset($_GET['composition'])? esc_attr(sanitize_text_field($_GET['composition'])):"";
$keyword 	 = isset($_GET['ds'])? esc_attr(sanitize_text_field($_GET['ds'])):""; // << Search Keyword
$orderby 	 = isset($_GET['orderby'])? esc_attr(sanitize_text_field($_GET['orderby'])):"";

$paged = explode("/", $_SERVER['REQUEST_URI']);
$page_num = is_numeric($paged[ array_search('page', $paged) + 1 ])? $paged[ array_search('page', $paged) + 1 ]: 1;

// Get Manufacturer Details
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
$user_img_url = get_cupp_meta($user->ID, 'thumbnail');
$address = get_user_meta($user->ID, 'billing_address_1', true).",<br/>".get_user_meta($user->ID, 'billing_address_2', true).",<br/>".get_user_meta($user->ID, 'billing_city', true).",<br/>".get_user_meta($user->ID, 'billing_state', true);
$institution = get_user_meta($user->ID, 'institution', true);

//Start Javascript Google Map
$j_institution = get_user_meta($user->ID, 'institution', true);
$j_address = get_user_meta($user->ID, 'billing_address_1', true);// . "  " . get_user_meta($user->ID, 'billing_city', true);
$j_state = get_user_meta($user->ID, 'billing_state', true);
$j_email = $user->user_email;

$gmap  = get_user_meta($user->ID, 'gmap_coords', true);
$map_coords = explode(",", $gmap);
$gmap_lat = $map_coords[0];
$gmap_lon = $map_coords[1];

// Main Product Query
$args = array(
	'post_type'  => 'product', 
	'ignore_sticky_posts' => 1,
	'posts_per_page' => 9,
	'paged'		 => $page_num,
	'orderby'	 => 'date',
	'meta_query' => array(
		'key'    => '_visibility',
		'value'  => array('visible'),
		'compare'=> 'IN'
	),
	'tax_query'	 => array(
		'taxonomy' => 'pa_manufacturer',
		'field'    => 'slug',
		'terms'    => $manu_slug
	),
);

if(DS_Util::get_manufacturer_pdts($manu_slug) != null){
	$ids = DS_Util::get_manufacturer_pdts($manu_slug);
	if($ids != null) $args['post__in'] = DS_Util::get_manufacturer_pdts($manu_slug);  
}

$subtitle = "All products by $institution";
$search_param = '';
// Add Keyword to args
if ($keyword != "") {
	$args['s'] = $keyword;
	$subtitle = "You searched for '<i>$keyword</i>'" ;
	$search_param = "&ds=$keyword";
}

// Product Filters
if($dist_category != "" && $pa_composition != "" ){
	$args['tax_query'] = array(
		"relation" => "AND",
		array(
			'taxonomy' => 'pa_manufacturer',
			'field'    => 'slug',
			'terms'    => $manu_slug
		),
		array(
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
		));
	}else if($dist_category != "" && $pa_composition == ""){
		$args['tax_query'] =  array(
			"relation" => "AND",
			array(
				'taxonomy' => 'pa_manufacturer',
				'field'    => 'slug',
				'terms'    => $manu_slug
			),
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $dist_category
			)
		);
	}else if($dist_category == "" && $pa_composition != ""){
		$args['tax_query'][] = array(
			"relation" => "AND",
			array(
				'taxonomy' => 'pa_manufacturer',
				'field'    => 'slug',
				'terms'    => $manu_slug
			),
			array(
				'taxonomy' => 'pa_composition',
				'field'    => 'slug',
				'terms'    => $pa_composition
			));
}

//*********************
// Sort by Functions 
//*********************
if($orderby != ""){ 
	if($orderby == "price"){ // By Price Low to High
		$args['meta_query'][] = array(
		    array(
		        'key'         => '_price',
		        'value'       => 0,
		        'compare'     => '>',
		        'type'        => 'NUMERIC'
		    )
		);
		$args['orderby']  = 'meta_value_num';
		$args['order'] 	  = 'ASC';  
 	}elseif ($orderby == "price-desc") { // By Price High to Low
		$args['meta_query'][] = array(
		    array(
		        'key'         => '_price',
		        'value'       => 0,
		        'compare'     => '>',
		        'type'        => 'NUMERIC'
		    )
		);
		$args['orderby']  = 'meta_value_num';
		$args['order'] 	  = 'DESC';
 	}elseif ($orderby == "popularity") { // By Popularity
		$args['meta_query'][] = array(
		    array(
		        'key'         => 'total_sales',
		        'value'       => 0,
		        'compare'     => '>',
		        'type'        => 'NUMERIC'
		    )
		);
		$args['orderby']  = 'meta_value_num';
		$args['order'] 	  = 'DESC';
 	}elseif ($orderby == "date") { // By Newest
		$args['orderby']  = 'date';
		$args['order'] 	  = 'DESC';
 	}else{  // Default
 		$args['orderby']  = 'menu_order';
 	} 
}

query_posts( $args );

// Filtered IDs
$post_ids = join(',', wp_list_pluck( $wp_query->posts, 'ID' ) );

// Product Categories
// Get all products with set pa-taxonomy
$pdt = $wpdb->get_results("SELECT t.name, t.slug, count(t.term_id) as no_of_pdts FROM wp_terms as t
  INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id
  INNER JOIN wp_term_relationships as wtr on wtr.term_taxonomy_id = p.term_taxonomy_id
  WHERE p.taxonomy LIKE 'product_cat' and wtr.object_id IN (
	$post_ids
	-- SELECT p.ID FROM wp_posts as p INNER JOIN wp_postmeta as w on p.ID = w.post_id WHERE p.post_status='publish' AND w.meta_key LIKE '$meta_key' AND w.meta_value != '' 
  ) GROUP BY t.name ORDER BY no_of_pdts DESC ");

// Product Composition
$pdt_comp = $wpdb->get_results("SELECT t.name, t.slug, count(t.term_id) as no_of_pdts FROM wp_terms as t
  INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id
  INNER JOIN wp_term_relationships as wtr on wtr.term_taxonomy_id = p.term_taxonomy_id
  WHERE p.taxonomy LIKE 'pa_composition' and wtr.object_id IN ($post_ids) GROUP BY t.name ORDER BY t.name ASC ");
?>
<div id="dist_category" style="display:none">  
	<h3 style="margin-bottom:0">Search</h3>
	<p style="margin-top: 0"><?php echo $institution?> Products</p>
	<form method="get" class="search_mini">
		<input type="text" name="ds" id="search_mini" value="<?php echo $keyword;?>" title="Search all <?php echo $institution;?> products" alt="Search <?php echo $institution;?> products" placeholder="<?php if( is_shop_installed() ): _e( "Search", 'yit' ); else: _e( 'search for...', 'yit' ); endif ?>" />
		<input type="submit" value="Search" title="Search all <?php echo $institution;?> products" alt="Search all <?php echo $institution;?> products" style="height: 38px; margin-top: 0; float: right; background-color: rgba(255, 151, 0, 0.75); border-radius: 7px; border: solid 1px #FF9700; color: #fff;"/>
	</form>
	<h3>Categories</h3>
	<div style="max-height: 600px;overflow-y: auto;">
		<ul class="distCategory">
			<?php
			foreach ($pdt as $key => $category) { ?>
			    <li><a href='<?php echo home_url("/m/$manu_slug/?category={$category->slug}&composition=$pa_composition&manufacturer=$pa_manufacturer$search_param")?>' style="cursor: pointer;"> <?php echo "{$category->name} ({$category->no_of_pdts})"; ?> </a></li>
			<?php
			}?>
		</ul>
	</div>
	<br/>
	<h3>Filter by Composition</h3>
	<select onChange="window.location.href=this.value" class="distComposition">
		<option></option>
		<?php
		foreach ($pdt_comp as $key => $composition) { ?>
		    <option value='<?php echo home_url("/m/$manu_slug/?category=$dist_category&composition={$composition->slug}$search_param")?>' <?php echo ($pa_composition == $composition->slug)? 'selected':'' ?> > <?php echo "{$composition->name} ({$composition->no_of_pdts})"; ?> </option>
		<?php
		}?>
	</select><br/>
	<?php if($dist_category != "" || $pa_composition!=""){?>
		<p align="center"><a href='<?php echo home_url("/m/$manu_slug/");?>' > Reset Filters </a></p>
	<?php }
	// Distributors
 	if($manu_slug != ""){?>
	<h3 style="margin-bottom:0">Distributors</h3>
		<br/>
		<ul class="distCategory">  
	    <?php
		    $distributors = explode(",", get_user_meta($user->ID,'distributor_list', true) );
	   		foreach ($distributors as $key => $dist) { ?> 
      		<li>
      			<a href="<?php echo home_url('/vendor/'.substr($dist, count($dist)-1, -6)."/?manufacturer={$manu_slug}");?>" style="color:black">
	      			<?php echo DS_Util::getDistributorNamebyKey($dist);?>
	      		</a>
      		</li> 
		    <?php
 			}?>
		</ul> 
	<?php
	}?>
</div>

<?php
get_header('shop');
/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
?>

<div class="container group">
    <div class="map-user-profile row">
        <div class="user-info-cn span3">
            <p class="user-avi">
            	<span class="round-cn-logo">
	                <img src="<?php echo $user_img_url; ?>"/>
            	</span>
            </p>
            <h3><?php echo $institution; ?></h3>
            <p class="user-status-badge">  <img src="<?php echo home_url('/'); ?>wp-content/themes/sistina/images/mfct-icon.png"><span>Verified Manufacturer</span></p><br>
            <?php include('drugstoc/includes/user-meta.php'); ?>
            <!-- <dl>
                <dt><?php //echo $address; ?></dt>
            </dl> -->
        </div>
        <div class="map-info-cn span9">
        	<ul class="nav nav-pills">
        		<li class="active">
					<a aria-controls="map" role="tab" data-toggle="tab">Map</a>
        		</li>
        		<!--<li>
					<a aria-controls="about" role="tab" data-toggle="tab">About</a>
        		</li>
        		<li>
					<a href="" aria-controls="about" role="tab" data-toggle="tab">Contact Us</a>
        		</li> -->
        	</ul>
        	<div id="tab-about" class="tab-pane active">
        		This is it.
        	</div>
        	<div id="tab-map" class="tab-pane active">
	            <?php
	            	do_shortcode('[show_homepage_map]');
	            	echo "<script> draw_single_map('$institution', '$j_address','$j_state','$j_email' ,'$gmap_lat', '$gmap_lon', 'Manufacturer'); </script>";
	            ?>
        	</div>
        </div>
    </div>
</div>
<?php do_action('woocommerce_before_main_content'); ?>
	<?php do_action( 'woocommerce_archive_description' );

    if ( is_user_logged_in() ):
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

				while ( have_posts() ) :  the_post();

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
	else :?>
		<p><?php _e( 'You must be logged in to view this section.', 'yit' ); ?></p>
	<?php
	endif;?>

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
	jQuery(".page-title").html("<?php echo $subtitle;?>");
	//jQuery("#sidebar-shop-sidebar").after('<br><a href="http://www.drugstoc.biz/product-category/anti-bacterials/">Anti Bacterials</a>');
	jQuery("#sidebar-shop-sidebar").append(jQuery("#dist_category").show());
	jQuery("#dist_manufacturer").show().insertAfter(".container .group");
	// jQuery("#sidebar-shop-sidebar").hide();
	jQuery(".distComposition, .distManufacturer").chosen({no_results_text: "Oops, nothing found!"});

</script>


