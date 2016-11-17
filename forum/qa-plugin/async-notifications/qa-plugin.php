<?php

/*
	Plugin Name: asyncNotifications
	Plugin URI:
	Plugin Description: asyncNotifications compatible with https://github.com/q2apro/q2apro-on-site-notifications
	Plugin Version: 1.0
	Plugin Date: 2016-11-11
	Plugin Author: Argeento & Arkadiusz Waluk
	Plugin Author URI: http://forum.pasja-informatyki.pl
	Plugin License:
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_layer('async-notifications-layer.php', 'asyncNotifications layer');
qa_register_plugin_module('page', 'async-notifications-page.php', 'async_notifications_page', 'asyncNotifications page');