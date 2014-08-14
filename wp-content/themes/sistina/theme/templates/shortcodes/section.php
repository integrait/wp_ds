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

$args = array(
    'items' => $items,
    'title' => $title,
    'description' => $description,
    'category' => $category,
    'portfolio' => $portfolio,
    'show_excerpt' => $show_excerpt,
    'excerpt_length' => $excerpt_length,
    'readmore_text' => $readmore_text,
    'show_title' => $show_title,
    'show_readmore' => $show_readmore,
    'show_comments' => $show_comments,
    'show_date' => $show_date,
    'show_overlay' => $show_overlay,
    'show_lightbox_hover' => $show_lightbox_hover,
    'show_detail_hover' => $show_detail_hover,
    'show_title_hover' => $show_title_hover,
    'show_categories' => $show_categories,
    'featured_excerpt_length' => $featured_excerpt_length,
    'other_posts_label' => $other_posts_label,
	'show_services_button' => $show_services_button,
	'services_button_text' => $services_button_text,
    'items_per_row' => $items_per_row,
    'services_icon_title' => $services_icon_title,
    'blog_icon_title' => $blog_icon_title,
    'blog_show_hover' => $blog_show_hover,
    'portfolio_icon_title' => $portfolio_icon_title
);
switch( $type ) {
    case 'blog'     : yit_get_template( '/shortcodes/section-blog.php', $args );      break;
    case 'services' : yit_get_template( '/shortcodes/section-services.php', $args );      break;
	case 'portfolio':
	case 'video'    :
	case 'gallery'  : yit_get_template( '/shortcodes/section-portfolio.php', $args );      break;
		
	default: break;
}