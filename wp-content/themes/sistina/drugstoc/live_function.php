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
    <p><img src="http://drugstoc.biz/wp-content/uploads/2014/10/splash-logo-beta.png"/></p>';
  
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

    //var_dump($sms);
    //var_dump($apg_sms);

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
  update_user_meta($user_id, 'phonenumber', esc_attr($_POST['phonenumber']));
  update_user_meta($user_id, 'usertype', esc_attr($_POST['usertype']));
  update_user_meta($user_id, 'has_to_be_activated', sha1( $user_id . time()), true);

  $id = array( 'ID' => $user_id, 'display_name' => esc_attr($_POST['institution']) );

  $user = new WP_User($user_id);
  $user->set_role('ds_unverified');

  $message = "Hi ".esc_attr($_POST['institution']).", \nThanks for creating an account on DrugStoc. Your account will be held for moderation and you will be unable to login until it is approved. DrugStoc.";

  sendSMS(esc_attr($_POST['phonenumber']), $message); 

  $message = '<div> 
    <p>Hi '.$institution.', </p>
    <p>Thanks for creating an account on DrugStoc. </p>
    <p>Your account will be held for moderation and you will be unable to login until it is approved.</p>
    <p><br/>Thank you,<br/>DrugStoc Team.</p> 
  </div>';

  // Send the mail
  sendMail($user->user_email, "Welcome to DrugStoc", $message);
} 	

function check_user_status () {
  if (current_user_can( 'not_authenticate' ) && !current_user_can( 'manage_options' )) {
    wp_logout();
    wp_redirect( '/user-not-approved', '302' );
    exit;
  }
} 

/**
 * Proper way to enqueue scripts and styles
 */
function add_custom_drugstoc_style() {  
//<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
//<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> 
  
  wp_enqueue_style( 'additional-css', get_template_directory_uri() . '/css/additionalCss.css' );
}

/**
 * Proper way to enqueue scripts and styles
 */
function add_custom_drugstoc_scripts() { 
  wp_register_script( 'additional-js', get_template_directory_uri() . '/js/drugstoc.js' , array('jquery'));
  wp_enqueue_script('additional-js');
}

//add_action( 'wp_enqueue_scripts', 'add_custom_drugstoc_scripts' );
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
      	wp_logout();
      	return get_permalink(woocommerce_get_page_id('myaccount')) . "?approved=false";
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

//Email Notifications
//Content parsing borrowed from: woocommerce/classes/class-wc-email.php
function send_user_approve_email($user_id){
    global $woocommerce;
    //Instantiate mailparse_rfc822_parse_addresses(addresses)
    $mailer = $woocommerce->mailer();
        if (!$user_id) return;
        $user = new WP_User($user_id);
        $institution = get_user_meta($user_id, 'institution', true); 
	$phone_number = get_user_meta($user_id, 'phonenumber', true); 
	
        $user_email = stripslashes($user->user_email); 
        $blogname = wp_specialchars_decode(get_option('DrugStoc'), ENT_QUOTES);
        $subject  = sprintf( __( 'Your account on Drugstoc has been approved!', 'woocommerce'), "Drugstoc" );

        $message = '<div> 
          <p>Hi '.$institution.', </p>
          <p>Good News! Your account has been approved. </p>
          <p>You can now login here: <a href="'.get_permalink(woocommerce_get_page_id('myaccount')).'">'.get_permalink(woocommerce_get_page_id('myaccount')).'</a></p>
          <p><br/>Thank you,<br/>DrugStoc Team.</p> 
        </div>';

        // Send the mail
        sendMail( $user_email, $subject, $message);
        
        // Send Text Approval
        sendSMS($phone_number, "Hi ".$institution.", \nGood News! Your DrugStoc account has been approved. Start ordering today at http://drugstoc.biz \nDrugStoc Team.");
}
add_action('wpau_approve', 'send_user_approve_email', 10, 1);

function send_user_unapprove_email($user_id){
        return;
}
add_action('wpau_unapprove', 'send_user_unapprove_email', 10, 1);

// //Email notification after registration successful registration
// add_filter('wp_mail_content_type', 'set_html_content_type');
// add_action('woocommerce_created_customer','new_user_email_notification');
// add_action('woocommerce_created_customer','set_new_user_unverified');
// add_action('get_header','check_user_status');


function fb_add_custom_user_profile_fields( $user ) {
?>
  <h3><?php _e('Primary Distributor', 'your_textdomain'); ?></h3>

  <table class="form-table">
    <tr>
      <th>
        <label for="primary_distributor"><?php _e('Primary Distributor', 'your_textdomain'); ?>
      </label></th>
      <td>
        <?php  
          global $wpdb;
          $prices = $wpdb->get_results("SELECT meta_key FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '%_price' AND meta_key NOT IN ('_price','_regular_price', '_sale_price') GROUP BY meta_key  ");
          
          $price = array();
          foreach ($prices as $key => $dist_code) { 
            $price[ $dist_code->meta_key ] = __( $dist_code->meta_key, 'woocommerce');
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
        <label for="free_user"><?php _e('Free User', 'your_textdomain'); ?>
      </label></th>
      <td>
        <?php  
          $inprint = get_the_author_meta( 'ds_free_user', $user->ID );
          woocommerce_wp_select( 
            array( 
              'id'      => 'free_user',  
              'options' => array(
                'Yes'   => __( 'Yes', 'woocommerce' ),
                ''   => __( 'No', 'woocommerce' )
                ),
              'value'  => ($inprint == "Yes")? $inprint : "",
              'desc_tip' => true,
              'description'   => __( 'Not a Premium User?', 'woocommerce' ) 
              )
            ); 
        ?>
        <span class="description"><?php _e('Not a Premium User? ', 'your_textdomain'); ?></span>
      </td>
    </tr> 
  </table>
<?php  
}

function fb_save_custom_user_profile_fields( $user_id ) {
  update_usermeta( $user_id, 'primary_distributor', $_POST['primary_distributor'] );
  update_usermeta( $user_id, 'ds_free_user', $_POST['free_user'] );
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
//add_action('woocommerce_before_single_product', 'edit_product_link');
add_filter('option_woocommerce_enable_sku', function ($value) 
{ 
  if (!is_admin()) 
  { 
    return 'no'; 
  }
  return $value; 
});
//send notification if user is activated
function send_notification_after_user_role_changed( $user_id, $role ) { 
  $user = new WP_User($user_id);
  if('customer' == $role){ // Notify User id

    $institution = get_user_meta($user_id, 'institution', true); 
    $phone_number = get_user_meta($user_id, 'phonenumber', true); 

    $user_email = stripslashes($user->user_email); 
    $blogname = wp_specialchars_decode(get_option('DrugStoc'), ENT_QUOTES);
    $subject  = sprintf( __( 'Your account on Drugstoc has been approved!', 'woocommerce'), "Drugstoc" );

    $message = '<div> 
      <p>Hi '.$institution.', </p>
      <p>Good News! Your account has been approved. </p>
      <p>You can now login here: <a href="'.get_permalink(woocommerce_get_page_id('myaccount')).'">'.get_permalink(woocommerce_get_page_id('myaccount')).'</a></p>
      <p><br/>Thank you,<br/>DrugStoc Team.</p> 
    </div>';

    // Send the mail
    sendMail( $user_email, $subject, $message);
    
    // Send Text Approval
    sendSMS($phone_number, "Hi ".$institution.", \nGood News! Your DrugStoc account has been approved. Start ordering today at http://drugstoc.biz \nDrugStoc Team.");
  }
}
add_action( 'set_user_role', 'send_notification_after_user_role_changed', 10, 2  );

function getMyPost(){
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

  //if we have a priamary distributor
  $primary_distributor = get_user_meta($current_user->ID, 'primary_distributor', true);
 
  //if we have a priamary distributor
  if(strlen($primary_distributor) > 0){ 
    // temporary nhc_price hack
    //if($primary_distributor == 'nhc_price') {
    //  $nhc_price = get_post_meta($_product->post->ID, $primary_distributor, true);
    //  $primary_distributor_price = $nhc_price * 1.05;
    //}else{ 
    $primary_distributor_price = get_post_meta($_product->post->ID, $primary_distributor, true);
    //}

    if($primary_distributor_price != '') {
      return $primary_distributor_price * 1.05;
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


add_action('admin_init', 'no_backend_access_');
function no_backend_access_() { 

  $user = wp_get_current_user();
  $allowed_roles = array('author', 'administrator','shop_manager');
  if( !array_intersect($allowed_roles, $user->roles ) ) {  
    //stuff here for allowed roles
    wp_redirect(home_url()); 
    exit;
  }     
}

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
// Nice URLs for Distributors                                                         
// 
// Rewrite Tag for distributors
function custom_rewrite_tag() {
  add_rewrite_tag('%distributor%', '([^&]+)'); 
}
add_action('init', 'custom_rewrite_tag', 10, 0);

// Rewrite rule for distributors
function custom_rewrite_basic() {
  add_rewrite_rule('^vendor/([^/]*)/?', 'index.php?page_id=43504&distributor=$matches[1]', 'top');
}
add_action('init', 'custom_rewrite_basic', 10, 0);

// Tell WordPress about our new query var
function add_var_for_distributor( $query_vars ){
    $query_vars[] = 'distributor';
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
  <button onclick="jQuery('#showToken').slideDown();" class="button">View API Credentials</button>
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

// Check user Email
//add_filter( 'woocommerce_api_check_authentication', 'filter_woocommerce_api_check_authentication', 10, 2 );
function filter_woocommerce_api_check_authentication($instance, $number){
  
  var_dump($instance);
  var_dump($number);

  return;
}