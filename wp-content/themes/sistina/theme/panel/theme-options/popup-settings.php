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

function yit_popup_title_std( $array ) {
    $array['size'] = 24;
    $array['color'] = '#a3a3a1';
    $array['family'] = 'Open Sans';
    $array['style'] = 'normal';
    
    return $array;
}
add_filter( 'yit_popup-title_std', 'yit_popup_title_std' );

function yit_popup_title_style( $array ) {
    $array['selectors'] = '#popupWrap .popup  h3';
    
    return $array;
}
add_filter( 'yit_popup-title_style', 'yit_popup_title_style' );


function yit_popup_content_std( $array ) {
    $array['size'] = 12;
    $array['family'] = 'Open Sans';
    $array['color'] = '#747373';
    
    return $array;
}
add_filter( 'yit_popup-content_std', 'yit_popup_content_std' );
