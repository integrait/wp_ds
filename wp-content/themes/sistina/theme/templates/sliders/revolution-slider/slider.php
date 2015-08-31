<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */                         
 
global $is_primary;

if ( ! class_exists( 'RevSlider' ) )  return;
define( 'YIT_SLIDER_USED', true );

$sliderID = $this->get('slider_name');
$the_slider = new RevSlider();
$the_slider->initByMixed($sliderID);            

$slider_class = '';
$slider_class .= yit_slide_get('align') != '' ? ' align' . yit_slide_get('align') : '';
$slider_class .= ' ' . $the_slider->getParam('slider_type');

$is_fixed = false;
if ( ! $is_primary && in_array( $the_slider->getParam('slider_type'), array( 'fixed', 'responsitive' ) ) ) $is_fixed = true;

// text align
$slider_text = yit_slide_get( 'slider_text' );
if ( ! $is_fixed ) $slider_text = '';
if ( !empty( $slider_text ) ) $slider_class .= ' align' . ( yit_slide_get( 'slider_align' ) == 'left' ? 'right' : 'left' ); 
?>

<?php if ( $is_fixed && yit_get_option( 'show-header-search' ) ) echo '<div class="slider-space"></div>' ?>

<!-- START SLIDER -->
<div class="revolution-wrapper<?php if ( $is_fixed ) echo ' container'; ?>">
    <div id="<?php echo $slider_id ?>"<?php yit_slider_class($slider_class) ?>> 
        <div class="shadowWrapper">
            <?php echo do_shortcode('[rev_slider ' . yit_slide_get( 'slider_name' ) . ']'); ?>
        </div>          
    </div>
</div>
<!-- END SLIDER -->