<?php

/**
 * @package reloadr
 * @version 0.1
 */
/*
 * Plugin Name: Reloadr
 * Description: A WordPress-Plugin that reloads all pages when a file in the current theme is updated.
 * Author: Luca Schmid
 * Version: 0.1
 * License: Public Domain
 * Author URI: http://netzkinder.cc/
 * Plugin URI: https://github.com/Kriegslustig/reloadr
*/

$plugin_name = 'reloadr';

add_action('wp_enqueue_scripts', 'reloadr_wp_enqueue_scripts');
function reloadr_wp_enqueue_scripts () {
  wp_enqueue_script( 'reloadr', plugin_dir_url(__FILE__) . 'reloadr.js', array(), false, true);
}

function js_set_event_source () {
  $url =  plugin_dir_url(__FILE__) . 'reloadr_source.php';
  echo "<script>var reloadr = {}; reloadr.reloadSource = '$url'; </script>";
}
add_action('wp_head', 'js_set_event_source');