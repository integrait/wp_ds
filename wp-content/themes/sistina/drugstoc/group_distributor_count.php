<?php 

//require_once( $_SERVER['DOCUMENT_ROOT'] . '/drugstoc_2/wp-config.php' );
//require_once( $_SERVER['DOCUMENT_ROOT'] . '/drugstoc_2/wp-includes/wp-db.php' );
 

function get_Group_Distributor(){
	// header('Content-Type: application/json');
	global $wpdb;

		
		
	//$drug_partial_name = $searchParam;


	$drug_partial_name = $_REQUEST['drug_name']; 



// 	$get_Group_Distributor_count = $wpdb->get_results("SELECT count(p.id) as total_result, m1.meta_key, p.post_title
//   FROM wp_posts p
//   LEFT
//   JOIN wp_postmeta m1
//     ON m1.post_id = p.id 
//  WHERE p.post_type = 'product'
//  and post_status = 'Publish' 
//  and m1.meta_key = 'nhc_price' and post_title like '%".$drug_partial_name."%' or m1.meta_key = 'elfimo_price' and post_title like  '%".$drug_partial_name."%'
// group by m1.meta_key ");


$get_Group_Distributor_count = $wpdb->get_results("SELECT count(p.id) as total_result,(select meta_value from wp_usermeta where user_id = (select user_id from wp_usermeta where meta_key ='primary_distributor' and meta_value = m1.meta_key) and meta_key = 'institution') as Distributor, m1.meta_key, p.post_title
  FROM wp_posts p
  LEFT
  JOIN wp_postmeta m1
    ON m1.post_id = p.id 
 WHERE p.post_type = 'product'
 and p.post_status = 'publish' 
 and m1.meta_key = 'nhc_price' and post_title like '%".$drug_partial_name."%' or m1.meta_key = 'elfimo_price' and post_title like '%".$drug_partial_name."%'

group by m1.meta_key ");
 
 


//$get_Group_Distributor_count = $wpdb->get_results("SELECT count(id) from wp_posts ");



	header('Content-Type: application/json');
	echo json_encode($get_Group_Distributor_count);
	die();
}


add_action( 'wp_ajax_get_Group_Distributor_json', 'get_Group_Distributor');
add_action( 'wp_ajax_nopriv_get_Group_Distributor_json', 'get_Group_Distributor');

?>