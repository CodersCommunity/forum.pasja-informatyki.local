<?php
/*
  Plugin Name: Set limit to change username
  Plugin URI: https://github.com/CodersCommunity/forum.pasja-informatyki.local
  Plugin Description: Set limit to change username
  Plugin Version: 1.0.0
  Plugin Date: 2021-03-28
  Plugin Author: @event15
  Plugin Author URI: https://github.com/event15
  Plugin License: GPLv2
  Plugin Minimum Question2Answer Version: 1.5
  Plugin Update Check URI: https://github.com/CodersCommunity/forum.pasja-informatyki.local/tree/master/forum/qa-plugin/q2a-change-username-limit/metadata.json
*/


use CodersCommunity\q2a_changeusernamelimit_admin;
use CodersCommunity\q2a_changeusernamelimit_event;
use CodersCommunity\q2a_changeusernamelimit_widget;

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_phrases('q2a-changeusernamelimit-lang-*.php', 'plugin_username_limit');

qa_register_plugin_module(
    'module',
    'q2a-changeusernamelimit-admin.php',
    q2a_changeusernamelimit_admin::class,
    'q2a Change username limit Admin'
);

qa_register_plugin_module(
    'event',
    'q2a-changeusernamelimit-event.php',
    q2a_changeusernamelimit_event::class,
    'User page for admin edition'
);

qa_register_plugin_module(
    'widget',
    'q2a-changeusernamelimit-widget.php',
    q2a_changeusernamelimit_widget::class,
    'q2a Change username limit user profile widget'
);

qa_register_plugin_overrides('q2a-changeusernamelimit-override.php');
