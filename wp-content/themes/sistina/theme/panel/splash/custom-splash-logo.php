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

function yit_splash_custom_logo_image_std( $array ) {
    return YIT_IMAGES_URL . '/logo.png';
}
add_filter( 'yit_splash-logo_image_std', 'yit_splash_custom_logo_image_std' );



?>