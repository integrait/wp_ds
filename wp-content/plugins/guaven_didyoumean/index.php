<?php
/*
Plugin Name: Easy DidYouMean and Autocomplete for WP Search
Plugin URI: http://guaven.com/didyoumean
Description: "Easy DidYouMean and Autocomplete for WP Search" is a WordPress plugin which will enrich your WordPress search box and results page. The plugin doesn't affect your website search results, it just enrich your website search box with smart autosuggestions(which are built in admin area) and using those smart relevant keywords pushes DidYouMean message in your search results page.
Author: Elvin Haci
Version: 1.0.1
Author URI: http://guaven.com/
*/


require_once(dirname(__FILE__)."/settings.php");
require_once(dirname(__FILE__)."/functions.php");

guaven_dym_load_defaults();


add_action('admin_menu', 'guaven_dym_admin');





?>