<?php
/**
 * Plugin Name: DrugStoc Pages
 * Plugin URI: http://integrahealth.com.ng/integraitlabs.php
 * Description: Modified Pages for Users, Search, Index and Landing
 * Version: 1.0.0
 * Author: Drugstoc | IntegraIT Labs
 * Author URI: http://integrahealth.com.ng
 * Text Domain: cpac
 * Domain Path: /languages
 * License: GPL2
 *
====================================================================================

    Copyright 2015  DS_GROUP_CART  (email : info@drugstoc.biz)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

====================================================================================
*/
defined('ABSPATH') or die("No script kiddies please!");


function ds_pages_css () {
    wp_enqueue_style( 'ds-pages-css', get_template_directory_uri() . '/css/ds-pages.css' , array(), rand(999, 9999));
}

/**
 * vendor page style sheet
 * @var [type]
 */
$url_frag = explode('/', $_SERVER['REQUEST_URI']);
if ( in_array('vendor', $url_frag) || in_array('pharma', $url_frag) || in_array('m', $url_frag) || in_array('manufacturer', $url_frag)){ 
    add_action('woocommerce_before_main_content', 'ds_pages_css');
}

/**
 * contains the markup for different rows of product
 * shown on the drugstoc home page
 */
if (is_front_page()) { 
    add_action('ds_featured_manufacturer', 'load_row_tpl');

} 