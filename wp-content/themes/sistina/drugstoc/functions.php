<?php  
  
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

function wooc_extra_register_fields( ) {
  
	//lets make the field required so that i can show you how to validate it later; 
	?> 
	<p class="form-row form-row-first">

	    <label for="reg_institution"><?php _e( 'Name of Institution', 'yit' ); ?> <span class="required">*</span></label>

	    <input type="text" class="input-text" name="institution" id="reg_institution" value="<?php if (isset($_POST['institution'])) echo esc_attr($_POST['institution']); ?>" />

	</p>

	<p class="form-row form-row-last">

        <label for="reg_usertype"><?php _e( 'Register as:', 'yit' ); ?> <span class="required">*</span></label>

        <select name="usertype" id="reg_usertype">

            <option value="pharmacy" >Pharmacy</option>

            <option value="clinic">Clinic</option>

            <option value="hospital">Hospital</option>

        </select> 

    </p>

	<p class="form-row form-row-wide">

        <label for="reg_phonenumber"><?php _e( 'Phone Number', 'yit' ); ?> <span class="required">*</span></label>

        <input type="text" class="input-text " name="phonenumber" id="billing_phone" placeholder="" value="<?php if (isset($_POST['phonenumber'])) echo esc_attr($_POST['phonenumber']); ?>"> 

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
}

// function set_html_content_type(){ return 'text/html'; }

// Email notification for new Users
function new_user_email_notification($user_id){

	$user = new WP_User( $user_id );

	$code = sha1( $user_id . time() );
	$activation_link = add_query_arg( array( 'key' => $code, 'user' => $user_id ), get_permalink(811)); // 811 = activatation page id

	$subject = 'Activate your DrugStoc Account'; 
	$to = stripslashes($user->user_email); 
	$headers = array();
	$headers[0] = 'From: DrugStoc <mailer@drugstoc.ng>'; 
	 
	// $attachments = array('/images/404.png'); 
	$headers[1] = "Content-Type: text/htmlrn";
 
	$message = 'Hello '.$user->user_login.',<br/><br/>';
	$message .= 'To activate your account and access the all features, copy and paste the following link into your web browser:';
	$message .= "<br/><a href='";
	$message .= $activation_link;
	$message .= "'>".$activation_link."</a><br/><br/>";
	$message .= "Thank you for registering with us.";
	$message .= '<br/><br/>Yours sincerely,<br/> DrugStoc Team';
  
	$mail = wp_mail($to, $subject, $message, $headers);  
	wc_add_notice('A confirmation link has been sent to your email address. Please follow the instructions in the email to activate your account.',$notice_type = 'success');
}
 
// override core function to authenticate user
if ( !function_exists('wp_authenticate') ) :
function wp_authenticate($username, $password) {
    $username = sanitize_user($username);
    $password = trim($password);

    $user = apply_filters('authenticate', null, $username, $password);

    if ( $user == null ) { 
        $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
    } elseif ( get_user_meta( $user->ID, 'has_to_be_activated', true ) != false ) {
        $user = new WP_Error('activation_failed', __('<strong>ERROR</strong>: User is not activated.'));
    }

    $ignore_codes = array('empty_username', 'empty_password');

    if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
        do_action('wp_login_failed', $username);
    }

    return $user;
}
endif;

add_action( 'woocommerce_before_my_account', 'wpse8170_activate_user' );
function wpse8170_activate_user() {
    // if ( is_page() && get_the_ID() == 811) {
    if ( is_page() && strpos($_SERVER['QUERY_STRING'], '/activate') !== false) {	
        $user_id = filter_input( INPUT_GET, 'user', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
        if ( $user_id ) {
            $code = get_user_meta( $user_id, 'has_to_be_activated', true );
            if ( $code == filter_input( INPUT_GET, 'key' ) ) {
                delete_user_meta( $user_id, 'has_to_be_activated' );
                update_user_meta($user_id, 'user_status', 1);  // Update user_status
			 
				wc_add_notice('Your Account has been Activated',$notice_type = 'success');
            }else{
				$user = new WP_Error('activation_failed', __('<strong>ERROR</strong>: User is not activated.'));
            }
        }
    }
}

//Adding Registration fields to the form   
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' ); 

//Validation registration form  after submission using the filter registration_errors 
add_filter( 'woocommerce_registration_errors', 'registration_errors_validation' );

//Updating use meta after registration successful registration 
add_action('woocommerce_created_customer','adding_extra_reg_fields'); 

// //Email notification after registration successful registration 
// add_filter('wp_mail_content_type', 'set_html_content_type');
add_action('woocommerce_created_customer','new_user_email_notification', $user_id);






