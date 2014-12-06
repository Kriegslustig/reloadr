<?php

/*
  This adds a section to the "general" settings-page
  The section is called "Reloadr"
  It should point to where the templates for posts are
 */

  add_action('admin_init', 'reloadr_add_settings_page');
  function reloadr_add_settings_page () {
    add_settings_section(
      'reloadr',
      'Reloadr',
      'reloadr_add_settings_page_callback',
      'general'
    );

    register_setting('general', 'reloadr_watch_direcotry');
    add_settings_field(
      'reloadr_watch_direcotry',
      'Watch this directory',
      'reloadr_add_setting_watch_dir_callback',
      'general',
      'reloadr'
    );

    register_setting('general', 'reloadr_ignore');
    add_settings_field(
      'reloadr_ignore',
      'Ignore these files',
      'reloadr_add_setting_ignore_callback',
      'general',
      'reloadr'
    );
  }

  function reloadr_add_settings_page_callback () {
    echo '';
  }

  function reloadr_add_setting_watch_dir_callback () {
    echo '<input '.
      'name="reloadr_watch_direcotry" '.
      'id="reloadr_watch_direcotry" '.
      'class="regular-text ltr" '.
      'value="' . get_option('reloadr_watch_direcotry') . '" '.
      'type="text" >';
  }

  function reloadr_add_setting_ignore_callback () {
    echo '<input '.
      'name="reloadr_ignore" '.
      'id="reloadr_ignore" '.
      'class="regular-text ltr" '.
      'value="' . get_option('reloadr_ignore') . '" '.
      'type="text" >'.
      '<p>You can use simple regex for this, seperate them with commas<br>/.*\.DS_STORE/, /.*\.git.*/<br>You connot use commas.</p>';
  }