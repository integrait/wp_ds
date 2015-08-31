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
 * Class to print fields in the tab Shop -> Typography
 * 
 * @since 1.0.0
 */
class YIT_Submenu_Tabs_Theme_option_Shop_Typography extends YIT_Submenu_Tabs_Abstract {
    /**
     * Default fields
     * 
     * @var array
     * @since 1.0.0
     */
    public $fields;
    
    /**
     * Merge default fields with theme specific fields using the filter yit_submenu_tabs_theme_option_shop_typography
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
        	/* === START FONT === */   
            10 => array(
                'type' => 'title',
                'name' => __( 'Cart header widget', 'yit' ),
                'desc' => __( 'Typography settings for the widget of shopping cart in header.', 'yit' )
            ),


            20 => array(
                'id'   => 'shop-cart-header-items-font',
                'type' => 'typography',
                'name' => __( 'Shopping cart items header font', 'yit' ),
                'desc' => __( 'Choose the font type, size and color.', 'yit' ),
                'min'  => 8,
                'max'  => 18,
                'std'  => array(
                    'size'   => 13,
                    'unit'   => 'px',
                    'family' => 'Open Sans',
                    'style'  => 'bold',
                    'color'  => '#ffffff'
                ),
                'style' => array(
                    'selectors' => '.woo_cart .widget_shopping_cart .cart_label span',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                )
            ),


            30 => array(
                'id'   => 'shop-cart-font',
                'type' => 'typography',
                'name' => __( 'Shopping cart list font', 'yit' ),
                'desc' => __( 'Choose the font type, size and color.', 'yit' ),
                'min'  => 10,
                'max'  => 18,
                'std'  => array(
                    'size'   => 12,
                    'unit'   => 'px',
                    'family' => 'Open Sans',
                    'style'  => 'regular',
                    'color'  => '#625548'
                ),
                'style' => array(
                    'selectors' => '.woo_cart .yit_cart_widget.widget_shopping_cart .cart_wrapper ul.cart_list li a, .woo_cart .yit_cart_widget.widget_shopping_cart .cart_wrapper .cart_list li.empty, .woo_cart .yit_cart_widget.widget_shopping_cart .cart_wrapper h2',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                )
            ),


            40 => array(
                'id' => 'shop-cart-font-hover',
                'type' => 'colorpicker',
                'name' => __( 'Shopping cart list font hover', 'yit' ),
                'desc' => __( 'Select the color of shop cart list on hover.', 'yit' ),
                'std' => '#b1690c',
                'style' => array(
                    'selectors' => '.woo_cart .yit_cart_widget.widget_shopping_cart .cart_wrapper ul.cart_list li a:hover',
                    'properties' => 'color'
                )
            ),


            50 => array(
                'id'   => 'price-cart-font',
                'type' => 'typography',
                'name' => __( 'Shopping cart price font', 'yit' ),
                'desc' => __( 'Choose the font type, size and color.', 'yit' ),
                'min'  => 10,
                'max'  => 18,
                'std'  => array(
                    'size'   => 12,
                    'unit'   => 'px',
                    'family' => 'Open Sans',
                    'style'  => 'regular',
                    'color'  => '#9f6110'
                ),
                'style' => array(
                    'selectors' => '.woo_cart .yit_cart_widget.widget_shopping_cart ul.product_list_widget li .quantity, .woo_cart .yit_cart_widget.widget_shopping_cart ul.product_list_widget li .amount',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                )
            ),

            60 => array(
                'type' => 'title',
                'name' => __( 'Single product page', 'yit' ),
                'desc' => __( 'Common settings for the single product page.', 'yit' )
            ),

            70 => array(
                'id'   => 'shop-price',
                'type' => 'typography',
                'name' => __( 'Product price', 'yit' ),
                'desc' => __( 'Choose the font type, size and color.', 'yit' ),
                'min'  => 10,
                'max'  => 36,
                'std'  => array(
                    'size'   => 18,
                    'unit'   => 'px',
                    'family' => 'Open Sans',
                    'style'  => 'regular',
                    'color'  => '#5c5c5b'
                ),
                'style' => array(
                    'selectors' => '.woocommerce div.product .summary p.price',
                    'properties' => 'font-size, font-family, color, font-style, font-weight'
                )
            )
        );
    }
}