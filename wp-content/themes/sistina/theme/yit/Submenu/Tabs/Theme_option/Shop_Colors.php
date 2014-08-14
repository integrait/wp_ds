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
 
/**
 * Class to print fields in the tab Shop -> Colors
 * 
 * @since 1.0.0
 */
class YIT_Submenu_Tabs_Theme_option_Shop_Colors extends YIT_Submenu_Tabs_Abstract {
    /**
     * Default fields
     * 
     * @var array
     * @since 1.0.0
     */
    public $fields;
    
    /**
     * Merge default fields with theme specific fields using the filter yit_submenu_tabs_theme_option_shop_colors
     * 
     * @param array $fields
     * @since 1.0.0
     */
    public function __construct() {
        $fields = $this->init();
        $this->fields = apply_filters( strtolower( __CLASS__ ), $fields );
    }
    
    /**
     * Set default values
     * 
     * @return array
     * @since 1.0.0
     */
    public function init() {  
        return array(
        	/* === START COLORS === */     
            5 => array(
                'type' => 'title',
                'name' => __( 'Cart header widget', 'yit' ),
                'desc' => __( 'Color settings for the widget of shopping cart in header.', 'yit' )
            ),

            10 => array(
                'id' => 'shop-cart-header-items-background',
                'type' => 'colorpicker',
                'name' => __( 'Number of items background', 'yit' ),
                'desc' => __( 'Select the background color for the number of items in cart.', 'yit' ),
                'std' => '#e1820a',
                'style' => array(
                    'selectors' => '.woo_cart .widget_shopping_cart .cart_label span',
                    'properties' => 'background-color'
                )
            ),

            20 => array(
                'id' => 'shop-cart-background',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart background', 'yit' ),
                'desc' => __( 'Select the color of shop cart on topbar background.', 'yit' ),
                'std' => '#ffffff',
                'style' => array(
                    'selectors' => '.woo_cart .widget_shopping_cart .widget_shopping_cart_content',
                    'properties' => 'background-color'
                )
            ),

            30 => array(
                'id' => 'shop-cart-border',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart items border', 'yit' ),
                'desc' => __( 'Select the color of borders.', 'yit' ),
                'std' => '#ebe5e5',
                'style' => array(
                    'selectors' => '.woo_cart .cart_wrapper ul.product_list_widget li, .cart_wrapper ul.product_list_widget li img, .woo_cart .widget_shopping_cart_content h2',
                    'properties' => 'border-color'
                )
            ),

            70 => array(
                'type' => 'title',
                'name' => __( 'Grid View', 'yit' ),
                'desc' => __( 'Colors for the grid view.', 'yit' )
            ),
            120 => array(
                'id' => 'shop-out-of-stock-bg',
                'type' => 'colorpicker',
                'name' => __( 'Color background out of stock message', 'yit' ),
                'desc' => __( 'Select the background color of out of stock message.', 'yit' ),
                'std' => '#8e0404',
                'style' => array(
                	'selectors' => '#primary ul.products li.product a.button.out-of-stock',
                	'properties' => 'background-color'
				)
            ),
            130 => array(
                'id' => 'shop-out-of-stock-text',
                'type' => 'colorpicker',
                'name' => __( 'Color text out of stock message', 'yit' ),
                'desc' => __( 'Select the text color of out of stock message.', 'yit' ),
                'std' => '#fff',
                'style' => array(
                	'selectors' => '#primary ul.products li.product a.button.out-of-stock',
                	'properties' => 'color'
				)
            ),
            135 => array(
                'id' => 'shop-out-of-stock-shadow',
                'type' => 'colorpicker',
                'name' => __( 'Border bottom Color out of stock message', 'yit' ),
                'desc' => __( 'Select the bottom border color of out of stock message.', 'yit' ),
                'std' => '#570404',
                'boxshadow' => '0 2px 0',
                'style' => array(
                    'selectors' => '#primary ul.products li.product a.button.out-of-stock',
                    'properties' => 'box-shadow'
                )
            ),
            190 => array(
                'type' => 'title',
                'name' => __( 'Classic Layout', 'yit' ),
                'desc' => __( 'Colors for the classic layout.', 'yit' )
            ),
            250 => array(
                'id' => 'shop-classic-border',
                'type' => 'colorpicker',
                'name' => __( 'Border color on image', 'yit' ),
                'desc' => __( 'Select the border color of the image.', 'yit' ),
                'std' => '#dcd9d3',
                'style' => array(
                	'selectors' => '#primary ul.products li.product.grid.classic.with-border a.thumb',
                	'properties' => 'border-color'
				)
            ), 
            260 => array(
                'id' => 'shop-classic-border-hover',
                'type' => 'colorpicker',
                'name' => __( 'Border color on image (on hover)', 'yit' ),
                'desc' => __( 'Select the border color of the image.', 'yit' ),
                'std' => '#f4c491',
                'style' => array(
                	'selectors' => '#primary ul.products li.product.grid.classic.with-border a.thumb:hover',
                	'properties' => 'border-color'
				)
            ),
            275 => array(
                'type' => 'title',
                'name' => __( 'Button colors', 'yit' ),
                'desc' => __( 'Colors for the buttons.', 'yit' )
            ),
            280 => array(
                'id' => 'shop-buttons-background',
                'type' => 'colorpicker',
                'name' => __( 'Color background of buttons', 'yit' ),
                'desc' => __( 'Select the background color of buttons.', 'yit' ),
                'std' => '#e89222',
                'style' => array(
                    'selectors' => '.product .single_add_to_cart_button, .cart .button, input.checkout-button.alt.button, .shipping-calculator-form .button, .multistep_step .button, #place_order.button, .single-product .single_add_to_cart_button.button.alt, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woo_cart .cart_wrapper .buttons .button, .sidebar .widget_product_search #searchform #searchsubmit, .woocommerce-page .shop_table.wishlist_table .add_to_cart.button, .woocommerce .shop_table.wishlist_table .add_to_cart.button, .woocommerce .cart_totals_container input.button.alt.checkout-button, .welcome_menu form input.button, .single-product.woocommerce div.product .single_add_to_cart_button, .woocommerce .wishlist_table .product-add-to-cart a.add_to_cart.button.alt',
                    'properties' => 'background-color'
                )
            ),
            290 => array(
                'id' => 'shop-buttons-hover-background',
                'type' => 'colorpicker',
                'name' => __( 'Color background of buttons on hover', 'yit' ),
                'desc' => __( 'Select the background color of buttons on hover.', 'yit' ),
                'std' => '#e37a1c',
                'style' => array(
                    'selectors' => 'div.product form.cart .button:hover, #content div.product form.cart .button:hover, .cart .button:hover, input.checkout-button.alt.button:hover, .shipping-calculator-form .button:hover, .multistep_step .button:hover, #place_order.button:hover, .single-product .single_add_to_cart_button.button.alt:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover, .woo_cart .yit_cart_widget .cart_wrapper .buttons .button:hover, .sidebar .widget_product_search #searchform #searchsubmit:hover, .woocommerce-page .shop_table.wishlist_table .add_to_cart.button:hover, .woocommerce .shop_table.wishlist_table .add_to_cart.button:hover, .woocommerce .cart_totals_container input.button.alt.checkout-button:hover,.welcome_menu form input.button:hover, .single-product.woocommerce div.product .single_add_to_cart_button:hover, .woocommerce .wishlist_table .product-add-to-cart a.add_to_cart.button.alt:hover',
                    'properties' => 'background-color'
                )
            ),

            291 => array(
                'id' => 'shop-buttons-background-bottom-shadow',
                'type' => 'colorpicker',
                'name' => __( 'Border bottom color for buttons.', 'yit'),
                'desc' => __('Select the color of border bottom for buttons', 'yit'),
                'std' => apply_filters('yit_general-button-bottom-shadow_std', '#cd6b02'),
                'boxshadow' => '0 2px 0',
                'style' => array(
                    'selectors' => '.product .single_add_to_cart_button, .cart .button, input.checkout-button.alt.button, .shipping-calculator-form .button, .multistep_step .button, #place_order.button, .single-product .single_add_to_cart_button.button.alt, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .sidebar .widget_product_search #searchform #searchsubmit, .woocommerce .cart_totals_container input.button.alt.checkout-button, .welcome_menu form input.button, .woocommerce .wishlist_table .product-add-to-cart a.add_to_cart.button.alt',
                    'properties' => 'box-shadow'

                )
            ),

            292 => array(
                'id' => 'shop-buttons-background-bottom-shadow-hover',
                'type' => 'colorpicker',
                'name' => __( 'Border bottom color for buttons on hover.', 'yit'),
                'desc' => __('Select the color of border bottom for buttons on hover', 'yit'),
                'std' => apply_filters('yit_general-button-bottom-shadow_std', '#cd6b02'),
                'boxshadow' => '0 2px 0',
                'style' => array(
                    'selectors' => 'div.product form.cart .button:hover, #content div.product form.cart .button:hover, .cart .button:hover, input.checkout-button.alt.button:hover, .shipping-calculator-form .button:hover, .multistep_step .button:hover, #place_order.button:hover, .single-product .single_add_to_cart_button.button.alt:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover, .sidebar .widget_product_search #searchform #searchsubmit:hover, .woocommerce .cart_totals_container input.button.alt.checkout-button:hover, .welcome_menu form input.button:hover, .woocommerce .wishlist_table .product-add-to-cart a.add_to_cart.button.alt:hover',
                    'properties' => 'box-shadow'

                )
            ),

            300 => array(
                'id' => 'shop-buttons2-background',
                'type' => 'colorpicker',
                'name' => __( 'Color background of buttons (style 2)', 'yit' ),
                'desc' => __( 'Select the background color of buttons (style 2).', 'yit' ),
                'std' => '#838280',
                'style' => array(
                    'selectors' => '.woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce .cart_totals_container input.button.update-button',
                    'properties' => 'background-color'
                )
            ),
            310 => array(
                'id' => 'shop-buttons2-hover-background',
                'type' => 'colorpicker',
                'name' => __( 'Color background of buttons on hover (style 2)', 'yit' ),
                'desc' => __( 'Select the background color of buttons on hover (style 2).', 'yit' ),
                'std' => '#4f4f4f',
                'style' => array(
                    'selectors' => '.woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce .cart_totals_container .checkout-button.button:hover, .woocommerce-page .cart_totals_container .checkout-button.button:hover, .woocommerce .cart_totals_container input.button.update-button:hover',
                    'properties' => 'background-color'
                )
            ),



            312 => array(
                'id' => 'shop-buttons2-background-bottom-shadow',
                'type' => 'colorpicker',
                'name' => __( 'Border bottom color for buttons (style 2).', 'yit'),
                'desc' => __('Select the color of border bottom for buttons (style 2)', 'yit'),
                'std' => apply_filters('yit_general-button-bottom-shadow_std', '#605F5D'),
                'boxshadow' => '0 2px 0',
                'style' => array(
                    'selectors' => '.woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page a.button.alt, .woocommerce-page button.button.alt, .woocommerce-page input.button.alt, .woocommerce-page #respond input#submit.alt, .woocommerce-page #content input.button.alt, .woocommerce .cart_totals_container input.button.update-button',
                    'properties' => 'box-shadow'

                )
            ),

            313 => array(
                'id' => 'shop-buttons2-background-bottom-shadow-hover',
                'type' => 'colorpicker',
                'name' => __( 'Border bottom color for buttons on hover (style 2).', 'yit'),
                'desc' => __('Select the color of border bottom for buttons on hover (style 2)', 'yit'),
                'std' => apply_filters('yit_general-button-bottom-shadow_std', '#605F5D'),
                'boxshadow' => '0 2px 0',
                'style' => array(
                    'selectors' => '.woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover, .woocommerce #respond input#submit.alt:hover, .woocommerce #content input.button.alt:hover, .woocommerce-page a.button.alt:hover, .woocommerce-page button.button.alt:hover, .woocommerce-page input.button.alt:hover, .woocommerce-page #respond input#submit.alt:hover, .woocommerce-page #content input.button.alt:hover, .woocommerce .cart_totals_container input.button.update-button:hover',
                    'properties' => 'box-shadow'

                )
            ),

            320 => array(
                'id' => 'shop-buttons-text',
                'type' => 'colorpicker',
                'name' => __( 'Color text of buttons', 'yit' ),
                'desc' => __( 'Select the text color of buttons.', 'yit' ),
                'std' => '#FFFFFF',
                'style' => array(
                	'selectors' => '.product .summary .single_add_to_cart_button, .cart .button, input.checkout-button.alt.button, .shipping-calculator-form .button, .multistep_step .button, #place_order.button, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit, .woocommerce #content input.button, .woocommerce-page a.button, .woocommerce-page button.button, .woocommerce-page input.button, .woocommerce-page #respond input#submit, .woocommerce-page #content input.button, .woocommerce a.button.alt, .home-widget .newsletter-call3 .newsletter-submit .submit-field, .sidebar .widget_product_search #searchform #searchsubmit, a.button, button.button, input.button, #respond input#submit, .welcome_menu form input.button',
                	'properties' => 'color'
				)
            ),
            330 => array(
                'id' => 'shop-buttons-text-hover',
                'type' => 'colorpicker',
                'name' => __( 'Color text hover of buttons', 'yit' ),
                'desc' => __( 'Select the text color of buttons on hover.', 'yit' ),
                'std' => '#FFFFFF',
                'style' => array(
                	'selectors' => 'div.product form.cart .button:hover, #content div.product form.cart .button:hover, .cart .button:hover, input.checkout-button.alt.button:hover, .shipping-calculator-form .button:hover, .multistep_step .button:hover, #place_order.button:hover,  .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce #respond input#submit:hover, .woocommerce #content input.button:hover, .woocommerce-page a.button:hover, .woocommerce-page button.button:hover, .woocommerce-page input.button:hover, .woocommerce-page #respond input#submit:hover, .woocommerce-page #content input.button:hover, .woocommerce a.button.alt:hover, .home-widget .newsletter-call3 .newsletter-submit .submit-field:hover, .woo_cart .cart_wrapper .buttons .button:hover, .sidebar .widget_product_search #searchform #searchsubmit:hover, a.button:hover, button.button:hover, input.button:hover,.welcome_menu form input.button:hover',
                	'properties' => 'color'
				)
            ),


            340 => array(
                'type' => 'title',
                'name' => __( 'Shop Widgets', 'yit' ),
                'desc' => __( 'Colors for the widgets.', 'yit' )
            ),   
            350 => array(
                'id' => 'shop-price-filter-bar-inactive',
                'type' => 'colorpicker',
                'name' => __( 'Price Filter - Inactive bar', 'yit' ),
                'desc' => __( 'Select the color for the bar rappresents the prices not included in the filtering.', 'yit' ),
                'std' => '#DADADA',
                'style' => array(
                	'selectors' => '.widget.widget_price_filter .price_slider_wrapper .ui-widget-content',
                	'properties' => 'background-color'
				)
            ),  
            360 => array(
                'id' => 'shop-price-filter-bar-active',
                'type' => 'colorpicker',
                'name' => __( 'Price Filter - Active bar', 'yit' ),
                'desc' => __( 'Select the color for the bar rappresents the prices included in the filtering.', 'yit' ),
                'std' => '#CD8906',
                'style' => array(
                	'selectors' => '.widget.widget_price_filter .ui-slider .ui-slider-range, .widget.widget_price_filter .ui-slider .ui-slider-handle',
                	'properties' => 'background-color'
				)
            ), 
            370 => array(
                'id' => 'shop-layered-nav-active-text',
                'type' => 'colorpicker',
                'name' => __( 'Layered Nav - Active filter text', 'yit' ),
                'desc' => __( 'Select the text color for the selected filter.', 'yit' ),
                'std' => '#c38204',
                'style' => array(
                	'selectors' => '.widget.widget_layered_nav .sizes li.chosen .size-filter',
                	'properties' => 'color'
				)
            ),
            380 => array(
                'id' => 'shop-layered-nav-active-border',
                'type' => 'colorpicker',
                'name' => __( 'Layered Nav - Active filter border', 'yit' ),
                'desc' => __( 'Select the text color for the selected filter.', 'yit' ),
                'std' => '#dec084',
                'style' => array(
                	'selectors' => '.widget.widget_layered_nav .sizes li.chosen .size-filter',
                	'properties' => 'border-color'
				)
            ),
            390 => array(
                'id' => 'shop-layered-nav-bg-toggle-opened',
                'type' => 'colorpicker',
                'name' => __( 'Layered Nav - Opened toggle square (with minus)', 'yit' ),
                'desc' => __( 'Select the text color for the opened toggle.', 'yit' ),
                'std' => '#C58408',
                'style' => array(
                	'selectors' => '.faq-title .minus, #sidebar-shop-sidebar .widget .minus',
                	'properties' => 'background-color'
				)
            ),
            400 => array(
                'id' => 'shop-layered-nav-bg-toggle-closed',
                'type' => 'colorpicker',
                'name' => __( 'Layered Nav - Closed toggle square (with plus)', 'yit' ),
                'desc' => __( 'Select the text color for the closed toggle.', 'yit' ),
                'std' => '#2C2B2B',
                'style' => array(
                	'selectors' => '.faq-title .plus, #sidebar-shop-sidebar .widget .plus',
                	'properties' => 'background-color'
				)
            ),
            410 => array(
                'id' => 'shop-layered-nav-links',
                'type' => 'colorpicker',
                'name' => __( 'Layered Nav - Links color', 'yit' ),
                'desc' => __( 'Select the text color for the links.', 'yit' ),
                'std' => '#4F4D4D',
                'style' => array(
                	'selectors' => '.widget.widget_layered_nav li a, .widget_product_categories .product-categories li a, .widget.widget_layered_nav .sizes li .size-filter',
                	'properties' => 'color'
				)
            ),
            420 => array(
                'id' => 'shop-layered-nav-links-hover',
                'type' => 'colorpicker',
                'name' => __( 'Layered Nav - Links color (hover and active)', 'yit' ),
                'desc' => __( 'Select the text color for the links.', 'yit' ),
                'std' => '#AA7309',
                'style' => array(
                	'selectors' => '.widget.widget_layered_nav li a:hover, .widget_product_categories .product-categories li a:hover, .woocommerce .widget_layered_nav ul li.chosen a:after, .woocommerce .widget_layered_nav ul li.chosen a:before, .woocommerce .widget_layered_nav ul li.chosen a, .widget_product_categories .product-categories li.current-cat a, .widget.widget_layered_nav .sizes li .size-filter:hover, .widget.widget_layered_nav .sizes li.chosen .size-filter',
                	'properties' => 'color, border-color'
				)
            ),
        );
    }
}