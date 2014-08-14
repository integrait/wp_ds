<?php
/**
 * Loop Price - prices are not displayed for non-users
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price"><?php echo (is_user_logged_in())? $price_html:""; ?></span>
<?php endif; ?>