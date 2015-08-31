<?php

/*

Plugin Name:    Drugstoc Hospital TypeAhead plugin
Version:      0.0.0.1
Description:    Auto suggestion for Institution name field on DrugStoc Registration
Author:       Drugstoc
Author URI:     http://www.drugstoc.biz
Text Domain:    cpac
Domain Path:    /languages
License:      GPLv2

Copyright 2011-2014  Codepress  info@codepress.nl

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined('ABSPATH') or die("You shall not pass");

add_action( 'wp_ajax_searchFormat', 'searchFormat' );
add_action( 'wp_ajax_nopriv_searchFormat', 'searchFormat' );
add_action( 'wp_footer', 'add_custom_drugstoc_scripts_styles' );

/**
 * Proper way to enqueue scripts and styles
 */
function add_custom_drugstoc_scripts_styles() {
  wp_enqueue_script( 'h-typeahead-js', plugin_dir_url(__FILE__) . 'typeahead.bundle.js');
  wp_enqueue_script( 'h-main-js', plugin_dir_url(__FILE__) . 'type-plugin.js', array('jquery'));
  wp_localize_script( 'h-main-js', 'wp_hospital', array( 'ajaxurl' => admin_url('admin-ajax.php') ));
  wp_enqueue_style( 'h-typeahead-css', plugin_dir_url(__FILE__) . 'plugin.css' );
}


function searchFormat () {

  global $wpdb;

  if (empty($_GET['query'])) {
    die();
  }

  $getQ = mysql_real_escape_string($_GET['query']);

  $q = "SELECT `facilityName` FROM `wp_govt_hospitals` WHERE `facilityName` LIKE '%$getQ%'";

  $r = $wpdb->get_results($q, 'ARRAY_N');

  $responseArray = array();

  foreach ($r as $key => $value) {
    array_push($responseArray, array("value" => $value[0]));
  }

  // echo $_GET['query'];
  wp_send_json($responseArray);

  die();

}

?>