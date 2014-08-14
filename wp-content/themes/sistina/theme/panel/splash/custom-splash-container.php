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


function yit_splash_container_width_std() {
    return 372;
}
add_filter( 'yit_splash-container_width_std', 'yit_splash_container_width_std' );

function yit_splash_container_height_std() {
    return 422;
}
add_filter( 'yit_splash-container_height_std', 'yit_splash_container_height_std' );

function yit_splash_container_submit_font_std( $array ) {

    $array['family'] = 'Open Sans';
    $array['size'] = 12;
    $array['style'] = 'extra-bold';


    return $array;
}
add_filter( 'yit_splash-container-submit_font_std', 'yit_splash_container_submit_font_std' );

function yit_splash_container_label_font_std( $array ) {

    $array['family'] = 'Open Sans';
    $array['color'] = '#3a3a39';
    $array['size'] = 13;
    return $array;
}
function yit_splash_container_lostback_font_std( $array ) {

    $array['family'] = 'Open Sans';
    $array['color'] = '#979795';
    $array['size'] = 13;

    return $array;
}

add_filter( 'yit_splash-container-label_font_std', 'yit_splash_container_label_font_std' );
add_filter( 'yit_splash-container-lostback_font_std', 'yit_splash_container_label_font_std' );

function yit_splash_container_submit_bg_color_std( $array ) {
    return '#e89222';
}
add_filter( 'yit_splash-container-submit_bg_color_std', 'yit_splash_container_submit_bg_color_std' );

function yit_splash_container_submit_bg_color_hover_std( $array ) {
    return '#e37a1c';
}
add_filter( 'yit_splash-container-submit_bg_color_hover_std', 'yit_splash_container_submit_bg_color_hover_std' );

function yit_splash_container_bg_color_std( $array ) {
    return '#fefefe';
}
add_filter( 'yit_splash-container-bg_color_std', 'yit_splash_container_bg_color_std' );

function yit_splash_container_submit_border_color_std( $fields ) {
    $fields[85] = array(
                    'id' => 'splash-container-submit_border_color',
                    'type' => 'colorpicker',
                    'name' => __( 'Submit button border color', 'yit' ),
                    'desc' => __( 'Submit button border color', 'yit' ),
                    'std' => apply_filters( 'yit_splash-container-submit_border_color_std', '#cd6b02' )
                );

    return $fields;
}

add_filter('yit_submenu_tabs_splash_custom_splash_container','yit_splash_container_submit_border_color_std' );
