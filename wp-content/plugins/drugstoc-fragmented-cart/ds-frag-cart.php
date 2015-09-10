<?php
/**
 * Plugin Name: DrugStoc Grouped Cart  
 * Plugin URI: http://integrahealth.com.ng/integraitlabs.php
 * Description: Display and manage drugs added to a Cart grouped per supplier
 * Version: 1.0.0
 * Author: Drugstoc | IntegraIT Labs
 * Author URI: http://integrahealth.com.ng
 * Text Domain: cpac
 * Domain Path: /languages
 * License: GPL2
 *
====================================================================================
    
    Copyright 2014  DS_GROUP_CART  (email : info@drugstoc.biz)

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

====================================================================================
*/
defined('ABSPATH') or die("No script kiddies please!"); 

if(!class_exists('DS_Frag_Cart')):

register_activation_hook( __FILE__, array( 'DS_Frag_Cart', 'ds_cart_install'));
register_deactivation_hook( __FILE__, array( 'DS_Frag_Cart', 'ds_cart_deactivate'));


/**
 * DS_Frag_Cart class.
 * Display and manage DrugStoc Commissions per order / item.
 *
 * @since 1.0.0
 */
class DS_Frag_Cart
{ 
    private static $instance; 

    /**
     * Table Name
     *
     * @var string
     * @since 2.0.0
     */
    public $table_name;

    const VERSION = '1.0.0';
 
    private static function has_instance() {
        return isset(self::$instance) && self::$instance != null;
    }

    public static function get_instance() {
        if (!self::has_instance())
            self::$instance = new DS_Frag_Cart;
        return self::$instance;
    }

    public static function setup() {
        self::get_instance();
    }

    protected function __construct() {
        global $wpdb;

        if (!self::has_instance()) {
            add_action('init', array(&$this, 'init'));  
        } 

        $this->table_name = $wpdb->prefix.'ds_cart';
        add_action('init', array($this, 'remove_loop_button'));
    } 

    // Plug into all necessary actions and filters
    function init(){
        // Actions  
        add_action( 'wp_enqueue_scripts', array($this, 'ds_cart_scripts' )); 
        add_action( 'yit_header_cart', array($this, 'ds_add_dscart_link'));
        // add_action( 'yit_after_header_right_content', array($this, 'ds_add_dscart_link'));

        add_action( 'woocommerce_before_add_to_cart_button', array($this, 'ds_add_to_cart_button')); 
        add_action( 'woocommerce_before_add_to_cart_button', array($this, 'show_all_distributor_prices' ));
        add_action( 'woocommerce_after_shop_loop_item', array($this, 'replace_add_to_cart' ));
        add_action( 'woocommerce_before_calculate_totals', array($this, 'set_selected_distributor_price'), 1, 1 );

        // Order Actions
        add_action( 'woocommerce_checkout_update_order_meta', array($this, 'ds_add_distributor_order_meta'), 10, 2 );

        // Ajax Actions
        add_action( 'wp_ajax_load-dscart', array( $this, 'ds_load_dscart' ));
        add_action( 'wp_ajax_add-to-dscart', array( $this, 'ds_add_to_cart' ));
        add_action( 'wp_ajax_update-dscart', array( $this, 'ds_update_cart' ));
        add_action( 'wp_ajax_remove-from-dscart', array( $this, 'ds_remove_from_cart' )); 
        add_action( 'wp_ajax_checkout-to-wc-cart', array( $this, 'ds_checkout_to_wc_cart' ));  

        // Add Shortcode for Drugstoc Order Basket
        add_shortcode( 'drugstoc_cart_items', array( $this, 'show_all_drugstoc_cart_items'));
    }

    // Create Schema for DS Price Model
    public function ds_cart_install(){
 
        global $wpdb, $ds_cart_db_version;  

        $table_name = $wpdb->prefix.'ds_cart';
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

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id int PRIMARY KEY AUTO_INCREMENT,
            user_id int NOT NULL,  
            product_id int NOT NULL,
            quantity int DEFAULT 1 NOT NULL,
            price float NOT NULL, 
            distributor varchar(50) NOT NULL, 
            created_at datetime NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
        )$charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        add_option( 'ds_cart_db_version', $ds_cart_db_version );
    }

    // Remove Loop buton
    public function remove_loop_button(){
        if ( is_plugin_active( 'drugstoc-fragmented-cart/ds-frag-cart.php'  ) ) {
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        }
    } 

    // Replace add to cart button with link
    public function replace_add_to_cart() {
        if ( is_plugin_active( 'drugstoc-fragmented-cart/ds-frag-cart.php'  ) ) {
            global $product;  
            echo '<p class="btn-v-p"><a href="' . esc_attr($product->get_permalink()) . '" > View Prices </a></p>';
        }  
    }

    // Enqueue Scripts for plugin
    public function ds_cart_scripts()
    {     
        wp_enqueue_script('dsr-chosen-js', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js' , array('jquery'));
        wp_enqueue_style('dsr-chosen-css', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css'); 
        wp_enqueue_script('ds-cart-js', plugins_url("/drugstoc-fragmented-cart/js/ds-cart.js") , array('jquery'));
        wp_enqueue_style('ds-cart-css', plugins_url("/drugstoc-fragmented-cart/css/ds-f-c.css"));
        wp_localize_script('ds-cart-js', 'ds_cart', array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ), 
            'home_url'      => home_url('/'),
            'ds_cart_nouce' => wp_create_nonce( 'ds_cart_ajax_nouce' )
        )); 
    } 

    // Add to Cart Button 
    public function ds_add_to_cart_button(){
        global $product;

        $show = false;
        $user = wp_get_current_user();
        $premium_user = get_user_meta($user->ID, 'ds_premium_user', true);
        $distributor = get_user_meta($user->ID, 'primary_distributor', true);
        $pdt_dist = get_post_meta($product->id, $distributor, true);

        if($premium_user == 0){  // Basic = 0, Premium = 1

            if($distributor != '' && floatval($pdt_dist) > 0){ // Basic referred by Distributor
                $show = true;
            } 
        }else $show = true;  

        if($show == true){
        ?>
            <input type="button" value="<?php echo ($this->is_product_in_ds_cart($product->id))? "Added":"Add";?> to Order Basket" id="ds-addtocart" class="button" />
        <?php
        }else{?> 
            <a class="button" href="<?php echo home_url('/my-account');?>">Upgrade to Order</a>
            <script type="text/javascript">
            jQuery(document).ready(function(event) { // Dirty Hack - hide all prices, quantity and my ds button
                jQuery('div[itemprop=offers], div.variations_button.simple_product, div.wishlist').remove();
            }); 
            </script>
        <?php
        }
    } 
 
    // Add Cart link to Header
    public function ds_add_dscart_link(){ ?> 
        <div class="woo_cart">
            <div class="yit_cart_widget widget_shopping_cart">
                <div class="cart_label">
                    <a href="<?php echo home_url('/ds-cart');?>" class="cart-items">
                        Order Basket <span class="dscart-items-number"><?php echo $this->count();?></span>
                    </a>
                </div> 
                <div class="cart_wrapper" style="display: none;">
                    <span class="cart_arrow"></span>
                    <div class="widget_shopping_cart_content">   
                    </div>
                </div>  
            </div>
        </div>
        <?php
    }

    // Show all prices on Single Product Page
    public function show_all_distributor_prices(){ 
        global $product, $wpdb;

        $current_user = wp_get_current_user();
        $primary_distributor = get_user_meta($current_user->ID, 'primary_distributor', true);

        //if we have a primary distributor
        $distributors = $wpdb->get_results("SELECT post_id, meta_key, meta_value FROM `wp_postmeta` WHERE `meta_key` LIKE '".((strlen($primary_distributor) > 0)? $primary_distributor : '%_price')."' and meta_key NOT IN ('_price','_regular_price','_sale_price') group by meta_key"); 

        if(count($distributors) > 0){
            // div to hold all prices
            echo '<div id="allprices" style="height: 150px; border-top: 1px solid #F2F2F2; border-bottom: 1px solid #F2F2F2; overflow-y: auto; overflow-wrap: normal;padding-left: 5px;" >';
	     
            foreach ($distributors as $key => $dist) {
                # code...
                $dist_price = floatval(get_post_meta($product->id, $dist->meta_key, true));
		
                if( $dist_price > 0 ){ ?> 
                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">   
                        <?php 
                        //echo "$dist->meta_key:<br> ";
                        if(is_user_logged_in() ){ 
                            //echo "$dist_price : <br> ";
                            // Add additional user role checks here <<<< 
                            $user = $current_user;
                            $premium_user = get_user_meta($user->ID, 'ds_premium_user', true);
                            $distributor = get_user_meta($user->ID, 'primary_distributor', true);
                            $pdt_dist = get_post_meta($product->id, $distributor, true);
                 
                            // Free User
                            if($premium_user == 0){ // Not Premium = 0, Premium = 1 
                                if($distributor != '' && floatval($pdt_dist) > 0){ // Free User referred by Distributor
                                    $show = true;
                                } 
                            }else $show = true;  
                        ?>
                        <input type="radio" name="_supplier_price" class="supplier_price" value="<?php echo $dist_price;?>" data-pdtid="<?php echo $dist->post_id;?>" data-supplier="<?php echo $dist->meta_key;?>"/>
                        <p itemprop="price" class="price">
                            <span class="amount"><?php echo wc_price($dist_price);?></span>
                        </p>
                        <?php 
                        }else{
                            echo '<p style="color:'. get_option('yith_hide_price_change_color') .'"><a style="display:inline; color:'. get_option('yith_hide_price_change_color') .'" href="' .get_permalink(function_exists( 'wc_get_page_id' ) ? wc_get_page_id('myaccount') : woocommerce_get_page_id('myaccount')). '">'. get_option('yith_hide_price_link_text') .'</a> '. get_option('yith_hide_price_text').'</p>';
                        }?> 
                        <span>&nbsp;<a href="<?php echo home_url('/vendor/'.substr($dist->meta_key, count($dist->meta_key)-1, -6));?>"><?php echo (is_user_logged_in())? DS_Util::getDistributorNamebyKey($dist->meta_key):'';?></a></span>
                        <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
                        <link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
                    </div>   
                    <?php
                }    
            }
            echo "</div>";
        }?> 
        <input type="hidden" name="supplier_key" id="supplier_key" data-pdt="<?php echo $product->id;?>" value="" />
        <input type="hidden" name="supplier_price" id="supplier_price" value="" /><br/>
        <?php 
    }

    // [drugstoc_cart_items]
    public function show_all_drugstoc_cart_items(){
        global $wpdb, $woocommerce;

        if( is_user_logged_in() ) {  
            $user_id = get_current_user_id(); 
            
            $sql = sprintf( "SELECT *,(quantity*price) as linetotal FROM {$wpdb->prefix}ds_cart WHERE user_id = %d ORDER BY distributor, created_at", $user_id );

            $all_items = $wpdb->get_results($sql);

            if( count($all_items) > 0 ){ 
 
                $line_total = $cart_total = 0; 
                $supp_key = '';
                ?>
                <div class="woocommerce">
                    <div class="woocommerce_cart row">
                        <div class="woocommerce-message" style="display:none"></div>
                        <div class="span9"> 
                            <h3>Order Basket</h3>
                            <table class="shop_table cart" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="product-remove">&nbsp;</th>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name"></th> 
                                        <th class="product-quantity"><?php echo __( 'Quantity', 'woocommerce' ); ?></th>
                                        <th class="product-subtotal"><?php echo __( 'Total', 'woocommerce' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach ($all_items as $cart_item_key => $values) {
                                        $values = (array) $values;

                                        $_product = new WC_Product($values['product_id']);

                                        $cart_total += $values['linetotal'];

                                        if($supp_key != $values['distributor'] ){ 
                                            $supp_key = $values['distributor']; ?>
                                        <tr style="background-color: #EBEFF3" class="dist_subhead">
                                            <td colspan='5'>
                                                <?php echo DS_Util::getDistributorNamebyKey($supp_key); //get_user_meta($user->ID, 'institution', true);?> 
                                                <input class="checkout-button button alt move_to_cart" style="float:right;font-weight: lighter; width:180px" data-supplier="<?php echo $supp_key;?>" name="proceed" value="Checkout this supplier →">
                                            </td>
                                        </tr> 
                                        <?php 
                                        }

                                        if ( $_product->exists() && $values['quantity'] > 0 ) {?>
                                        <tr class="cart_table_item" >
                                            <!-- Remove from cart link -->  
                                            <td class="dscartitem product-remove" data-dscartid="<?php echo $values['id'];?>" data-line="<?php echo $values['product_id'];?>" >
                                                <a class="remove" style="cursor:pointer" title="Remove this item">×</a>
                                            </td>

                                            <!-- The thumbnail -->
                                            <td class="product-thumbnail">
                                                <?php echo $_product->get_image();?>
                                            </td>

                                            <!-- Product Name -->
                                            <td class="product-name"> 
                                                <a href="<?php echo get_permalink($values['product_id']);?>"> <?php echo $_product->get_title();?></a>
                                                &nbsp;&nbsp; <b><?php echo wc_price($values['price']); ?></b> 
                                            </td>

                                            <td class="product-quantity"> 
                                                <!-- <form> -->
                                                <?php
                                                if ( $_product->is_sold_individually() ) {
                                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                } else {
                                                    $product_quantity = woocommerce_quantity_input( array(
                                                        'input_name'  => "dscart_{$_product->id}",
                                                        'input_value' => $values['quantity'],
                                                        'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                                        'min_value'   => '1'
                                                    ), $_product, false );

                                                    echo $product_quantity; 
                                                }?> 
                                                <!-- </form> -->
                                            <!-- <a>Update Qty</a> -->
                                            </td>

                                            <!-- Product subtotal -->
                                            <td class="product-subtotal">
                                                <?php echo wc_price($values['linetotal']);?>
                                            </td>

                                        </tr>
                                        <?php    
                                        }
                                    }?>  
                                <?php
                                ?>
                                </tbody>
                            </table>
                        </div> 
                        <div class="span3">  
                            <div class="sidebar cart_totals_container">  
                                <div class="cart_totals "> 
                                    <h2>Order Basket Summary</h2> 
                                    <?php $supp_totals = $this->ds_supplier_subtotals(); ?>
                                    <table cellspacing="0"> 
                                        <tbody>
                                            <?php 
                                            foreach ($supp_totals as $key => $total) {?>
                                            <tr class="cart-subtotal">
                                                <th colspan="2"><?php echo DS_Util::getDistributorNamebyKey($total->distributor);?></th>
                                                <td><span class="amount"><?php echo wc_price($total->subtotals);?></span></td>
                                            </tr>
                                            <?php    
                                            }?>
                                            <tr class="cart-subtotal" style="border-top: 1px solid black">
                                                <th colspan="2">Total:</th>
                                                <td><span class="amount"><?php echo wc_price($cart_total);?></span></td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" style="float:left;"> 
                                                    <input type="button" class="checkout-button button alt update_basket" style="font-weight: lighter; width:130px; background-color:#838280; text-align: center;" value="Update Basket"/>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php  
            } else { 
                echo "<h4>No items in your Order Basket</h4>";
            }
        } else { 
            echo "<h4>Restricted to Users only, Thanks.</h4>";
        }
    }

    // Load items into Cart    
    public function ds_load_dscart()
    { 
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ds_cart_ajax_nouce' ) )
            die ( 'Busted!');
        
        wp_send_json( array( 'code' => 1, 'order_items' => $this->order_items() ));
    }

    // Add Item to DS Frag Cart
    public function ds_add_to_cart(){

        global $wpdb;

        if( is_user_logged_in()){     
            // check to see if the submitted nonce matches with the
            // generated nonce we created earlier
            if ( ! wp_verify_nonce( $_POST['nonce'], 'ds_cart_ajax_nouce' ) )
                die ( 'Busted!'); 

            $prod_id = $_POST['pID'];
            $user_id = get_current_user_id();
            $quantity = $_POST['qty'];
            $price = $_POST['price'];  // Get value from post
            $supplier =  sanitize_text_field($_POST['dist']);

            // Check if already in cart
            if(!$this->is_product_in_ds_cart($prod_id, $supplier) ){
                $insert_args = array(
                    'user_id'    => $user_id,
                    'product_id' => $prod_id, 
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'distributor'=> $supplier,
                    'created_at' => date( 'Y-m-d H:i:s' )
                );

                // Insert into DB
                $result = $wpdb->insert( $this->table_name, $insert_args ); 

                $content = $this->order_items();

                if($result) wp_send_json( array( 'code' => 1, 'message' => 'Product Added to Order Basket', 'count' => $this->count(), 'order_items' => $content ));
                else wp_send_json( array( 'code' => 0, 'message' => "An Error occured - Couldn't add Product to Cart" ));
            }else{ // Update quantity 
                $update = $this->update_product_in_ds_cart($prod_id, $supplier, $quantity);
                
                if($update == true) wp_send_json( array( 'code' => 1, 'message' => 'Product Quantity Updated in Cart') );
                else wp_send_json( array( 'code' => 0, 'message' => 'Could Not Update Product in Cart' ) );
            }   
        } 
    }

    // Update Product Count in Cart
    public function ds_update_cart(){
        global $wpdb;

        if( is_user_logged_in()){  
            if ( ! wp_verify_nonce( $_POST['nonce'], 'ds_cart_ajax_nouce' ) )
                die ( 'Busted!'); 

            $items = isset($_POST['data']) ? $_POST['data'] : array();
            if(count($items) > 0){
                foreach ($items as $key => $item) {
                    $update = $this->update_product_in_ds_cart($item['pID'], null, $item['qty']);
                    if($update == false)
                        wp_send_json( array( 'code' => 0, 'message' => 'Could Not Update Product ' ) );
                }    
                
                wp_send_json( array( 'code' => 1, 'message' => "Product Quantity Updated ") );
            } 
        }
    }
 
    // Remove item from cart
    public function ds_remove_from_cart(){
        global $wpdb;

        if( is_user_logged_in()){     
            // check to see if the submitted nonce matches with the
            // generated nonce we created earlier
            if ( ! wp_verify_nonce( $_POST['nonce'], 'ds_cart_ajax_nouce' ) )
                die ( 'Busted!'); 

            $user_id = get_current_user_id(); 
            $dscartid = $_POST['dscartid'];

            $sql = "DELETE FROM {$this->table_name} WHERE id = %d ";
            $sql_args = array( 
                $dscartid
            );

            $result = $wpdb->query( $wpdb->prepare( $sql, $sql_args ) );

            if ( $result )  
                wp_send_json( array( 'code' => 1, 'message' => 'Product Removed from Cart', 'count' => $this->count() ));
            else 
                wp_send_json( array( 'code' => 0, 'message' => 'Error Processing Request - Remove Item from Cart' )); 
        }
    }
  
    /**
     *  Checkout Items from DS Cart to WC Cart
     *  1. Create WC Cart 
     *  2. Add items to WC Cart 
     *
     * @access public
     * @return string
     */
    public function ds_checkout_to_wc_cart( ){

        global $wpdb;//, $woocommerce;

        if( is_user_logged_in()){     
            // check to see if the submitted nonce matches with the
            // generated nonce we created earlier
            if ( ! wp_verify_nonce( $_POST['nonce'], 'ds_cart_ajax_nouce' ) )
                die ( 'Busted!'); 

            $user_id = get_current_user_id(); 
            $supp_key = isset($_POST['supp_key'])? sanitize_text_field($_POST['supp_key']):'';

            $sql = sprintf("SELECT product_id, quantity, price FROM {$this->table_name} WHERE user_id = %d AND distributor='%s'", $user_id, $supp_key);
            $items = $wpdb->get_results($sql);

            if(count($items) > 0){
                  
                foreach ($items as $key => $value) {
                    $value = (array) $value; 
                    $cart_item_key = WC()->cart->add_to_cart($value['product_id'], $value['quantity']);
                    // Set shipping class
                    wp_set_object_terms( $value['product_id'], $supp_key, 'product_shipping_class' );
                }

                // Add distributor key to session
                WC()->session->set('distributor_order', $supp_key ); 

                wp_send_json( array( 'code' => 1, 'message' => 'Products Ready for Checkout' ));
            } else wp_send_json( array( 'code' => 0, 'message' => 'No Products to Checkout' ));
        } 
    } 

    // Set Selected Distributor price for item in cart
    public function set_selected_distributor_price( $cart_object ) {
        foreach ( $cart_object->cart_contents as $cart_item_key => $value ) {       
            $value['data']->price = $this->get_selected_price($value['product_id']); 
        }
    }

    // Add Distributor Order meta
    // Route to distributor 
    public function ds_add_distributor_order_meta( $order_id, $posted ) {  
        global $wpdb, $woocommerce;

        // Set Order meta
        if(WC()->session->get('distributor_order') != ""){

            $supplier = WC()->session->get('distributor_order');

            update_post_meta( $order_id, 'distributor_order', $supplier);
            
            // Remove Order Items from DS Cart 
            $order = new WC_Order( $order_id );

            $items = $order->get_items(); 
            foreach ( $items as $item ) {    
                $remove = $this->ds_remove_item($item['product_id']);
            }
            
            // Route to distibutor here   
            DS_RouteOrder::route( $order_id, $supplier); 
            error_log("Routing order #$order_id to $supplier..."); // << Error Log
        
            // Unset session variable 
            WC()->session->__unset( 'distributor_order' );
        }
    }

    /*
     * ***************************************
     *        UTILITY FUNCTIONS BELOW
     * ***************************************
     */
    
    // Count items in Order Basket - per user
    public function count(){
        global $wpdb;
        
        $items = $wpdb->get_row( $wpdb->prepare("SELECT COUNT( distinct( product_id )) as `count` FROM {$this->table_name} WHERE `user_id` = %d", array( get_current_user_id() )) );
        
        return $items->count;
    }

    // Update Ajax Order Basket - per user
    public function order_items(){
        global $wpdb, $woocommerce;
        
        $items = $wpdb->get_results( $wpdb->prepare("SELECT count(product_id) as multiple, id, product_id, quantity, price, distributor FROM {$this->table_name} WHERE `user_id` = %d GROUP BY product_id ORDER BY created_at DESC LIMIT 10", array( get_current_user_id() )) );
        
        $html = "";
        if(count($items) > 0){ 
            $html = '<h2>Recently added items</h2><ul class="cart_list product_list_widget ">';
            foreach ($items as $key => $item) {
                $pdt  = new WC_Product($item->product_id);
                $html.= '<li><a href="'.get_permalink($item->product_id).'">'.get_the_post_thumbnail( $item->product_id, array(50, 50) )."<span> {$pdt->get_title()} </span></a>";
                $html.= '<a class="remove_item dscartitem" data-dscartid="'.$item->id.'" title="Remove this item" style="cursor:pointer">remove</a>';
                $html.= '<span class="quantity">'.$item->quantity.' × '.wc_price($item->price).'</span></li>';            
            }   
            $html .= '</ul><div><button class="button cart" style="text-align:center; width:100%">View Order Basket</button></div>'; 
        }else{
            $html .= '<p>No Products to display</p>'; 
        } 
        $html .= '</ul>'; 
 
        return $html;
    }

    // Get Selected price to be added to cart 
    public function get_selected_price($product_id){
        global $wpdb;

        $product = $wpdb->get_results(sprintf("SELECT price FROM {$this->table_name} WHERE product_id= %d AND user_id=%d LIMIT 1", $product_id, get_current_user_id()));
        
        return (empty($product))? 0 : $product[0]->price;
    }

    // Get all supplier subtotals in sidebar
    public function ds_supplier_subtotals(){
        global $wpdb;

        $totals = $wpdb->get_results("SELECT distributor, sum(quantity*price) as subtotals FROM {$this->table_name} group by distributor ORDER BY subtotals DESC");

        return (count($totals) > 0)? $totals : array();
    }

    /**
     * Remove an entry from ds cart.
     *
     * @return bool
     * @since 1.0.0
     */
    public function ds_remove_item( $id ) {
        global $wpdb; 

        $user_id = get_current_user_id();  

        $sql = "DELETE FROM {$this->table_name} WHERE product_id = %d AND user_id = %d ";
        $sql_args = array( $id, $user_id );

        $result = $wpdb->query( $wpdb->prepare( $sql, $sql_args ) );

        if ( $result ) { 
            return true;
        } else {
            return false;
        } 
    }

    /**
     * Check if the product exists in the ds cart.
     * 
     * @param int $product_id
     * @return bool
     * @since 1.0.0
     */
    public function is_product_in_ds_cart( $product_id , $supp_key = null ) {
        global $wpdb;
            
        $exists = false;
        $table_name = $wpdb->prefix.'ds_cart';
            
        if( is_user_logged_in() ) {    

            $user_id = get_current_user_id();

            $sql = "SELECT COUNT(*) as `count` FROM {$this->table_name} WHERE `product_id` = %d AND `user_id` = %d";
            $sql_args = array(
                $product_id,
                $user_id 
            ); 

            if(isset($supp_key) && !empty($supp_key)) {
                $sql .= " AND distributor LIKE '%s'";
                array_push($sql_args, $supp_key); // Distributor present
            }
            $results = $wpdb->get_var( $wpdb->prepare($sql, $sql_args) );
            $exists = (bool) ( $results > 0 );
        } 
        
        return $exists;
    }

    /**
     * Update product quantity.
     * 
     * @param int $product_id
     * @return bool
     * @since 1.0.0
     */
    public function update_product_in_ds_cart( $product_id , $supp_key = null, $qty = 1 ) {
        global $wpdb;

        $exists = false;
        if( is_user_logged_in() ) {    
            $user_id = get_current_user_id();

            $sql = "UPDATE {$this->table_name} SET quantity = %d WHERE `product_id` = %d AND `user_id` = %d";
            $sql_args = array(
                $qty,
                $product_id,
                $user_id 
            );  

            $results = $wpdb->update(   
                $this->table_name, 
                array( 'quantity' => $qty), 
                array( 'product_id' => $product_id, 'user_id' => $user_id), 
                array( '%d' ), 
                array( '%d', '%d' ) 
            );

            if ( false === $results ) return false;
            else return true;
        }
    }

    // Deactivate 
    public function ds_cart_deactivate()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        // Ensure request is from Admin Plugins Page
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );

        // delete_option('drugstoc_commission_per_order'); 
    }

    public function __destruct()
    {
        // update_option( 'drugstoc_commission_per_order',0);
    }
}
endif;

// Activate Plugin 
DS_Frag_Cart::setup();