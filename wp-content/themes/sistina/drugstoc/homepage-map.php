<?php 
//require_once( $_SERVER['DOCUMENT_ROOT'] . '/drugstoc_2/wp-config.php' );
//require_once( $_SERVER['DOCUMENT_ROOT'] . '/drugstoc_2/wp-includes/wp-db.php' );

function add_custom_drugstoc_map_style() {  



$url_frag = explode('/', $_SERVER['REQUEST_URI']);

  if (is_front_page()) { 

  	 wp_enqueue_script( 'home-page_map', get_template_directory_uri() . '/js/homepage-map.js' );
     wp_enqueue_style( 'homepage-css', get_template_directory_uri() . '/css/homepage-map.css' );
       

  }else if (in_array('vendor', $url_frag) || in_array('pharma', $url_frag) || in_array('m', $url_frag) || in_array('manufacturer', $url_frag)) {

  	wp_enqueue_script( 'single-map', get_template_directory_uri() . '/js/single-map.js' );

  }else if (is_search()) {// for search page
  
  	wp_enqueue_script( 'search-page-map', get_template_directory_uri() . '/js/search-page-map.js' );
  	wp_enqueue_script( 'Group-Search-Count', get_template_directory_uri() . '/js/group_distributor_count-js.js' );

  }
  else if (is_page()) {// for manufacturer, pharmacy index page
  	wp_enqueue_script( 'home-page_map', get_template_directory_uri() . '/js/homepage-map.js' );
  }

      
}
add_action( 'wp_enqueue_scripts', 'add_custom_drugstoc_map_style' );









function getHomepageMap(){
	// header('Content-Type: application/json');
	global $wpdb;

	$my_user_type = $_REQUEST['my_user_type'];

		
		//$get_all_supplier = $my_user_type;
		
	//$drug_partial_name = $searchParam;

	
	//$get_all_supplier = $wpdb->get_results("SELECT w.user_id,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'user_type') as user_type,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'address') as address , (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'gmap_coords') as gmap_coords ,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'institution') as institution,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as state, u.* FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = '$my_user_type' ");

	

	if ($my_user_type == "All" or $my_user_type == ""){
	$get_all_supplier = $wpdb->get_results("SELECT w.user_id,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'user_type') as user_type,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'address') as address , (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'gmap_coords') as gmap_coords ,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'institution') as institution,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as state, u.* FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' ");
	}

	if($my_user_type == "Distributor" or $my_user_type == "Wholesale"){
		$get_all_supplier = $wpdb->get_results("SELECT w.user_id,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'user_type') as user_type,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'address') as address , (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'gmap_coords') as gmap_coords ,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'institution') as institution,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as state, u.* FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = 'Distributor' or w.meta_value = 'Wholesale' ");
	} 

	if($my_user_type == "Importer" or $my_user_type == "Manufacturer"){
		$get_all_supplier = $wpdb->get_results("SELECT w.user_id,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'user_type') as user_type,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'address') as address , (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'gmap_coords') as gmap_coords ,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'institution') as institution,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as state, u.* FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = 'Manufacturer'  or w.meta_value = 'Importer' ");
	} 

	if($my_user_type == "Pharmacy"){
		$get_all_supplier = $wpdb->get_results("SELECT w.user_id,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'user_type') as user_type,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'address') as address , (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'gmap_coords') as gmap_coords ,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'institution') as institution,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as state, u.* FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = 'Pharmacy' ");
	}


	// else{

	// 	$get_all_supplier = $wpdb->get_results("SELECT w.user_id,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'user_type') as user_type,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'address') as address , (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'gmap_coords') as gmap_coords ,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'institution') as institution,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as state, u.* FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = '$my_user_type' ");
	// }


	header('Content-Type: application/json');
	echo json_encode($get_all_supplier);
	//echo "Hello";
	//die();
	exit;
}


add_action( 'wp_ajax_getHomepageMap_json', 'getHomepageMap');
add_action( 'wp_ajax_nopriv_getHomepageMap_json', 'getHomepageMap');

 
//show all map
function show_homepage_map_(){

if(is_page() || is_front_page()){
	?>

<div id="show_homepage_map" class="show_homepage_map" style="height: 400px;width:100%;margin-top: -78px;" >
<?}else
{
?>


<div id="show_homepage_map" class="show_homepage_map" style="height: 400px;width:100%;" >

<?php
}?>
    
</div>

<?php
}
add_shortcode( 'show_homepage_map', 'show_homepage_map_'); 


//update manufacture cordinates
function updateDistributorCordinates(){

//	global $wpdb;

	$user_id = $_REQUEST['user_id'];
	$map_coordinates = $_REQUEST['coordinates'];

	$response = update_user_meta($user_id,'gmap_coords', $map_coordinates); 
	$geo_map =  get_user_meta($user_id, 'gmap_coords',true);
	echo "result : ". $geo_map;
	exit;
}


add_action( 'wp_ajax_updateDistributorCordinates_json', 'updateDistributorCordinates');
add_action( 'wp_ajax_nopriv_updateDistributorCordinates_json', 'updateDistributorCordinates');


function add_map_cordinates_to_user(){

global $wpdb;


// The Query
//$user_query = new WP_User_Query();

$user_query = $wpdb->get_results("SELECT * from wp_users");

// User Loop

			 foreach ($user_query as $user) {
            
            	$user_id = $user->ID;

            	$geo_map =  get_user_meta($user_id, 'gmap_coords',true);

	            // if($geo_map == ""){

	            // var_dump($user->ID.'<br/>');
	             add_user_meta($user_id, 'gmap_coords', '', true);
	            // var_dump($user->ID. "  --  success "  .'<br/>');
	            // 	continue;
	            // }
           	}
}

//add_action('init','add_map_cordinates_to_user');



//show all map
function show_user_status_(){
?>
<div id="show_user_status_activities" class="show_user_status_activities" style="height: auto;" style="">
       


	<div id="shiva"><span class="count">125</span></br>Manufacturers</div>
	<div id="shiva"><span class="count">23</span></br>Distributors</div>
	<div id="shiva"><span class="count">443</span></br>Importers</div>
	<div id="shiva"><span class="count">564</span></br>Pharmacies</div>
	<div id="shiva"><span class="count">195</span></br>Wholesalers</div>
	<div id="shiva"><span class="count">2000</span></br>Hospitals</div>
	<!-- <div style="clear:both"></div>
	<div id="talkbubble"><span class="count">1421</span></div>
	<div id="talkbubble"><span class="count">145</span></div>
	<div id="talkbubble"><span class="count">78</span></div>
	<br />
	<br />
	<a href="www.i-visionblog.com">visit tutorial</a>
	<br /> -->

	<div style="clear:both; height:30px;"></div>




</div>

<?php
}
add_shortcode( 'show_user_status', 'show_user_status_'); 




?>