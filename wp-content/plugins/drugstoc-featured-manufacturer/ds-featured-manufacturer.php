<?php
/**
 * Plugin Name: Feature Manufacturer   
 * Plugin URI: http://integrahealth.com.ng/integraitlabs.php
 * Description: Display drugs of Featured Manufacturer.
 * Version: 1.0.0
 * Author: Caleb Chinga | Drugstoc
 * Author URI: http://integrahealth.com.ng
 * Text Domain: cpac
 * Domain Path: /languages
 * License: GPL2
 */

/*  Copyright 2014  DRUGSTOC_FEATURED_MANUFACTURER  (email : info@drugstoc.biz)

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

if(!class_exists('DS_FeaturedManufacturer')):
/**
 * DS_FeaturedManufacturer class.
 * Display drugs of Featured Manufacturer.
 *
 * @since 1.0.0
 */
class DS_FeaturedManufacturer
{ 
    private static $instance;
    const VERSION = '1.0.0';

    private static function has_instance() {
        return isset(self::$instance) && self::$instance != null;
    }

    public static function get_instance() {
        if (!self::has_instance())
            self::$instance = new DS_FeaturedManufacturer;
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
        add_action( 'admin_head', array( $this, 'ds_f_m_scripts' )); 
        add_action( 'admin_menu', array( $this,'ds_f_m_menu' )); 
        add_action( 'admin_notices', array( $this, 'ds_f_m_notices'));  

        // Add Featured Manufacturer Section to Home page
        add_action('ds_featured_manufacturer', array( $this,'ds_feature_manufacturer'));
    	add_shortcode( 'featured_manufacturer', array( $this, 'ds_feature_manufacturer'));
    }

    function ds_f_m_scripts()
    {     
        wp_enqueue_script('additional-chosen-js', get_template_directory_uri() . '/js/chosen.jquery.min.js' , array('jquery'));
        wp_enqueue_style('additional-chosen-css', get_template_directory_uri() . '/js/chosen.css');
    }

    function ds_f_m_menu() {
      add_options_page( 'Feature a Manufacturer', 'Feature a Manufacturer', 'manage_options', 'front-page-manufacturer', array($this, 'manufacturer_product_setting_html' ));
    }

    function ds_f_m_notices()
    {
      $manufacturer = $_POST['f_manufacturer'];
      if( isset($manufacturer) && $manufacturer != "" && is_string($manufacturer)){
        echo '<div class="updated"><p>';
        echo __('Your <b>Featured Manufacturer</b> updated successfully');
        echo "</p></div>";
      } 
    }

    // Get number of products a Manufacturer has
    function getProductCount($manufacturer){ 

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

    // Settings Page for Feature Drugs by Manufacturer plugin 
    function manufacturer_product_setting_html() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
      }   
	
      $manufacturer = $_POST['f_manufacturer'];
      if( isset($manufacturer) && $manufacturer != "" && is_string($manufacturer)){
        if($this->getProductCount($manufacturer) > 3){ // Check if Manufacturer has up to 4 products  
          //Set wp option for featured manufacturer
          update_option( 'ds_featured_manufacturer', $manufacturer );
        }else{?>
          <i><h3> Please select a manufacturer with at least four products </h3></i>
        <?php
        }
      }                                                                                                                         

      // Compile list of Manufacturers
      $manufacturers[] = "--- Select A Manufacturer ---";

      $m_list = get_terms('pa_manufacturer'); 
      foreach ($m_list as $key => $manufacturer) {
        $manufacturers [ $manufacturer->name ] = __($manufacturer->name, 'woocommerce')." - ".$this->getProductCount($manufacturer->name)." product(s)";
      }?>

      <h2>Feature a Manufacturer</h2>
      <p><i>Select a Manufacturer to be featured on the Home Page </i></p>
      <form method="post">
      <?php
      woocommerce_wp_select( 
        array( 
          'id'      => 'f_manufacturer', 
          'label'   => __( 'Manufacturers on DrugStoc  ', 'woocommerce' ), 
          'options' => $manufacturers,
          'value'  => get_option('ds_featured_manufacturer'),
          'desc_tip' => true,
          'description'   => __( 'Select a Manufacturer to be featured on the Home Page', 'woocommerce' ) 
          )
        );
      ?>  
      <input type="submit" value="Set Manufacturer" class="button button-primary button-large" />
      </form><br/><br/>
      <script defer type="text/javascript">
        jQuery("#f_manufacturer").chosen({no_results_text: "No Manufacturer found!"});
      </script> 
    <?php 
    
    }

    // Pick a random manufacturer from the top 25
    function get_random_manufacturer(){
      global $wpdb;
 
      $m_list = $wpdb->get_results("SELECT t.name, t.slug, count(t.term_id) as no_of_pdts FROM wp_terms as t INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id INNER JOIN wp_term_relationships as wtr on wtr.term_taxonomy_id = p.term_taxonomy_id WHERE p.taxonomy LIKE 'pa_manufacturer' and t.name != 'CHECK PICTURE' GROUP BY t.name ORDER BY no_of_pdts DESC LIMIT 30");
 
      if(count($m_list) > 0) return $m_list[ array_rand($m_list) ]->name;
      else return null;

     }

    // Show featured Manufacturer's drugs
    // [featured_manufacturer]
    function ds_feature_manufacturer()
    { 
      // $manufacturer = $this->get_random_manufacturer(); // Randomly select a manufacturer

      // if($this->getProductCount($manufacturer) < 3) // Re-run if less than 3
      //   $manufacturer = $this->get_random_manufacturer();  

      $manufacturer = 'Afrab-Chem Ltd.'; 
 
      $args['meta_query'][] = array(
          'key'     => '_visibility',
          'value'   => array('catalog', 'visible'),
          'compare' => 'IN'
      );

      $query_args = array(
          'posts_per_page'=> '3', 
          'post_status'   => 'publish',
          'post_type'     => 'product', 
          'order'         => 'desc', 
          'meta_query'  => $args['meta_query'],
          'ignore_sticky_posts'   => 1,
      );

      $query_args['tax_query'] = array(
        array(
            'taxonomy'=> 'pa_manufacturer',
            'field'   => 'slug',
            'terms'   =>  $manufacturer
        )
      );
      
      global $woocommerce_loop;

      $products = new WP_Query( $query_args );

      global $woocommerce_loop;
      $woocommerce_loop['loop'] = 0;
      if ( isset( $layout ) && $layout != 'default' ) $woocommerce_loop['layout'] = 'classic';

      if ( $products->have_posts() ) : ?>
        <div id="manufacturer" class="row">
          <div class="woocommerce">
            <div class="row ft-box">
              <div class="span3">
                  <h3 style="margin-bottom: 0">
                    Manufacturer
                  </h3>
                  <p style="margin-top: 0">of the hour</p>
                  <h1><?php echo $manufacturer; ?></h1>
                  <p>
                    <a href="<?php echo home_url('/manufacturers/');?>">See All Manufacturers</a>
                  </p>
              </div>
              <div class="span9"> 
                <ul class="products row" style="margin-bottom: 0">
                  <li class="span3 product type-product ">
                  </li>
                  <?php while ( $products->have_posts() ) :  $products->the_post(); ?>
                    <?php
                          if ( function_exists( 'wc_get_template_part')){
                              wc_get_template_part('content', 'product');
                          }else{
                              woocommerce_get_template_part( 'content', 'product' );
                          }
                    ?>
                  <?php endwhile; // end of the loop. ?>
                </ul>
              </div>
            </div>
            <div class="region-badges-extra row">
              <div class="span3">
                <span style="font-weight: 500;">DrugStoc Standards:</span>
              </div>
              <div class="span9">
                <div class="one-rating-r">
                  <span class="rbadge">V</span>
                  <span>Verified</span>
                </div>
                <div class="one-rating-r">
                  <span class="rbadge">I</span>
                  <span>Inspected</span>
                </div> 
                <div class="one-rating-r">
                  <span class="rbadge">U</span>
                  <span>Unverified</span>
                </div>
              </div>
            </div>
            </div>
          </div>
          <div class="clear"></div> 
        </div>  
      <?php endif; 

      wp_reset_query();       
                             
      $woocommerce_loop['loop'] = 0;      
    }   
}
endif;

// Activate Plugin 
DS_FeaturedManufacturer::setup();