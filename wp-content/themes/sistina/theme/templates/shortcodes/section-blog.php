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

$items = (!is_null( $items )) ? $items : -1;
$sidebar_layout = yit_get_sidebar_layout() ?>
<div class="row">
    <!-- START SECTION BLOG -->
    <div class="section blog margin-bottom span<?php echo $sidebar_layout == 'sidebar-no' ? 12 : 9 ?>">
        <?php
        //Separated code for a better organization of the code

        if( !empty( $title ) ) {
            if( !empty($blog_icon_title)) { yit_image("src=$blog_icon_title"); }
            yit_string( '<h3 class="title">', yit_decode_title($title), '</h3>' );
        }

	    if( !empty( $description ) ) { yit_string( '<p class="desc">', $description, '</p>' ); }
        ?>
            
        <div class="row">
            <?php
            //Sticky posts loop args

            $args = array(
                'post_type' => 'post',
                'posts_per_page' => $items
            );

            if( isset( $category ) && !empty( $category ) ) {
                $args['category_name'] = $category;
            }

            $posts = new WP_Query( $args );

            if( $posts->have_posts() ) :
                while( $posts->have_posts() ) : $posts->the_post();

                    if( !is_single() )
                        { $more = 0; }
                    ?>
                    <div <?php post_class( 'hentry-post span3 yit_item' ) ?>>

                        <div class="figure">
                            <?php if( has_post_thumbnail() ) { ?>

                                <?php the_post_thumbnail( 'section_blog' ) ;

                            } else { ?>

                                <img class="attachment-section_blog wp-post-image" width="270" height="270" alt="04" src="<?php echo YIT_IMAGES_URL ?>/placeholder.png">

                            <?php } ?>

                            <?php if( $show_date == '1' || $show_date == 'yes' ) : ?>
                                <p class="date"><span class="month"><?php echo get_the_date( 'M' ) ?></span><span class="day"><?php echo get_the_date( 'd' ) ?></span></p>
                            <?php endif ?>

                            <?php if ( isset($blog_show_hover) && $blog_show_hover == 'yes' ) : ?>
                                <div class="description">
                                    <?php if( $show_title == '1' || $show_title == 'yes' ) :
                                        the_title( '<h3><a href="' . get_permalink() . '" title="' . get_the_title() . '">', '</a></h3>' );
                                    endif ?>

                                    <?php if( isset($show_comments) && ( $show_comments == '1' || $show_comments == 'yes' ) && get_comments_number() != 0 ) : ?>
                                        <div class="comments">
                                            <a href="<?php comments_link(); ?> "><?php echo get_comments_number();  ?></a>
                                        </div>
                                    <?php endif ?>
                                </div>
                            <?php else :
                                echo '<h3><a href="' . get_permalink() . '" title="' . get_the_title() . '"></a></h3>';
                            endif ?>
                        </div>

                    </div>
                <?php endwhile;

            endif;

            wp_reset_query() ?>
        </div>
    </div>
    <!-- END SECTION BLOG -->
    <div class="clear"></div>
    <?php wp_reset_query() ?>
</div>
<?php add_action( 'the_content_more_link', 'yit_simple_read_more_classes' ) ?>