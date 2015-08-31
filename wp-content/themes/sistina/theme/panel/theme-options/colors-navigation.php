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
 * Add specific fields to the tab Colors -> Navigation
 * 
 * @param array $fields
 * @return array
 */ 
function yit_tab_colors_navigation( $fields ) {
	
	unset( $fields[10] );
	unset( $fields[20] );
	
	
	return array_merge( $fields, array(

        	10 => array(
                'id' => 'navigation-background',
                'type' => 'colorpicker',
                'name' => __( 'Navigation background color', 'yit' ),
                'desc' => __( 'Select the background color of the navigation.', 'yit' ),
                'std' => apply_filters( 'yit_navigation-background_std', '#ffffff' ),
                'style' => array(
                	'selectors' => '#nav, #header-right-content, .header_skin2',
                	'properties' => 'background-color'
				)
            ),

            20 => array(
                'id' => 'sub-navigation-background',
                'type' => 'colorpicker',
                'name' => __( 'Sub navigation background color', 'yit' ),
                'desc' => __( 'Select the background color of the sub navigation.', 'yit' ),
                'std' => apply_filters( 'yit_sub-navigation-background_std', '#ffffff' ),
                'style' => array(
                	'selectors' => '#nav ul.sub-menu, #nav ul.children, #lang_sel_list ul, .welcome_menu .welcome_menu_inner',
                	'properties' => 'background-color'
				)
            ),
            30 => array(
                'id' => 'navigation-border-color',
                'type' => 'colorpicker',
                'name' => __( 'Navigation border color', 'yit' ),
                'desc' => __( 'Select the border color for the navigation.', 'yit' ),
                'std' => apply_filters( 'yit_navigation-item-hover-background_std', '#f2f2f2' ),
                'style' => array(
                    'selectors' => '#header-right-content, #header-right-content > * + *, #nav ul > li, .header_skin2',
                    'properties' => 'border-color, border-right-color'
                )
            ),


            40 => array(
                'id' => 'navigation-border-top-color',
                'type' => 'colorpicker',
                'name' => __( 'Navigation items border top color', 'yit' ),
                'desc' => __( 'Select the border top color for the menu items.', 'yit' ),
                'std' => apply_filters( 'yit_navigation-border-top-color_std', '#ffffff' ),
                'style' => array(
                    'selectors' => '.header_skin1 #nav > ul > li, .header_skin1 #header-right-content .welcome_username, .header_skin1 #header-right-content .wpml, .header_skin1 #header-right-content .woo_cart',
                    'properties' => 'border-top-color'
                ),
                'deps' => array(
                    'ids' => 'header-skin',
                    'values' => 'skin1'
                )
            ),


            50 => array(
                'id' => 'navigation-border-top-color-hover',
                'type' => 'colorpicker',
                'name' => __( 'Navigation items border top color on hover', 'yit' ),
                'desc' => __( 'Select the border top color for the menu items when they are hovered or active.', 'yit' ),
                'std' => apply_filters( 'yit_navigation-border-top-color-hover_std', '#e6e6e6' ),
                'style' => array(
                    'selectors' => '.header_skin1 #nav > ul > li:hover, .header_skin1 #nav > ul > li.current-menu-item, .header_skin1 #nav > ul > li.current-menu-ancestor, .header_skin1 #nav > ul > li.current_page_ancestor, .header_skin1 #nav > ul > li.current_page_item, .header_skin1 #header-right-content .welcome_username:hover, .header_skin1 #header-right-content .wpml:hover, .header_skin1 #header-right-content .woo_cart:hover',
                    'properties' => 'border-top-color'
                ),
                'deps' => array(
                    'ids' => 'header-skin',
                    'values' => 'skin1'
                )
            ),

            60 => array(
                'id' => 'nav-custom-text',
                'type' => 'colorpicker',
                'name' => __( 'Custom text color', 'yit' ),
                'desc' => __( 'Select the color of the custom text.', 'yit' ),
                'std' => apply_filters( 'yit_custom-text-highlight_std', '#656464' ),
                'style' => array(
                    'selectors' => '#nav .megamenu ul.sub-menu li.menu-item-custom-content p',
                    'properties' => 'color'
                )
            ),
            70 => array(
                'id' => 'nav-custom-text-highlight',
                'type' => 'colorpicker',
                'name' => __( 'Highlight custom text color', 'yit' ),
                'desc' => __( 'Select the color of the custom text highlight.', 'yit' ),
                'std' => apply_filters( 'yit_custom-text-highlight_std', '#b1690c' ),
                'style' => array(
                    'selectors' => '#nav .megamenu ul.sub-menu li.menu-item-custom-content span.highlight',
                    'properties' => 'color'
                )
            ),
        /*
        	30 => array(
                'id' => 'navigation-item-hover-background',
                'type' => 'colorpicker',
                'name' => __( 'Navigation items background color on hover', 'yit' ),
                'desc' => __( 'Select the background color of the navigation items on hover.', 'yit' ),
                'std' => apply_filters( 'yit_navigation-item-hover-background_std', '#ffffff' ),
                'style' => array(
                	'selectors' => '#nav ul li a:hover, #nav ul li:hover a',
                	'properties' => 'background-color'
				)
            ),

        	40 => array(
                'id' => 'navigation-item-active-background',
                'type' => 'colorpicker',
                'name' => __( 'Navigation active items background color', 'yit' ),
                'desc' => __( 'Select the background color of the navigation items when they are active.', 'yit' ),
                'std' => apply_filters( 'yit_navigation-item-active-background_std', '#ffffff' ),
                'style' => array(
                	'selectors' => '#nav .current-menu-item > a, #nav .current-menu-ancestor > a, #nav .current_page_ancestor > a,div#nav ul .current_page_item > a',
                	'properties' => 'background-color'
				)
            ),
	        80 => array(
	            'id' => 'nav-megamenu-borders',
	            'type' => 'colorpicker',
	            'name' => __( 'Border color of megamenu columns', 'yit' ),
	            'desc' => __( 'Select the border color of the columns inside the megamenu.', 'yit' ),
	            'std' => apply_filters( 'yit_nav-megamenu-borders_std', '#e9e9e9' ),
	            'style' => array(
	               	'selectors' => '#nav .megamenu ul.sub-menu li',
	               	'properties' => 'border-color'
				)
	        ),

         */
        ) );
}
add_filter( 'yit_submenu_tabs_theme_option_colors_navigation', 'yit_tab_colors_navigation' );