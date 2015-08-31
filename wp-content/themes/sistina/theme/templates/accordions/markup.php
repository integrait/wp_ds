<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */

?>

<?php
$style = $this->shortcode_atts['style'];
if ( $style == 'accordion' ) : ?>
    <?php if( ! yit_is_accordion_empty() ): ?>
        <div class="accordion-container">
            <?php while( yit_have_accordion_item() ): ?>

                <div class="accordion-wrapper row">
                    <div class="accordion-title span9">
                        <div class="plus">+</div>
                        <h4><?php yit_accordion_item_the('title'); ?></h4>
                    </div>
                    <div class="accordion-item span9">
                        <div class="row">
                            <div class="span2">
                                <div class="accordion-item-thumb">
                                    <?php list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( yit_accordion_item_get('item_id'), 'accordion_thumb' ); ?>
                                    <img src="<?php echo $thumbnail_url ?>" alt="<?php yit_accordion_item_the('title'); ?>" />
                                </div>
                            </div><!-- end span3 -->
                            <div class="span7">
                                <div class="accordion-item-content">
                                    <?php echo yit_content(yit_accordion_item_get('content'), 0); ?>
                                    <?php if (yit_accordion_item_get('subtitle') != '' || yit_accordion_item_get('website') != '' || yit_accordion_item_get('social') != '' ) : ?>
                                        <div class="meta">
                                            <?php if (yit_accordion_item_get('subtitle') != '') : ?><p><img class="icon" src="<?php echo YIT_THEME_ASSETS_URL ."/images/icons/role.png" ?>" alt="role_icon" /><?php _e('Role', 'yit') ?>: <?php yit_accordion_item_the('subtitle'); ?></p><?php endif ?>
                                            <?php if (yit_accordion_item_get('website') != '') : ?><p><img class="icon" src="<?php echo YIT_THEME_ASSETS_URL ."/images/icons/website.png" ?>" alt="website_icon" /><?php _e('Website', 'yit') ?>: <a href="<?php yit_accordion_item_the('website'); ?>"><?php yit_accordion_item_the('website'); ?></a></p><?php endif ?>
                                            <?php if (yit_accordion_item_get('social') != '') : ?>
                                                <div>
                                                    <div class="social_title">
                                                        <p><img class="icon" src="<?php echo YIT_THEME_ASSETS_URL ."/images/icons/social-meta.png" ?>" alt="social_icon" /><?php _e('Get in touch', 'yit') ?>:</p>
                                                    </div>
                                                    <?php echo do_shortcode(yit_accordion_item_get('social')); ?>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile ?>
        </div>

        <script>
            jQuery(document).ready(function($){
                $('.accordion-title').click(function(){
                    if( !$(this).hasClass('active') ) {
                        $('.accordion-title').removeClass('active').find(':first-child').addClass('plus').text("+").removeClass('minus');
                        $('.accordion-item').slideUp();

                        $(this).toggleClass('active')
                            .find(':first-child').removeClass('plus').addClass('minus').text("-").parent().next().slideToggle();
                    }
                }).filter(':first').click();
            });
        </script>
    <?php endif ?>

<?php elseif ($style == 'rounded') :
    wp_enqueue_script( 'caroufredsel' );
    wp_enqueue_script( 'touch-swipe' );
    wp_enqueue_script( 'mousewheel' );
    wp_enqueue_script( 'black-and-white' ); ?>
    <?php if( ! yit_is_accordion_empty() ): ?>
    <div class="team-slider wrapper team-rounded margin-top margin-bottom">
        <div class="list_carousel"><ul class="team-slides">
                <?php while( yit_have_accordion_item() ): ?>

                    <li>
                        <?php list( $thumbnail_url, $thumbnail_width, $thumbnail_height ) = wp_get_attachment_image_src( yit_accordion_item_get('item_id'), 'team_rounded_thumb' ); ?>
                        <div class="team-circle bwWrapper">
                            <img src="<?php echo $thumbnail_url ?>" alt="<?php yit_accordion_item_the('title'); ?>" />
                        </div>
                        <h6><?php yit_accordion_item_the('title'); ?></h6>

                        <?php echo yit_content(yit_accordion_item_get('content'), 1000); ?>

                        <?php if (yit_accordion_item_get('subtitle') != '' || yit_accordion_item_get('website') != '' || yit_accordion_item_get('social') != '' ) : ?>
                            <div class="meta">
                                <?php if (yit_accordion_item_get('subtitle') != '') : ?><p><strong><?php yit_accordion_item_the('subtitle'); ?></strong></p><?php endif ?>
                                <?php if (yit_accordion_item_get('website') != '') : ?><p><a href="<?php yit_accordion_item_the('website'); ?>"><?php yit_accordion_item_the('website'); ?></a></p><?php endif ?>
                                <?php if (yit_accordion_item_get('social') != '') : ?>
                                    <div>
                                        <?php echo do_shortcode(yit_accordion_item_get('social')); ?>
                                    </div>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </li>

                <?php endwhile ?>
            </ul></div>
        <div class="clearfix"></div>
        <div class="nav">
            <a id="team-slider-prev" class="prev" href="#"></a>
            <a id="team-slider-next" class="next" href="#"></a>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(function($){
            var maxHeight = 0;

            $('.team-slides li').each(function(){
                if ($(this).height() > maxHeight) {
                    maxHeight = $(this).height();
                }
            });

            $('.team-slides li').height(maxHeight + 20);

            $('.team-slides').imagesLoaded(function(){
                $('.team-slides').carouFredSel({
                    auto: true,
                    width: '100%',
                    prev: '#team-slider-prev',
                    next: '#team-slider-next',
                    swipe: {
                        onTouch: true
                    },
                    scroll : {
                        items     : 1,
                        duration  :	500,
                        pauseOnHover : 'immediate'
                    }
                });
            });

            $('.bwWrapper').BlackAndWhite({
                hoverEffect : true, // default true
                // set the path to BnWWorker.js for a superfast implementation
                webworkerPath : false,
                // for the images with a fluid width and height
                responsive:true,
                speed: { //this property could also be just speed: value for both fadeIn and fadeOut
                    fadeIn: 200, // 200ms for fadeIn animations
                    fadeOut: 300 // 800ms for fadeOut animations
                }
            });
        });
    </script>

<?php endif ?>
<?php endif ?>