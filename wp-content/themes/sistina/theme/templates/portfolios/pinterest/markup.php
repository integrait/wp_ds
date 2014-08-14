<?php
/**
 * @package WordPress
 * @subpackage Your Inspiration Themes
 */

$thumbs = '';
$portfolio_type = yit_work_get( 'portfolio_type' );
?>

<script>
    jQuery(document).ready(function($){
        $('.sidebar').remove();

        if( !$('#primary').hasClass('sidebar-no') ) {
            $('#primary').removeClass().addClass('sidebar-no');
            $('.content').removeClass('span9').addClass('span12');
        }

    });
</script>

<div id="portfolio" class="portfolio-<?php echo $portfolio_type; ?> pinterest thumbnails">
    <?php while ( yit_have_works() ) :
        $classes = "work span3 yit_item";

        $image_url = yit_work_get( 'image_url' );
        $image_id  = yit_work_get( 'item_id' );

        $post_permalink = yit_work_permalink( $image_id );
        ?>
        <div <?php yit_work_class( $classes ) ?>>
            <?php yit_image( "id=$image_id&size=" . apply_filters( 'yit-thumb_portfolio_pinterest-image', 'thumb_portfolio_pinterest' ) ); ?>

            <?php if( yit_work_get('enable_hover') ): ?>
            <div class="description">
                <div class="description-container">
                <?php if( yit_work_get('title') ): ?>
                    <!-- title -->
                    <h2><?php yit_work_the('title') ?></h2>
                <?php endif ?>

                <?php if( yit_work_get('enable_categories') && yit_work_get('terms') ): ?>
                    <!-- categories -->
                    <span class="categories"><img src="<?php echo get_template_directory_uri() ?>/theme/templates/portfolios/pinterest/images/terms.png" alt="" /><?php echo implode( ', ', yit_work_get('terms') ) ?></span>
                <?php endif ?>
                </div>
            </div>
            <?php endif ?>

<!--            <span class="detail"></span>-->
            <a href="<?php echo $post_permalink ?>"></a>
        </div>
    <?php endwhile ?>
</div>
<?php yit_portfolio_pagination() ?>


<script type="text/javascript">
    jQuery(document).ready(function($){
       var container = $('#portfolio');
        container.imagesLoaded( function(){
            container.masonry({
                itemSelector: '.span3'
            });
        });

        $(window).resize(function(){
            $('#portfolio').masonry({
                itemSelector: '.span3'
            });
        })
    });
</script>
