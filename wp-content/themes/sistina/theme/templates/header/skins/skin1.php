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
        <p>
            <img src="http://drugstoc.biz/wp-content/uploads/2014/10/old-phone-5122.png">
            <span style="margin-left:5px;vertical-align:middle;color:#ff9700;">+234.810.460.8748</span>
            <?php  
            $user_id = get_current_user_id(); 
            $refcode = get_user_meta($user_id, 'ds_referral_code', true); 
            if( $refcode != ""){
                $url = DS_ReferralCodes::geturl($refcode); ?>
                <span>
                    <a href='<?php echo home_url("/{$url}");?>' title='Visit Store' alt='Visit Store'>
                        <img style="width: 30px;" src="<?php echo plugins_url('/drugstoc-commission/images/ds_store.png');?>"/> 
                        <span>Visit Store</span>
                    </a>
                </span> 
            <?php
            }?> 
        </p> 
    </div>
    <?php do_action( 'yit_after_header_right_content' ); ?>
    <!-- END HEADER RIGHT CONTENT -->

    <!-- START SEARCH BOX -->
    <?php do_action( 'yit_header_search_box' ) ?>
    <!-- END SEARCH BOX -->
</div>