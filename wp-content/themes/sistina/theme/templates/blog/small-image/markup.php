<?php
/**
 * Your Inspiration Themes
 *
 * @package    WordPress
 * @subpackage Your Inspiration Themes
 * @author     Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

$post_classes = 'hentry-post group blog-small-image row';
$is_single = is_single();
$span = yit_get_sidebar_layout() == 'sidebar-no' ? 12 : 9;

$post_format = get_post_format() == '' ? 'standard' : get_post_format();
$post_format = yit_get_option( 'blog-post-formats-list' ) && get_post_format() != '' ? get_post_format() : $post_format;

if ( ! $is_single && $post_format == 'quote' ) {
    $meta = false;
}
else {
    $meta = yit_get_option( 'blog-show-author' )
        || yit_get_option( 'blog-show-comments' ) && comments_open()
        || yit_get_option( 'blog-show-categories' )
        || yit_get_option( 'blog-show-tags' )
        || yit_get_option( 'blog-show-share' );
}

if ( yit_get_option( 'blog-post-formats-list' ) ) {
    $post_classes .= ' post-formats-on-list';
}

if ( $is_single ) {
    $post_classes .= ' blog-small-image-single';
}

$has_thumbnail = ( ! has_post_thumbnail() || ( ! is_single() && ! yit_get_option( 'blog-show-featured' ) ) || ( is_single() && ! yit_get_option( 'blog-show-featured-single' ) ) ) ? false : true;

$upper = yit_get_option( 'blog-title-uppercase' ) ? ' upper' : '';

?>
<div id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
    <div class="span<?php echo $span ?>">
        <?php if ( is_single() ): ?>
            <div class="row">

                <?php if ( $post_format != 'audio' ) : ?>
                    <?php yit_get_template( 'blog/small-image/post-formats/' . $post_format . '.php', array( 'span' => $span, 'has_thumbnail' => $has_thumbnail ) ); ?>
                <?php else: ?>
                    <?php yit_get_template( 'blog/small-image/post-formats/standard.php', array( 'span' => $span, 'has_thumbnail' => $has_thumbnail ) ); ?>
                <?php endif ?>

                <div class="the-content-single the-content group">
                    <div class="blog-small-image-content">

                        <!-- post title -->
                        <?php
                        $link = get_permalink();
                        $title = get_the_title() == '' ? __( '(this post does not have a title)', 'yit' ) : get_the_title();

                        if ( $post_format != 'quote' ) {
                            if ( $is_single ) {
                                yit_string( "<h1 class=\"post-title{$upper}\"><a href=\"$link\">", $title, "</a></h1>" );
                            }

                            the_content();

                            if ( yit_get_option( 'blog-show-tags' ) ) {
                                the_tags( '<p class="tags">' . __( 'Tags: ', 'yit' ), ', ', '</p>' );
                            }
                        }
                        else {
                            yit_string( "<blockquote><p><a href=\"$link\">", get_the_content(), "</a></p><cite>" . $title . "</cite></blockquote>" );
                        }
                        ?>

                        <?php if ( $post_format == 'audio' ) : ?>
                            <?php yit_get_template( 'blog/small-image/post-formats/audio.php', array( 'span' => $span, 'has_thumbnail' => $has_thumbnail ) ); ?>
                            <div class="clear"></div>
                        <?php endif ?>

                        <?php wp_link_pages(); ?>
                        <?php if ( is_paged() && $is_single ) {
                            previous_post_link();
                            echo ' | ';
                            next_post_link();
                        } ?>

                        <div class="clear"></div>

                        <div class="post-footer group">
                            <?php if (yit_get_option( 'blog-show-share' ) == 1) : ?>
                            <div class="share"><?php echo do_shortcode( '[share icon_type="round"]' ); ?></div><?php endif ?>
                            <?php if (get_the_author() != '' && yit_get_option( 'blog-show-author' )) : ?>
                            <p class="author"><?php the_author_link(); ?></p><?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php yit_get_template( 'blog/small-image/post-formats/standard.php', array( 'span' => $span, 'has_thumbnail' => $has_thumbnail ) ); ?>

                <!-- post content -->
                <div class="the-content-list the-content <?php if ( $post_format == 'quote' ) {
                    echo 'the-content-quote';
                } ?> span<?php echo $has_thumbnail ? $span - 4 : $span ?> group">
                    <div class="blog-small-image-content">

                        <!-- post title -->
                        <?php
                        $link = get_permalink();
                        $title = get_the_title() == '' ? __( '(this post does not have a title)', 'yit' ) : get_the_title();

                        if ( $post_format != 'quote' ) {
                            if ( $is_single ) {
                                yit_string( "<h1 class=\"post-title{$upper}\"><a href=\"$link\">", $title, "</a></h1>" );
                            }
                            else {
                                yit_string( "<h2 class=\"post-title{$upper}\"><a href=\"$link\">", $title, "</a></h2>" );
                            }

                            if ( yit_get_option( 'blog-show-read-more' ) ) {
                                the_content( yit_get_option( 'blog-read-more-text' ) );
                            }
                            else {
                                the_excerpt();
                            }
                        }
                        else {
                            yit_string( "<blockquote><p><a href=\"$link\">", get_the_content(), "</a></p><cite>" . $title . "</cite></blockquote>" );
                        }
                        ?>

                        <?php wp_link_pages(); ?>
                        <?php if ( is_paged() && $is_single ) {
                            previous_post_link();
                            echo ' | ';
                            next_post_link();
                        } ?>

                        <div class="clear"></div>

                        <div class="post-footer">
                            <?php if (yit_get_option( 'blog-show-share' ) == 1) : ?>
                            <div class="share"><?php echo do_shortcode( '[share icon_type="round"]' ); ?></div><?php endif ?>
                            <?php if (get_the_author() != '' && yit_get_option( 'blog-show-author' )) : ?>
                            <p class="author"><?php the_author_link(); ?></p><?php endif; ?>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        <?php endif ?>

        <div class="clear"></div>
    </div>
</div>