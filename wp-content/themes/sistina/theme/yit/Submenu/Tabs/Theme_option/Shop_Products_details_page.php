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
 * Class to print fields in the tab Shop -> Products details page
 * 
 * @since 1.0.0
 */
class YIT_Submenu_Tabs_Theme_option_Shop_Products_details_page extends YIT_Submenu_Tabs_Abstract {
    /**
     * Default fields
     * 
     * @var array
     * @since 1.0.0
     */
    public $fields;
    
    /**
     * Merge default fields with theme specific fields using the filter yit_submenu_tabs_theme_option_shop_products_details_page
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
        	30 => array(
                'id'   => 'shop-products-tab-layout',
                'type' => 'select',
                'name' => __( 'Product tab orientation', 'yit' ),
                'desc' => __( 'Set the orientation for the product tab.', 'yit' ),
                'options' => array(
                    'vertical' => __( 'Vertical', 'yit' ),
                    'horizontal' => __( 'Horizontal', 'yit' )
                ),
                'std'  => 'vertical'
            ),
            40 => array(
                'id'   => 'shop-single-show-breadcrumb',
                'type' => 'onoff',
                'name' => __( 'Show breadcrumb', 'yit' ),
                'desc' => __( 'Say if you want the breadcrumb in the product detail page.', 'yit' ),
                'std'  => true,
            ),
            50 => array(
                'id'   => 'shop-products-details-nav',
                'type' => 'onoff',
                'name' => __( 'Show Prev and Next link', 'yit' ),
                'desc' => __( 'Activate/Deactivate the Prev and Next navigation in the product page.', 'yit' ),
                'std'  => true,
            ),
        	60 => array(
                'id'   => 'shop-products-details-title',
                'type' => 'onoff',
                'name' => __( 'Show products details page title', 'yit' ),
                'desc' => __( 'Activate/Deactivate the page title on Products details.', 'yit' ),
                'std'  => true,
            ),
            70 => array(
                'id'   => 'shop-products-details-contact-form',
                'type' => 'select',
                'name' => __( 'Ask info form', 'yit' ),
                'desc' => __( 'The contact form.', 'yit' ),
                'options' => yit_contact_forms(),
				'std' => -1,
            ),
            170 => array(
                'id'   => 'shop-detail-show-price',
                'type' => 'onoff',
                'name' => __( 'Show price', 'yit' ),
                'desc' => __( 'Select if you want to show a the price on the products list.', 'yit' ),
				'std' => 1
            ),
            180 => array(
                'id'   => 'shop-detail-add-to-cart',
                'type' => 'onoff',
                'name' => __( 'Show button add to cart', 'yit' ),
                'desc' => __( 'Select if you want to show the purchase button.', 'yit' ),
				'std' => 1
            ),
            190 => array(
                'id'   => 'shop-single-show-wishlist',
                'type' => 'onoff',
                'name' => __( 'Show wishlist icon', 'yit' ),
                'desc' => __( 'Say if you want to show the wishlist icon.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-view-show-wishlist_std', 1 )
            ),
            200 => array(
                'type' => 'title',
                'name' => __( 'Socials', 'yit' ),
                'desc' => __( 'Options for the box socials of product detail page.', 'yit' )
            ),
            210 => array(
                'id'   => 'shop-single-show-socials',
                'type' => 'onoff',
                'name' => __( 'Show socials box', 'yit' ),
                'desc' => __( 'Say if you want to show the box of socials to share the product.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-view-show-socials_std', 1 )
            ),
            220 => array(
                'id'   => 'shop-single-show-social-facebook',
                'type' => 'onoff',
                'name' => __( 'Show facebook link share', 'yit' ),
                'desc' => __( 'Say if you want to show the social share link in the socials box.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-view-show-social-facebook_std', 1 ),
                'deps' => array(
                    'ids' => 'shop-single-show-socials',
                    'values' => 1
                )
            ),
            230 => array(
                'id'   => 'shop-single-show-social-pinterest',
                'type' => 'onoff',
                'name' => __( 'Show pinterest link share', 'yit' ),
                'desc' => __( 'Say if you want to show the social share link in the socials box.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-view-show-social-pinterest_std', 1 ),
                'deps' => array(
                    'ids' => 'shop-single-show-socials',
                    'values' => 1
                )
            ),
            240 => array(
                'id'   => 'shop-single-show-social-twitter',
                'type' => 'onoff',
                'name' => __( 'Show twitter link share', 'yit' ),
                'desc' => __( 'Say if you want to show the social share link in the socials box.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-view-show-social-twitter_std', 1 ),
                'deps' => array(
                    'ids' => 'shop-single-show-socials',
                    'values' => 1
                )
            ),
            250 => array(
                'id'   => 'shop-single-show-social-email',
                'type' => 'onoff',
                'name' => __( 'Show email link share', 'yit' ),
                'desc' => __( 'Say if you want to show the link that allows to share product by email.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-view-show-social-email_std', 1 ),
                'deps' => array(
                    'ids' => 'shop-single-show-socials',
                    'values' => 1
                )
            ),
            270 => array(
                'type' => 'title',
                'name' => __( 'Related products', 'yit' ),
                'desc' => __( 'Manage the related products.', 'yit' )
            ),
            275 => array(
                'id'   => 'shop-show-related',
                'type' => 'onoff',
                'name' => __( 'Show related products', 'yit' ),
                'desc' => __( 'Select if you want to display Related Products.', 'yit' ),
                'std'  => 1
            ),
            280 => array(
                'id'   => 'shop-show-custom-related',
                'type' => 'onoff',
                'name' => __( 'Custom Related Products number', 'yit' ),
                'desc' => __( 'Select if you want to customize the number of Related Products. Note: if you are already using a custom filter to do that, please don\'t enable this option.', 'yit' ),
                'std'  => 0,
                'deps' => array(
                    'ids' => 'shop-show-related',
                    'values' => 1
                ),
            ),
            290 => array(
                'id'   => 'shop-number-related',
                'type' => 'number',
                'name' => __( 'Number of Related Products', 'yit' ),
                'desc' => __( 'Select the total numbers of the related products displayed, on the product detail page. Note: related products are displayed randomly from Woocommerce. Sometimes the number of related products could be less than the number of items selected. This number depends from the query plugin, not from the theme.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-number-related_std', 3 ),
                'min'  => 1,
                'deps' => array(
					'ids' => 'shop-show-custom-related',
					'values' => 1
				),
            ),
            300 => array(
                'id'   => 'shop-show-metas',
                'type' => 'onoff',
                'name' => __( 'Show product metas (categories and tags)', 'yit' ),
                'desc' => __( 'Say if you want to show product metas in your single product page. It also remove Bands if you are using WooCommerce Brands Addon.', 'yit' ),
                'std'  => apply_filters( 'yit_shop-show-metas_std', 1 ),
            ),
        );
    }
}