<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices();
?>

<div class="woocommerce_cart row">

    <?php do_action( 'woocommerce_before_cart' ); ?>

    <form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

    <div class="span9">

            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <table class="shop_table cart" cellspacing="0">
                <thead>
                <tr>
                    <th class="product-remove">&nbsp;</th>
                    <th class="product-thumbnail">&nbsp;</th>
                    <th class="product-name"></th>
                    <th class="product-quantity"><?php _e( 'Quantity', 'yit' ); ?></th>
                    <th class="product-subtotal"><?php _e( 'Total', 'yit' ); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                        $_product = apply_filters( 'woocommerce_cart_item_product', $values['data'], $values, $cart_item_key );

                        if ( $_product->exists() && $values['quantity'] > 0 ) {
                            ?>
                            <tr class="<?php echo esc_attr( apply_filters('woocommerce_cart_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?>">
                                <!-- Remove from cart link -->
                                <td class="product-remove">
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'yit' ) ), $cart_item_key );
                                    ?>
                                </td>

                                <!-- The thumbnail -->
                                <td class="product-thumbnail">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $values, $cart_item_key );

                                    if ( ! $_product->is_visible() )
                                        echo $thumbnail;
                                    else
                                        /**
                                         * Deprecated
                                         *
                                         * @see /woocommerce/includes/wc-deprecated-functions.php
                                         * @since version 2.1.12
                                         */
                                        //printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
                                        printf( '<a href="%s">%s</a>', esc_url( $_product->get_permalink() ), $thumbnail );

                                    ?>
                                </td>

                                <!-- Product Name -->
                                <td class="product-name">
                                    <?php
                                    if ( ! $_product->is_visible() ) {
                                        echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $values, $cart_item_key );
                                    } else {
                                       /**
                                        * Deprecated
                                        *
                                        * @see /woocommerce/includes/wc-deprecated-functions.php
                                        * @since version 2.1.12
                                        */
                                        //printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );
                                        echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $values, $cart_item_key );
                                    }

                                    // Meta data
                                    echo WC()->cart->get_item_data( $values );

                                    // Backorder notification
                                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                                        echo '<p class="backorder_notification">' . __( 'Available on backorder', 'yit' ) . '</p>';
                                    ?>


                                    <span class="product-price">
                                        <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $values, $cart_item_key ); ?>
                                    </span>
                                </td>


                                <!-- Quantity inputs -->
                                <td class="product-quantity">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                    } else {
                                        $product_quantity = woocommerce_quantity_input( array(
                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $values['quantity'],
                                            'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                            'min_value'   => '0'
                                        ), $_product, false );
                                    }

                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                                    ?>
                                </td>

                                <!-- Product subtotal -->
                                <td class="product-subtotal">
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
                                    ?>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                }

                do_action( 'woocommerce_cart_contents' );
                ?>
                <tr>
                    <td colspan="6" class="actions">


                    </td>
                </tr>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                </tbody>
            </table>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </div>

    <div class="span3">
        <?php if( WC()->cart->coupons_enabled() || !( get_option('woocommerce_enable_shipping_calc')=='no' || ! WC()->cart->needs_shipping() ) ) : ?>
        <div class="sidebar">
            <?php if ( WC()->cart->coupons_enabled() ) : ?>
            <div class="coupon widget">
                <h3><?php _e('Apply a <strong>coupon</strong>', 'yit') ?></h3>

                <div>
                    <input name="coupon_code" class="input-text" id="coupon_code" placeholder="<?php _e('Enter the coupon code', 'yit') ?>" value="" type="text" />
                    <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply', 'yit' ); ?>" />
                    <?php do_action('woocommerce_cart_coupon'); ?>
                </div>
            </div>
            <?php endif ?>

            <div class="cart-collaterals widget">

                <?php do_action('woocommerce_cart_collaterals'); ?>

                <?php woocommerce_shipping_calculator(); ?>

            </div>
        </div>
        <?php endif ?>

        <div class="sidebar cart_totals_container">

            <?php woocommerce_cart_totals(); ?>

            <div class="group">
                <input type="submit" class="update-button button" name="update_cart" value="<?php _e( 'Update Cart', 'yit' ); ?>" />
                <input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'Checkout &rarr;', 'yit' ); ?>" />

                <?php do_action('woocommerce_proceed_to_checkout'); ?>

                <?php wp_nonce_field('woocommerce-cart') ?>
            </div>
        </div>
    </div>

    </form>
    <?php do_action( 'woocommerce_after_cart' ); ?>
</div>