<?php
  //global $sms,$apg_sms;

function sendSMS($to, $message){
  // Twilio api library
  require_once('twilio-php/Services/Twilio.php');

  // Twilio Account Details
  $AccountSid = "ACee9332ba9fa2eb4becb6eb25e8a9f1eb";
  $AuthToken = "bda3517430f468419934d1ea054675a8";

  $client = new Services_Twilio($AccountSid, $AuthToken);

  if(isset($to)){
   // validate and sanitize number
    try{
      $sms = $client->account->messages->sendMessage("+12086960938", $to, $message);
      return true;
    }catch(Services_Twilio_RestException $e){
      return false;
    }
  }else{
    wc_print_notice("Recipient's Number not available");
    return false;
  }
}

function sendMail($to, $subject, $message){

  // Set HTML Headers for mail
  $headers  = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
  $headers .= 'From: DrugStoc <mailer@drugstoc.ng>'."\r\n";   
  
  $message .= '<p>Tel: +2348096879999<br/>Email: info@drugstoc.com</p>
    <p><img src="http://drugstoc.biz/wp-content/uploads/2014/10/splash-logo-beta.png"/></p>
    <div style="text-align:center; border-top:1px solid #eee;padding:5px 0 0 0;" id="email_footer">
      <small style="font-size:11px; color:#999; line-height:14px;">
        You have received this email because you are a member of <?php echo $site_title; ?>.
        <br/>Please do not reply to this email. This mailbox is not monitored and you will not receive a response.
        <br/><b>Copyright © '.date('Y').' DrugStoc. All rights reserved.</b>
      </small>
    </div>';
  	
  // Send the mail
  $mail = mail( $to, $subject, $message, $headers);
}

// Remove the comments admin menu
function remove_menus(){
  $user = wp_get_current_user();
  $allowed_roles = array('editor', 'administrator', 'author');
  if( !array_intersect($allowed_roles, $user->roles ) ) {  
   //stuff here for allowed roles
    remove_menu_page( 'index.php' );
  }   
  remove_menu_page( 'edit-comments.php' );          //Comments  
}
add_action( 'admin_menu', 'remove_menus' );

/*
Plugin Name: Redistributor Menu
Description: Drugstoc Redistributor
*/ 

function wooc_extra_register_fields( ) { 

  //lets make the field required so that i can show you how to validate it later;
  ?>
  <p class="form-row form-row-first">

      <label for="reg_institution"><?php _e( 'Name of Institution', 'yit' ); ?> <span class="required">*</span></label>

      <input type="text" class="input-text typeahead" name="institution" id="reg_institution" value="<?php if (isset($_POST['institution'])) echo esc_attr($_POST['institution']); ?>" />

  </p>

  <p class="form-row form-row-last">

        <label for="reg_usertype"><?php _e( 'Register as:', 'yit' ); ?> <span class="required">*</span></label>

        <select name="usertype" id="reg_usertype">

            <option value="pharmacy" >Pharmacy</option>

            <option value="clinic">Clinic</option>

            <option value="hospital" selected="selected">Hospital</option>

	    <option value="Doctor">Doctor</option>
        </select>

    </p>

  <p class="form-row form-row-wide">

        <label for="phonenumber">
          <?php _e( 'Phone Number', 'yit' ); ?> <span class="required">*</span>
          <span style="font-size:10px; font-style:italics: float: left">Important: Your Phone Number is needed for SMS Notification.</span>
          <span style="font-size:10px; font-style:italics: float: left">Without leading 0 eg. +2348123456789</span>
        </label>
        <input type="text" class="input-text " name="phonenumber" id="billing_phone" placeholder="" value="+234">
    </p>
  <p class="form-row form-row-first"> 
    <label for="referral_code">
      <?php _e( 'Referral Code', 'yit' ); ?> 
    </label>
    <input type="text" class="input-text " name="referral_code" id="referral_code" placeholder="" value="">
  </p>
   <?php  
}

/**a
 * @param WP_Error $reg_errors
 *
 * @return WP_Error
 */
function registration_errors_validation( $reg_errors ) {

  if ( empty( $_POST['institution'] ) || empty( $_POST['phonenumber'] ) || empty( $_POST['usertype'] ) ) {
    $reg_errors->add( 'empty required fields', __( 'Please fill in the required fields.', 'woocommerce' ) );
  }
  return $reg_errors;

}

function adding_extra_reg_fields($user_id) {

  extract($_POST);
 
  update_user_meta($user_id, 'institution', esc_attr($_POST['institution']));
  update_user_meta($user_id, 'billing_company', esc_attr($_POST['institution']));
  update_user_meta($user_id, 'shipping_company', esc_attr($_POST['institution']));
  update_user_meta($user_id, 'phonenumber', esc_attr($_POST['phonenumber']));
  update_user_meta($user_id, 'usertype', esc_attr($_POST['usertype']));
  update_user_meta($user_id, 'ds_premium_user', 0);
  update_user_meta($user_id, 'has_to_be_activated', sha1( $user_id . time()), true);
  // Save referral code if any
  update_user_meta($user_id, 'ds_referral_code', strtoupper(esc_attr($_POST['referral_code'])));

  $id = array( 'ID' => $user_id, 'display_name' => esc_attr($_POST['institution']) );

  $user = new WP_User($user_id);
  $user->set_role('ds_unverified');

  $message = "Hi ".ucfirst(esc_attr($_POST['institution'])).", \n\nThanks for creating an account on DrugStoc. Your account will be held for moderation and you will be unable to login until it is approved.\n\nThank you, \nDrugStoc Team.";

  sendSMS(esc_attr($_POST['phonenumber']), $message); 

  $message = '<div> 
    <p>Hi '.$institution.', <br/><br/></p>
    <p>Thanks for creating an account on DrugStoc. </p>
    <p>Your account will be held for moderation and you will be unable to login until it is approved.</p>
    <p><br/>Thank you,<br/>DrugStoc Team.</p> 
  </div>';

  // Send the mail
  sendMail($user->user_email, "Welcome to DrugStoc", $message);
  
  // Notify Admin
  sendMail(get_option("admin_email"), "DrugStoc - New User Notification", "New Customer: {$_POST['institution']}\n Phone Number: {$_POST['phonenumber']}, Customer Type: {$_POST['usertype']}"); 
} 	
 
/**
 * Proper way to enqueue scripts and styles
 */
function add_custom_drugstoc_style() {    
  wp_enqueue_style( 'additional-css', get_template_directory_uri() . '/css/additionalCss.css' );
  //wp_enqueue_style( 'additional-css', get_template_directory_uri() . '/css/additionalCss.css' , array(), rand(99, 9999));
 
  $url = explode("/", $_SERVER['REQUEST_URI']);
  if(!in_array('product', $url)){
    wp_enqueue_style( 'product_grid', get_template_directory_uri() . '/css/product_grid.css' , array(), rand(99, 9999));
  }
  wp_enqueue_style( 'dschosen-css', "//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css");
  wp_enqueue_script('dschosen-js',"//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js", array('jquery'));
  wp_enqueue_style( 'fontastic-store-icon-css', "//fontastic.s3.amazonaws.com/tsFuyMtjQsfYm8KifquYj7/icons.css");
  
}

/**
 * Proper way to enqueue scripts and styles
 */
function add_custom_drugstoc_scripts() { 
  wp_enqueue_script( 'additional-js', get_template_directory_uri() . '/js/drugstoc.js' , array('jquery'));
  wp_localize_script('additional-js', 'ds_cart_vars', array(
      'ajaxurl'       => admin_url( 'admin-ajax.php' ),
      'home_url'      => home_url('/'),
      'ds_supp_add_price_nouce' => wp_create_nonce( 'myajax-post-comment-nonce' )
    )
  );
}

add_action( 'wp_enqueue_scripts', 'add_custom_drugstoc_scripts' );
add_action( 'wp_enqueue_scripts', 'add_custom_drugstoc_style' );


//Adding Registration fields to the form
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

//Validation registration form  after submission using the filter registration_errors
add_filter( 'woocommerce_registration_errors', 'registration_errors_validation' );

//Updating use meta after registration successful registration
add_action('woocommerce_created_customer','adding_extra_reg_fields');

function user_autologout(){
   if ( is_user_logged_in() ) {
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;
      $approved_status = get_user_meta($user_id, 'wp-approve-user', true);
      //if the user hasn't been approved yet by WP Approve User plugin, destroy the cookie to kill the session and log them out
      if ( $approved_status == 1 ){
      //if(!current_user_can('ds_unverified')){
          return $redirect_url;
      }else{
      	$redirect_url = get_permalink(woocommerce_get_page_id('myaccount')) . "?approved=false";
      	wp_logout();
      	exit();
      }
    }
} 

add_action('woocommerce_registration_redirect', 'user_autologout', 2);

function registration_message(){
        $not_approved_message = "NOTE: Your account will be held for moderation and you will be unable to login until it is approved.";
        if( isset($_REQUEST['approved']) ){
            $approved = $_REQUEST['approved'];
            if ($approved == 'false'){ 
            	wc_print_notice("Registration successful! You will be notified upon approval of your account. Thanks. ");
            } 
        }
        //else wc_print_notice($not_approved_message, 'notice' );
}
add_action('woocommerce_before_customer_login_form', 'registration_message', 2);

// //Email notification after registration successful registration
// add_filter('wp_mail_content_type', 'set_html_content_type');
// add_action('woocommerce_created_customer','new_user_email_notification');
// add_action('woocommerce_created_customer','set_new_user_unverified');
// add_action('get_header','check_user_status');


function fb_add_custom_user_profile_fields( $user ) {
?>
  <h3><?php _e('DrugStoc Attributes', 'your_textdomain'); ?></h3>

  <table class="form-table">
    <tr>
      <th>
        <label for="primary_distributor"><?php _e('Primary Distributor', 'your_textdomain'); ?>
      </label></th>
      <td>
        <?php  
          global $wpdb;
          $prices = $wpdb->get_results("SELECT DISTINCT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key LIKE 'primary_distributor' AND meta_value != '' ");
          
          $price = array();
          foreach ($prices as $key => $dist_code) { 
            $price[ $dist_code->meta_value ] = __( DS_Util::getDistributorNamebyKey($dist_code->meta_value), 'woocommerce');
          }
          $price[''] = 'None';

          $inprint = get_the_author_meta( 'primary_distributor', $user->ID );
          
          woocommerce_wp_select( 
            array( 
              'id'      => 'primary_distributor',  
              'options' => $price,
              'value'  => ($inprint != "")? $inprint : "",
              'desc_tip' => true,
              'description'   => __( 'Please select your primary distributor', 'woocommerce' ) 
              )
            ); 
        ?>
        <span class="description"><?php _e('Please enter your primary distributor.', 'your_textdomain'); ?></span>
      </td>
    </tr>
    <tr>
      <th>
        <label for="free_user"><?php _e('Customer Status', 'your_textdomain'); ?>
      </label></th>
      <td>
        <?php  
          $inprint = get_the_author_meta( 'ds_premium_user', $user->ID );
          woocommerce_wp_select( 
            array( 
              'id'      => 'free_user',  
              'options' => array(
                '1'   => __( 'Premium', 'woocommerce' ),
                '0'   => __( 'Basic', 'woocommerce' )
                ),
              'value'  => ($inprint == 1)? $inprint : '0',
              'desc_tip' => true,
              'description'   => __( '', 'woocommerce' ) 
              )
            ); 
        ?>
        <span class="description"><?php _e('Basic or Premium ', 'your_textdomain'); ?></span>
      </td>
    </tr> 
    <?php  
      if(!in_array("shop_manager", $user->roles) && !in_array("pharmacy", $user->roles) && !in_array("manufacturer", $user->roles)){ 
    ?>
    <tr>
      <th>
        <label for="referral_code"><?php _e('Referral Code', 'your_textdomain'); ?>
      </label></th>
      <td>
        <input type="text" name="referral_code" id="referral_code" value="<?php echo esc_attr( get_the_author_meta( 'ds_referral_code', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e('Distributor Referral Code', 'your_textdomain'); ?></span>
      </td>
    </tr>
    <?php 
      // For Distributors, Manufacturers and Pharmacies only Get the Coordinates
      }elseif(in_array("manufacturer", $user->roles)){ 
        $distributors = explode(",", get_user_meta($user->ID,'distributor_list', true) ); ?>
        <tr>
          <th>
            <label for="manufacturer_slug"><?php _e('Manufacturer slug', 'your_textdomain'); ?>
          </label></th>
          <td>
            <input type="text" name="manufacturer_slug" id="manufacturer_slug" value="<?php echo esc_attr( get_the_author_meta( 'manufacturer_slug', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Slug associated with Manufacturer on DrugStoc", 'your_textdomain'); ?></span>
          </td>
        </tr>
        <tr>
          <th>
            <label for="distributor_list"><?php _e('Manufacturer Distributor List', 'your_textdomain'); ?></label>
          </th>
          <td>
            <select name="distributor_list[]" id="distributor_list" width="500px" multiple >
              <?php  
              // Loop through all distributors
              foreach ($prices as $key => $dist_code) {
                if(in_array($dist_code->meta_value, $distributors)){?>
                  <option value="<?php echo $dist_code->meta_value?>" selected><?php echo DS_Util::getDistributorNamebyKey($dist_code->meta_value); ?></option>
                <?php }else{ ?>
                  <option value="<?php echo $dist_code->meta_value?>" ><?php echo DS_Util::getDistributorNamebyKey($dist_code->meta_value); ?></option>
          <?php }                   
              }?>
            </select>
            <br>
            <span class="description"><?php _e("List of Manufacturer's distributors", 'your_textdomain'); ?></span>
          </td>
        </tr>
    <?php    
      }?>
    <tr>
      <th>
        <label for="institution"><?php _e('Institution', 'your_textdomain'); ?>
      </label></th>
      <td>
        <input type="text" name="institution" id="institution" value="<?php echo esc_attr( get_the_author_meta( 'institution', $user->ID ) ); ?>" class="regular-text" /><br />
      </td>
    </tr>
    <tr>
      <th>
        <label for="gmap_coords"><?php _e('GMap Coordinates [Lat., Long.]', 'your_textdomain'); ?>
      </label></th>
      <td>
        <input type="text" name="gmap_coords" id="gmap_coords" value="<?php echo esc_attr( get_the_author_meta( 'gmap_coords', $user->ID ) ); ?>" class="regular-text" /><br />
        <br><span class="description"><?php _e("Vendor's Google Map Coordinates e.g 6.34234, 3.123123", 'your_textdomain'); ?></span>
      </td>
    </tr> 
  </table>
  <script type="text/javascript">
  jQuery(document).ready(function(event) {  
    jQuery('select#distributor_list, select#primary_distributor').chosen({ width: "350px" }); 
  });
  </script>
<?php
}

function fb_save_custom_user_profile_fields( $user_id ) { 
  if(isset($_POST['primary_distributor'])) update_usermeta( $user_id, 'primary_distributor', $_POST['primary_distributor'] );
  if(isset($_POST['ds_premium_user'])) update_usermeta( $user_id, 'ds_premium_user', $_POST['free_user'] );
  if(isset($_POST['ds_referral_code'])) update_usermeta( $user_id, 'ds_referral_code', esc_attr(sanitize_text_field($_POST['referral_code'])) );
  if(isset($_POST['gmap_coords'])) update_usermeta( $user_id, 'gmap_coords', esc_attr(sanitize_text_field($_POST['gmap_coords'])) );
  if(isset($_POST['manufacturer_slug'])) update_usermeta( $user_id, 'manufacturer_slug', esc_attr(sanitize_text_field($_POST['manufacturer_slug'])) );
  if(isset($_POST['distributor_list'])) update_usermeta( $user_id, 'distributor_list', join(",", $_POST['distributor_list']) );
  if(isset($_POST['institution'])) update_usermeta( $user_id, 'institution', esc_attr(sanitize_text_field($_POST['institution'])) );
}

add_action( 'show_user_profile', 'fb_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'fb_add_custom_user_profile_fields' );

add_action( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );
 

// add_action( 'init', 'getMyUrlParameter');
function edit_product_link () {
  if (current_user_can('ds_nhc_items')) {
    echo "<p><a href='" . get_permalink() ."?cred-edit-form=11624'>Edit Product - NHC</a></p>";
  }else if (current_user_can('ds_elfimo_items')) {
    echo "<p><a href='" . get_permalink() ."?cred-edit-form=11627'>Edit Product - Elfimo</a></p>";
  }   
  
   $url_parameter = $_GET["s"];
   echo  $url_parameter;
   ?>
 <p><a href='http://drugstoc.biz/report-not-found-drug/?drug_not_found=<?php echo $url_parameter; ?>'>Submit Contact Form  for drugs</a></p>


   <?php
   //echo "<p><a href='htt://www.google.com?drug_not_found='.$url_parameter.'>Submit Contact Form  for drugs</a></p>";

}
add_action('woocommerce_before_single_product', 'edit_product_link');

add_filter('option_woocommerce_enable_sku', function ($value) 
{ 
  if (!is_admin()) 
  { 
    return 'no'; 
  }
  return $value; 
});

// Add Custom Action to Users Bulk Action dropdown for "Approve User"
add_action('admin_footer', 'bulk_footer_approve_user');
add_action('load-users.php', 'bulk_request_approve' );  

function bulk_footer_approve_user() 
{   
  $screen = get_current_screen();
  if ( $screen->id != "users" )   // Only add to users.php page
  	return;
  
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('<option>').val('approve').text('Approve')
                .appendTo("select[name='action'], select[name='action2']");

            $('<option>').val('unapprove').text('Unapprove')
                .appendTo("select[name='action'], select[name='action2']");
        });
    </script>
    <?php  
} 
 
function bulk_request_approve() {  

    if(isset($_GET['action']) && $_GET['action'] === 'approve') {  // Check if our custom action was selected
        $approve_users = $_GET['users'];  // Get array of user id's which were selected for approve
        if ($approve_users && count($approve_users)>0) {  // If any users were selected 
         // var_dump($_GET);
          for ($i = 0;$i < count($approve_users); $i++) {
            $user_id = $approve_users[$i];
            $user = new WP_User($user_id); 
		
	    // Remove Role	
	    $user-> remove_role('ds_unverified');
	    // Add role
	    $user->add_role('customer');

            $institution = get_user_meta($user_id, 'institution', true); 
            $phone_number = get_user_meta($user_id, 'phonenumber', true); 

            $user_email = stripslashes($user->user_email);   

            $message = '<div> 
              <p>Hi '.$institution.', </p>
              <p>Good News! Your account has been approved. </p>
              <p>You can now login here: <a href="'.get_permalink(woocommerce_get_page_id('myaccount')).'">'.get_permalink(woocommerce_get_page_id('myaccount')).'</a></p>
              <p><br/>Thank you,<br/>DrugStoc Team.</p> 
            </div>'; 
 
            // Send the mail
            sendMail( $user_email, 'Your Drugstoc Account has been approved', $message); 
            
            // Send Text Approval
            sendSMS($phone_number, "Hi $institution, \nGood News! Your DrugStoc account has been approved. Start ordering today at http://drugstoc.biz \nDrugStoc Team.");
	    
          }  
          wp_redirect(home_url( '/' )."wp-admin/users.php");
          exit;
        }
    }else if(isset($_GET['action']) && $_GET['action'] === 'unapprove'){
    	$approve_users = $_GET['users'];  // Get array of user id's which were selected for approve
        if ($approve_users && count($approve_users)>0) {  // If any users were selected
            //var_dump($approve_users);
          foreach ($approve_users as $user_id) {
            $user = new WP_User($user_id); 
		 
	    // Add role
	    $user->add_role('ds_unverified'); 
          }  
          wp_redirect(home_url( '/' )."wp-admin/users.php");
          exit;
        }
    }
}

function getMyPost(){
 // Set Search parameter and post type to product 
  if (isset($_GET['s']) && !empty($_GET['s'])){
    $mySearch = new WP_Query("s=".$_GET['s']."&post_type=product");
    $NumResults = $mySearch->post_count;
  } 
}
add_action( 'init', 'getMyPost');


/**
 * Diplay prices based on a user's primary distributor, if any,
 * else show regular price
 */

add_filter( 'woocommerce_get_price', 'maybe_return_price', 999, 2);
  
 function maybe_return_price( $price , $_product ) { 
  global $current_user;  
 
  $primary_distributor = get_user_meta($current_user->ID, 'primary_distributor', true);
 
  //if we have a priamary distributor
  if(strlen($primary_distributor) > 0){  

    $primary_distributor_price = (float)get_post_meta($_product->post->ID, $primary_distributor, true);
    
    if($primary_distributor_price != '') {  // Show distributor price
      return $primary_distributor_price;// * 1.05;
    }
    return $price;
  }else{ 
    return $price;
  } 
  return $price;
} 

/* Free Delivery Button */
add_action('wp_head','hook_css');

function hook_css()
{?>
  <style type="text/css">
  .free_delivery{
      width: 200px;
      height: 200px;
      border-color: #0000ff !important;
      border-style: solid !important;
      position: relative !important;
      border-radius: 50% !important;
      margin-top: -140% !important;
      margin-left: 90% !important;
      z-index: 1110 !important;
      background: url('http://drugstoc.biz/wp-content/uploads/DEL-BUTON.png') no-repeat;
  }
  </style>
<?php 
}

/**
  * Change the default image placeholder
  **/
add_action( 'init', 'custom_fix_thumbnail' );
 
function custom_fix_thumbnail() {
  add_filter('woocommerce_placeholder_img_src', 'drugstoc_image_placeholder_img_src');
   
  function drugstoc_image_placeholder_img_src( $src ) {
    $upload_dir = wp_upload_dir();
    $uploads = untrailingslashit( $upload_dir['baseurl'] );
    $src = $uploads . '/pill-gradient2.png';
     
    return $src;
  }
}


// List Duplicate and Free Products on Drugstoc
add_action( 'admin_menu', 'ds_duplicate_product' );  
function ds_duplicate_product()
{ 
  add_options_page('List Duplicate Products', 'List Duplicate Products', 'manage_options', 'ds-dup-products', 'ds_duplicate_product_html');
  add_options_page('List Free Products', 'List Free Products', 'manage_options', 'ds-free-products', 'ds_free_product_html');
}

function ds_duplicate_product_html() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }   

  global $wpdb;
  
  $products = $wpdb->get_results("SELECT item.* FROM wp_posts as item INNER JOIN( SELECT post_title FROM wp_posts WHERE post_status='publish' GROUP BY post_title HAVING COUNT(post_title) >1 )temp ON item.post_title= temp.post_title where post_type = 'product' and post_status='publish' ORDER BY item.post_title ASC");
  
  ?>
  <h1>List Duplicate Products </h1><br/>
  <i>Below is a list of Duplicate Products Published on DrugStoc</i><br/><br/>
  <p>Total Count: <?php echo count($products); ?></p>
  <table id="dup_products" class="wp-list-table widefat fixed posts">
    <thead>
      <tr>
        <th scope="col" id="cb" class="manage-column column-cb check-column">ID</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Status</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Product Name</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Composition</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Price</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Date</th> 
      </tr>
    </thead>
    <tbody id="the-list">
      <?php 
        foreach ($products as $key => $product) {
          $price = get_post_meta($product->ID, '_regular_price', true); 
          $nhc = get_post_meta($product->ID, 'nhc_price', true); 
          $elfimo = get_post_meta($product->ID, 'elfimo_price', true); 
      ?>
      <tr>
        <td><?php echo $product->ID; ?></td>
        <td><?php echo $product->post_status; ?></td>
        <td>
          <a href="<?php echo admin_url( 'post.php?post='.$product->ID.'&action=edit');?>"><?php echo $product->post_title; ?></a>
        </td>
        <td><?php echo $product->post_content; ?></td>
        <td><?php echo "<b>DS:</b>  $price <br/><b>NHC</b>: $nhc <br/><b>ELF:</b> $elfimo"; ?></td>
        <td><?php echo $product->post_date; ?></td> 
      </tr>
      <?php } ?>
    </tbody>
  </table>  
<?php
}

function ds_free_product_html() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }   

  global $wpdb;
  
  $products = $wpdb->get_results("SELECT w.* FROM `wp_posts` as w inner join wp_postmeta as m on w.ID = m.post_id where w.post_type = 'product' and w.post_status = 'publish' and m.meta_key = '_regular_price' and m.meta_value = 0");
  
  ?>
  <h1>List Free Products </h1><br/>
  <i>Below is a list of Free Products Published on DrugStoc</i><br/><br/>
  <p>Total Count: <?php echo count($products); ?></p>
  <table id="dup_products" class="wp-list-table widefat fixed posts">
    <thead>
      <tr>
        <th scope="col" id="cb" class="manage-column column-cb check-column">ID</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Status</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Product Name</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Composition</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Price</th>
        <th scope="col" id="cb" class="manage-column column-cb check-column">Date</th> 
      </tr>
    </thead>
    <tbody id="the-list">
      <?php 
        foreach ($products as $key => $product) {
          $price = get_post_meta($product->ID, '_regular_price', true); 
          $nhc = get_post_meta($product->ID, 'nhc_price', true); 
          $elfimo = get_post_meta($product->ID, 'elfimo_price', true); 
      ?>
      <tr>
        <td><?php echo $product->ID; ?></td>
        <td><?php echo $product->post_status; ?></td>
        <td>
          <a href="<?php echo admin_url( 'post.php?post='.$product->ID.'&action=edit');?>"><?php echo $product->post_title; ?></a>
        </td>
        <td><?php echo $product->post_content; ?></td>
        <td><?php echo "<b>DS:</b>  $price <br/><b>NHC</b>: $nhc <br/><b>ELF:</b> $elfimo"; ?></td>
        <td><?php echo $product->post_date; ?></td> 
      </tr>
      <?php } ?>
    </tbody>
  </table>  
<?php
}


// Faulty code
// add_action('admin_init', 'no_backend_access_');
// function no_backend_access_() {

//   $user = wp_get_current_user();
//   $allowed_roles = array('author', 'administrator','shop_manager');
//   if( !array_intersect($allowed_roles, $user->roles ) ) {
//     //stuff here for allowed roles
//     wp_redirect(home_url());
//     exit;
//   }
// }

// Add NAFDAC no post meta
//add_action('init','myinit');
function myinit(){
  global $wpdb, $woocommerce;

  $products = $wpdb->get_results("SELECT * FROM wp_posts where post_type = 'product'");
   
  foreach ($products as $key => $product_) {
    # code...
    $product = new WC_Product($product_->ID);
    $nafdac_no = $product->get_attribute("pa_nafdac-no");

    update_post_meta($product->id ,"nafdac_no", $nafdac_no); 

    // delete_post_meta($product->id ,"nafdac_no");   
  } 
}

/******************************/
// Nice URLs for Distributors/Pharmacy                                                     
// 
// Rewrite Tag for distributors
function custom_rewrite_tag() {
  add_rewrite_tag('%distributor%', '([^&]+)'); 
  add_rewrite_tag('%pharmacy%', '([^&]+)');
  add_rewrite_tag('%maker%', '([^&]+)'); 
  add_rewrite_tag('%m%', '([^&]+)');
}
add_action('init', 'custom_rewrite_tag', 10, 0);

// Rewrite rule for distributors
function custom_rewrite_basic() {
  add_rewrite_rule('^vendor/([^/]*)/?', 'index.php?page_id=43504&distributor=$matches[1]', 'top');
  add_rewrite_rule('^pharma/([^/]*)/?', 'index.php?page_id=43557&pharmacy=$matches[1]', 'top');
  add_rewrite_rule('^m/([^/]*)/?', 	'index.php?page_id=43633&maker=$matches[1]', 'top');
}
add_action('init', 'custom_rewrite_basic', 10, 0);

// Tell WordPress about our new query var
function add_var_for_distributor( $query_vars ){
    $query_vars[] = 'distributor';
    $query_vars[] = 'pharmacy';
    $query_vars[] = "maker";
    $query_vars[] = 'm';
    
    return $query_vars;
}
add_filter( 'query_vars', 'add_var_for_distributor' );


/**
 * Display API Credentials for ds_inv_mgr user roles
 */ 
add_action('woocommerce_after_my_account', 'show_ds_api_keys', 10, 0);
function show_ds_api_keys()
{
  global $woocommerce;

  $user = wp_get_current_user();

  if($user->woocommerce_api_consumer_key != ""){ ?>
  <br>
  <button onclick="jQuery('#showToken').toggle('slow',function(){});" class="button">View API Credentials</button>
  <br>
  <div id="showToken" style="display: none">
    <h1>API Credentials</h1><br>
    <table>
      <tbody>
        <tr>
          <td>KEY</td>
          <td>SECRET</td>
        </tr>
        <tr>
          <td style="font-size: 18px;"><?php echo $user->woocommerce_api_consumer_key; ?></td>
          <td style="font-size: 18px;"><?php echo $user->woocommerce_api_consumer_secret; ?></td>
        </tr>
      </tbody>
    </table>
    <button onclick="jQuery('#showToken').slideUp();" class="button">Hide API Credentials</button>
  </div>  
  <?php
  }
}

// Display API Credentials For ds_inv_mgr user roles woocommerce_before_my_account
add_action('woocommerce_after_my_account', 'show_my_pricelist', 10, 0);
function show_my_pricelist()
{
  $user = wp_get_current_user();
  $user_role = implode(',', $user->roles); 

  if($user_role == 'shop_manager'){ ?>
  <p>
   <a href="<?php echo home_url('/my-price-list');?>" >
    <button class="button">View My Pricelist</button>
   </a>
  </p>
<?php 
  }
} 

/** All Short Codes */ 
// [show_all_distributors]
function show_all_distributors_(){
  global $wpdb;
 
  $users  = $wpdb->get_results("SELECT u.*, (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as 'state' FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and ( w.meta_value = 'Distributor' or w.meta_value = 'Wholesale')");
 
  $states = $wpdb->get_results("SELECT (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as 'state' FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and ( w.meta_value = 'Distributor' or w.meta_value = 'Wholesale') group by state");
 
  if(is_user_logged_in()){
  ?>
  <div class="view_distributors responsive ">
    <div class="row">
      <div class="span12">
        <div class="header-opts">
          <h4>Distributors</h4>
            <select>
            <?php foreach ($states as $key => $state): ?>
              <option value="<?php echo $state->state;?>" style="text-transform: uppercase"><?php echo $state->state;?></option>
            <?php endforeach ?>
            </select>
        </div>
        <div style="width:100%; float: left; margin-bottom: 30px;" class="responsive" id="show_all_dist">
        <?php
          $state_header = '';
          foreach ($users as $key => $_user) { 
            if(strtoupper($_user->state) != strtoupper($state_header)){
              $state_header = $_user->state; 
              ?>   
              <?php
            }
            $user = get_user_by('id', $_user->ID);
            $dist = substr(get_user_meta($_user->ID,'primary_distributor',true), count(get_user_meta($_user->ID,'primary_distributor',true)), -6);
            ?>
            <button  href="<?php echo home_url('/vendor/'.$dist);?>" class="button button--saqui button--inverted button--text-thick button--text-upper button--size-s" data-text="<?php echo get_user_meta($_user->ID,'state', true);?>">
              <?php echo get_user_meta($_user->ID,'institution',true);?>
            </button>
        <?php
          }?>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <?php
  }else{ ?>
  <div style="margin-top: 30px; text-align:center">
    <div class="border-img">
        <img class="error-404-image group" src="<?php echo home_url('/');?>wp-content/themes/sistina/images/403.png" title="Error 404" alt="404 Error">
    </div>
    <div class="error-404-text group">
        <h2>This is restricted to users only</h2>
        <p>To access the full list of distributors and their prices please <a href="<?php echo home_url('/my-account');?>"> sign up </a></p>
        <p>You can also visit our <a href="http://localhost:81/drugstoc_2">home page</a> or use the search box above.</p>
    </div>
  </div>
  <?php
  }
}
add_shortcode( 'show_all_distributors', 'show_all_distributors_');
 
// [show_all_pharmacies]
function show_all_pharmacies_(){
  global $wpdb;
 
  $users = $wpdb->get_results("SELECT u.*, (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as 'state' FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = 'Pharmacy'");
 
  $states = $wpdb->get_results("SELECT (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as 'state' FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = 'Pharmacy' group by state");
 
  ?>
  <div class="view_distributors responsive ">
    <div class="row">
      <div class="span12">
        <div class="header-opts">
          <h4>All Pharmacies in Lagos</h4>
            <select>
            <?php foreach ($states as $key => $state): ?>
              <option value="<?php echo $state->state;?>" style="text-transform: uppercase"><?php echo $state->state;?></option>
            <?php endforeach ?>
            </select>
        </div>
        <div style="width:100%; float: left; margin-bottom: 30px;" class="responsive" id="show_all_dist">
        <?php
          $state_header = '';
          foreach ($users as $key => $_user) {
            if(strtoupper($_user->state) != strtoupper($state_header)){
              $state_header = $_user->state;
              ?>
              <?php
            }
            $user = get_user_by('id', $_user->ID);
            $dist = substr(get_user_meta($_user->ID,'primary_distributor',true), 0, -6);
            ?>
            <button  href="<?php echo home_url('/pharma/'.$dist);?>" class="button button--saqui button--inverted button--text-thick button--text-upper button--size-s" data-text="<?php echo get_user_meta($_user->ID,'state', true);?>">
              <?php echo get_user_meta($_user->ID,'institution',true);?>
            </button>
        <?php
          }?>
        </div>
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <?php
}
add_shortcode( 'show_all_pharmacies', 'show_all_pharmacies_');
 
// [show_all_manufacturers]
function show_all_manufacturers_(){
  global $wpdb;
 
  $users  = $wpdb->get_results("SELECT u.*,(SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as 'state' FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = 'Manufacturer' or w.meta_value = 'Importer' ORDER BY state");
 
  $states = $wpdb->get_results("SELECT (SELECT meta_value FROM wp_usermeta WHERE user_id = u.ID and meta_key LIKE 'state') as 'state' FROM `wp_users` as u INNER JOIN wp_usermeta as w on u.ID = w.user_id WHERE w.meta_key = 'user_type' and w.meta_value = 'Manufacturer' or w.meta_value = 'Importer' group by state");
  ?>
  <div class="view_distributors responsive ">
    <div class="row">
      <div class="span12">
        <div class="header-opts">
          <h4>Manufacturer</h4>
            <select class="go-to-title">
            <?php foreach ($states as $key => $state): ?>
              <option value="<?php echo $state->state;?>" style="text-transform: uppercase"><?php echo $state->state;?></option>
            <?php endforeach ?>
            </select>
        </div>
 
        <div style="width:100%; float: left; margin-bottom: 30px;" class="responsive" id="show_all_dist">
        <?php
          $state_header = '';
          foreach ($users as $key => $_user) {
            if(strtolower($_user->state) != strtolower($state_header)){
              $state_header = $_user->state;
              ?>
              <p align="center" style="width:100%">
                <h4 style="text-transform: uppercase"><a id="goto-<?php echo $state_header;?>" name="<?php echo $state_header;?>"><?php echo $state_header;?></a></h4>
              </p>
              <?php
            }
            $user = get_user_by('id', $_user->ID);
            $dist = substr(get_user_meta($_user->ID,'primary_distributor',true), 0, -6);
            ?>
            <button href="<?php echo home_url('/pharma/'.$dist);?>" class="button button--saqui button--inverted button--text-thick button--text-upper button--size-s" data-text="<?php echo get_user_meta($_user->ID,'state', true);?>">
              <?php echo get_user_meta($_user->ID,'institution',true);?>
            </button>
        <?php
        }?>
        </div>
      </div>
      <script type="text/javascript">
      jQuery('.go-to-title').on('change', function (e) {
        var val = jQuery(e.currentTarget).val();
        jQuery('html, body').animate({
            scrollTop: jQuery('#goto-' + val).offset().top
        }, 2000);
      })
      </script>
    </div>
    <div class="clear"></div>
  </div>
  <?php
}
add_shortcode( 'show_all_manufacturers', 'show_all_manufacturers_');

// [show_all_categories]
function show_all_categories_()
{
  global $wpdb;

  $categories = $wpdb->get_results("SELECT t.name, t.slug, count(t.term_id) as count FROM wp_terms as t 
      INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id 
      INNER JOIN wp_term_relationships as wtr on wtr.term_taxonomy_id = p.term_taxonomy_id
      WHERE p.taxonomy LIKE 'product_cat' and wtr.object_id IN (
      SELECT p.ID FROM wp_posts as p WHERE p.post_status='publish' AND p.post_type='product') GROUP BY t.name ORDER BY t.name ASC"); ?>
  
  <div class="view_categories responsive">
    <a style="float: right;cursor: pointer" class="button" onclick="jQuery('#show_all_cat').toggle('slow',function(){})" >View All Categories</a>
    <div style="width:100%; display:none; float: left; margin-top: 30px; margin-bottom: 30px;" class="responsive" id="show_all_cat">
    <?php
      foreach ($categories as $key => $category) { 
        if($category->count > 0){?>  
        <a href="<?php echo home_url('/product-category/'.$category->slug);?>" >
          <span style="border: 2px solid rgb(242, 242, 242); padding: 5px; margin: 5px; float: left">
            <?php echo $category->name;?>
          </span>  
        </a>
    <?php  
        }
      }?>
    </div> 
  </div>  
  <?php
} 
add_shortcode( 'show_all_categories', 'show_all_categories_'); 

// [login_popup]
function ds_popup(){
  // Unverified account notice
  if (isset($_GET['status']) && !empty($_GET['status'])){ 
  ?>
    <div id="light" class="white_content" onclick="document.getElementById('light').style.display='none';">
      
      You will be notified when your account is activated. Thank You
      
      <p style="text-align: right;border-top: 1px solid #DBDBDB;">
        <a href="javascript:void(0)" onclick="document.getElementById('light').style.display='none';">
          <b style="color: #BCBCBC;">CLOSE X</b>
        </a>
      </p> 
    </div> 
    <style type="text/css">
      div.white_content {
        top: 20%;
        left: 25%;
        width: 530px;
        height: 40px;
        padding: 16px;
        border: 4px solid #7DACFC;
        background-color: white;
        z-index: 1002;
        position: fixed;
        font-size: large;
        border-radius: 8px;
        box-shadow: 0 0 8px;
      }
 
      @media all and (max-width: 800px) {
        div.white_content {left:4%; width:250px; height:100px;}
      }
    </style> 
  <?php
  }
}
add_shortcode( 'login_popup', 'ds_popup');

// Login Redirect for Users with Referral Code
function my_login_redirect($redirect, $user){
 
  global $wpdb;
   
  if( in_array('customer', $user->roles) || 
      in_array('shop_manager', $user->roles) ||
      in_array('ds_inv_mgr', $user->roles) ||
      in_array('manufacturer', $user->roles) ||
      in_array('pharmacy', $user->roles) ||
      in_array('administrator', $user->roles)){
 
    $refcode = trim(get_user_meta($user->ID, 'ds_referral_code', true));
    $query = "SELECT * FROM {$wpdb->prefix}ds_referral_codes WHERE referral_code LIKE '".$refcode."'";
    $vendor = $wpdb->get_results($query);
 
    //check for vendor urls if any
    if(count($vendor) > 0) $redirect = home_url("/{$vendor[0]->url}");
 
    return $redirect;
 
  } else { 
    wp_logout();
    wp_safe_redirect( home_url( '/?status='.uniqid() ) ); 
    exit; 
  }
}
 
add_filter('woocommerce_login_redirect', 'my_login_redirect', 10, 2);
 


// Increase Session Time
add_filter('wc_session_expiring' , 'filter_ExtendSessionExpiring') ; 
add_filter('wc_session_expiration' ,  'filter_ExtendSessionExpired');

 function filter_ExtendSessionExpiring($seconds) {
  return (60 * 60 * 24 * 8) - (60 * 60);
}
 function filter_ExtendSessionExpired($seconds) {
  return 60 * 60 * 24 * 8;
}

add_filter( 'manage_users_sortable_columns', 'my_website_manage_sortable_columns' );
function my_website_manage_sortable_columns( $sortable_columns ) {

   /**
    * In this scenario, I already have a column with an
    * ID (or index) of 'release_date_column'. Both column 
    * indexes MUST match.
    * 
    * The value of the array item (after the =) is the
    * identifier of the column data. For example, my
    * column data, 'release_date', is a custom field
    * with a meta key of 'release_date' so my
    * identifier is 'release_date'.
    */
   $sortable_columns[ 'column-user_registered' ] = 'user_registered';   
   $sortable_columns[ 'column-meta-2' ] = 'meta-2';
   $sortable_columns[ 'role' ] = 'role';
   // Let's also make the film rating column sortable
   // $sortable_columns[ 'film_rating_column' ] = 'film_rating';

   return $sortable_columns;
}

// Turn of Product Reviews on Product Page
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab($tabs) {
 unset($tabs['reviews']);
 return $tabs;
}