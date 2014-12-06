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

require 'settings.php';

$plugin_name = 'reloadr';

add_action('wp_enqueue_scripts', 'reloadr_wp_enqueue_scripts');
function reloadr_wp_enqueue_scripts () {
  wp_enqueue_script( 'reloadr', plugin_dir_url(__FILE__) . 'reloadr.js', array(), false, true);
}

function js_set_event_source () {
  $settings_arr = [
    'ignore' => get_option('reloadr_ignore'),
    'watch_dir' => get_option('reloadr_watch_direcotry')
  ];
  $url =  plugin_dir_url(__FILE__) . 'source.php';
  $url .= '?' . http_build_query($settings_arr);
  $notifications = (get_option('reloadr_notifications') == 'on' ? 'true' : 'false');
  echo "<script>var reloadr = {reloadSource: '$url', notifications: $notifications};</script>";
}
add_action('wp_head', 'js_set_event_source');