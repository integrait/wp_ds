<?php
/**
 * Plugin Name: DrugStoc Distributor Referral Codes   
 * Plugin URI: http://integrahealth.com.ng/integraitlabs.php
 * Description: Manage all Referral Codes for distributors
 * Version: 1.0.0
 * Author: Integra Health | Drugstoc
 * Author URI: http://integrahealth.com.ng
 * Text Domain: cpac
 * Domain Path: /languages
 * License: GPL2
 */

/*  Copyright 2014  DRUGSTOC_REFERRAL_CODES  (email : info@drugstoc.biz)

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

if(!class_exists('DS_ReferralCodes')):
/**
 * DS_ReferralCodes class.
 * Display drugs of Featured Manufacturer.
 *
 * @since 1.0.0
 */

register_activation_hook( __FILE__, array( 'DS_ReferralCodes', 'ds_referral_code_install'));
register_deactivation_hook( __FILE__, array( 'DS_ReferralCodes', 'ds_referral_code_deactivate'));

class DS_ReferralCodes
{  

    private static $instance;
    const VERSION = '1.0.0'; 

    private static function has_instance() {
        return isset(self::$instance) && self::$instance != null;
    }

    public static function get_instance() {
        if (!self::has_instance())
            self::$instance = new DS_ReferralCodes;
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
        add_action( 'admin_head', array( $this, 'ds_r_c_scripts' )); 
        add_action( 'admin_menu', array( $this,'ds_r_c_menu' )); 
        add_action( 'admin_notices', array( $this, 'ds_r_c_notices'));  

        // Add Featured Manufacturer Section to Home page
        // add_action('ds_featured_manufacturer', array( $this,'ds_feature_manufacturer'));
    } 

    // Create Schema for DS Referral Codes
    public function ds_referral_code_install()
    { 
      global $ds_ref_code_db_version;
      $ds_ref_code_db_version = '1.0'; 

      $table_name = $wpdb->prefix . 'ds_referral_codes';        
      /*
       * We'll set the default character set and collation for this table.
       * If we don't do this, some characters could end up being converted 
       * to just ?'s when saved in our table.
       */
      $charset_collate = '';

      if ( ! empty( $wpdb->charset ) ) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
      }

      if ( ! empty( $wpdb->collate ) ) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
      }

      $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          id int PRIMARY KEY AUTO_INCREMENT,
          referral_code varchar(50) NOT NULL, 
          distributor_id int NOT NULL,
          url varchar(250) NOT NULL, 
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
      )$charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $sql );

      add_option( 'ds_pricemodel_db_version', $ds_pricemodel_db_version );
    }

    public function ds_r_c_scripts()
    {     
        wp_enqueue_script('jquery');  
        wp_enqueue_style( 'dataTables-css'); 
        wp_enqueue_script('dataTables-js');

        wp_enqueue_script('dsr-chosen-js', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js' , array('jquery'));
        wp_enqueue_style('dsr-chosen-css', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css');    
    }

    public function ds_r_c_menu() {
      add_options_page( 
        'DS Referral Codes', 
        'DS Referral Codes', 
        'manage_options', 
        'ds-referral-codes', 
        array($this, 'ds_referral_code_setting_html' )
      );
    }

    public function ds_r_c_notices()
    {
      $manufacturer = $_POST['f_manufacturer'];
      if( isset($manufacturer) && $manufacturer != "" && is_string($manufacturer)){
        echo '<div class="updated"><p>';
        echo __('Your <b>Featured Manufacturer</b> updated successfully');
        echo "</p></div>";
      } 
    }

    // Get number of products a Manufacturer has
    public function getProductCount($manufacturer){ 

      $query_args = array(  
          'post_status'   => 'publish',
          'post_type'     => 'product',   
          'ignore_sticky_posts'   => 1,
      );

      $query_args['tax_query'] = array(
        array(
            'taxonomy'=> 'pa_manufacturer',
            'field'   => 'slug',
            'terms'   =>  $manufacturer
        )
      );

      $products = new WP_Query( $query_args );

      return $products->post_count;
    }

    // Settings Page for DrugStoc Referral Code  
    public function ds_referral_code_setting_html() {
      global $wpdb;

      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        exit;
      }    

      // Add New referral code  
      $ref_dist = $_POST['dsr_dist'];
      $ref_url  = isset($_POST['dsr_url'])? sanitize_text_field($_POST['dsr_url']):''; 
      $ref_code = isset($_POST['dsr_refcode'])? strtoupper(sanitize_text_field($_POST['dsr_refcode'])):''; 
      $ref_auto = isset($_POST['dsr_auto'])? $_POST['dsr_auto']:"";

      if($ref_auto == "yes") $ref_code = $this->ds_generate_code();   

      if( !empty($_POST['dsr_dist']) && !empty($ref_code) && !empty($ref_url) ){ 

        if(!$this->ds_ref_code_exists($ref_code, $ref_url)){ // if ref_code is empty it will return false

          $new = $wpdb->insert(
            $wpdb->prefix . 'ds_referral_codes', 
            array( 
              'referral_code'  => $ref_code, 
              'distributor_id' => $ref_dist,
              'url'            => $ref_url, 
              'created_at'     => date('Y-m-d H:m:s')
            ));

          echo '<div class="updated"><p>Referral Code Created </p></div>';

        }else{?>
          <div class="updated"><p>Referral Code Already Exists</p></div>
        <?php
        } 
      }

      // Delete Referral Code
      if(isset($_POST['dsr_delete']) && !empty($_POST['dsr_delete'])){ 
        $wpdb->delete( $wpdb->prefix.'ds_referral_codes', array( 'id' => $_POST['dsr_delete'] ), array( '%d' ) );
      }

      // Compile list of Distributors
      $distributor_list[] = "-- Select --"; 
      $distributors = $wpdb->get_results("SELECT * FROM wp_usermeta WHERE meta_key like 'primary_distributor'");
      if(count($distributors) > 0){
        foreach ($distributors as $key => $distributor)  $distributor_list [ $distributor->user_id ] = get_user_meta($distributor->user_id,'institution', true);
      } 

      // Get All Ref codes
      $refcodes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ds_referral_codes");
      ?>

      <h2>DrugStoc Referral Codes</h2>
      <p><i>All Referral Codes</i></p> 
      <div style="width:70%; float:left; background-color:#DCE3E0">
        <form method="post">
          <table id="ds_referral_codes_table" class="table table-striped hover display compact" cellspacing="0">
            <thead>
              <tr>
                <th>#</th>
                <th>Distributor</th>
                <th>URL</th>
                <th>Referral Code</th>
                <th>Issued</th>
                <th></th>
              </tr> 
            </thead>
            <tbody>
              <?php 
              if(count($refcodes) > 0){
                foreach ($refcodes as $key => $refcode) {?>
                <tr>
                  <td><?php echo ($key+1); ?></td>
                  <td> 
                    <?php echo $distributor_list[$refcode->distributor_id];?>
                  </td>
                  <td><?php echo $refcode->url; ?></td>
                  <td><?php echo $refcode->referral_code; ?></td>
                  <td><?php echo date("d M Y", strtotime($refcode->created_at)); ?></td>
                  <td>
                    <form method="post">
                      <input type="hidden" name="dsr_delete" value="" />
                      <input type="submit" value="Delete" data-id="<?php echo $refcode->id;?>" onclick='if(confirm("Are you sure?") == false){ return false; }else{ jQuery(this).prev().val(jQuery(this).data("id")); };' />
                    </form>
                  </td>
                </tr> 
              <?php } 
              }else{ ?>
                <tr> <td colspan="5"> No Referral Codes </td> </tr> 
              <?php  
              }?> 
            </tbody>    
            <tfoot> 
                <tr>
                  <th></th>
                  <th><?php woocommerce_wp_select( array('class' => 'dsr_dist', 'id' => 'dsr_dist', 'label' => __( '', 'woocommerce' ), 'options' => $distributor_list, 'value' => $refcode->distributor_id ) );?></th>
                  <th><input type="text" name="dsr_url" width="200px"/></th>
                  <th> 
                    Auto-generate code:&nbsp;<input type="checkbox" name="dsr_auto" value="yes" alt="Auto-generate referral code" title="Auto-generate referral code" onchange="jQuery(this).next().css( 'visibility', this.checked ? 'hidden' : 'visible' );" />
                    <input type="text" name="dsr_refcode" maxlength="8" style="width:100px" />
                  </th>
                  <th><input type="hidden" name="dsr_date" /></th>
                  <th><input type="submit" class="button" value="Create" id="create_code" /></th>
                </tr>
              
            </tfoot>
          </table> 
        </form>
      </div>
      <script defer type="text/javascript">
        jQuery(".dsr_dist").chosen({no_results_text: "No Distributor found!"});
        jQuery("#ds_referral_codes_table").dataTable({ "order": [[ 3, "desc" ]] });
      </script>
    <?php

    }// EOF

    // Check if referral code already exists
    private function ds_ref_code_exists($refcode, $url = '')
    { 
      global $wpdb;

      $q = "SELECT * FROM {$wpdb->prefix}ds_referral_codes WHERE referral_code='{$refcode}'";
      if($url != '') $q .= " AND url = '{$url}'";

      $refcodes = $wpdb->get_results($q);

      if(count($refcodes) > 0) return true;
      else return false;  
    }   

    // Generate six random alpha numeric code
    private function ds_generate_code()
    {  
      $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $base = strlen($charset);

      $result = ''; 
      $now = explode(' ', microtime())[1];
      while ($now >= $base){
        $i = $now % $base;
        $result = $charset[$i] . $result;
        $now /= $base;
      }
      $code = substr($result, -6);

      return ($this->ds_ref_code_exists($code))? $this->ds_generate_code():$code; 
    } 

    // Deactivate DS Referral Code
    public function ds_referral_code_deactivate()
    {
      if ( ! current_user_can( 'activate_plugins' ) )
            return;
      // Ensure request is from Admin Plugins Page
      $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
      check_admin_referer( "deactivate-plugin_{$plugin}" ); 
    }
 
    // Get URL from Referral Code
    public static function geturl($code)
    { 
      global $wpdb;

      $q = "SELECT url FROM {$wpdb->prefix}ds_referral_codes WHERE referral_code='{$code}'";

      $refcodes = $wpdb->get_results($q);

      if(count($refcodes) > 0) return $refcodes[0]->url;
      else return "";   
    } 

    // Get Distributor Name by key
    // DS_ReferralCodes::getDistributorNamebyKey($key)
    public static function getDistributorNamebyKey($key)
    {
      $user_args = array( 
          'role'         => 'shop_manager',
          'meta_key'     => 'primary_distributor',
          'meta_value'   => $key,
          'meta_compare' => '=',
          'number'       => '1'
      );
      $user = current(get_users($user_args));

      return ($user)? get_user_meta($user->ID, 'institution', true):"";
    }  

    // DS_ReferralCodes::getDistributorIDbyKey($key)
    public static function getDistributorID($key)
    { 
      $user_args = array( 
          'role'         => 'shop_manager',
          'meta_key'     => 'primary_distributor',
          'meta_value'   => $key,
          'meta_compare' => '=',
          'number'       => '1'
      );
      $user = current(get_users($user_args));

      return ($user)? $user->ID : 0;
    }
}
endif;

// Activate Plugin 
DS_ReferralCodes::setup();

