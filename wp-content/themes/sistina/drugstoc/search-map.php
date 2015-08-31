<?php 
 
//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );


function get_Seacrh_Map(){
	// header('Content-Type: application/json');
	global $wpdb;

		
		
	//$drug_partial_name = $searchParam;


	$drug_partial_name = $_REQUEST['search_parameter']; 

	$get_all_drugs = $wpdb->get_results("SELECT w.*,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'gmap_coords') as gmap_coords,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'institution') as institution, (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'billing_address_1') as billing_address_1, u.* FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'primary_distributor' and w.meta_value!='' and w.user_id IN (
	SELECT user_id from wp_usermeta where meta_key = 'primary_distributor' and meta_value IN (SELECT meta_key from wp_postmeta where post_id in (SELECT id FROM `wp_posts` WHERE post_type = 'product' and post_title like '%".$drug_partial_name."%') and meta_key in ('nhc_price' , 'elfimo_price') and not meta_value = '' )
	)");

	//$get_all_drugs = $wpdb->get_results("SELECT * from wp_users");

	//var_dump($get_all_drugs);

	//$json_result = json_encode($get_all_drugs);
	$json_result = $get_all_drugs;

	header('Content-Type: application/json');
	echo json_encode($get_all_drugs);
	//echo "Hello";
	die();
}

//function get_Seacrh_Map2(){
//	echo "stringy";
//	exit;
//}
add_action( 'wp_ajax_getSeacrhMap_json', 'get_Seacrh_Map');
add_action( 'wp_ajax_nopriv_getSeacrhMap_json', 'get_Seacrh_Map');

?>