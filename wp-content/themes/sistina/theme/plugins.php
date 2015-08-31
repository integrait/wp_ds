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

add_filter('yit_plugins', 'yit_add_plugins');
function yit_add_plugins( $plugins ) {
    return array_merge( $plugins, array(

        array(
            'name' 		=> 'Revolution Slider',
            'slug' 		=> 'revslider',
            'source'   				=> YIT_THEME_PLUGINS_DIR . '/revslider.zip', // The plugin source
            'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
            'version' 				=> '4.1.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation' 	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
        ),

        array(
            'name' 		=> 'WooCommerce',
            'slug' 		=> 'woocommerce',
            'required' 	=> false,
            'version'=> '2.0.12',
        ),

        array(
            'name' 		=> 'YITH WooCommerce Zoom Magnifier',
            'slug' 		=> 'yith-woocommerce-zoom-magnifier',
            'required' 	=> false,
            'version'=> '1.0.2',
        ),

        array(
            'name' 		=> 'YITH WooCommerce Wishlist',
            'slug' 		=> 'yith-woocommerce-wishlist',
            'required' 	=> false,
            'version'=> '1.0.2',
        ),

        array(
            'name' 		=> 'YITH WooCommerce Compare',
            'slug' 		=> 'yith-woocommerce-compare',
            'required' 	=> false,
            'version'=> '1.0.0',
        ),

        array(
            'name' 		=> 'YITH WooCommerce Ajax Navigation',
            'slug' 		=> 'yith-woocommerce-ajax-navigation',
            'required' 	=> false,
            'version'=> '1.0.0',
        ),

        array(
            'name' 		=> 'YITH WooCommerce Featured Video',
            'slug' 		=> 'yith-woocommerce-featured-video',
            'required' 	=> false,
            'version'=> '1.0.0',
        ),
        array(
            'name'      => 'Nextend Facebook Connect',
            'slug'      => 'nextend-facebook-connect/',
            'required' 				=> false,
            'version' 				=> '1.4.59',
        ),
    ));
}