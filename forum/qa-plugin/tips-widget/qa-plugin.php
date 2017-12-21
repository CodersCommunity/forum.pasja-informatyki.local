<?php
/*
  Plugin Name: Tips Widget
  Plugin URI:
  Plugin Description: Simple plugin for displaying random tip about forum as widget in sidebar
  Plugin Version: 0.2
  Plugin Date: 2017-11-19
  Plugin Author: Patrycjerz
  Plugin Author URI:
  Plugin License: GPLv2
  Plugin Minimum Question2Answer Version: 1.5
  Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

qa_register_plugin_layer(
  'tips-widget-layer.php',
  'Tips Widget Layer'
);

qa_register_plugin_module(
  'module',
  'tips-widget-admin.php',
  'tips_widget_admin',
  'Tips Widget Admin'
);

qa_register_plugin_module(
  'page',
  'tips-widget-page.php',
  'tips_widget_page',
  'Tips Widget Page'
);

qa_register_plugin_module(
  'widget',
  'tips-widget-widget.php',
  'tips_widget_widget',
  'Tips Widget Widget'
);
