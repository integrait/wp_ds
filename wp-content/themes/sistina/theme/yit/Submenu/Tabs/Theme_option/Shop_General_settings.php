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
 * Class to print fields in the tab Shop -> General settings
 *
 * @since 1.0.0
 */
class YIT_Submenu_Tabs_Theme_option_Shop_General_settings extends YIT_Submenu_Tabs_Abstract {
    /**
     * Default fields
     *
     * @var array
     * @since 1.0.0
     */
    public $fields;

    /**
     * Merge default fields with theme specific fields using the filter yit_submenu_tabs_theme_option_shop_general_settings
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
        	5 => array(
                'id'   => 'shop-enabled',
                'type' => 'onoff',
                'name' => __( 'Enable shop features', 'yit' ),
                'desc' => __( 'Say if you want to enable the shop features. If the option is disabled, products cannot be added to cart.', 'yit' ),
                'std'  => true,
            ),
        	20 => array(
                'id'   => 'show-header-woocommerce-cart',
                'type' => 'onoff',
                'name' => __( 'Show Woocommerce Cart in header', 'yit' ),
                'desc' => __( 'Say if you want to display the number of items in cart.', 'yit' ),
                'std'  => true,
            ),
        	80 => array(
                'id'   => 'shop-products-per-page',
                'type' => 'number',
                'name' => __( 'Products per page', 'yit' ),
                'desc' => __( 'Say how many products to show per page, in the shop pages.', 'yit' ),
                'std'  => 12,
            ),
            85 => array(
                'id'   => 'shop-redirect-to-checkout',
                'type' => 'onoff',
                'name' => __( 'Enable the redirect to checkout page', 'yit' ),
                'desc' => __( 'If the option is enabled, the user will be redirected automatically to the checkout page after the product is added to cart.', 'yit' ),
                'std'  => 0,
            ),
        	90 => array(
                'id'   => 'shop-checkout-multistep',
                'type' => 'onoff',
                'name' => __( 'Enable checkout multistep', 'yit' ),
                'desc' => __( 'Choose if you want to enable multistep checkout or use classic checkout.', 'yit' ),
                'std'  => 1,
            ),
            95 => array(
                'id'   => 'shop-coupon-checkout',
                'type' => 'onoff',
                'name' => __( 'Display coupon form on checkout', 'yit' ),
                'desc' => __( 'Choose if you want to display coupon form on checkout.', 'yit' ),
                'std'  => 1,
            ),
        	100 => array(
                'id'   => 'shop-customer-vat',
                'type' => 'onoff',
                'name' => __( 'Enable VAT field', 'yit' ),
                'desc' => __( 'Choose if you want to enable VAT field for Customer.', 'yit' ),
                'std'  => 1,
            ),
            102 => array(
                'id'   => 'shop-customer-ssn',
                'type' => 'onoff',
                'name' => __( 'Enable SSN field', 'yit' ),
                'desc' => __( 'Choose if you want to enable SSN field for Customer.', 'yit' ),
                'std'  => 0,
            ),
        	110 => array(
                'id'   => 'shop-fields-order',
                'type' => 'onoff',
                'name' => __( 'Restore fields order', 'yit' ),
                'desc' => __( 'Restore the order of fields as in previous Woocommerce version.', 'yit' ),
                'std'  => 1,
            ),
            120 => array(
                'id'    => 'compare-click-popup-enabled',
                'type'  => 'onoff',
                'name'  => __( 'Show compare lightbox onclick of compare button', 'yit' ),
                'desc'  => __( 'Set YES if you want to active the compare lightbox when you click into "Compare" button of products. If you set to "NO", you have to use the widget "WOO Compare products" to have the list of product to compare.', 'yit' ),
                'std'   => 1
            ),
            130 => array(
                'id'    => 'minicart-total-items',
                'type'  => 'onoff',
                'name'  => __( 'Count all items in the cart', 'yit' ),
                'desc'  => __( 'It changes the way like the cart in the header count items. If ON, everytime you add an item to the cart (also if the item already is in the cart) the quantity will be increased. If OFF, multiple items of the same type will be counted only one time.', 'yit' ),
                'std'   => 0
            ),
            140 => array(
                'id'    => 'add-to-cart-text',
                'type'  => 'text',
                'name'  => __( 'Set "Add to Cart" text', 'yit' ),
                'desc'  => __( 'Choose the text to display within the add to cart button. This will not work for external products. For them it\'s handled directly in the product admin page.', 'yit' ),
                'std'   => __('Add to cart', 'yit')
            )
        );
    }
}