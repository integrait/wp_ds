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

function yit_maintenance_newsletter_submit_border_color_std($options){
    $options[55] = array(
                'id' => 'maintenance-newsletter-submit-border-color',
                'type' => 'colorpicker',
                'name' => __( 'Newsletter submit border background', 'yit' ),
                'desc' => __( 'The submit border background', 'yit' ),
                'std'  => apply_filters( 'yit_maintenance-newsletter-submit-border-color_std' , '#cd6b02')
            );

    return $options;
}

add_filter( 'yit_submenu_tabs_maintenance_maintenance_newsletter' , 'yit_maintenance_newsletter_submit_border_color_std');

function yit_maintenance_newsletter_background_std(){
    return '#e89222';
}

add_filter( 'yit_maintenance-newsletter-background_std' , 'yit_maintenance_newsletter_background_std');

function yit_maintenance_enable_newsletter_background_hover_std(){
    return '#e37a1c';
}

add_filter( 'yit_maintenance-enable-newsletter-background_hover_std' , 'yit_maintenance_enable_newsletter_background_hover_std');



function yit_maintenance_newsletter_font_std( $array ) {
    $array['family'] = 'Open Sans';
	$array['size'] = 12;
	$array['color'] = '#ffffff';
    
    return $array;
}
add_filter( 'yit_maintenance-newsletter-font_std', 'yit_maintenance_newsletter_font_std' );

function yit_maintenance_newsletter_submit_font_std( $array ) {
    $array['family'] = 'Open Sans';
    $array['size'] = 12;
    $array['color'] = '#ffffff';
    $array['style'] = 'extra-bold';
    return $array;
}
add_filter( 'yit_maintenance-newsletter-submit-font_std', 'yit_maintenance_newsletter_submit_font_std' );