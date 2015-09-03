<?php
/**
 * Plugin Name: DS Custom Taxonomy
 * Plugin URI: http://integrahealth.com.ng/integraitlabs.php
 * Description: Multi-widget for displaying category listings for custom post types (custom taxonomies).
 * Version: 3.2
 * Author: Caleb Chinga
 * Author URI: http://celloexpressions.com/
 * Tags: custom taxonomy, custom tax, widget, sidebar, category, categories, taxonomy, custom category, custom categories, post types, custom post types, custom post type categories
 * License: GPL
 */

// Register 'DS Custom Taxonomy' widget
add_action( 'widgets_init', 'init_ds_lc_taxonomy' );
function init_ds_lc_taxonomy() { return register_widget('ds_lc_taxonomy'); }

class ds_lc_taxonomy extends WP_Widget {
	/** constructor */
	function ds_lc_taxonomy() {
		parent::WP_Widget( 'ds_lc_taxonomy', $name = 'DS Custom Taxonomy' );
	}

	/**
	 * This is the Widget
	 **/
	function widget( $args, $instance ) { ?>
		<div id="woocommerce_product_categories-2" class="widget-2 widget span3 woocommerce widget_product_categories brandnames">
			<h3>Manufacturers</h3>
			<ul class="product-categories">
				<?php
				// Compile list of Manufacturers
				global $wpdb;
				
				$m_list = $wpdb->get_results("SELECT t.* FROM wp_terms as t INNER JOIN wp_term_taxonomy as p on t.term_id = p.term_id WHERE p.taxonomy LIKE 'pa_manufacturer' ORDER BY t.name ASC");
				// $m_list = get_terms('pa_manufacturer');
				foreach ($m_list as $key => $manufacturer) {
					if($manufacturer->name != "CHECK PICTURE"){
				?>
				<li class="cat-item cat-item-<?php echo $manufacturer->term_id?>">
					<?php if(DS_Util::getManufacturerBySlug($manufacturer->slug) != null){ ?>
						<a href="<?php echo home_url("/m/$manufacturer->slug")?>/"><?php echo __($manufacturer->name, 'woocommerce')?></a>
					<?php } else { ?>
						<a href="<?php echo home_url("/manufacturer/$manufacturer->slug");?>/"><?php echo __($manufacturer->name, 'woocommerce')?></a>
					<?php }?>
				</li> 
				<?php }
				}?>
			</ul> 
		</div> 
	<?php
	} 
} // class lc_taxonomy