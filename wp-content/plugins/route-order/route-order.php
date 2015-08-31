<?php
/**
 * Plugin Name: Route Order
 * Plugin URI: http://integrahealth.com.ng/integraitlabs.php
 * Description: Route a drugstoc order to designated distributor.
 * Version: 1.0.0
 * Author: Caleb Chinga | Drugstoc
 * Author URI: http://integrahealth.com.ng
 * Text Domain: cpac
 * Domain Path: /languages
 * License: GPL2
 */

/*  Copyright 2014  PLUGIN_AUTHOR_NAME  (email : info@drugstoc.biz)

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
defined('ABSPATH') or die("Oops you cannot access this script");  

if(!class_exists('DS_RouteOrder')):

// Create ds_routed_items_table
register_activation_hook( __FILE__, array( 'DS_RouteOrder', 'ds_routed_install' ) );
 
/**
 * DS_RouteOrder class.
 * Route a drugstoc order to designated distributor.
 *
 * @since 1.0.0
 */
class DS_RouteOrder
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
            self::$instance = new DS_RouteOrder;
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

        $this->table_name = $wpdb->prefix.'routed_order_items'; 
    }

    // Plug into all necessary actions and filters
    function init(){
        // Actions   
		add_action( 'admin_head', array($this, 'route_order_scripts' ) );
		add_action( 'admin_menu', array($this, 'register_route_order') );  

        // Ajax Actions
        add_action( 'wp_ajax_route-order', array( $this, 'ds_route_order' ));
    } 
 
	// Enqueue all scripts/styles needed
	function route_order_scripts() {
		wp_enqueue_style( 'ds-datatable-css', "//cdn.datatables.net/1.10.4/css/jquery.dataTables.min.css"); 
		wp_enqueue_script('jquery');
		wp_enqueue_script('ds-datatable-js', "//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.3/js/jquery.dataTables.min.js",  array('jquery' ));

		if($_GET['page'] == 'routeorderitems' || $_GET['page'] == 'routeorder'){
			wp_enqueue_script('ds-pubnub-js', "//cdn.pubnub.com/pubnub-3.7.1.min.js");
			wp_enqueue_script('ds-route-js', plugins_url("/route-order/js/route-order.js"),  array('jquery' ), '1.0.0', true); 
			wp_localize_script( 'ds-route-js', 'ds_route', array( 
                'ajaxurl'          => admin_url( 'admin-ajax.php' ), 
                'ds_route_nouce'   => wp_create_nonce( 'ds_route_ajax_nouce' ),
                'pluginurl'        => plugins_url('/route-order/'))          
            );
		}  
	}  

	function ds_routed_install() {
		global $wpdb, $ds_routed_db_version;

		$ds_routed_db_version = '1.0';  

		$table_name = $wpdb->prefix.'routed_order_items';
		
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
			distributor varchar(100) NOT NULL,
			order_id int NOT NULL,
			item_id int NOT NULL,
			item_qty int NOT NULL,
			line_total float NOT NULL,
			routed int NOT NULL,
			in_stock int NOT NULL DEFAULT '1',	
			notes varchar(200) DEFAULT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
		)$charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		add_option( 'ds_routed_db_version', $ds_routed_db_version ); 
	}

	// Create Route Order Admin Menu 
	function register_route_order(){

		// Route Orders
	    add_menu_page( 
	    	'Route Orders', 
	    	'Route Orders', 
	    	'manage_options', 
	    	'routeorder', 
	    	array( &$this , 'route_order_content'), 
	    	'dashicons-editor-distractionfree', 
	    	7); 

	    add_submenu_page(
			'routeorder',
			'Order Details',
			'Route Order Items',
			'manage_options',
			'routeorderitems',
			array( &$this , 'order_details')
		);   
	}

	// Order List
	function route_order_content(){
		global $wpdb;   

		$args = array(
		  'post_type'   => 'shop_order',
		  'post_status' => 'publish',
		  'meta_key' 	=> '_customer_user',
		  'posts_per_page' => '-1',
		  'orderby'		=> 'date',
		  'order' 		=> 'desc'
		);

		$my_query = new WP_Query($args);

		$customer_orders = $my_query->posts; // Display all customer orders

		// Route 
		// DS_RouteOrder::route( 42991, 'nhc_price');

		$html = '<h3>Route Orders</h3>
			<i><h4>Select an order and route items to redistributors</h4></i><br/>
			<table class="wp-list-table widefat fixed posts" id="ordertable">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>
					<th scope="col" id="order_status" class="manage-column column-order_status" style=""><span class="status_head tips">Status</span></th>
					<th scope="col" id="order_title" class="manage-column column-order_title" style=""><span>Order</span><span class="sorting-indicator"></span></th>
					<th scope="col" id="order_items" class="manage-column column-order_items" style="">Purchased</th>
					<th scope="col" id="shipping_address" class="manage-column column-shipping_address" style="">Primary Distributor</th>
					<th scope="col" id="customer_message" class="manage-column column-customer_message" style=""><span class="notes_head tips">Customer Message</span></th>
					<th scope="col" id="order_notes" class="manage-column column-order_notes" style=""><span class="order-notes_head tips">Order Notes</span></th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc" style=""><span>Date</span><span class="sorting-indicator"></span></th>
					<th scope="col" id="order_total" class="manage-column column-order_total sortable desc" style=""><span>Total</span><span class="sorting-indicator"></span></th>
					<th>Routed to:</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($customer_orders as $customer_order) {
			$order = new WC_Order();

			$order->populate($customer_order);
			$orderdata = (array) $order; 

			// User
			$user = get_user_by( 'id', $order->customer_user );
			$user_primary_distributor = get_user_meta($user->ID,'primary_distributor',true);
 
 			// Determine if order is routed
 			if(get_post_meta($order->id,'distributor_order',true) != "")
				$owner = '<a>'.DS_Util::getDistributorNamebyKey(get_post_meta($order->id,'distributor_order',true)).'</a>';
			else if($this->isRouted($order->id) > 0){ 
				$owner = '<a>'.get_user_meta( $this->isRouted($order->id), 'institution', true ).'</a>'; 
			}else{
				$owner = '<a href="'.menu_page_url('routeorderitems', false).'&order='.$order->id.'"><button class="button">Route</button></a>';
			} 		

			$html .= '<tr>
						<td><input type="checkbox" name="post[]" value="'.esc_html( $order->get_order_number() ).'"></td>
						<td>'.$order->status.'</td>
						<td><a href="'.menu_page_url('routeorderitems',false).'&order='.$order->id.'"><b>'.esc_html( $order->get_order_number() ).'<b/> by '.esc_html( $user->display_name ).'</a></td>
						<td>'.$order->get_item_count().'</td>
						<td>'.$user_primary_distributor	.'</td>
						<td>'.(isset($order->customer_message)? $order->customer_message:'None').'</td>
						<td>'.(isset($order->customer_note)? $order->customer_note:'None').'</td>
						<td>'.date("d M Y H:i:s", strtotime($order->order_date)).'</td>
						<td>'.$order->get_formatted_order_total().'</td> 
						<td>
							<a title="View PDF Invoice" alt="View PDF Invoice" target="_blank" href='.wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&template_type=invoice&order_ids=' . $order->id ), 'generate_wpo_wcpdf' ).
								'<img src="'.plugins_url('/route-order/img/invoice.png').'" alt="View PDF Invoice" width="16px" />
							</a>'.$owner.'
						</td>
					</tr>';
		}

		$html .= '</tbody>
			<tfoot> 
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>
					<th scope="col" id="order_status" class="manage-column column-order_status" style=""><span class="status_head tips">Status</span></th>
					<th scope="col" id="order_title" class="manage-column column-order_title sortable desc" style=""><span>Order</span><span class="sorting-indicator"></span></th>
					<th scope="col" id="order_items" class="manage-column column-order_items" style="">Purchased</th>
					<th scope="col" id="shipping_address" class="manage-column column-shipping_address" style="">Primary Distributor</th>
					<th scope="col" id="customer_message" class="manage-column column-customer_message" style=""><span class="notes_head tips">Customer Message</span></th>
					<th scope="col" id="order_notes" class="manage-column column-order_notes" style=""><span class="order-notes_head tips">Order Notes</span></th>
					<th scope="col" id="order_date" class="manage-column column-order_date sortable desc" style=""><span>Date</span><span class="sorting-indicator"></span></th>
					<th scope="col" id="order_total" class="manage-column column-order_total sortable desc" style=""><span>Total</span><span class="sorting-indicator"></span></th>
					<th></th>
				</tr> 
			</tfoot>
		</table>'; 

		echo $html; 
	}

	// Check if an Item is routed to a distributor
	function isRouted($orderid, $itemid = 1){
		global $wpdb;

		if($itemid > 1){
			$item = $wpdb->get_row("SELECT distributor, notes, in_stock FROM {$wpdb->prefix}routed_order_items
				WHERE item_id = $itemid and order_id = $orderid ORDER BY created_at DESC LIMIT 1");

			return isset($item->distributor)? array($item->distributor, $item->notes, $item->in_stock): array("None","None",0);//$item->distributor":"None";
		}else{
			$order = $wpdb->get_row("SELECT distributor FROM {$wpdb->prefix}routed_order_items WHERE order_id = $orderid ORDER BY id DESC LIMIT 1");
			return (count($order) > 0)?	$order->distributor : 0;
		} 
	}

	// Change Number to Money format
	function show_price($value){
		return number_format((float)$value, 2);
	} 

	// Order Data + Item list(varname)
	function order_details($id)
	{ 
		if(isset($_GET['order']) && $_GET['order'] > 0){ 
			$orderid = $_GET['order'];  

			global $wpdb, $thepostid, $theorder, $woocommerce;

			$order = new WC_Order($orderid);
			// $distributors = new WP_Query('post_type=redistributor');
			$redistributors = '';
 
			$distributors = $wpdb->get_results("SELECT w.ID FROM `wp_users` as w INNER JOIN wp_usermeta as m on w.ID = m.user_id WHERE m.meta_key = 'primary_distributor' and m.meta_value!=''"); 

			if(count($distributors) > 0){ 
				foreach ($distributors as $key => $value) {
					$user = get_user_by('id', $value->ID); 
			        if(in_array('shop_manager', $user->roles ) ) {  
					  	$name = get_user_meta($user->ID, 'institution', true);
						$phonenumber = get_user_meta($user->ID, 'phonenumber', true);
						$email = $user->user_email;   
						// $redistributors .='<option value="'.$name.'" data-email="'.$email.'" data-phonenumber="'.$phonenumber.'">'.$name.'</option>';
						$redistributors .='<option value="'.$name.'" data-key="'.get_user_meta($user->ID, 'primary_distributor', true).'" >'.$name.'</option>';
					}
				}
			}
			$user = get_user_by( 'id', $order->customer_user );
			$user_primary_distributor = get_user_meta($user->ID,'primary_distributor',true);?>
			<div id="order_data" class="panel">
				<h2>Order Details</h2>
				<p class="order_number">Order number #<?php echo $order->id;?></p>

				<div class="order_data_column_container">
					<div class="order_data_column">
						<table>  
							<tr>
								<td align="right" valign="middle" class="form-field"><h4>General Details&nbsp;&nbsp;</h4></td>
								<td>
									<p class="form-field"><label for="order_date">Order date:</label>
										<span><?php echo date('d M Y h:m:s A', strtotime($order->order_date));?></span> 
									</p>
									<p class="form-field form-field-wide"><label>Order status:</label><span><?php echo $order->status;?></span></p>
									<p class="form-field form-field-wide">
										<label for="customer_user">Customer: </label>
										<?php echo esc_html( $user->display_name );?> 
									</p>  
									<p class="form-field form-field-wide"><label>Customer Phone Number: </label><span><?php echo get_user_meta($user->ID,'phonenumber',true);?></span></p>
									<p class="form-field form-field-wide"><label>Primary Distributor: </label><span><?php echo get_user_meta($user->ID,'primary_distributor',true);?></span></p>
									<p class="form-field form-field-wide"><label>Order Total: </label><h3><?php echo $order->get_formatted_order_total() ;?></h3></p>
									<p class="form-field form-field-wide"><label><a href="<?php echo menu_page_url('ds-commission-item',false).'&order='.$order->id;?>">View Commission per line item</a></label></p>
								</td>	
							</tr> 
							<tr>
								<td align="right" valign="top" class="form-field"><h4>Shipping Address&nbsp;&nbsp;</h4></td>
								<td><?php echo $order->get_formatted_shipping_address();?></td>
							</tr>
						</table> 
						<br/><br/>
					</div> 
				</div>
				<form>
					<p style="float: left; clear: right">
						<select id="myExternalSelect">
							<option value="">- Select a distributor -</option>'.
							<?php echo $redistributors;?>
						</select>
						<button id="route1" class="button" data-order="<?php echo $order->id;?>">Route Selected Item(s)</button>
					</p>
				</form>
				<div class="clear"></div> 
			</div>
			<?php $order_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', array( 'line_item', 'fee' ) ) );  ?> 
			<div id="woocommerce-order-items" class="postbox " >
				<div class="handlediv" title="Click to toggle"><br /></div><h3 class="hndle"><span id="orderitems">&nbsp;&nbsp;Order Items</span></h3>
				<div class="inside">
					<table class="wp-list-table widefat fixed posts" id="ordertable">
						<thead>
							<tr>
								<th><input type="checkbox" class="select_all" name="selectall" /></th>
								<th>Item</th>
								<th><b>Routed to</b></th> 
								<th>DS Price</th>
								<th>Distributor Price(s)</th>	
								<th>Quantity</th>
								<th>Total</th> 
								<th>Distributor Notes</th>
								<th>In stock</th>
							</tr>
						</thead>
						<tbody>
						<?php   
						foreach ($order_items as $key => $item) {
							$product = $order->get_product_from_item( $item );

							$meta = new WC_Product( $product );  
							$route = $this->isRouted($order->id, $item['product_id']); 
							$distr = strtoupper ( substr( get_user_meta( $route[0], 'primary_distributor', true ), 0, -6) );
				 			?>
				 			<tr class="item" data-item-id="<?php echo $item['product_id']; ?>">
					 			<td><input type="checkbox" class="case" name="case[]" data-order="<?php echo $order->id; ?>" /></td>
								<td class="name"><a href="<?php echo admin_url('post.php?post='.$item['product_id'].'&action=edit');?>"><?php echo $item['name'];?></a></td>
								<td class="dist"><?php echo $distr;?></td>
								<td><?php echo wc_price(get_post_meta($item['product_id'],'_price',true));?></td>
								<td>
									<div>
										NHC | <?php echo wc_price(get_post_meta($item['product_id'],'nhc_price',true)); ?>
										<br/>Elfimo | <?php echo wc_price(get_post_meta($item['product_id'],'elfimo_price',true));?>
										<br/>DS_X | <?php echo wc_price(get_post_meta($item['product_id'],'dsx_price',true));?>
									</div>
								</td>
								<td class="quantity"><?php echo $item['qty'];?></td>
								<td class="line_cost"><?php echo wc_price($item['line_total']);?></td>
								<td class="notes"><textarea class="notes" rows="2" cols="15" placeholder="Notes" readonly ><?php echo $route[1];?></textarea></td>
								<td>
						 			<?php if($route[2] == 1) {?>
										<p>Yes</p>
									<?php }else{ ?>
										<p>No</p>
									<?php }?>
					 			</td>
							</tr>
							<?php   
							}?> 
						</tbody>
					</table> 
		 	  	</div>
		 	  	<form>
				<p style="float: left; clear: right">
					<select id="myExternalSelect">
						<option value="">- Select a distributor -</option>'.
						<?php echo $redistributors;?>
					</select>
					<button id="route2" class="button">Route Selected Item(s)</button>
				</p>
				</form>
	 	  	</div>
	 	  	<?php  
		}else{
			echo '<h3>Please select an Order to route!</h3>';
		}
	} 

	// Ajax Process Order
	function ds_route_order(){
		
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ds_route_ajax_nouce' ) )
            die ( 'Busted!'); 

        if(isset($_POST['order']) && isset($_POST['distributor']) && is_numeric($_POST['order']) && is_string($_POST['distributor']))
			$routed = self::route($_POST['order'], sanitize_text_field($_POST['distributor']));

		if($routed) 
			wp_send_json( array( 'code' => 1, 'message' => "Order successfully routed to ".DS_Util::getDistributorNamebyKey($_POST['distributor'])) );
		else 
			wp_send_json( array( 'code' => 0, 'message' => "Order #{$_POST['order']} could not be routed ") );
	}

	// Route the Order to Distributor
	public static function route($orderid, $key = ''){  
		if($key != ''){
			global $wpdb, $woocommerce;  

			$table_name = $wpdb->prefix.'routed_order_items';

			// Get distributor object
 			$distributor = DS_Util::getDistributorNamebyKey($key, 2);

 			$order = new WC_Order($orderid); 
 			$orderitems = $order->get_items();
			$customer = get_user_meta($order->user_id, 'institution', true);

			// Compose Messages
			$sms_message = "New Order for ".get_user_meta($distributor->ID, 'institution', true).", \nOrder ID : #".$orderid."\nCustomer: ".$customer."\n"; // SMS
			$message2 = "";		// Email					
			$ordertotal = 0;  

			foreach($orderitems as $item) {  

			    $price = (float) $item['line_total'] / $item['qty'];
			    $sms_message.= "Item Name: ".$item['name']." Quantity: ".$item['qty']." Amount: ₦".$item['line_total']."\n";
			    
			    $message2.= '<tr><td scope="col" style="text-align:left; color: #333333;">'.$item['name'].'</td>';
			    $message2.= '<td scope="col" style="text-align:left; color: #333333;">'.wc_price($price).'</td>';
			    $message2.= '<td scope="col" style="text-align:left; color: #333333;">'.$item['qty'].'</td>';
			    $message2.= '<td scope="col" style="text-align:left; color: #333333;">'.wc_price($item['line_total']).'</td></tr>'; 
			    // $ordertotal += $item['line_total'];

			    // Check if item already exists in order 
				$old_order = $wpdb->get_row("SELECT order_id, item_id FROM {$wpdb->prefix}routed_order_items WHERE order_id = $orderid and item_id = {$item['product_id']} LIMIT 1");
				$_order_id = $old_order->order_id; 

			    // Update if order already exists 
			    if($_order_id > 1){
				    // Update Log routed_items
				    $wpdb->update(
						$table_name, 
						array( 
							'distributor' => $distributor->ID,     
							'routed' => 1
						),
						array('order_id' => $orderid),
						array( 
							'%s',	  
							'%d'	  
						), 
						array( '%d' ) 
					); 
			    }else{
					// Insert new Log routed_items
				    $wpdb->insert(	
						$table_name, 
						array( 
							'distributor'=> $distributor->ID,  
							'order_id'   => $orderid, 
							'item_id'    => $item['product_id'], 
							'item_qty'   => $item['qty'], 
							'line_total' => $item['line_total'], 
							'routed' => 1
						) 
					);
			    }  
			}

			$ordertotal = $order->order_total;

			$sms_message.= "Order Total: ₦".$ordertotal."\n\nThanks, \nDrugstoc Team.";  

			$site_title = __('DrugStoc','DrugStoc');
			$headers  = 'From: DrugStoc <mailer@drugstoc.ng>'."\r\n";
			$headers .= "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8"."\r\n"; 
			$headers .= 'Bcc: adhamyehia@gmail.com'."\r\n"; 

			$user = get_user_by( 'id', $order->user_id );
			$phonenumber = get_user_meta($distributor->ID, 'phonenumber', true);

			$subject = 'New Order Notification';

			ob_start();?>
			<html style="background:#0080FF">
				<head>
					<title><?php echo $subject ?></title>
				</head>
				<body>
					<div id="email_container">
						<div style="width:570px; padding:0 0 0 20px; margin:50px auto 12px auto" id="email_header">
							<span style="background:#0080FF; color:#fff; padding:12px;font-family:trebuchet ms; letter-spacing:1px;
							-moz-border-radius-topleft:5px; -webkit-border-top-left-radius:5px;
							border-top-left-radius:5px;moz-border-radius-topright:5px; -webkit-border-top-right-radius:5px;
							border-top-right-radius:5px;"> DrugStoc </span>
						</div>
					</div>
					<div style="width:550px; padding:0 20px 20px 20px; background:#fff; margin:0 auto; border:2px #0080FF solid;
						moz-border-radius:5px; -webkit-border-radus:5px; border-radius:5px; color:#333333;line-height:1.5em; " id="email_content">
						<h1 style="padding:5px 0 0 0; font-family:georgia;font-weight:500;font-size:24px;color:#0080FF;padding-bottom:10px;border-bottom:1px solid #0080FF">
							<?php echo $subject ?>
						</h1>

						<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

						<p style="color: #333333;">
						<?php printf( __( 'You have received an Order from %s. See below for details:', 'woocommerce' ), get_user_meta($user->ID, 'institution',true)); ?></p>

						<?php do_action( 'woocommerce_email_before_order_table', $order, true ); ?>

						<h2 style="color: #333333;"><?php printf( __( 'Order: %s', 'woocommerce'), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $order->order_date ) ), date_i18n( woocommerce_date_format(), strtotime( $order->order_date ) ) ); ?>)</h2>

						<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" bordercolor="#eee">
							<thead>
								<tr>
									<th scope="col" style="text-align:left; border: 1px solid #eee;color: #333333;"><?php _e( 'Product', 'woocommerce' ); ?></th>
									<th scope="col" style="text-align:left; border: 1px solid #eee;color: #333333;"><?php _e( 'Unit Price', 'woocommerce' ); ?></th>
									<th scope="col" style="text-align:left; border: 1px solid #eee;color: #333333;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
									<th scope="col" style="text-align:left; border: 1px solid #eee;color: #333333;"><?php _e( 'Price', 'woocommerce' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php echo $message2; ?>
							</tbody>
							<tfoot> 
								<tr>
									<th scope="row" colspan="3" style="color: #333333;text-align:left; border: 1px solid #eee; border-top-width: 4px;" > Order Total: </th>
									<td style="text-align:left; color: #333333;border: 1px solid #eee; border-top-width: 4px;"><?php echo wc_price($ordertotal); ?></td>
								</tr> 
							</tfoot>
						</table>

						<?php do_action('woocommerce_email_after_order_table', $order, true); ?>

						<?php do_action( 'woocommerce_email_order_meta', $order, true ); ?>

						<p style="color: #333333;">
						<strong style="color: #333333;"> 
							<?php echo 'Order Notes:'; ?></strong> 
							<?php echo $order->customer_note;?>
						</p>

						<h2 style="color: #333333;"><?php _e( 'Customer details', 'woocommerce' ); ?></h2>

						<?php if ( $order->billing_email ) { ?>
							<p style="color: #333333;"><strong style="color: #333333;"><?php _e( 'Email:', 'woocommerce' ); ?></strong> <?php echo $user->user_email; ?></p>
						<?php } ?>
						<?php if ( $order->billing_phone ) {?>
							<p style="color: #333333;"><strong style="color: #333333;"><?php _e( 'Tel:', 'woocommerce' ); ?></strong> <?php echo get_user_meta($user->ID,'phonenumber',true);?></p>
						<?php }?>

						<?php woocommerce_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); 
							do_action( 'woocommerce_email_footer' ); ?>

						<p style="color: #333333;">
							Thank You,<br/>
							DrugStoc Team.<br/>
							<b>Tel:<b/> +2348096879999<br/>
							<b>Email:<b/> info@drugstoc.com
						</p>
						<p><img src="http://drugstoc.biz/wp-content/uploads/2014/10/splash-logo-beta.png"/></p>
						<div style="text-align:center; border-top:1px solid #eee;padding:5px 0 0 0;" id="email_footer">
							<small style="font-size:11px; color:#999; line-height:14px;">
								You have received this email because you are a member of <?php echo $site_title; ?>.
								<br/>Please do not reply to this email. This mailbox is not monitored and you will not receive a response.
								<br/><b>Copyright © <?php echo date('Y');?> DrugStoc. All rights reserved.</b>
							</small>
						</div>
					</div>
				</body>
			</html>
			<? 
			$message = ob_get_contents();

			ob_end_clean();  

			//	Send Email
			$rt = mail($distributor->user_email, $subject, wordwrap($message, 200, "\n", true), wordwrap($headers, 75, "\n", true)); 
			// $rt = wp_mail("calebte2006@yahoo.com", "Test DrugStoc", "This is just a test", wordwrap($headers, 75, "\n", true)); 
			
			//  Send SMS 
			try{
				if(strlen($sms_message) < 1600){
					DS_Util::sendSMS($phonenumber, $sms_message);
				}else{
					$sms_message = "Hi ".get_user_meta($distributor->ID, 'institution', true).", \nYou have a new bulk order from $customer: \nOrder Number: #$orderid \nNumber of Items: ".$order->get_item_count()." \nOrder Total: ₦".$ordertotal." \nPlease check your email for more details. \nDrugStoc Team.";
					DS_Util::sendSMS($phonenumber, $sms_message);
				}
			}catch(Services_Twilio_RestException $e){
				return false;
			} 
			return true;
		}
		else return false;
	}
}

endif;

// Activate Plugin 
DS_RouteOrder::setup();



