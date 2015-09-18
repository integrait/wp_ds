<?php
/**
 * Plugin Name: DrugStoc Utility Functions   
 * Plugin URI: http://integrahealth.com.ng/integraitlabs.php
 * Description: Provides all utility functions/shortcodes used on Drugstoc - Distributor and referral code lookup, communication etc.
 * Version: 1.0.0
 * Author: Integra Health | Drugstoc
 * Author URI: http://integrahealth.com.ng
 * Text Domain: cpac
 * Domain Path: /languages
 * License: GPL2
 */

/*  Copyright 2014  DRUGSTOC_UTILITY  (email : info@drugstoc.biz)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined('ABSPATH') or die("No script kiddies please!"); 

if(!class_exists('DS_Util')):
/**
 * DS_Util class.
 * All Shortcodes and Static functions.
 *
 * @since 1.0.0
 */
 
register_activation_hook( __FILE__, array( 'DS_Util', 'ds_util_install'));
register_deactivation_hook( __FILE__, array( 'DS_Util', 'ds_util_deactivate'));

class DS_Util
{  
  private static $instance;
  const VERSION = '1.0.0'; 

  // Twilio Account Details
  private static $AccountSid = "ACee9332ba9fa2eb4becb6eb25e8a9f1eb";
  private static $AuthToken = "bda3517430f468419934d1ea054675a8";

  private static function has_instance() {
      return isset(self::$instance) && self::$instance != null;
  }

  public static function get_instance() {
      if (!self::has_instance())
          self::$instance = new DS_Util;
      return self::$instance;
  }

  public static function setup() {
      self::get_instance();
  }

  protected function __construct() {
    if (!self::has_instance()) {
      add_action('init', array(&$this, 'init'));
    }
  } 

  // Plug into all necessary actions and filters
  function init(){ 
    // Create ds_referral_code table
    // add_action( 'admin_head', array( $this, 'ds_r_c_scripts' )); 
    // add_action( 'admin_notices', array( $this, 'ds_r_c_notices'));  

    // All shortcodes
    // add_action();
  } 

  // Create Schema for DS Price Model
  public function ds_util_install(){
  }


  public function ds_r_c_scripts()
  {     
    wp_enqueue_script('jquery');   

    wp_enqueue_script('dsutil-chosen-js', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js' , array('jquery'));
    wp_enqueue_style('dsutil-chosen-css', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css');    
  } 

  // Send SMS
  public static function sendSMS($to, $message){
    // Twilio api library
    require_once('twilio-php/Services/Twilio.php'); 

    $client = new Services_Twilio(self::$AccountSid, self::$AuthToken);

    if(isset($to)){ // validate and sanitize number
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

  public static function sendMail($to, $subject, $message, $flag = 1){

    // Set HTML Headers for mail
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: DrugStoc <mailer@drugstoc.ng>'."\r\n";

    $message .= '<p>Tel: +2348096879999<br/>Email: info@drugstoc.com</p>
    <p><img src="http://drugstoc.biz/wp-content/uploads/2014/10/splash-logo-beta.png"/></p>
    <div style="text-align:center; border-top:1px solid #eee;padding:5px 0 0 0;" id="email_footer">
      <small style="font-size:11px; color:#999; line-height:14px;">
        You have received this email because you are a member of DrugStoc.
        <br/>Please do not reply to this email. This mailbox is not monitored and you will not receive a response.
        <br/><b>Copyright © '.date('Y').' DrugStoc - All rights reserved.</b>
      </small>
    </div>';

    // Send the mail
    // $mail = @mail( $to, $subject, $message, $headers);
    $mail = mail($to, $subject, wordwrap($message, 200, "\n", true), wordwrap($headers, 75, "\n", true)); 
    // $mail = wp_mail($to, $subject, wordwrap($message, 200, "\n", true), wordwrap($headers, 75, "\n", true));
    return $mail;
  }

  // All ShortCodes
  public static function printall(){
    return "This is just a test";
  }

  // Get Distributor Name by key
  // DS_Util::getDistributorNamebyKey($key)
  public static function getDistributorNamebyKey($key, $type = 1){

    $user_args = array( 
      'role'         => 'shop_manager',
      'meta_key'     => 'primary_distributor',
      'meta_value'   => $key,
      'meta_compare' => '=',
      'number'       => '1'
    );
    $user = current(get_users($user_args));

    if($user){
      if($type == 1) return get_user_meta($user->ID, 'institution', true); // only name
      else if($type == 2) return $user; // whole object
    }
    return null;
  }   

  // Get Manufacturer by slug
  public static function getManufacturerBySlug($key, $type = 1){
  
    if(isset($key)){
      $user = current(get_users(
        array( 
          'role'        => 'manufacturer',
          'meta_key'    => 'manufacturer_slug',
          'meta_value'  => $key,
          'meta_compare'=> '=',
          'number'      => '1'
        )
      ));

      if(count($user) > 0) return $user; // whole object
      else return array();
    }  
  }  

  // Get Manufacturer product ids
  public static function get_manufacturer_pdts($slug){
    global $wpdb, $woocommerce;
    
    $ids = array();
    $results = $wpdb->get_results("SELECT ID from wp_posts as pdt 
      INNER JOIN wp_term_relationships wtr on pdt.ID = wtr.object_ID 
      INNER JOIN wp_term_taxonomy as p on wtr.term_taxonomy_id = p.term_taxonomy_id 
      INNER JOIN wp_terms as t on p.term_id = t.term_id 
     WHERE p.taxonomy LIKE 'pa_manufacturer' and t.slug='$slug' AND pdt.post_status = 'publish'");

    if(count($results) > 0){
      foreach ($results as $key => $product) {  
        $ids[] = $product->ID; 
      }
      return $ids;
    } else return null;
  }

  // Determine if price should be shown or not
  public static function show_price($userid = null){
    if(!isset($userid)) $userid = get_current_user_id();
    
    $show = 0; // Do not show by default 
    // Add additional user role checks here <<<< 
    $user = wp_get_current_user();
    $premium_user = get_user_meta($user->ID, 'ds_premium_user', true);
    $distributor = get_user_meta($user->ID, 'primary_distributor', true);
    $pdt_dist = get_post_meta($product->id, $distributor, true);

    // Free User
    if($premium_user == 0){ // Not Premium = 0, Premium = 1
        if($distributor != '' && floatval($pdt_dist) > 0){ // Free User referred by Distributor
            $show = 1;
        } 
    }else $show = 1;

    return $show; 
  }

  // List of All Distributor Codes
  public static function distributors($except='')
  { 
    global $wpdb;

    $q = "SELECT DISTINCT * FROM `wp_usermeta` WHERE meta_key = 'primary_distributor' AND meta_value!='' AND meta_value NOT LIKE '%_dimprice' ";
    $q.= ($except!='')? "AND meta_value NOT LIKE '{$except}'":"";
    $q.= " GROUP BY meta_value";

    $distributors = $wpdb->get_results($q);
    $all = array();
    foreach ($distributors as $key => $value) {
      $all[] = '"'.$value->meta_value.'"';
    }

    return join(',',$all);
  }

  // Get all Product Categories
  public static function get_pdt_cat($product_id = 0){
    global $post;

    $categories = array(); 
    $terms = get_the_terms( $product_id, 'product_cat' );
    if( is_array($terms) > 0 ){
      foreach ($terms as $term) {
        $categories[] = $term->name;
      } 
      return join(', ', $categories);
    }
    return '';
  }

  // Pluck Arrays the fabulous way
  public static function array_pluck ($key, $array) {
      return array_map(function ($item) use ($key) {
          return $item[$key];
      }, $array); 
  }

  // Deactivate DS Referral Code
  public function ds_util_deactivate()
  {
    if ( ! current_user_can( 'activate_plugins' ) )
          return;
    // Ensure request is from Admin Plugins Page
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "deactivate-plugin_{$plugin}" ); 
  }
}
endif;

// Activate Plugin 
DS_Util::setup();








