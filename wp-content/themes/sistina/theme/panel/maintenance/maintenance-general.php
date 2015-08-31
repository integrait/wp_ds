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



function yit_maintenance_message_std(){
    return 'Hello there! We are not ready yet, but if you leave your mail address well let you know as soon as were in business :)';
}

add_filter( 'yit_maintenance-message_std' , 'yit_maintenance_message_std');

function yit_submenu_tabs_maintenance_maintenance_general_title( $options ) {
    $options[110] = array(
        'type' => 'text',
        'id' => 'maintenance-general-title',
        'name' => __( 'Title', 'yit' ),
        'desc' => __( 'The title displayed under logo.', 'yit' ),
        'std' => __( 'OPS! WE ARE NOT READY YET', 'yit' )
    );

    return $options;
}

add_filter( 'yit_submenu_tabs_maintenance_maintenance_general', 'yit_submenu_tabs_maintenance_maintenance_general_title' );


function yit_maintenance_social_shortcodes_tab_std($fields){

    $social_shortcodes =     '[social href="#" title="" type="facebook" icon_type="fade" size="" target="" ]' . "\n" .
'[social href="#" title="" type="twitter" icon_type="fade" size="" target="" ]' . "\n" .
'[social href="#" title="" type="pinterest" icon_type="fade" size="" target="" ]' . "\n" .
'[social href="#" title="" type="google" icon_type="fade" size="" target="" ]' . "\n" .
'[social href="#" title="" type="bookmark" icon_type="fade" size="" target="" ]' ;
    $fields[120] = array(
                'type' => 'textarea',
                'id' => 'maintenance_social_text_shortcode',
                'name' => __( 'Text below the newsletter form', 'yit' ),
                'desc' => __( 'Here you can set the text displayed below the newsletter form. You can also use the [social] shortocode in order to display your socials.', 'yit' ),
                'std' => apply_filters( 'yit_maintenance_social_text_shortcode_std' ,$social_shortcodes)

            );

    return $fields;
}

add_filter( 'yit_submenu_tabs_maintenance_maintenance_general' , 'yit_maintenance_social_shortcodes_tab_std');

