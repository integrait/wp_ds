<?php
/**
 * Your Inspiration Themes
 * 
 * @package WordPress
 * @subpackage Your Inspiration Themes
 * @author Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( is_shop_installed() ) :                   
 
/**
 * Add more shortcodes to the framework
 * 
 */
function yit_add_shortcodes_woocommerce( $shortcodes ) {
			
	$shop_categories = yit_get_shop_categories();
	$shop_categories_id = yit_get_shop_categories_by_id();
    $products = yit_get_shop_products();
	
    return array_merge( $shortcodes, array(
        'show_products' => array(
			'title' => __('Show the products', 'yit'),
			'description' => __('Show the products', 'yit'),
			'tab' => 'shop',
            'has_content' => false,
			'attributes' => array(
				'per_page' => array(
					'title' => __('N. of items', 'yit'),
					'description' => __('Show all with -1', 'yit'),
            		'type' => 'number', 
					'std'  => '8'
				),
				'category' => array(
					'title' => __('Category', 'yit'),
					'type' => 'select',
					'options' => $shop_categories,
					'std'  => '0'
				),
				'show' => array(
					'title' => __('Show', 'yit'),
					'type' => 'select',
					'options' => array(
						'all' => __('All', 'yit'),
						'featured' => __('Featured', 'yit'),
						'best_sellers' => __('Best sellers', 'yit'),
						'onsale' => __('On sale', 'yit'),
						 
					),
					'std' => 'all'
				),
				'orderby' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'menu_order' => __( 'Default sorting', 'yit' ),
        				'title' => __( 'Sort alphabetically', 'yit' ),
        				'date' => __( 'Sort by most recent', 'yit' ),
        				'meta_value_num' => __( 'Sort by price', 'yit' )
                    ) ),
                    'std' => 'menu_order'
                ),
				'order' => array(
					'title' => __('Sorting', 'yit'),
					'type' => 'select',
					'options' => array(
						'desc' => __('Descending', 'yit'),
						'asc' => __('Crescent', 'yit')
					),
					'std'  => 'desc'
				),
				'layout' => array(
					'title' => __('Layout', 'yit'),
					'type' => 'select',
					'options' => array(
						'default' => __('Default', 'yit'),
						'with-hover' => __('Popup', 'yit'),
						'classic' => __('Classic', 'yit')
					),
					'std'  => 'default'
				),
                'pagination' => array(
                    'title' => __( 'Select if you want to show the pagination', 'yit' ),
                    'type'  => 'checkbox',
                    'options' => array(
                        'yes', 'no',
                    ),
                    'std' => 'no',
                ),
			)
		),
		'products_slider' => array(
			'title' => __('Products slider', 'yit'),
			'description' => __('Add a products slider', 'yit'),
			'tab' => 'shop',
            'has_content' => false,
			'attributes' => array(
				'title' => array(
                    'title' => __( 'Title', 'yit' ),
                    'type' => 'text',
                    'std' => ''
                ),
                'per_page' => array(
                    'title' => __( 'Items', 'yit' ),
                    'type' => 'number',
                    'std' => '12'
                ),
				'category' => array(
					'title' => __('Category', 'yit'),
					'type' => 'select',
					'options' => $shop_categories,
					'std'  => '0'
				),
				'featured' => array(
					'title' => __('Featured', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'no'
				),
				'latest' => array(
					'title' => __('Latest', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'no'
				),
				'best_sellers' => array(
					'title' => __('Best sellers', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'no'
				),
				'on_sale' => array(
					'title' => __('On sale', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'no'
				),
				'orderby' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'description' => __( 'Select the products sorting', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'menu_order' => __( 'Default sorting', 'yit' ),
        				'title' => __( 'Sort alphabetically', 'yit' ),
        				'date' => __( 'Sort by most recent', 'yit' ),
        				'price' => __( 'Sort by price', 'yit' ) 
                    ) ),
                    'std' => 'menu_order'
                ),
				'order' => array(
					'title' => __('Sorting', 'yit'),
					'type' => 'select',
					'options' => array(
						'desc' => __('Descending', 'yit'),
						'asc' => __('Crescent', 'yit')
					),
					'std'  => 'desc'
				),
				'layout' => array(
					'title' => __('Layout', 'yit'),
					'type' => 'select',
					'options' => array(
						'default' => __('Default', 'yit'),
						'with-hover' => __('Popup', 'yit'),
						'classic' => __('Classic', 'yit')
					),
					'std'  => 'default'
				),				
			)
		),
		'products_categories_slider' => array(
			'title' => __('Categories slider', 'yit'),
			'description' => __('List all (or limited) product categories', 'yit'),
			'tab' => 'shop',
            'has_content' => false,
			'attributes' => array(
				'title' => array(
                    'title' => __( 'Title', 'yit' ),
                    'type' => 'text',
                    'std' => ''
                ),
                'per_page' => array(
                    'title' => __( 'Items', 'yit' ),
                    'type' => 'number',
                    'std' => '-1'
                ),
				'category' => array(
					'title' => __('Category', 'yit'),
					'type' => 'checklist',
					'options' => $shop_categories_id,
					'std'  => ''
				),
				'numbers' => array(
					'title' => __('Show numbers', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'yes'
				),
				'hide_empty' => array(
					'title' => __('Hide empty', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'yes'
				),	
				'style' => array(
					'title' => __('Style', 'yit'),
					'type' => 'select',
					'options' => array(
						'traditional' => __('Traditional', 'yit'),
						'transparent' => __('Transparent', 'yit')
					),
					'std'  => 'traditional'
				),
                'orderby' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'menu_order' => __( 'Default sorting', 'yit' ),
                        'title' => __( 'Sort alphabetically', 'yit' ),
                        'count' => __( 'Sort by number of items', 'yit' )
                    ) ),
                    'std' => 'menu_order'
                ),
				'order' => array(
					'title' => __('Sorting', 'yit'),
					'type' => 'select',
					'options' => array(
						'desc' => __('Descending', 'yit'),
						'asc' => __('Crescent', 'yit')
					),
					'std'  => 'desc'
				)				
			)
		),
		'products_categories' => array(
			'title' => __('Product Categories', 'yit'),
			'description' => __('List all (or limited) product categories', 'yit'),
			'tab' => 'shop',
            'has_content' => false,
			'attributes' => array(
				'per_page' => array(
					'title' => __('N. of items', 'yit'),
					'description' => __('Show all with -1', 'yit'),
					'type' => 'number', 
					'std'  => '-1'
				),
				'category' => array(
					'title' => __('Category', 'yit'),
					'type' => 'checklist',
					'options' => $shop_categories_id,
					'std'  => ''
				),
				'numbers' => array(
					'title' => __('Show numbers', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'yes'
				),
				'hide_empty' => array(
					'title' => __('Hide empty', 'yit'),
					'type' => 'checkbox', 
					'std'  => 'yes'
				),
				'style' => array(
					'title' => __('Style', 'yit'),
					'type' => 'select',
					'options' => array(
						'traditional' => __('Traditional', 'yit'),
						'transparent' => __('Transparent', 'yit')
					),
					'std'  => 'traditional'
				),
                'orderby' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'menu_order' => __( 'Default sorting', 'yit' ),
                        'title' => __( 'Sort alphabetically', 'yit' ),
                        'count' => __( 'Sort by number of items', 'yit' )
                    ) ),
                    'std' => 'menu_order'
                ),
				'order' => array(
					'title' => __('Sorting', 'yit'),
					'type' => 'select',
					'options' => array(
						'desc' => __('Descending', 'yit'),
						'asc' => __('Crescent', 'yit')
					),
					'std'  => 'desc'
				)				
			)
		),
        'products_tabs' => array(
            'title' => __('Products Tabs', 'yit'),
            'description' => __('List products in tabs', 'yit'),
            'tab' => 'shop',
            'multiple' => true,
            'unlimited'   => true,
            'has_content' => false,
            'attributes' => array(
                'title_1' => array(
                    'title' => __('Title', 'yit'),
                    'type' => 'text',
                    'std'  => '',
                    'multiple' => true
                ),
                'per_page_1' => array(
                    'title' => __('N. of items', 'yit'),
                    'description' => __('Show all with -1', 'yit'),
                    'type' => 'number',
                    'std'  => '10',
                    'multiple' => true
                ),
                'category_1' => array(
                    'title' => __('Category', 'yit'),
                    'type' => 'select',
                    'options' => $shop_categories,
                    'std'  => '0',
                    'multiple' => true
                ),
                'show_1' => array(
                    'title' => __('Show', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'all' => __('All', 'yit'),
                        'featured' => __('Featured', 'yit'),
                        'best_sellers' => __('Best sellers', 'yit'),
                        'onsale' => __('On sale', 'yit'),

                    ),
                    'std' => 'all',
                    'multiple' => true
                ),
                'orderby_1' => array(
                    'title' => __( 'Order by', 'yit' ),
                    'type' => 'select',
                    'options' => apply_filters( 'woocommerce_catalog_orderby', array(
                        'menu_order' => __( 'Default sorting', 'yit' ),
                        'title' => __( 'Sort alphabetically', 'yit' ),
                        'date' => __( 'Sort by most recent', 'yit' ),
                        'price' => __( 'Sort by price', 'yit' )
                    ) ),
                    'std' => 'menu_order',
                    'multiple' => true
                ),
                'order_1' => array(
                    'title' => __('Sorting', 'yit'),
                    'type' => 'select',
                    'options' => array(
                        'desc' => __('Descending', 'yit'),
                        'asc' => __('Crescent', 'yit')
                    ),
                    'std'  => 'desc',
                    'multiple' => true
                ),
            )
        ),
		'rating' => array(
			'title' => __('Rating', 'yit'),
			'description' => __('Print a product rating', 'yit'),
			'tab' => 'shop',
            'has_content' => false,
			'attributes' => array(
				'id' => array(
					'title' => __('Product ID', 'yit'),
					'type' => 'text', 
					'std'  => ''
				),
			)
		),
        'yit_add_to_cart' => array(
            'title' => __('Add to cart', 'yit'),
            'description' => __('Print add to cart button', 'yit'),
            'tab' => 'shop',
            'has_content' => false,
            'attributes' => array(
                'id' => array(
                    'title' => __('Product ID', 'yit'),
                    'type' => 'text',
                    'std'  => ''
                ),
                'attribute_id' => array(
                    'title' => __('Attributes ID', 'yit'),
                    'type' => 'text',
                    'std'  => ''
                ),
                'show_price' => array(
                    'title' => __('Show price', 'yit'),
                    'type' => 'checkbox',
                    'std'  => 'yes'
                ),
                'show_cart' => array(
                    'title' => __('Show cart', 'yit'),
                    'type' => 'checkbox',
                    'std'  => 'yes'
                ),
            )
        ),
        'review_slider' => array(
            'title' => __('Show reviews', 'yit'),
            'description' => __('Show review of the users', 'yit'),
            'tab' => 'shop',
            'has_content' => false,
            'attributes' => array(
                'title' => array(
                    'title' => __('Title', 'yit'),
                    'type' => 'text',
                    'std'  => ''
                ),
                'description' => array(
                    'title' => __('Description', 'yit'),
                    'type' => 'text',
                    'std'  => ''
                ),
                'review_icon_title' => array(
                    'title' => __('Title icon URL', 'yit'),
                    'type' => 'text',
                    'std'  => ''
                ),
                'items' => array(
                    'title' => __('N. of items', 'yit'),
                    'description' => __('Show all with -1', 'yit'),
                    'type' => 'number',
                    'std'  => '8'
                ),
                'id' => array(
                    'title' => __('Product', 'yit'),
                    'type' => 'select',
                    'options' => $products,
                    'std'  => ''
                ),
                'show_avatar' => array(
                    'title' => __('Show avatar', 'yit'),
                    'type' => 'checkbox',
                    'std'  => 'yes'
                ),
                'show_rating' => array(
                    'title' => __('Show rating', 'yit'),
                    'type' => 'checkbox',
                    'std'  => 'yes'
                ),
                'speed' => array(
                    'title' => __('Speed (ms)', 'yit'),
                    'type' => 'number',
                    'std'  => '500'
                ),
                'timeout' => array(
                    'title' => __('Time out (ms)', 'yit'),
                    'type' => 'number',
                    'std'  => '5000'
                ),
            )
        ),
        /* === CREDIT CARD === */
        'credit_card' => array(
            'title' => __('Credit card', 'yit' ),
            'description' =>  __('Show an images of credit cards', 'yit' ),
            'tab' => 'shop',
            'has_content' => false,
            'attributes' => array(
                'type' => array(
                    'title' => __('Type', 'yit'),
                    'type' => 'checklist',
                    'options'  => array(
                        'amazon' => 'Amazon',
                        'amex' => 'American Express',
                        'amex_gold' => 'American Express Gold',
                        'amex_green' => 'American Express Green',
                        'amex_silver' => 'American Express Silver',
                        'apple' => 'Apple',
                        'bank' => 'Bank',
                        'cash' => 'Cash',
                        'chase' => 'Chase',
                        'coupon' => 'Coupon',
                        'credit' => 'Credit',
                        'debit' => 'Debit',
                        'discover' => 'Discover',
                        'discover_novus' => 'Discover Novus',
                        'echeck' => 'eCheck',
                        'generic_1' => 'Generic 1',
                        'generic_2' => 'Generic 2',
                        'generic_3' => 'Generic 3',
                        'gift' => 'Gift',
                        'gold' => 'Gold',
                        'googleckout' => 'Google Checkout',
                        'itunes' => 'iTunes (red)',
                        'itunes_2' => 'iTunes (blue)',
                        'itunes_3' => 'iTunes (green)',
                        'mastercard' => 'Mastercard',
                        'mileage' => 'Mileage',
                        'paypal' => 'PayPal',
                        'sapphire' => 'Sapphire',
                        'solo' => 'Solo',
                        'visa' => 'Visa'
                    ),
                    'std' => 'generic_1'
                ),
            )
        ),
		'reset_woocommerce_loop' => array(
			'title' => __('Reset woocommerce loop', 'yit'),
			'description' => __('Reset woocommerce loop', 'yit'),
			'tab' => 'shop',
            'has_content' => false,
			'attributes' => array()
		),
	));
}
add_filter( 'yit_add_shortcodes', 'yit_add_shortcodes_woocommerce' );

function yit_shortcodes_tabs( $tabs ) {
	return array_merge( $tabs, array(
		'shop' => __('Shop', 'yit')
	) );
}
add_filter('yit_shortcodes_tabs', 'yit_shortcodes_tabs');

function yit_get_shop_categories(){
	global $wpdb, $blog_id, $current_blog;
	
	wp_reset_query();
	$terms = $wpdb->get_results('SELECT name, slug FROM ' . $wpdb->prefix . 'terms, ' . $wpdb->prefix . 'term_taxonomy WHERE ' . $wpdb->prefix . 'terms.term_id = ' . $wpdb->prefix . 'term_taxonomy.term_id AND taxonomy = "product_cat" ORDER BY name ASC;');
	
	$categories = array();
	$categories['0'] = __('All categories', 'yit');
	if ($terms) :
		foreach ($terms as $cat) : 
			$categories[$cat->slug] = ($cat->name) ? $cat->name : 'ID: '. $cat->slug;
		endforeach;
	endif;
	return $categories;		
}

    function yit_get_shop_categories_by_id(){
        global $wpdb, $blog_id, $current_blog;

        wp_reset_query();
        $terms = $wpdb->get_results('SELECT name, ' . $wpdb->prefix . 'terms.term_id FROM ' . $wpdb->prefix . 'terms, ' . $wpdb->prefix . 'term_taxonomy WHERE ' . $wpdb->prefix . 'terms.term_id = ' . $wpdb->prefix . 'term_taxonomy.term_id AND taxonomy = "product_cat" ORDER BY name ASC;');

        $categories = array();
        $categories['0'] = __('All categories', 'yit');
        if ($terms) :
            foreach ($terms as $cat) :
                $categories[$cat->term_id] = ($cat->name) ? $cat->name : 'ID: '. $cat->term_id;
            endforeach;
        endif;
        return $categories;
    }

    function yit_get_shop_products(){
        global $wpdb;

        wp_reset_query();
        //$terms = $wpdb->get_results('SELECT name, ' . $wpdb->prefix . 'terms.term_id FROM ' . $wpdb->prefix . 'terms, ' . $wpdb->prefix . 'term_taxonomy WHERE ' . $wpdb->prefix . 'terms.term_id = ' . $wpdb->prefix . 'term_taxonomy.term_id AND taxonomy = "product_cat" ORDER BY name ASC;');

        $terms = get_posts( array(
            'post_type'      => array( 'product' ),
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ));

        $products = array();
        $products['0'] = __('All products', 'yit');
        if ($terms) :
            foreach ($terms as $prod) :
                $products[$prod->ID] = ($prod->post_title) ? $prod->post_title : 'ID: '. $prod->ID;
            endforeach;
        endif;
        return $products;
    }

endif;