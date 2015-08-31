<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */ 
 
yit_register_slider_style(  $slider_type, 'slider-thumbnail', 'css/thumbnails.css' );
yit_register_slider_script( $slider_type, 'jquery-thumbnail', 'js/jquery.aw-showcase.js' );  

// set here if the slider is reponsive or not
$this->responsive_sliders[ $slider_type ] = true;

// add image size
yit_add_image_size( 'thumb-slider-thumbnails', 226, 100, true );

// add support to slide
yit_add_slide_support( $slider_type, 'title, subtitle, link' );
 
// add the slider fields for the admin
yit_add_slider_config( $slider_type, array(
    array(
        'name' => __( 'Effect', 'yit' ),
        'id' => 'effect',
        'type' => 'select',
        'desc' => __( 'Select the effect you want for slides transiction.', 'yit' ),
        'options' => array (
			'vslide' => 'vslide',
			'hslide' => 'hslide',
			'fade' => 'fade'
		),
        'std' => 'fade'
    ),
    array(
        'name' => __( 'Pause between slides (s)', 'yit' ),
        'id' => 'interval',
        'type' => 'slider',        
        'desc' => __( 'Select the delay between slides, expressed in seconds.', 'yit' ),
        'min' => 0.1,
        'max' => 20,
        'step' => 0.1,
        'std' => 3
    ),
    array(
        'name' => __( 'Animation speed (s)', 'yit' ),
        'id' => 'speed',
        'type' => 'slider',
        'desc' => __( 'The speed of the animation between two slide, expressed in seconds.', 'yit' ),
        'min' => 0.1,
        'max' => 20,   
        'step' => 0.1,  
        'std' => 0.8
    ),
    array(
        'name' => __( 'Content width', 'yit' ),
        'id' => 'width',
        'type' => 'number',
        'desc' => __( 'Select the width of content (set 0 for full width feature).', 'yit' ),
        'min' => 0,
        'max' => 2000,
        'std' => 0,
    ),
    array(
        'name' => __( 'Content height', 'yit' ),
        'id' => 'height',
        'type' => 'number',
        'desc' => __( 'Select the height of content (take in mind 22px of padding and border of the slider, so if you want the slider 400px height, set 378px as height.).', 'yit' ),
        'min' => 10,
        'max' => 2000,
        'std' => 378,
    ),
));      
 
// add the slider fields for the admin
yit_slider_typography_options( $slider_type, array(
    array(
        'id'   => 'tooltip-font',
        'type' => 'typography',
        'name' => __( 'Caption font', 'yit' ),
        'desc' => __( 'Configure the tooltip title of the slide.', 'yit' ),
        'min'  => 1,
        'max'  => 72,
        'std'  => array(
            'size'   => 26,
            'unit'   => 'px',
            'family' => 'Open Sans',
            'style'  => 'bold',
            'color'  => '#585555' 
        ),
        'style' => array(
			'selectors' => '.slider-%s.slider div.showcase-text h2',
			'properties' => 'font-size, font-family, color, font-style, font-weight'
		)
    ),
    array(
        'id'   => 'extra-tooltip-font',
        'type' => 'typography',
        'name' => __( 'Tooltip text font', 'yit' ),
        'desc' => __( 'Configure the font of the text in the slide.', 'yit' ),
        'min'  => 1,
        'max'  => 72,
        'std'  => array(
            'size'   => 12,
            'unit'   => 'px',
            'family' => 'Open Sans',
            'style'  => 'regular',
            'color'  => '#6e6e6e'
        ),
        'style' => array(
			'selectors' => '.slider-%s.slider div.showcase-text p',
			'properties' => 'font-size, font-family, color, font-style, font-weight'
		)
    ),
) );           