<?php 
include("ds-mysqli.php");

//Instantiate custom DB class
$drugstoc = new DrugStocDB;

// Process USERNAME and PASSWORD
$username = isset($_POST['username'])? $drugstoc->escape(strip_tags( trim( $_POST['username'] ) ) ): '';
$password = isset($_POST['password'])? trim( $_POST['password'] ): '';
$token = isset($_POST['auth_token'])? trim( $_POST['auth_token'] ):'';

// // JSON Responses ONLY
// Validate Token
if($token != $drugstoc->auth_token){
	header( "Content-Type: application/json" ); 
	echo json_encode(array('error_msg' => 'Invalid Request', 'code' => 1));
	exit;
}
/*
if( $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
	// Process only ajax requests
 	echo json_encode(array('error_msg' => 'Invalid HTTP Request', 'code' => 2));
	exit;		
}*/

// Fetch User Password and ID
$rs = $drugstoc->exec("SELECT ID, user_pass FROM wp_users WHERE user_email = '$username' LIMIT 1");
$results = $rs->fetch_assoc();

// No Result - Invalid Username
if(count($results) < 1){
	header( "Content-Type: application/json" );
	echo json_encode( array('error_msg' => 'Invalid Username'));
	exit;
}

// Compare Password hashes
if($drugstoc->check_password($password, $results['user_pass'])){
	 
	$rs = $drugstoc->exec("SELECT ID, user_login, user_email, (SELECT meta_value FROM wp_usermeta WHERE meta_key='primary_distributor' and user_id={$results['ID']}) as 'distributor_key', (SELECT meta_value FROM wp_usermeta WHERE meta_key='woocommerce_api_consumer_key' and user_id={$results['ID']}) as 'key', (select meta_value from wp_usermeta where meta_key='woocommerce_api_consumer_secret' and user_id={$results['ID']}) as 'secret' FROM wp_users where ID = {$results['ID']} LIMIT 1;");
	$results = $rs->fetch_assoc();  
} else { 
	$results = array('error_msg' => 'Invalid Password');
}

header( "Content-Type: application/json" );
echo json_encode($results);
exit;