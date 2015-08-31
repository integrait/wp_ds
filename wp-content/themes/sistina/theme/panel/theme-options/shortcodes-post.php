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


add_filter( 'yit_recent-posts-title_std', create_function('',"return array(
            'size'   => 13,
            'unit'   => 'px',
            'family' => 'Open Sans',
            'style'  => 'regular',
            'color'  => '#656464'
			);")
);

add_filter( 'yit_recent-posts-title-hover_std', create_function('','return "#a86e24";'));

add_filter( 'yit_recent-posts-excerpt_std', create_function('',"return array(
            'size'   => 11,
            'unit'   => 'px',
            'family' => 'Open Sans',
            'style'  => 'regular',
            'color'  => '#8e8a83'
			);")
);

add_filter( 'yit_recent-posts-excerpt_style', create_function('',"return array(
            'selectors'   => '.sidebar .recent-post p, .recent-post p'
			);")
);

add_filter( 'yit_recent-posts-date_std', create_function('',"return array(
            'size'   => 11,
            'unit'   => 'px',
            'family' => 'Open Sans',
            'style'  => 'regular',
            'color'  => '#8e8a83'
			);")
);

add_filter( 'yit_recent-posts-readmore_std', create_function('',"return array(
            'size'   => 11,
            'unit'   => 'px',
            'family' => 'Open Sans',
            'style'  => 'bold',
            'color'  => '#585555'
			);")
);

add_filter( 'yit_recent-posts-readmore-hover_std', create_function('','return "#d98104";'));

function yit_submenu_tabs_theme_option_shortcodes_post( $items ) {
    unset( $items[50] );
    $items[80] = array(
        'id' => 'recent-posts-comment',
        'type' => 'typography',
        'name' => __( 'Numbers comments text', 'yit' ),
        'desc' => __( 'Choose the font type, size and color for the text of the number of comments.', 'yit' ),
        'min'  => 1,
        'max'  => 30,
        'std'  => apply_filters( 'yit_recent-posts-comment_std', array(
            'size'   => 11,
            'unit'   => 'px',
            'family' => 'Open Sans',
            'style'  => 'regular',
            'color'  => '#c26511'
        ) ),
        'style' => apply_filters( 'yit_recent-posts-comment_selectors', array(
            'selectors' => '#footer .recent-post .text p.post-comments, .recent-post .text p.post-comments',
            'properties' => 'background-color'
        ) )
    );

    return $items;
}
add_filter( 'yit_submenu_tabs_theme_option_shortcodes_post', 'yit_submenu_tabs_theme_option_shortcodes_post' );