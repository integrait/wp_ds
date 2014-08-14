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
?>


<div id="header-container" class="header_skin1 container">
    <!-- START LOGO -->
    <?php do_action( 'yit_before_logo' ) ?>
    <div id="logo">
        <?php
        /**
         * @see yit_logo
         */
        do_action( 'yit_logo' ) ?>
    </div>
    <?php do_action( 'yit_after_logo' ) ?>
    <!-- END LOGO -->


    <!-- START HEADER RIGHT CONTENT -->
    <?php do_action( 'yit_before_header_right_content' ); ?>
    <div id="header-right-content">
        <!-- START NAVIGATION -->
        <div id="nav">
            <?php
            /**
             * @see yit_main_navigation
             */
            do_action( 'yit_main_navigation') ?>
        </div>
        <!-- END NAVIGATION -->

        <!-- welcome -->
        <?php if( yit_get_option('topbar-login') ): ?>
            <?php yit_get_template( '/header/welcome.php' ); ?>
        <?php endif ?>

        <!-- wpml -->
        <?php if( defined('ICL_SITEPRESS_VERSION') ): ?>
            <?php yit_get_template( '/header/wpml.php' ); ?>
        <?php endif ?>

        <!-- cart -->
        <?php do_action('yit_header_cart') ?>
    </div>
    <?php do_action( 'yit_after_header_right_content' ); ?>
    <!-- END HEADER RIGHT CONTENT -->

    <!-- START SEARCH BOX -->
    <?php do_action( 'yit_header_search_box' ) ?>
    <!-- END SEARCH BOX -->
</div>