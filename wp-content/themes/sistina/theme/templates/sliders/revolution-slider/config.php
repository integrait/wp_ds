<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */                               

//define( 'YIT_REVSLIDER_DIR', dirname(__FILE__) );
//define( 'YIT_REVSLIDER_URL', YIT_THEME_SLIDERS_URL . '/' . $slider_type );

//if ( is_admin() ) RevSliderAdmin::onActivate();

// yit_register_slider_style(  $slider_type, 'revolution-slider-style', 'css/slider.css' );
// yit_register_slider_style(  $slider_type, 'rs-settings', 'rs-plugin/css/settings.css' );
// yit_register_slider_style(  $slider_type, 'rs-captions', 'rs-plugin/css/captions.css' );
// yit_register_slider_script( $slider_type, 'themepunch.plugins', 'rs-plugin/js/jquery.themepunch.plugins.min.js' );
// yit_register_slider_script( $slider_type, 'themepunch.revolution', 'rs-plugin/js/jquery.themepunch.revolution.min.js' );

// add the table for the importer of sample data
function yit_add_revslider_tables( $tables ) {
    global $wpdb;                                
    
    if ( ! yit_if_thereis_revslider() ) return $tables;
    
    $tables[] = str_replace( $wpdb->prefix, '', GlobalsRevSlider::$table_sliders );
    $tables[] = str_replace( $wpdb->prefix, '', GlobalsRevSlider::$table_slides );
    
    return $tables;    
}           
add_filter( 'yit_sample_data_tables', 'yit_add_revslider_tables' );

// add the layer slider in the sample data
//add_filter( 'yit_sample_data_options', create_function( '$options', '$options[] = "layerslider-slides"; return $options;' ) );

// set here if the slider is reponsive or not
$this->responsive_sliders[ $slider_type ] = true;
 
// add the slider fields for the admin
yit_add_slider_config( $slider_type, array(
	array(
        'type' => 'simple-text',
        'desc' => sprintf( __( 'Configure the slider in <a href="%s">Revolution Slider</a> page and then select below the slider to use for this slider.', 'yit' ), admin_url( 'admin.php?page=revslider' ) )
    ),
	array(
        'id' => 'slider_name', 
        'name' => __('Select the slider', 'yit'),        
        'desc' => __('Select the slider you want to show when you want to show this slider.', 'yit'),
        'type' => 'select',   
        'options' => yit_get_revolution_sliders()
    ),
)); 

function yit_get_revolution_sliders() {
    global $wpdb;

    if ( ! yit_if_thereis_revslider() ) {
        return array();
    }

    $tableName = GlobalsRevSlider::$table_sliders;
    
    $slider = new RevSlider();
    return $slider->getArrSlidersShort();
    
}  

function yit_if_thereis_revslider() {
    global $wpdb;

    if ( ! class_exists( 'GlobalsRevSlider' ) ) return false;

    $table_sliders = GlobalsRevSlider::$table_sliders;
    $table_slides  = GlobalsRevSlider::$table_slides;
    
    if ( $wpdb->get_var("show tables like '$table_sliders'") == $table_sliders && $wpdb->get_var("show tables like '$table_slides'") == $table_slides ) {
        return true;
    }
    
    return false;
    
}                                                          