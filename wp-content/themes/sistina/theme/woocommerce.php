<?php
/**
 * All functions and hooks for woocommerce plugin
 *
 * @package WordPress
 * @subpackage YIThemes
 */

global $woocommerce;
global $woo_shop_folder;


if ( ! YIT_DEBUG ){
        $message = get_option( 'woocommerce_admin_notices', array() );
        $message = array_diff( $message, array( 'template_files' ));
        update_option( 'woocommerce_admin_notices', $message );
    }

if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
    add_filter( 'woocommerce_template_url', create_function( "", "return 'woocommerce_2.0.x/';" ) );
    add_action( 'wp_enqueue_scripts', 'yit_enqueue_woocommerce_styles', 11 );
    add_action( 'woocommerce_after_shop_loop_item', 'yit_shop_rating', 5 );
    add_filter( 'woocommerce_catalog_settings', 'yit_add_featured_products_slider_image_size' );
    $woo_shop_folder = 'shop';
}
else {
    add_filter( 'WC_TEMPLATE_PATH', create_function( "", "return 'woocommerce/';" ) );
    add_filter( 'woocommerce_enqueue_styles', 'yit_enqueue_wc_styles' );
    add_filter( 'woocommerce_product_settings', 'yit_add_featured_products_slider_image_size' );
    $woo_shop_folder = 'global';
    add_action( 'admin_init', 'yit_check_version', 8 );
}

/* === GENERAL SETTINGS === */
add_theme_support('woocommerce');
register_sidebar( yit_sidebar_args( 'Shop Sidebar' ) );


/* === HOOKS === */
add_action( 'wp_enqueue_scripts', 'yit_enqueue_woocommerce_assets' );
add_action( 'woocommerce_before_main_content', 'yit_shop_page_meta' );
add_action( 'shop_page_meta'     , 'yit_woocommerce_catalog_ordering' );
add_action( 'shop_page_meta'     , 'yit_woocommerce_list_or_grid' );
add_filter( 'loop_shop_per_page' , 'yit_set_posts_per_page');

add_filter( 'woocommerce_get_image_size_shop_featured', 'yit_add_featured_image_size' );
add_filter( 'yith_wcwl_button_label', 'yit_change_wishlist_label' );
add_filter( 'yith-wcwl-browse-wishlist-label', 'yit_change_browse_wishlist_label' );
add_action( 'get_footer', 'yit_woocp_footer_script', 20 );
add_action( 'yit_activated', 'yit_woocommerce_default_image_dimensions');
add_action( 'admin_init', 'yit_woocommerce_update' ); //update image names after woocommerce update
add_action( 'wp_head', 'yit_size_images_style' );
add_action( 'woocommerce_before_main_content', 'yit_woocommerce_primary_start', 5 );
add_action( 'woocommerce_sidebar', 'yit_woocommerce_primary_end', 99 );

add_filter( 'yit_sample_data_tables',  'yit_save_woocommerce_tables' );
add_filter( 'yit_sample_data_options', 'yit_save_woocommerce_options' );
add_filter( 'yit_sample_data_options', 'yit_save_wishlist_options' );
add_filter( 'yit_sample_data_options', 'yit_add_plugins_options' );

/* shop */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_filter( 'woocommerce_breadcrumb_defaults', 'yit_change_breadcrumb_args' );
//add_action( 'woocommerce_after_shop_loop_item', 'yit_shop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'yit_shop_grid_description', 8 );
add_action( 'woocommerce_after_shop_loop_item', 'yit_product_buttons_wrapper_list_start', 9 );
add_action( 'woocommerce_after_shop_loop_item', 'yit_product_other_actions' );
add_action( 'woocommerce_after_shop_loop_item', 'yit_product_buttons_wrapper_list_end', 11 );
add_action( 'wp_head', 'yit_woocommerce_javascript_scripts' );
add_action( 'woocommerce_before_shop_loop_item_title', 'yit_woocommerce_out_of_stock_flash' );

// Redirect to checkout page after add to cart
if ( yit_get_option( 'shop-redirect-to-checkout' ) ) {
    add_filter ( 'add_to_cart_redirect', 'yit_redirect_to_checkout' );
    add_filter ( 'wc_add_to_cart_params', 'yit_redirect_cart_to_checkout' );
}

/* single */
remove_action( 'yit_after_header', 'yit_page_meta', 20 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );
add_action( 'shop_page_meta', 'woocommerce_breadcrumb', 6, 0 );

add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash' );
add_action( 'woocommerce_after_single_product_summary', 'yit_shop_single_socials', 5 );

add_filter( 'woocommerce_product_tabs', 'yit_reorder_product_tabs' );
if ( !yit_get_option('shop-show-related') ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
}

if ( yit_get_option('shop-show-custom-related') ) {
	add_action( 'woocommerce_related_products_args', 'yit_related_posts_per_page' );
}
function yit_related_posts_per_page() {
	global $product;
	$related = $product->get_related(yit_get_option('shop-number-related'));
	return array(
		'posts_per_page' 		=> -1,
		'post_type'				=> 'product',
		'ignore_sticky_posts'	=> 1,
		'no_found_rows' 		=> 1,
		'post__in' 				=> $related
	);
}

function yit_check_version() {
    if ( get_option( 'yit_theme_version_1.3.1' ) || ! isset( $_GET['do_update_woocommerce'] ) ) {
        return;
    }
    clear_menu_from_old_woo_pages();
    update_option( 'yit_theme_version_1.3.1', true );
}

function clear_menu_from_old_woo_pages() {
    $locations = get_nav_menu_locations();
    $logout    = get_page_by_path( 'my-account/logout' );
    $vieworder    = get_page_by_path( 'my-account/view-order' );
    $parent    = get_page_by_path( 'my-account' );
    $permalink = get_option( 'permalink_structure' );

    $pages_deleted = array(
        get_option( 'woocommerce_pay_page_id' ), get_option( 'woocommerce_thanks_page_id' ), get_option( 'woocommerce_view_order_page_id' ), get_option( 'woocommerce_view_order_page_id' ),
        get_option( 'woocommerce_change_password_page_id' ), get_option( 'woocommerce_edit_address_page_id' ), get_option( 'woocommerce_lost_password_page_id' )
    );


    foreach ( (array) $locations as $name => $menu_ID ) {
        $items = wp_get_nav_menu_items( $menu_ID );
        foreach ( (array) $items as $item ) {

            if ( ! is_null( $logout ) && ! is_null( $parent ) && $item->object_id == $logout->ID ) {
                update_post_meta( $item->ID, '_menu_item_object', 'custom' );
                update_post_meta( $item->ID, '_menu_item_type', 'custom' );
                if ( $permalink == '' ) {
                    $new_url = get_permalink( $parent->ID ) . '&customer-logout';
                }
                else {
                    wp_update_post( array(
                            'ID'        => $logout->ID,
                            'post_name' => 'customer-logout', )
                    );
                    $new_url = get_permalink( $logout->ID );
                }
                update_post_meta( $item->ID, '_menu_item_url', $new_url );
                wp_update_post( array(
                        'ID'         => $item->ID,
                        'post_title' => $logout->post_title, )
                );
            }

            if ( ! is_null( $vieworder ) && ! is_null( $parent ) && $item->object_id == $vieworder->ID ) {
                update_post_meta( $item->ID, '_menu_item_object', 'custom' );
                update_post_meta( $item->ID, '_menu_item_type', 'custom' );
                if ( $permalink == '' ) {
                    $new_url = get_permalink( $parent->ID ) . '&view-order';
                }
                else {
                    wp_update_post( array(
                            'ID'        => $vieworder->ID,
                            'post_name' => 'view-order', )
                    );
                    $new_url = get_permalink( $vieworder->ID );
                }
                update_post_meta( $item->ID, '_menu_item_url', $new_url );
                wp_update_post( array(
                        'ID'         => $item->ID,
                        'post_title' => $vieworder->post_title, )
                );
            }

            foreach ( $pages_deleted as $page ) {

                if ( $page && $item->object_id == $page && $item->object == 'page' ) {

                    wp_delete_post( $item->ID );

                }

            }
        }

    }
}

if( !yit_get_option( 'shop-show-metas') ) {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}

/* tabs */
add_action( 'woocommerce_product_tabs', 'yit_woocommerce_add_tabs' );  // Woo 2
add_action( 'woocommerce_product_tab_panels', 'yit_woocommerce_add_info_panel', 40 );
add_action( 'woocommerce_product_tab_panels', 'yit_woocommerce_add_custom_panel', 50 );

/* cart */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display' );

/* admin */
add_action( 'woocommerce_product_options_general_product_data', 'yit_woocommerce_admin_product_ribbon_onsale' );
add_action( 'woocommerce_process_product_meta', 'yit_woocommerce_process_product_meta',2,2 );
add_action( 'yit_after_import', create_function( '', 'update_option("woocommerce_responsive_images", "yes");' ) );


// active the price filter
if( version_compare($woocommerce->version,"2.0.0",'<') ) {
    add_action('init', 'woocommerce_price_filter_init');
}

//add to cart button
add_filter('single_add_to_cart_text', 'yit_add_to_cart_text', 1);
add_filter('add_to_cart_text', 'yit_add_to_cart_text', 1);
function yit_add_to_cart_text( $text ) {
    global $product;

    if( $product->product_type != 'external' ) {
        $text = __( yit_get_option( 'add-to-cart-text' ), 'yit' );
    }

    return $text;
}

/* compare button */
global $yith_woocompare;
if ( isset($yith_woocompare) ) {
    remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
    remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link' ), 35 );
}

/* magnifier option */
add_filter( 'yith_wcmg_options_theme_plugin', 'yit_remove_items_options_yith_wcmg' );

/* === FUNCTIONS === */
function yit_product_layout() {
    return yit_get_option( 'shop-single-layout', 'layout-1' );
}
function yit_enqueue_woocommerce_assets() {
    //yit_enqueue_style( 1000, 'woocommerce-custom-style', YIT_THEME_URL . '/woocommerce/custom.css' );
    wp_enqueue_script( 'yit-woocommerce', YIT_THEME_ASSETS_URL . '/js/woocommerce.js', array( 'jquery', 'jquery-cookie' ), '1.0', true );
    wp_localize_script( 'yit-woocommerce', 'yit_woocommerce', array(
        'no_filters' => __( 'No filters to apply!', 'yit' ) ,
        'woocommerce_ship_to_billing' =>  get_option( 'woocommerce_ship_to_billing' )=='yes' ? 1 : 0 ,
    ));
}

function yit_shop_rating() {
    global $product, $woocommerce_loop;
    if ( $woocommerce_loop['view'] != 'list' && yit_get_option( 'shop-view-show-rating' ) )  echo '<div class="classic-rating">' . $product->get_rating_html() . '</div>';
}

function yit_woocommerce_javascript_scripts() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        $('body').bind('added_to_cart', function(){
            $('.add_to_cart_button.added').text('<?php echo apply_filters( 'yit_added_to_cart_text', __( 'ADDED!', 'yit' ) ); ?>');
        });
    });
    </script>
    <?php
}

/*function yit_woocommerce_primary_start() {
    woocommerce_get_template( 'shop/primary-start.php' );
}

function yit_woocommerce_primary_end() {
    woocommerce_get_template( 'shop/primary-end.php' );
}*/

function yit_woocommerce_catalog_ordering() {
    if ( ! is_single() && have_posts() ) woocommerce_catalog_ordering();
}

function yit_set_posts_per_page( $cols ) {
    $items = yit_get_option( 'shop-products-per-page', $cols );
    return $items == 0 ? -1 : $items;
}

function yit_product_other_actions() {
    yith_wc_get_template( 'loop/other-actions.php' );
}

function yit_woocommerce_list_or_grid() {
    global $woo_shop_folder;
    yith_wc_get_template( $woo_shop_folder .'/list-or-grid.php' );
}


function yit_woocommerce_show_product_thumbnails() {
    yith_wc_get_template( 'single-product/thumbs.php' );
}

function yit_shop_page_meta() {
    global $woo_shop_folder;
    if ( is_single() ) return;
    yith_wc_get_template( $woo_shop_folder .'/page-meta.php' );
}

function yit_change_breadcrumb_args( $args ) {
    $args['delimiter'] = ' &gt; ';

    return $args;
}

function yit_product_buttons_wrapper_list_start() {
    ?><div class="buttons-list-wrapper"><?php
}
function yit_product_buttons_wrapper_list_end() {
    ?></div><?php
}

function yit_shop_grid_description() {
    $description_class = '';
    if ( yit_get_option('shop-view-show-description') ) $description_class .= 'show-on-list';
    if ( yit_get_option('shop-classic-show-description') ) $description_class .= 'show-on-classic';
    if ( ! empty( $description_class ) ) $description_class = ' ' . $description_class;
    ?>

<div class="product-description<?php echo $description_class ?>">
    <?php woocommerce_template_single_excerpt(); ?>
    </div><?php
}

function yit_shop_single_socials() {
    yith_wc_get_template('single-product/socials.php');
}



/* Woo >= 2 */
function yit_woocommerce_add_tabs( $tabs ) {
    global $post;

    if ( yit_get_post_meta( $post->ID, '_use_ask_info' ) ) {
    	$tabs['info'] = array(
    		'title'    => apply_filters( 'yit_ask_info_label', __('Product Inquiry', 'yit') ),
    		'priority' => 30,
    		'callback' => 'yit_woocommerce_add_info_panel'
    	);
    }

	$custom_tabs = yit_get_post_meta($post->ID, '_custom_tabs');
	if( !empty( $custom_tabs ) ) {
        foreach( $custom_tabs as $tab ) {
        	$tabs['custom'.$tab["position"]] = array(
        		'title'    => $tab["name"],
        		'priority' => 30,
        		'callback' => 'yit_woocommerce_add_custom_panel',
        		'custom_tab' => $tab
        	);
        }
    }

	return $tabs;
}

function yit_woocommerce_prev_next_nav() {
    yith_wc_get_template( 'single-product/nav-links.php' );
}

/* custom and info tabs Woo 2 < */
function yit_woocommerce_add_info_tab() {
    yith_wc_get_template( 'single-product/tabs/tab-info.php' );
}

function yit_woocommerce_add_info_panel() {
    yith_wc_get_template( 'single-product/tabs/info.php' );
}

function yit_woocommerce_add_custom_tab() {
    yith_wc_get_template( 'single-product/tabs/tab-custom.php' );
}

function yit_woocommerce_add_custom_panel( $key, $tab ) {
    yith_wc_get_template( 'single-product/tabs/custom.php', array( 'key' => $key, 'tab' => $tab ) );
}

function woocommerce_template_loop_product_thumbnail() {
    global $product;

	echo '<a href="' . get_permalink() . '" class="thumb">' . woocommerce_get_product_thumbnail();

    // add another image for hover
	if( yit_get_option( 'shop-hover-product' ) )
	{
		$attachments = $product->get_gallery_attachment_ids();
		if ( ! empty( $attachments ) && isset( $attachments[0] ) ) {
			yit_image( "id=$attachments[0]&size=shop_catalog&class=image-hover" );
		}
	}

    echo  '</a>';
}

function yit_product_rate_size() {
	return 13;
}

function yit_woocommerce_sharethis() {
    if ( is_product() ) return;
    do_action('woocommerce_share');
}

function yit_woocommerce_out_of_stock_flash() {
    global $product;

    if ( ! $product->is_in_stock() ) echo '<span class="out-of-stock" style="display: inline;">' . __( 'out of stock', 'yit' ) . '</span>';
}

/* share */
function yit_woocommerce_share() {
    if( !yit_get_option( 'shop-share' ) )
        { return; }

	echo do_shortcode('[share class="product-share" title="' . yit_get_option( 'shop-share-title' ) . '" socials="' . yit_get_option( 'shop-share-socials' ) . '"]');
}

function yit_add_sharethis_button_js() {
    global $woocommerce, $product, $post, $yit_sharethis;

    if ( ! isset( $woocommerce->integrations->integrations['sharethis'] ) ||
        empty( $woocommerce->integrations->integrations['sharethis']->publisher_id ) ||
        ( (! yit_get_option('shop-view-show-share') && ! is_product()) || (! yit_get_option('shop-single-show-share') && is_product()) ) ||
        ( yit_get_option( 'shop-layout', 'with-hover' ) == 'classic' && is_product() )
    ) return;

    if ( ! isset( $yit_sharethis ) ) {
        $sharethis = ( is_ssl() ) ? 'https://ws.sharethis.com/button/buttons.js' : 'http://w.sharethis.com/button/buttons.js';
        echo '<script type="text/javascript">var switchTo5x=true;</script><script type="text/javascript" src="' . $sharethis . '"></script>';
        echo '<script type="text/javascript">stLight.options({publisher:"' . $woocommerce->integrations->integrations['sharethis']->publisher_id . '" });</script>';
    }

    printf('<script type="text/javascript">
    stLight.options({
            onhover:false
    });
    stWidget.addEntry({
        	"service" : "sharethis",
        	"element" : document.getElementById("%s"),
        	"url"     : "%s",
        	"title"   : "%s",
        	"type"    : "large",
        	"text"    : "%s",
        	"image"   : "%s",
        	"summary" : "%s"
        }, {button:true});
    </script>', "share_$product->id", get_permalink($product->id), get_the_title(), get_the_title(), yit_image( "output=url", false ), str_replace( array( "\r", "\n" ), ' ', esc_attr( strip_tags( $post->post_content ) ) ) );

    $yit_sharethis = true;
}

/* checkout */

/*Display coupon form on checkout*/
if(!yit_get_option('shop-coupon-checkout')){
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
}


add_filter('wp_redirect', 'yit_woocommerce_checkout_registration_redirect');
function yit_woocommerce_checkout_registration_redirect( $location ) {
	if ( isset($_POST['register']) && $_POST['register'] && isset($_POST['yit_checkout']) && $location == get_permalink( yith_wc_get_page_id( 'myaccount' ) ) ) {
		$location = get_permalink( yith_wc_get_page_id( 'myaccount' ) );
	}

	return $location;
}

function yit_change_wishlist_label() {
    return __( 'DrugStoc', 'yit' );
}

function yit_change_browse_wishlist_label() {
    return __( 'View DrugStoc', 'yit' );
}



/* admin */
function yit_woocommerce_admin_product_ribbon_onsale() {
    yith_wc_get_template( 'admin/custom-onsale.php' );
}
function yit_woocommerce_process_product_meta( $post_id, $post ) {

    $active = (isset($_POST['_active_custom_onsale'])) ? 'yes' : 'no';
    update_post_meta( $post_id, '_active_custom_onsale', esc_attr( $active ) );

    if (isset($_POST['_preset_onsale_icon'])) update_post_meta( $post_id, '_preset_onsale_icon', esc_attr( $_POST['_preset_onsale_icon'] ) );
    if (isset($_POST['_custom_onsale_icon'])) update_post_meta( $post_id, '_custom_onsale_icon', esc_attr( $_POST['_custom_onsale_icon'] ) );
}

// Detect the span to use for the products list
function yit_detect_span_catalog_image() {
    global $woocommerce_loop, $yit_is_feature_tab;

    $content_width = yit_get_sidebar_layout() == 'sidebar-no' ? 1170 : 870;
    if ( isset( $yit_is_feature_tab ) && $yit_is_feature_tab ) $content_width -= 300;
    $product_width = yit_shop_catalog_w() + ( $woocommerce_loop['layout'] == 'classic' ? 6 : 10 ) + 2;  // 10 = padding & 2 = border
    $is_span = false;
    if ( get_option('woocommerce_responsive_images') == 'yes' ) {
        $is_span = true;
        if ( yit_get_sidebar_layout() == 'sidebar-no' ) {
                if ( $product_width >= 0   && $product_width < 120 ) { $woocommerce_loop['li_class'][] = 'span1'; $woocommerce_loop['columns'] = 12; }
            elseif ( $product_width >= 120 && $product_width < 220 ) { $woocommerce_loop['li_class'][] = 'span2'; $woocommerce_loop['columns'] = 6;  }
            elseif ( $product_width >= 220 && $product_width < 320 ) { $woocommerce_loop['li_class'][] = 'span3'; $woocommerce_loop['columns'] = 4;  }
            elseif ( $product_width >= 320 && $product_width < 470 ) { $woocommerce_loop['li_class'][] = 'span4'; $woocommerce_loop['columns'] = 3;  }
            elseif ( $product_width >= 470 && $product_width < 620 ) { $woocommerce_loop['li_class'][] = 'span6'; $woocommerce_loop['columns'] = 2;  }
            else $is_span = false;

        } else {
                if ( $product_width >= 0   && $product_width < 150 ) { $woocommerce_loop['li_class'][] = 'span1'; $woocommerce_loop['columns'] = 12; }
            elseif ( $product_width >= 150 && $product_width < 620 ) { $woocommerce_loop['li_class'][] = 'span3'; $woocommerce_loop['columns'] = 3;  }
            else $is_span = false;

        }

    } else {
        $grid = yit_get_span_from_width( $product_width );
        $woocommerce_loop['li_class'][] = 'span' . $grid;
        $product_width = yit_width_of_span( $grid );
    }
    if ( $yit_is_feature_tab || ! $is_span ) $woocommerce_loop['columns'] = floor( ( $content_width + 30 ) / ( $product_width + 30 ) );
}


/**
 * SIZES
 */

if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
    // shop small
    if ( ! function_exists('yit_shop_catalog_w') ) : function yit_shop_catalog_w() { global $woocommerce; $size = $woocommerce->get_image_size('shop_catalog'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_catalog_h') ) : function yit_shop_catalog_h() { global $woocommerce; $size = $woocommerce->get_image_size('shop_catalog'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_catalog_c') ) : function yit_shop_catalog_c() { global $woocommerce; $size = $woocommerce->get_image_size('shop_catalog'); return $size['crop']; } endif;
    // shop thumbnail
    if ( ! function_exists('yit_shop_thumbnail_w') ) : function yit_shop_thumbnail_w() { global $woocommerce; $size = $woocommerce->get_image_size('shop_thumbnail'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_h') ) : function yit_shop_thumbnail_h() { global $woocommerce; $size = $woocommerce->get_image_size('shop_thumbnail'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_c') ) : function yit_shop_thumbnail_c() { global $woocommerce; $size = $woocommerce->get_image_size('shop_thumbnail'); return $size['crop']; } endif;
    // shop large
    if ( ! function_exists('yit_shop_single_w') ) : function yit_shop_single_w() { global $woocommerce; $size = $woocommerce->get_image_size('shop_single'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_single_h') ) : function yit_shop_single_h() { global $woocommerce; $size = $woocommerce->get_image_size('shop_single'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_single_c') ) : function yit_shop_single_c() { global $woocommerce; $size = $woocommerce->get_image_size('shop_single'); return $size['crop']; } endif;
    // shop featured
    if ( ! function_exists('yit_shop_featured_w') ) : function yit_shop_featured_w() { global $woocommerce; $size = $woocommerce->get_image_size('shop_featured'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_featured_h') ) : function yit_shop_featured_h() { global $woocommerce; $size = $woocommerce->get_image_size('shop_featured'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_featured_c') ) : function yit_shop_featured_c() { global $woocommerce; $size = $woocommerce->get_image_size('shop_featured'); return $size['crop']; } endif;
}
else{
    // shop small
    if ( ! function_exists('yit_shop_catalog_w') ) : function yit_shop_catalog_w() { $size = wc_get_image_size('shop_catalog'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_catalog_h') ) : function yit_shop_catalog_h() { $size = wc_get_image_size('shop_catalog'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_catalog_c') ) : function yit_shop_catalog_c() { $size = wc_get_image_size('shop_catalog'); return $size['crop']; } endif;
    // shop thumbnail
    if ( ! function_exists('yit_shop_thumbnail_w') ) : function yit_shop_thumbnail_w() { $size = wc_get_image_size('shop_thumbnail'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_h') ) : function yit_shop_thumbnail_h() { $size = wc_get_image_size('shop_thumbnail'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_thumbnail_c') ) : function yit_shop_thumbnail_c() { $size = wc_get_image_size('shop_thumbnail'); return $size['crop']; } endif;
    // shop large
    if ( ! function_exists('yit_shop_single_w') ) : function yit_shop_single_w() { $size = wc_get_image_size('shop_single'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_single_h') ) : function yit_shop_single_h() { $size = wc_get_image_size('shop_single'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_single_c') ) : function yit_shop_single_c() { $size = wc_get_image_size('shop_single'); return $size['crop']; } endif;
    // shop featured
    if ( ! function_exists('yit_shop_featured_w') ) : function yit_shop_featured_w() { $size = wc_get_image_size('shop_featured'); return $size['width']; } endif;
    if ( ! function_exists('yit_shop_featured_h') ) : function yit_shop_featured_h() { $size = wc_get_image_size('shop_featured'); return $size['height']; } endif;
    if ( ! function_exists('yit_shop_featured_c') ) : function yit_shop_featured_c() { $size = wc_get_image_size('shop_featured'); return $size['crop']; } endif;
}

// print style for small thumb size
function yit_size_images_style() {
    $content_width = yit_get_sidebar_layout() == 'sidebar-no' ? 1170 : 870;
    $margin = 2.5641025641025641025641025641026;    // 30px

    $images_container_w = yit_shop_single_w() / $content_width * 100;
	?>
	<style type="text/css">
	   ul.products li.product.list .product-wrapper { padding-left:<?php echo yit_shop_catalog_w() + 30 + 7 + 2; ?>px; }
	   ul.products li.product.list .product-wrapper a.thumb { margin-left:-<?php echo yit_shop_catalog_w() + 30 + 7 + 2; ?>px; width: <?php echo yit_shop_catalog_w() ?>px; }
	   .single-product.woocommerce div.product div.images { width:<?php echo $images_container_w ?>%; }
	   .single-product.woocommerce div.product div.summary { width:<?php echo 100 - $images_container_w - $margin ?>%; }
	</style>
    <?php
}

// ADD IMAGE CATEGORY OPTION
function yit_add_featured_products_slider_image_size( $options ) {

    global $woocommerce;

    $field1 =array(
        'name' => __( 'Featured Products Widget', 'woocommerce' ),
        'desc' 		=> __( 'This size is usually used for the products thubmnails in the slider widget Featured Products.', 'yit' ),
        'id' 		=> 'shop_featured_image_size',
        'css' 		=> '',
        'type' 		=> 'image_width',
        'default'	=> array(
            'width' => 160,
            'height' => 160,
            'crop' => true
        ),
        'std' 		=> array(
            'width' => 160,
            'height' => 160,
            'crop' => true
        ),
        'desc_tip'	=>  true,
    );
    $field2 = array(
        'name'		=> __( 'Active responsive images', 'yit' ),
        'desc' 		=> __( 'Active this to make the images responsive and adaptable to the layout grid.', 'yit' ),
        'id' 		=> 'woocommerce_responsive_images',
        'std' 		=> 'yes',
        'default'   => 'yes',
        'type' 		=> 'checkbox'
    );
    if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
        $tmp = array_pop( $options );
        $options[] = $field1 + $field2 + $tmp;
    }
    else {
        $offset  = -6;
        $start   = array_slice( $options, 0, count( $options ) + $offset );
        $end     = array_slice( $options, $offset );
        $options = array_merge( $start, array( $field1, $field2 ), $end );
    }

    return $options;
}

function yit_add_featured_image_size() {
    return get_option( 'shop_featured_image_size', array() );
}

// ADD IMAGE RESPONSIVE OPTION
function yit_add_responsive_image_option( $options ) {
    $tmp = $options[ count($options)-1 ];
    unset( $options[ count($options)-1 ] );

    $options[] = array(
		'name'		=> __( 'Active responsive images', 'yit' ),
		'desc' 		=> __( 'Active this to make the images responsive and adaptable to the layout grid.', 'yit' ),
		'id' 		=> 'woocommerce_responsive_images',
		'std' 		=> 'yes',
        'default'   => 'yes',
		'type' 		=> 'checkbox'
	);

	$options[] = $tmp;

    return $options;
}



/** NAV MENU
-------------------------------------------------------------------- */

add_action('admin_init', array('yitProductsPricesFilter', 'admin_init'));

class yitProductsPricesFilter {
	// We cannot call #add_meta_box yet as it has not been defined,
    // therefore we will call it in the admin_init hook
    static function admin_init() {

		if ( ! is_shop_enabled() || basename($_SERVER['PHP_SELF']) != 'nav-menus.php' )
			return;

		wp_enqueue_script('nav-menu-query', YIT_THEME_ASSETS_URL . '/js/metabox_nav_menu.js', 'nav-menu', false, true);
		add_meta_box('products-by-prices', 'Prices Filter', array(__CLASS__, 'nav_menu_meta_box'), 'nav-menus', 'side', 'low');
	}

	function nav_menu_meta_box() { ?>
	<div class="prices">
		<input type="hidden" name="woocommerce_currency" id="woocommerce_currency" value="<?php echo get_woocommerce_currency_symbol( get_option('woocommerce_currency') ) ?>" />
		<input type="hidden" name="woocommerce_shop_url" id="woocommerce_shop_url" value="<?php echo get_option('permalink_structure') == '' ? YIT_SITE_URL . '/?post_type=product' : get_permalink( get_option('woocommerce_shop_page_id') ) ?>" />
		<input type="hidden" name="menu-item[-1][menu-item-url]" value="" />
		<input type="hidden" name="menu-item[-1][menu-item-title]" value="" />
		<input type="hidden" name="menu-item[-1][menu-item-type]" value="custom" />

		<p>
		    <?php _e( sprintf( 'The values are already expressed in %s', get_woocommerce_currency_symbol( get_option('woocommerce_currency') ) ), 'yit' ) ?>
		</p>

		<p>
			<label class="howto" for="prices_filter_from">
				<span><?php _e('From', 'yit'); ?></span>
				<input id="prices_filter_from" name="prices_filter_from" type="text" class="regular-text menu-item-textbox input-with-default-title" title="<?php esc_attr_e('From', 'yit'); ?>" />
			</label>
		</p>

		<p style="display: block; margin: 1em 0; clear: both;">
			<label class="howto" for="prices_filter_to">
				<span><?php _e('To', 'yit'); ?></span>
				<input id="prices_filter_to" name="prices_filter_to" type="text" class="regular-text menu-item-textbox input-with-default-title" title="<?php esc_attr_e('To'); ?>" />
			</label>
		</p>

		<p class="button-controls">
			<span class="add-to-menu">
				<img class="waiting" src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" alt="" style="display: none;" />
				<input type="submit" class="button-secondary submit-add-to-menu" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-custom-menu-item" />
			</span>
		</p>

	</div>
<?php
	}
}


if( yit_get_option('shop-customer-vat' ) && yit_get_option('shop-customer-ssn' ) ) {

	add_filter( 'woocommerce_billing_fields' , 'woocommerce_add_billing_fields' );
	function woocommerce_add_billing_fields( $fields ) {
        //$fields['billing_country']['clear'] = true;
		$field = array('billing_ssn' => array(
	        'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
		    'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-first'),
		    'clear'       => false
	     ));

		yit_array_splice_assoc( $fields, $field, 'billing_address_1');

		$field = array('billing_vat' => array(
	        'label'       => apply_filters( 'yit_vatssn_label', __('VAT', 'yit') ),
		    'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-last'),
		    'clear'       => true
	     ));

		yit_array_splice_assoc( $fields, $field, 'billing_address_1');

		return $fields;
	}


	add_filter( 'woocommerce_shipping_fields' , 'woocommerce_add_shipping_fields' );
	function woocommerce_add_shipping_fields( $fields ) {
		$field = array('shipping_ssn' => array(
	        'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
		    'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-first'),
		    'clear'       => false
	     ));

		yit_array_splice_assoc( $fields, $field, 'shipping_address_1');

		$field = array('shipping_vat' => array(
	        'label'       => apply_filters( 'yit_vatssn_label', __('VAT', 'yit') ),
		    'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-last'),
		    'clear'       => true
	     ));

		yit_array_splice_assoc( $fields, $field, 'shipping_address_1');
		return $fields;
	}


    add_filter( 'woocommerce_admin_billing_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    function woocommerce_add_billing_shipping_fields_admin( $fields ) {
        $fields['vat'] = array(
            'label' => apply_filters( 'yit_vatssn_label', __('VAT', 'yit') )
        );
        $fields['ssn'] = array(
            'label' => apply_filters( 'yit_ssn_label', __('SSN', 'yit') )
        );

        return $fields;
    }

    add_filter( 'woocommerce_load_order_data', 'woocommerce_add_var_load_order_data' );
    function woocommerce_add_var_load_order_data( $fields ) {
        $fields['billing_vat'] = '';
        $fields['shipping_vat'] = '';
        $fields['billing_ssn'] = '';
        $fields['shipping_ssn'] = '';
        return $fields;
    }



} elseif( yit_get_option('shop-customer-vat' ) ) {
	add_filter( 'woocommerce_billing_fields' , 'woocommerce_add_billing_fields' );
	function woocommerce_add_billing_fields( $fields ) {
		$fields['billing_company']['class'] = array('form-row-first');
		$fields['billing_company']['clear'] = false;
        //$fields['billing_country']['clear'] = true;
		$field = array('billing_vat' => array(
	        'label'       => apply_filters( 'yit_vatssn_label', __('VAT/SSN', 'yit') ),
		    'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT or SSN', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-last'),
		    'clear'       => true
	     ));

		yit_array_splice_assoc( $fields, $field, 'billing_address_1');
		return $fields;
	}

	add_filter( 'woocommerce_shipping_fields' , 'woocommerce_add_shipping_fields' );
	function woocommerce_add_shipping_fields( $fields ) {
		$fields['shipping_company']['class'] = array('form-row-first');
		$fields['shipping_company']['clear'] = false;
        //$fields['shipping_country']['clear'] = true;
		$field = array('shipping_vat' => array(
	        'label'       => apply_filters( 'yit_vatssn_label', __('VAT/SSN', 'yit') ),
		    'placeholder' => apply_filters( 'yit_vatssn_label_x', _x('VAT or SSN', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-last'),
		    'clear'       => true
	     ));

		yit_array_splice_assoc( $fields, $field, 'shipping_address_1');
		return $fields;
	}

    add_filter( 'woocommerce_admin_billing_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_add_billing_shipping_fields_admin' );
    function woocommerce_add_billing_shipping_fields_admin( $fields ) {
        $fields['vat'] = array(
            'label' => apply_filters( 'yit_vatssn_label', __('VAT/SSN', 'yit') )
        );

        return $fields;
    }

    add_filter( 'woocommerce_load_order_data', 'woocommerce_add_var_load_order_data' );
    function woocommerce_add_var_load_order_data( $fields ) {
        $fields['billing_vat'] = '';
        $fields['shipping_vat'] = '';
        return $fields;
    }
}
elseif( yit_get_option('shop-customer-ssn' ) ) {
	add_filter( 'woocommerce_billing_fields' , 'woocommerce_add_billing_ssn_fields' );
	function woocommerce_add_billing_ssn_fields( $fields ) {
		$fields['billing_company']['class'] = array('form-row-first');
		$fields['billing_company']['clear'] = false;
		$field = array('billing_ssn' => array(
	        'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
		    'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-last'),
		    'clear'       => true
	     ));

		yit_array_splice_assoc( $fields, $field, 'billing_address_1');
		return $fields;
	}

	add_filter( 'woocommerce_shipping_fields' , 'woocommerce_add_shipping_ssn_fields' );
	function woocommerce_add_shipping_ssn_fields( $fields ) {
		$fields['shipping_company']['class'] = array('form-row-first');
		$fields['shipping_company']['clear'] = false;
		$field = array('shipping_ssn' => array(
	        'label'       => apply_filters( 'yit_ssn_label', __('SSN', 'yit') ),
		    'placeholder' => apply_filters( 'yit_ssn_label_x', _x('SSN', 'placeholder', 'yit') ),
		    'required'    => false,
		    'class'       => array('form-row-last'),
		    'clear'       => true
	     ));

		yit_array_splice_assoc( $fields, $field, 'shipping_address_1');
		return $fields;
	}

    add_filter( 'woocommerce_admin_billing_fields', 'woocommerce_add_billing_shipping_ssn_fields_admin' );
    add_filter( 'woocommerce_admin_shipping_fields', 'woocommerce_add_billing_shipping_ssn_fields_admin' );
    function woocommerce_add_billing_shipping_ssn_fields_admin( $fields ) {
        $fields['ssn'] = array(
    		'label' => apply_filters( 'yit_ssn_label', __('SSN', 'yit') )
  		);

        return $fields;
    }

    add_filter( 'woocommerce_load_order_data', 'woocommerce_add_var_load_order_ssn_data' );
    function woocommerce_add_var_load_order_ssn_data( $fields ) {
        $fields['billing_ssn'] = '';
        $fields['shipping_ssn'] = '';
        return $fields;
    }
}


if( yit_get_option('shop-fields-order') ) {
	add_filter( 'woocommerce_billing_fields' , 'woocommerce_restore_billing_fields_order' );
	function woocommerce_restore_billing_fields_order( $fields ) {
		$fields['billing_city']['class'][0] = 'form-row-last';
		$fields['billing_country']['class'][0] = 'form-row-first';
		$fields['billing_address_1']['class'][0] = 'form-row-first';
 		$fields['billing_address_2']['class'][0] = 'form-row-last';

		$country = $fields['billing_country'];
		unset( $fields['billing_country'] );
		yit_array_splice_assoc( $fields, array('billing_country' => $country), 'billing_state' );

		return $fields;
	}

	add_filter( 'woocommerce_shipping_fields' , 'woocommerce_restore_shipping_fields_order' );
	function woocommerce_restore_shipping_fields_order( $fields ) {
		$fields['shipping_city']['class'][0] = 'form-row-last';
		$fields['shipping_country']['class'][0] = 'form-row-first';
		$fields['shipping_address_1']['class'][0] = 'form-row-first';
 		$fields['shipping_address_2']['class'][0] = 'form-row-last';

		$country = $fields['shipping_country'];
		unset( $fields['shipping_country'] );
		yit_array_splice_assoc( $fields, array('shipping_country' => $country), 'shipping_state' );

		return $fields;
	}
}




/**
 * Return the following cart info:
 * 	- items
 *  - subtotal
 *  - currency
 *
 * @return array
 */
function yit_get_current_cart_info() {
	global $woocommerce;

    if( get_option( 'woocommerce_display_cart_prices_excluding_tax' ) == 'yes' || $woocommerce->customer->is_vat_exempt() ) {
        $subtotal = $woocommerce->cart->subtotal_ex_tax;
    } else {
        $subtotal = $woocommerce->cart->subtotal;
    }

    $items = yit_get_option( 'minicart-total-items' ) ? $woocommerce->cart->get_cart_contents_count() : count( $woocommerce->cart->get_cart() );

    return array(
        $items,
        $subtotal,
        get_woocommerce_currency_symbol()
    );
}

function yit_format_cart_subtotal( $price ) {
	$num_decimals = (int) get_option( 'woocommerce_price_num_decimals' );

	$price = apply_filters( 'raw_woocommerce_price', (double) $price );
	$price = number_format( $price, $num_decimals, stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) );

	return explode(get_option( 'woocommerce_price_decimal_sep' ), $price);
}

function yit_add_to_cart_success_ajax( $datas ) {
	global $woocommerce;

	list( $cart_items, $cart_subtotal, $cart_currency ) = yit_get_current_cart_info();

	$datas['.woo_cart .cart-items-number'] = '<span class="cart-items-number">' . $cart_items . '</span>';

	return $datas;
}
add_filter( 'add_to_cart_fragments', 'yit_add_to_cart_success_ajax' );



/* COMPARE */

function yit_woocp_footer_script() {
	$woocp_compare_events = wp_create_nonce("woocp-compare-events");
// 	$woocp_compare_popup = wp_create_nonce("woocp-compare-popup");
// 	$comparable_settings = get_option('woo_comparable_settings');
// 	if (trim($comparable_settings['popup_width']) != '') $popup_width = $comparable_settings['popup_width'];
// 	else $popup_width = 1000;
//
// 	if (trim($comparable_settings['popup_height']) != '') $popup_height = $comparable_settings['popup_height'];
// 	else $popup_height = 650;

	$script_add_on = '';
	$script_add_on .= '<script type="text/javascript">
			jQuery(document).ready(function($){';
	$script_add_on .= '
					woo_update_total_compare_list = function(){
						var data = {
							action: 		"woocp_update_total_compare",
							security: 		"'.$woocp_compare_events.'"
						};
						$.post( ajax_url, data, function(response) {
							total_compare = $.parseJSON( response );
							$("#total_compare_product").html("("+total_compare+")");
                            $(".woo_compare_button_go").trigger("click");';
// 	if (trim($comparable_settings['popup_type']) == 'lightbox') {
//         $script_add_on .= '
//                             $.lightbox(ajax_url+"?action=woocp_get_popup&security='.$woocp_compare_popup.'", {
//                                 "width"       : '.$popup_width.',
//                                 "height"      : '.$popup_height.'
//                             });';
// 	}else {
//         $script_add_on .= '
//     						$.fancybox({
//     							href: ajax_url+"?action=woocp_get_popup&security='.$woocp_compare_popup.'",
//     							title: "Compare Products",
//     							maxWidth: '.$popup_width.',
//     							maxHeight: '.$popup_height.',
//     							openEffect	: "none",
//     							closeEffect	: "none"
//     						});';
// 	}

	$script_add_on .= '
    					});
					};

				});
			</script>';
	echo $script_add_on;
}


/**
 * Add default images dimensions to woocommerce options
 *
 */
function yit_woocommerce_default_image_dimensions() {
	$field = 'yit_woocommerce_image_dimensions_' . get_template();

	if( get_option($field) == false ) {
		update_option($field, time());

		//woocommerce 2.0
		update_option( 'shop_thumbnail_image_size', array( 'width' => 65, 'height' => 65, 'crop' => true ) );
		update_option( 'shop_single_image_size', array( 'width' => 670, 'height' => 420, 'crop' => true ) );
		update_option( 'shop_catalog_image_size', array( 'width' => 254, 'height' => 203, 'crop' => true ) );
		update_option( 'woocommerce_magnifier_image', array( 'width' => 1340, 'height' => 840, 'crop' => true ) );
		update_option( 'shop_featured_image_size', array( 'width' => 160, 'height' => 160, 'crop' => true ) );
	}
}



/**
 * Backup woocoomerce options when create the export gz
 *
 */
function yit_save_woocommerce_tables( $tables ) {
	$tables[] = 'woocommerce_termmeta';
	$tables[] = 'woocommerce_attribute_taxonomies';
	return $tables;
}

/**
 * Backup woocoomerce options when create the export gz
 *
 */
function yit_save_woocommerce_options( $options ) {
	$options[] = '%woocommerce%';
    $options[] = '%wc_average_rating%';
    $options[] = '%wc_product_children_ids%';
    $options[] = '%wc_term_counts%';
    $options[] = '%wc_products_onsale%';
    $options[] = '%wc_needs_pages%';
    $options[] = '%wc_needs_update%';
    $options[] = '%wc_activation_redirect%';
    $options[] = '%wc_hidden_product_ids%';
    $options[] = '%shop_catalog_image_size%';
    $options[] = '%shop_featured_image_size%';
    $options[] = '%shop_single_image_size%';
    $options[] = '%shop_thumbnail_image_size%';

	return $options;
}

/**
 * Backup woocoomerce wishlist when create the export gz
 *
 */
function yit_save_wishlist_options( $options ) {
	$options[] = 'yith\_wcwl\_%';
	$options[] = 'yith-wcwl-%';
	return $options;
}

/**
 * Backup options of plugins when create the export gz
 *
 */
function yit_add_plugins_options( $options ) {
    $options[] = 'yith_woocompare_%';
    $options[] = 'yith_wcmg_%';

    return $options;
}

/**
 * Update woocommerce options after update from 1.6 to 2.0
 */
function yit_woocommerce_update() {
	global $woocommerce;

	$field = 'yit_woocommerce_update_' . get_template();

	if( get_option($field) == false && version_compare($woocommerce->version,"2.0.0",'>=') ) {
		update_option($field, time());

		//woocommerce 2.0
        update_option(
            'shop_catalog_image_size',
            array(
                'width' => get_option('woocommerce_catalog_image_width', 254 ),
                'height' => get_option('woocommerce_catalog_image_height', 203 ),
                'crop' => get_option('woocommerce_catalog_image_crop', 0)
            )
        );

		update_option(
			'shop_thumbnail_image_size',
			array(
				'width' => get_option('woocommerce_thumbnail_image_width', 79),
				'height' => get_option('woocommerce_thumbnail_image_height', 79),
				'crop' => get_option('woocommerce_thumbnail_image_crop', 1)
			)
		);

		update_option(
			'shop_single_image_size',
			array(
				'width' => get_option('woocommerce_single_image_width', 470 ),
				'height' => get_option('woocommerce_single_image_height', 420 ),
				'crop' => get_option('woocommerce_single_image_crop', 1)
			)
		);

		update_option(
			'woocommerce_magnifier_image',
			array(
				'width' => get_option('woocommerce_magnifier_image_width', 940 ),
				'height' => get_option('woocommerce_magnifier_image_height', 840 ),
				'crop' => get_option('woocommerce_magnifier_image_crop', 0)
			)
		);

		update_option(
			'shop_featured_image_size',
			array(
				'width' => get_option('woocommerce_featured_products_slider_image_width', 160 ),
				'height' => get_option('woocommerce_featured_products_slider_image_height', 160 ),
				'crop' => get_option('woocommerce_featured_products_slider_image_crop', 1)
			)
		);
	}
}

function woocommerce_taxonomy_archive_description() {
    if ( is_tax( array( 'product_cat', 'product_tag' ) ) && get_query_var( 'paged' ) == 0 ) {
        global $wp_query;

        $cat = $wp_query->get_queried_object();
        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
        $image = wp_get_attachment_image_src( $thumbnail_id, 'full' );

        $description = apply_filters( 'the_content', term_description() );

        if( $image && yit_get_option('shop-category-image') == 1 ) {
            echo '<div class="term-header-image"><img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[1] . '" alt="' . $cat->name . '" /></div>';
        }

        if ( $description ) {
            echo '<div class="term-description">' . $description . '</div>';
        }
    }
}

function yit_reorder_product_tabs( $tabs ) {
    if( isset( $tabs['reviews'] ) ) {
        $tabs['reviews']['priority'] = 40;
    }

    return $tabs;
}



/** COMPARE DEFAULT OPTIONS */
function yith_change_woocompare_options( $options ) {
    foreach ( $options['general'] as $i => $option ) {
        if ( in_array( $option['id'], array( 'yith_woocompare_compare_button_in_product_page', 'yith_woocompare_compare_button_in_products_list' ) ) ) {
            $options['general'][$i]['default'] = $options['general'][$i]['std'] = 'yes';
        }
    }
    return $options;
}
add_filter( 'yith_woocompare_tab_options', 'yith_change_woocompare_options' );

/**
 * Remove Items option from the magnifier
 *
 * @param array $array
 * @return array
 * @since 1.0
 */
function yit_remove_items_options_yith_wcmg( $array ) {

    foreach( $array['slider'] as $key => $option ) {
        if( $option['id'] == 'yith_wcmg_slider_items' ) {
            unset( $array['slider'][$key] );
        }
    }

    return $array;
}




/**
 * Ajax search products
 */
function yit_ajax_search_products() {
    global $woocommerce;

    $search_keyword = esc_attr($_REQUEST['query']);
    $ordering_args = $woocommerce->query->get_catalog_ordering_args( 'title', 'asc' );
    $products = array();

    $args = array(
        's'                     => apply_filters('yit_ajax_search_products_search_query', $search_keyword),
        'post_type'				=> 'product',
        'post_status' 			=> 'publish',
        'ignore_sticky_posts'	=> 1,
        'orderby' 				=> $ordering_args['orderby'],
        'order' 				=> $ordering_args['order'],
        'posts_per_page' 		=> apply_filters('yit_ajax_search_products_posts_per_page', 10),
        'meta_query' 			=> array(
            array(
                'key' 			=> '_visibility',
                'value' 		=> array('catalog', 'visible'),
                'compare' 		=> 'IN'
            )
        )
    );
    $products_query = new WP_Query( $args );

    if ( $products_query->have_posts() ) {
        while ( $products_query->have_posts() ) {
            $products_query->the_post();

            $products[] = array(
                'id' => get_the_ID(),
                'value' => get_the_title(),
                'url' => get_permalink()
            );
        }
    } else {
        $products[] = array(
            'id' => -1,
            'value' => __('No results', 'yit'),
            'url' => ''
        );
    }
    wp_reset_postdata();


    $products = array(
        'suggestions' => $products
    );


    echo json_encode( $products );
    die();
}
add_action('wp_ajax_yit_ajax_search_products', 'yit_ajax_search_products');
add_action('wp_ajax_nopriv_yit_ajax_search_products', 'yit_ajax_search_products');


/* Function to add compatibility with WC 2.1 */
function yit_woocommerce_primary_start() {
    global $woo_shop_folder;
    yith_wc_get_template( $woo_shop_folder . '/primary-start.php' );
}

function yit_rating_singleproduct() {
    yith_wc_get_template( 'single-product/rating.php' );
}

function yit_woocommerce_primary_end() {
    global $woo_shop_folder;
    yith_wc_get_template( $woo_shop_folder . '/primary-end.php' );
}


if ( ! function_exists( 'yith_wc_get_page_id' ) ) {

    function yith_wc_get_page_id( $page ) {

        global $woocommerce;

        if ( version_compare( preg_replace( '/-beta-([0-9]+)/', '', $woocommerce->version ), '2.1', '<' ) ) {
            return woocommerce_get_page_id( $page );
        }
        else {

            if ( $page == 'pay' || $page == 'thanks' ) {
                $wc_order = new WC_Order();
                $page     = $wc_order->get_checkout_order_received_url();
            }
            return wc_get_page_id( $page );
        }

    }
}

if ( ! function_exists( 'yith_wc_get_template' ) ) {
    function yith_wc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
        if ( function_exists( 'wc_get_template' ) ) {
            wc_get_template( $template_name, $args, $template_path, $default_path );
        }
        else {
            woocommerce_get_template( $template_name, $args, $template_path, $default_path );
        }
    }
}

function yit_enqueue_woocommerce_styles() {
    wp_deregister_style( 'woocommerce_frontend_styles' );
    wp_enqueue_style( 'woocommerce_frontend_styles', get_stylesheet_directory_uri() . '/woocommerce_2.0.x/style.css' );
}

function yit_enqueue_wc_styles( $styles ) {
    unset( $styles['woocommerce-layout'], $styles['woocommerce-smallscreen'], $styles['woocommerce-general'] );

    $styles ['yit-layout'] = array(
        'src'     => get_stylesheet_directory_uri() . '/woocommerce/style.css',
        'deps'    => '',
        'version' => '1.0',
        'media'   => ''
    );
    return $styles;
}


//add_action( 'admin_init', 'add_myaccount_pages', 11 );
function add_myaccount_pages() {
    global $pagenow;
    if ( $pagenow == 'index.php' && isset( $_GET['page'] ) && $_GET['page'] == 'wc-about' && isset( $_GET['wc-updated'] ) && $_GET['wc-updated'] == 'true' ) {
        $parent = get_page_by_path( 'my-account' );
        if ( $parent != null ) {
            $wc_pages = array(
                array(
                    'post_title'   => __( 'View Orders', 'yit' ),
                    'post_name'    => 'view-order',
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_parent'  => $parent->ID
                ),
                array(
                    'post_title'   => __( 'Edit Address', 'yit' ),
                    'post_name'    => 'edit-address',
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_parent'  => $parent->ID
                ),
                array(
                    'post_title'   => __( 'Change Password', 'yit' ),
                    'post_name'    => 'edit-account',
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_parent'  => $parent->ID
                ),
                array(
                    'post_title'   => __( 'Logout', 'yit' ),
                    'post_name'    => 'customer-logout',
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_parent'  => $parent->ID
                )

            );
            foreach ( $wc_pages as $p ) {
                $ex = get_page_by_path( 'my-account/' . $p['post_name'] );

                if ( is_null( $ex ) ) {
                    $pid = wp_insert_post( $p );
                }
            }
        }
    }


}

if( !function_exists( 'yit_wc_catalog_is_product_purchase' ) ) {
    /**
     * Detect if there is a wc catalog mode plugin installed
     *
     *
     * @return void
     * @since 1.7.1
     */
    function yit_wc_catalog_is_product_purchase($product) {

        return WC_Catalog_Restrictions_Filters::instance()->user_can_purchase( $product );

    }
}

if ( ! function_exists( 'yit_redirect_to_checkout' ) ) {
    /**
     * Redirect to checkout page after add to cart
     *
     * @since 1.6.0
     *
     * @return string
     */
    function yit_redirect_to_checkout() {
        global $woocommerce;
        $checkout_url = $woocommerce->cart->get_checkout_url();
        return $checkout_url;
    }
}

if ( ! function_exists( 'yit_redirect_cart_to_checkout' ) ) {
    /**
     * Redirect to checkout after product is added to cart
     *
     * This function is called only on shop pages and shortcodes.
     *
     * @since 1.6.0
     *
     * @param $params
     * @return null
     */
    function yit_redirect_cart_to_checkout( $params ) {
        return null;
    }
}