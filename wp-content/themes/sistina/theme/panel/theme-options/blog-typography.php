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

function yit_submenu_tabs_theme_option_blog_typography( $items ) {
    $items[12] = array(
        'id'   => 'blog-title-hover-color',
        'type' => 'colorpicker',
        'name' => __( 'Title hover color', 'yit' ),
        'desc' => __( 'Choose the font color for the title hover.', 'yit' ),
        'std'  => apply_filters( 'yit_blog-title-hover-color_std', '#D98104'),
        'style' => apply_filters('yit_blog-title-hover-color_style',array(
            'selectors' => '.post-title:hover, .post-title a:hover, .blog-big .meta .post-title a:hover,
                            .hentry-post .post-title > a:hover',
            'properties' => 'color'
        ))
    );

    $items[13] = array(
        'id'   => 'blog-title-uppercase',
        'type' => 'colorpicker',
        'name' => __( 'Force title uppercase', 'yit' ),
        'desc' => __( 'Set uppercase for all titles of the blog.', 'yit' ),
        'std'  => 1
    );

    /* Remove blog meta font and blog meta font hover */
    unset($items[20]);
    unset($items[21]);

    return $items;
}
add_filter( 'yit_submenu_tabs_theme_option_blog_typography', 'yit_submenu_tabs_theme_option_blog_typography' );
 
function yit_blog_title_std( $array ) {
    $array['color'] = '#c17836';
    $array['family'] = 'Open Sans';
    $array['style'] = 'extra-bold';
    $array['size'] = 18;
    
    return $array;    
}
add_filter( 'yit_blog-title-font_std', 'yit_blog_title_std' );

function yit_blog_title_style( $array ) {
    $array['selectors'] = '.post-title, .post-title a, .blog-big .meta .post-title a, .blog-small .meta .post-title a';
    return $array;    
}
add_filter( 'yit_blog-title-font_style', 'yit_blog_title_style' );

function yit_section_blog_post_title_std( $array ) {
    $array['color'] = '#ffffff';
    $array['family'] = 'Open Sans';
    $array['style'] = 'bold';
    $array['size'] = 14;
    
    return $array;    
}
add_filter( 'yit_section-blog-post-title_std', 'yit_section_blog_post_title_std' );
function yit_section_blog_post_title_style( $array ) {
    $array['selectors'] = '.section.blog .description h3 a';
    return $array;    
}
add_filter( 'yit_section-blog-post-title_style', 'yit_section_blog_post_title_style' );

function yit_section_blog_post_title_hover_std( $array ) {
    return '#ffffff';
}
add_filter( 'yit_section-blog-post-title-hover_std', 'yit_section_blog_post_title_hover_std' );

function yit_section_blog_post_title_hover_style( $array ) {
    $array['selectors'] = '.section.blog .description h3 a:hover';
    return $array;  
}
add_filter( 'yit_section-blog-post-title-hover_style', 'yit_section_blog_post_title_hover_style' );

function yit_section_blog_metas_std( $array ) {
    $array['family'] = 'Open Sans';
    $array['color'] = '#5F5E5E';
    return $array;    
}
add_filter( 'yit_blog-meta-font_std', 'yit_section_blog_metas_std' );

function yit_section_blog_metas_style( $array ) {
    $array['selectors'] = '.blog-big .meta div p, .blog-big .meta div p a, .blog-pinterest .meta div p, .blog-pinterest .meta div p a';
    return $array;    
}
add_filter( 'yit_blog-meta-font_style', 'yit_section_blog_metas_style' );