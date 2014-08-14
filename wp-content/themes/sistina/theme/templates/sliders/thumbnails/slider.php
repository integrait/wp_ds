<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */    
 
global $is_primary;
define( 'YIT_SLIDER_USED', true );

$thumbs = ''; 
$slider_type = yit_slide_get( 'slider_type' );

$slider_class = 'group slider-thumbnails';
//if ( ! $is_primary ) $slider_class = 'container';
$slider_class .= yit_slide_get('align') != '' ? ' align' . yit_slide_get('align') : '';
?>
<!-- START SLIDER -->
<div id="<?php echo $slider_id ?>"<?php yit_slider_class($slider_class) ?>>
    <div class="showcase group">
        <?php while( yit_have_slide() ) :

            list( $thumbnail, $thumb_width, $thumb_height ) = yit_image( "id=" . yit_slide_get('item_id') . "&size=thumb-slider-thumbnails&output=array" );
            ?>

            <div class="showcase-slide">
                <div class="showcase-content">
                    <!-- If the slide contains multiple elements you should wrap them in a div with the class
                    .showcase-content-wrapper. We usually wrap even if there is only one element,
                    because it looks better. -->
                    <div class="showcase-content-wrapper">
                        <?php yit_slide_the( 'featured-content', array( 'container' => false ) ) ?>
                    </div>
                </div>
                <div class="showcase-thumbnail">
                    <img src="<?php echo $thumbnail ?>" width="<?php echo $thumb_width ?>" height="<?php echo $thumb_height ?>" />
                </div>

                <?php if(yit_slide_get('title') || yit_slide_get('subtitle')) : ?>
                    <div class="showcase-text">
                        <h2><?php yit_slide_the( 'title' ) ?></h2>
                        <p><?php yit_slide_the( 'subtitle' ) ?></p>
                    </div>
                <?php endif; ?>
            </div>

        <?php endwhile; ?>
    </div>


</div>
<!-- END SLIDER -->

<script type="text/javascript">

    jQuery(document).ready(function($){
        var slider = $("#<?php echo $slider_id ?>.slider-thumbnails"),
            slider_cloned = slider.html(),
            thumbnails_width = <?php yit_slide_the( 'width' ) ?>,
            thumbnails_height = <?php yit_slide_the( 'height' ) ?>,
            fix_slider_sizes = function() {
                if ( $('html#ie8').length != 0 ) return;

                var grid = $('#header-container').width();
                slider.find('.showcase-content-container, .showcase-content, .showcase-content-wrapper img').height( ( thumbnails_height * grid ) / 1170 );
                slider.find('.showcase-text').css({ marginLeft: slider.find('.showcase-thumbnail-container').width() / -2 });
                if ( $('body').hasClass('responsive') && $('body').hasClass('stretched') ) {
                    slider.find('.showcase-content').css({
                        width: $(window).width(),
                        left: $(window).width() / -2
                    });
                }
            },
            render_thumbnails_slider = function(){
                slider.removeClass('thumbnails');
                slider.find('.showcase').awShowcase(
                    {
                        content_width           : thumbnails_width,
                        content_height          : thumbnails_height,
                        fit_to_parent           : true,
                        fullscreen_width_x      : $(window).width() * -1,
                        show_caption            : 'onhover', /* onload/onhover/show */
                        continuous              : true,
                        buttons                 : false,
                        auto                    : true,
                        thumbnails              : true,
                        beforechange            : fix_slider_sizes,
                        transition              : '<?php yit_slide_the( 'effect' ) ?>', /* hslide / vslide / fade */
                        interval                : <?php echo yit_slide_get( 'interval' ) * 1000 ?>,
                        transition_speed        : <?php echo yit_slide_get( 'speed' ) * 1000 ?>,
                        thumbnails_position     : 'outside-last', /* outside-last/outside-first/inside-last/inside-first */
                        thumbnails_direction    : 'horizontal', /* vertical/horizontal */
                        thumbnails_slidex       : 1 /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */
                    });
                slider.find('.showcase-thumbnail-container').removeClass('container').addClass('container');
            };

        if($('body').hasClass('stretched')){
            thumbnails_width = $(window).width();
            thumbnails_height = <?php yit_slide_the( 'height' ) ?>;
        }

        $(window).resize(function(){
            slider.stop(true);
            slider.find('.showcase').remove();
            slider.append(slider_cloned);
            render_thumbnails_slider();
            fix_slider_sizes();
        });

        render_thumbnails_slider();
        fix_slider_sizes();
    });

</script>