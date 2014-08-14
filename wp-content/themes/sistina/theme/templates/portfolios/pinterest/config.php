<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */

yit_register_portfolio_style(  $portfolio_type, 'portfolio-' . $portfolio_type, 'css/style.css' );
yit_register_portfolio_script(  $portfolio_type, 'jquery-masonry' );

// add the image size
add_image_size( 'thumb_portfolio_pinterest', 270, 270, false );

// add the slider fields for the admin
yit_add_portfolio_config( $portfolio_type, array(

    array(
        'name' => __( 'Items per page', 'yit' ),
        'id' => 'nitems',
        'type' => 'number',
        'min' => 0,
        'max' => 200,
        'desc' => __( 'Select the number of items to show. The option will active a pagination system below the items. Leave 0 to show all.', 'yit' ),
        'std' => 0
    ),

    array(
        'name' => __( 'Hover effect', 'yit' ),
        'id' => 'enable_hover',
        'type' => 'onoff',
        'desc' => __( 'Enable the hover effect on each portfolio item', 'yit' )
    ),

    array(
        'name' => __( 'Project title on hover', 'yit' ),
        'id' => 'enable_title',
        'type' => 'onoff',
        'desc' => __( 'Show the project name on image hover.', 'yit' )
    ),


    array(
        'name' => __( 'Project categories on hover', 'yit' ),
        'id' => 'enable_categories',
        'type' => 'onoff',
        'desc' => __( 'Show the project categories on image hover.', 'yit' )
    ),
/*
    array(
        'name' => __( 'Project excerpt on hover', 'yit' ),
        'id' => 'enable_excerpt',
        'type' => 'onoff',
        'desc' => __( 'Show the project excerpt on image hover. (The excerpt lenght can be customized in each single item.)', 'yit' )
    ),
*/

    array(
        'type' => 'sep'
    ),

    array(
        'type' => 'sep'
    ),
    array(
        'type' => 'simple-text',
        'id'   => 'simple_text',
        'desc' => '<h4>' . __('Page detail settings', 'yit') . '</h4>'
    ),
    array(
        'name' => __( 'Select detail layout', 'yit' ),
        'id' => 'layout',
        'type' => 'select',
        'desc' => __( 'Show the selected layout in detail page.', 'yit' ),
        'options' => array(
            'small' => __('Small', 'yit'),
            'big' => __('Big', 'yit')
        ),
        'std' => 'small'
    ),
    array(
        'name' => __( 'Display Other Projects', 'yit' ),
        'id' => 'display_related',
        'type' => 'onoff',
        'desc' => __( 'Select if you want to show other projects below the item.', 'yit' )
    ),
    array(
        'name' => __( 'Items', 'yit' ),
        'id' => 'detail_nitems',
        'type' => 'number',
        'min' => 0,
        'max' => 200,
        'desc' => __( 'Select the number of items to show below the item. Leave 0 to show all.', 'yit' ),
        'std' => 0
    ),
    array(
        'name' => __( 'Enable lightbox icon', 'yit' ),
        'id' => 'event_project_lightbox',
        'type' => 'onoff',
        'desc' => __( 'Enable lightbox icon for detail images.', 'yit' ),
    ),
    array(
        'name' => __( 'Other Projects label', 'yit' ),
        'id' => 'other_projects_label',
        'type' => 'text',
        'std' =>  __( 'Other Projects', 'yit' ),
        'desc' => __( 'Customize the Other Projects label', 'yit' )
    ),

    array(
        'name' => __( 'Enable lightbox icon (on other projects)', 'yit' ),
        'id' => 'event_lightbox_other_projects',
        'type' => 'onoff',
        'desc' => __( 'Enable lightbox icon on projects image.', 'yit' ),
    ),

    array(
        'name' => __( 'Enable project details icon (on other projects)', 'yit' ),
        'id' => 'event_details_other_projects',
        'type' => 'onoff',
        'desc' => __( 'Enable project details icon on projects image.', 'yit' ),
    ),
    array(
        'name' => __( 'Project title on hover (on other projects)', 'yit' ),
        'id' => 'event_title_other_projects',
        'type' => 'onoff',
        'desc' => __( 'Show the project name on image hover.', 'yit' ),
    ),
) );
