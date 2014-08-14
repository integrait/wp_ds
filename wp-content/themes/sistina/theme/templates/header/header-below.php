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

$border = get_post_meta( yit_post_id(), '_header-border-bottom-custom', true );
if ( empty( $border ) || $border == 'default' ) $border = yit_get_option('header-enable-border') ? 'enable' : 'remove';
?>

<div class="header-below<?php if ( $border == 'remove' ) echo ' noborder'  ?>">
    <?php
    /**
     * @see yit_slider_section
     * @see yit_slogan
     */
    do_action( 'yit_header_below' );
    ?>
</div>