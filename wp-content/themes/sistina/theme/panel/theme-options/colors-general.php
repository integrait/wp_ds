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

/**
 * Add specific fields to the tab Colors -> General
 * 
 * @param array $fields
 * @return array
 */ 
function yit_tab_colors_general( $fields ) {
	
    return array_merge( $fields, array(
        80 => array(
            'id' => 'general-border-hover',
            'type' => 'colorpicker',
            'name' => __( 'Border hover color', 'yit' ),
            'desc' => __( 'Select the color of the border when is hover.', 'yit' ),
            'std' => apply_filters( 'yit_general-border-hover_std', '#f9c076' ),
            'style' => array(
            	'selectors' => '.portfolio-big-image .work-thumbnail .thumb-wrapper:hover, .related_project .related_img:hover, .portfolio-categories ul li:hover, #portfolio .more-link:hover, .portfolio-big-image a.more:hover, #portfolio.columns .overlay_a:hover, .showcase-thumbnail img:hover, .widget_archive ul li a:hover, .widget_nav_menu ul li a:hover, .widget_pages ul li a:hover, .widget_categories ul li a:hover, .picture_overlay:hover, .section-portfolio-classic .work-projects a.img:hover, .section-portfolio-classic .work-projects a.img.active,
#portfolio.filterable .ch-item-hover:hover, #portfolio.filterable .ch-item-opened',
            	'properties' => 'border-color'
			)
        ),
        190 => array(
            'id' => 'general-button-bg',
            'type' => 'colorpicker',
            'name' => __( 'General buttons background', 'yit' ),
            'desc' => __( 'Set background color for all general buttons.', 'yit' ),
            'std' => apply_filters( 'yit_general-button-bg_std', '#e89222' ),
            'style' => array(
                'selectors' => '.teaser .image p, .teaser .image p a, .yit_quick_contact .contact-form li.submit-button input.sendmail, .sidebar .cta .newsletter-submit .submit-field, #footer .cta .newsletter-submit .submit-field, .home-widget .newsletter-call3 .newsletter-submit .submit-field, #searchsubmit, .sidebar .widget_search #searchform .button, #footernewsletter #footernewslettersubmit, .newsletter-section .contact-form .submit-button input.sendmail,
                .section-services-bandw .service-wrapper .service .read-more a, .not-btn.more-link, .not-btn.read-more, #portfolio .read-more, #portfolio .more-link, #respond #commentsubmit, .sidebar .cta .contact-form li.submit-button input.sendmail,
                .general-pagination a.selected, .general-pagination span.current, .contact-form li.submit-button input.sendmail,
                .woocommerce nav.woocommerce-pagination ul li span.current, .error-404-search input#searchsubmit, #headersearchform #headersearchsubmit, .error404.not-found input#searchsubmit',
                'properties' => 'background-color'
            )
        ),
        200 => array(
            'id' => 'general-button-bg-hover',
            'type' => 'colorpicker',
            'name' => __( 'General buttons background (hover)', 'yit' ),
            'desc' => __( 'Set background color for all general buttons (on hover).', 'yit' ),
            'std' => apply_filters( 'yit_general-button-bg-hover_std', '#e37a1c' ),
            'style' => array(
                'selectors' => '.teaser .image p:hover, .teaser .image p a:hover, .yit_quick_contact .contact-form li.submit-button input.sendmail:hover, .sidebar .cta .newsletter-submit .submit-field:hover, #footer .cta .newsletter-submit .submit-field:hover, .home-widget .newsletter-call3 .newsletter-submit .submit-field:hover, #searchsubmit:hover,#headersearchform #headersearchsubmit:hover, .error-404-search input#searchsubmit:hover, #footernewsletter #footernewslettersubmit:hover, .sidebar .widget_search #searchform .button:hover, #footernewsletter #footernewslettersubmit:hover, .newsletter-section .contact-form .submit-button input.sendmail:hover,
                .section-services-bandw .service-wrapper .service .read-more a:hover, .not-btn.more-link:hover, .not-btn.read-more:hover, #portfolio .read-more:hover, #portfolio .more-link:hover, #respond #commentsubmit:hover, .error404.not-found input#searchsubmit:hover, .sidebar .cta .contact-form li.submit-button input.sendmail:hover, .contact-form li.submit-button input.sendmail:hover',
                'properties' => 'background-color'
            )
        ),
        205 => array(
            'id' => 'general-button-bottom-shadow',
            'type' => 'colorpicker',
            'name' => __( 'General buttons bottom shadow color.', 'yit'),
            'desc' => __('Set bottom shadow color for all general buttons', 'yit'),
            'std' => apply_filters('yit_general-button-bottom-shadow_std', '#cd6b02'),
            'boxshadow' => '0 2px 0',
            'style' => array(
                'selectors' => '.teaser .image p, .teaser .image p a, .yit_quick_contact .contact-form li.submit-button input.sendmail, .sidebar .cta .newsletter-submit .submit-field, #footer .cta .newsletter-submit .submit-field, .home-widget .newsletter-call3 .newsletter-submit .submit-field, #searchsubmit, .sidebar .widget_search #searchform .button, #footernewsletter #footernewslettersubmit, .newsletter-section .contact-form .submit-button input.sendmail,
                .section-services-bandw .service-wrapper .service .read-more a, .not-btn.more-link, .not-btn.read-more, #portfolio .read-more, #portfolio .more-link, #respond #commentsubmit, .error-404-search input#searchsubmit, #headersearchform #headersearchsubmit, .error404.not-found input#searchsubmit, .sidebar .cta .contact-form li.submit-button input.sendmail, .contact-form li.submit-button input.sendmail',
                'properties' => 'box-shadow'

            )
        ),
        210 => array(
            'id' => 'general-button-bottom-shadow-hover',
            'type' => 'colorpicker',
            'name' => __('General buttons shadow color (on hover)', 'yit'),
            'desc' => __('Set bottom shadow color for all general buttons (on hover)', 'yit'),
            'std' => apply_filters('yit_general-button-bottom-shadow-hover_std', '#cd6b02'),
            'boxshadow' => '0 2px 0',
            'style' => array(
                'selectors' => '.teaser .image p:hover, .teaser .image p a:hover, .yit_quick_contact .contact-form li.submit-button input.sendmail:hover, .sidebar .cta .newsletter-submit .submit-field:hover, #footer .cta .newsletter-submit .submit-field:hover, .home-widget .newsletter-call3 .newsletter-submit .submit-field:hover, #searchsubmit:hover,#headersearchform #headersearchsubmit:hover, .error-404-search input#searchsubmit:hover, .sidebar .widget_search #searchform .button:hover, #footernewsletter #footernewslettersubmit:hover, .newsletter-section .contact-form .submit-button input.sendmail:hover,
                .section-services-bandw .service-wrapper .service .read-more a:hover, .not-btn.more-link:hover, .not-btn.read-more:hover, #portfolio .read-more:hover, #portfolio .more-link:hover, #respond #commentsubmit:hover, .error404.not-found input#searchsubmit:hover, .sidebar .cta .contact-form li.submit-button input.sendmail:hover, .contact-form li.submit-button input.sendmail:hover',
                'properties' => 'box-shadow'
            )
        ),

        220 => array(
            'id' => 'general-button-text',
            'type' => 'colorpicker',
            'name' => __( 'General buttons text', 'yit' ),
            'desc' => __( 'Set text color for text in all general buttons.', 'yit' ),
            'std' => apply_filters( 'yit_general-button-text_std', '#FFFFFF' ),
            'style' => array(
                'selectors' => '.teaser .image p, .teaser .image p a, .yit_quick_contact .contact-form li.submit-button input.sendmail, .sidebar .cta .newsletter-submit .submit-field, #footer .cta .newsletter-submit .submit-field, .home-widget .newsletter-call3 .newsletter-submit .submit-field, .sidebar .widget_search #searchform .button, .not-btn.more-link, #footernewsletter #footernewslettersubmit, #headersearchform #headersearchsubmit, .sidebar .cta .contact-form li.submit-button input.sendmail',
                'properties' => 'color'
            )
        ),
        230 => array(
            'id' => 'general-button-text-hover',
            'type' => 'colorpicker',
            'name' => __( 'General buttons text (hover)', 'yit' ),
            'desc' => __( 'Set text color for text in all general buttons (on hover).', 'yit' ),
            'std' => apply_filters( 'yit_general-button-text-hover_std', '#FFFFFF' ),
            'style' => array(
                'selectors' => '.teaser .image p:hover, .yit_quick_contact .contact-form li.submit-button input.sendmail:hover, .sidebar .cta .newsletter-submit .submit-field:hover, #footer .cta .newsletter-submit .submit-field:hover, .home-widget .newsletter-call3 .newsletter-submit .submit-field:hover, .sidebar .widget_search #searchform .button:hover, .not-btn.more-link:hover, #footernewsletter #footernewslettersubmit:hover, #headersearchform #headersearchsubmit:hover, .sidebar .cta .contact-form li.submit-button input.sendmail:hover',
                'properties' => 'color'
            )
        ),
        240 => array(
            'id'   => 'back-top-background',
            'type' => 'colorpicker',
            'name' => __( 'Back to Top background', 'yit' ),
            'desc' => __( 'Select the color to use for Back to Top background. ', 'yit' ),
            'std'  => apply_filters( 'yit_back-top-background_std', '#93866d' ),
            'style' => apply_filters( 'yit_back-top-background_style', array(
                'selectors' => '#back-top',
                'properties' => 'background-color'
            ) )
        ),
        250 => array(
            'id'   => 'section-title',
            'type' => 'title',
            'name' => __( 'Section colors', 'yit' ),
            'desc' => '',
        ),
        260 => array(
            'id'   => 'hover-section',
            'type' => 'colorpicker',
            'name' => __( 'Hover of Blog and Portfolio section and Pinterest portfolio', 'yit' ),
            'desc' => __( 'Select the color to use for the overlay background for section Blog, section Portfolio and Pinterest portfolio.', 'yit' ),
            'std'  => apply_filters( 'yit_hover-section_std', '#ec7a0f' ),
            'opacity' => 0.7,
            'style' => apply_filters( 'yit_hover-section_style', array(
                'selectors' => '.yit_item .description',
                'properties' => 'background-color'
            ) )
        ),
        270 => array(
            'id'   => 'data-background-section',
            'type' => 'colorpicker',
            'name' => __( 'Date background of the Blog', 'yit' ),
            'desc' => __( 'Select the color to use for the date background of the Blog', 'yit' ),
            'std'  => apply_filters( 'yit_data-background-section_std', '#e08401' ),
            'style' => apply_filters( 'yit_data-background-section_style', array(
                'selectors' => '.section.blog .date, .recent-post .thumb-date',
                'properties' => 'background-color'
            ) )
        )
    ) );
}
add_filter( 'yit_submenu_tabs_theme_option_colors_general', 'yit_tab_colors_general' );

add_filter( 'yit_general-border_std', create_function( '', 'return "#dad9d9";' ) );

function yit_general_border_style( $array ) {
    $array['selectors'] = 'code, pre, body hr, #copyright .inner, #footer .inner, .gallery img, .gallery img, .content .archive-list ul, .content .archive-list ul li, 
.more-projects-widget .work-thumb, .more-projects-widget .controls, .more-projects-widget .top, .featured-projects-widget img,
.thumb-project img, #searchform input, .portfolio-categories ul li, .portfolio-categories ul li:hover, .recent-comments .avatar img,
.content .contact-form li.submit-button input, #portfolio .read-more, #portfolio .more-link, #portfolio .read-more:hover,
#portfolio .more-link:hover, .accordion-title, .accordion-item-thumb img, form input[type="text"], form textarea, .testimonial-page,
div.section-caption .caption, .line, .last-tweets-widget ul li, .toggle p.tab-index, .toggle .content-tab, .testimonial,
.google-map-frame, .section.blog .post, .section.blog h4.other-articles, .section.blog .sticky .thumbnail, .section .portfolio-sticky .work-categories,
.testimonial, #searchform input, .blog-big .meta p, .blog-big p.list-tags, .blog-small .image-wrap, .comment-container, .image-square-style #comments img.avatar,
#comments .comment-author img, .comment-meta, #respond input, #respond textarea, img.comment-avatar, .portfolio-big-image a.thumb, .portfolio-big-image a.more,
.portfolio-big-image a.more:hover, .portfolio-big-image .work-thumbnail a.nozoom, .portfolio-big-image .work-skillsdate, .internal_page_item, .gallery-wrap li h5,
.gallery-filters, .portfolio-full-description a.thumb, .portfolio-full-description a.more, .portfolio-full-description a.more:hover,
.portfolio-full-description .work-skillsdate, .related_img, #portfolio.columns .overlay_a, .yit-widget-content .widget,
.slider.thumbnails .showcase-thumbnail img, .slider.thumbnails .showcase-thumbnail img:hover, .slider.thumbnails .showcase-thumbnail.active img,
.recent-post .thumb-img img, .widget_archive ul li a, .widget_archive ul li a:hover, .widget_nav_menu ul li a, .widget_nav_menu ul li a:hover,
.widget_pages ul li a, .widget_pages ul li a:hover, .widget_categories ul li a, .widget_categories ul li a:hover, #searchform input,
.widget_flickrRSS img, .widget_nav_menu ul li a, .widget_pages ul li a, .widget_categories ul li a, .widget_archive ul li a:hover,
.widget_nav_menu ul li.current_page_item > a, .widget_pages ul li.current_page_item > a, .widget_categories ul li.current_page_item > a,
.testimonial-widget div.name-testimonial, .last-tweets-widget ul li, .yit-widget-content .widget, .portfolio-categories ul li, .recent-comments .avatar img,
.more-projects-widget .work-thumb, .more-projects-widget .controls, .more-projects-widget .top, .featured-projects-widget img, .thumb-project img, .picture_overlay,
#respond textarea:focus, .section-portfolio-classic .work-projects a.img, .border, #header-cart-search .cart-items, #header-cart-search .cart-subtotal,
#header-cart-search .widget_shopping_cart .cart_control, #nav .container, .sitemap h3, #copyright .border,
#topbar .widget_search_mini, .topbar-border, .faq-filters-container, .woocommerce .cart-collaterals .cart_totals,
.woocommerce table, .woocommerce table.shop_table, .woocommerce-page table.shop_table, .ie_border, .woocommerce form.login,
.woocommerce .woocommerce_checkout_coupon, .woocommerce form.register, .woocommerce-page form.login, .woocommerce-page .woocommerce_checkout_coupon, .woocommerce-page form.register,
.woocommerce-account .woocommerce form, .woocommerce .address,
.woocommerce div.product .product_title,
.single-layout-2.woocommerce div.product div.images img, .woocommerce div.product div.images .thumbnails img, .single-layout-2.woocommerce .woocommerce-tabs ul.tabs
#primary .woocommerce div.product table.variations,
.single-product.woocommerce div.product .related-products h2, .woocommerce .content #page-meta, .single-product.woocommerce div.product div.images .thumbnails img,

.single-product.woocommerce div.product .single_variation_wrap span.label, .single-product.woocommerce div.product .single_variation_wrap span.value,
.woocommerce table:after, .woocommerce-page .woocommerce_checkout_coupon:after, .woocommerce .woocommerce_checkout_coupon:after, .woocommerce .address:after, .woocommerce-account .woocommerce form:after, .woocommerce form.checkout_coupon:after,

.team-professional ul li .padding, .the-content-list > div,
.thumb-img img, .recent-post .thumb-img img, .sidebar .recent-post .thumb-img img, .recent-post .thumb-img img,
#portfolio.filterable .ch-item, .error404 .border-img-bottom, .error404 .error-404-text, .error404 .error-404-search, .error-404-search input#s,
.faq-title, .recent-post .hentry-post, .toggle h4.tab-index,

.teaser .image img, ul.filters, #map .view-map a,

.woocommerce ul.products li.product.grid.classic.with-border a.thumb,

div.yit_quick_contact, .woocommerce ul.cart_list li, .woocommerce-page ul.cart_list li, .woocommerce ul.product_list_widget li,
.woocommerce-page ul.product_list_widget li, .woocommerce.widget_best_sellers, .sidebar .recent-post .hentry-post,
.sidebar .recent-comments .border, .testimonial-widget li blockquote, .almost-all-categories ul > li, .sidebar .home-widget.contact-info ul li, .sidebar .widget.contact-info ul li,
#footer .widget.contact-info ul li, .yit_toggle_menu ul.menu li a, .widget_product_categories .product-categories li,
.widget.widget_layered_nav li small.count, .widget_product_categories .product-categories li span.count,

.boxed #header-container .innerborder,
.boxed #header-cart:after, .woocommerce table th, .woocommerce table.shop_table th, .woocommerce-page table.shop_table th,
.woocommerce table td, .woocommerce table.shop_table td, .woocommerce-page table.shop_table td, .woocommerce table.cart .product-thumbnail img,
.woocommerce #content table.cart .product-thumbnail img, .woocommerce-page table.cart .product-thumbnail img, .woocommerce-page #content table.cart .product-thumbnail img,
.woocommerce .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce-page #content .quantity input.qty,
.sidebar input[type=text], .sidebar input[type=search], .sidebar .widget, .welcome_menu .welcome_menu_inner form,
.comment .comment-meta, .comment .comment-content .comment_line, .comment .comment-content .comment-border, .comment-flexslider, .comment-flexslider .comment-text,

.autocomplete-suggestions';
    return $array;
}
add_filter( 'yit_general-border_style', 'yit_general_border_style' );



function yit_container_background_style( $array ) {
    $array['selectors'] = '.boxed #wrapper, #header .slider.slider-thumbnails, #header .slider.slider-thumbnails .showcase-thumbnail-container';
    return $array;
}
add_filter( 'yit_container-background_style', 'yit_container_background_style' );


