<?php

/*
	Plugin Name: User changeable theme
	Plugin URI:
	Plugin Description: Plugin lets change theme colours by user, compatible with SnowFlat theme
	Plugin Version: 1.0
	Plugin Date: 2017-03-18
	Plugin Author: Argeento & Arkadiusz Waluk
	Plugin Author URI: https://forum.pasja-informatyki.pl
	Plugin License:
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) {
	header('Location: ../../');
	exit;
}

qa_register_plugin_layer('user-theme-layer.php', 'User changeable theme layer');
qa_register_plugin_module('event', 'user-theme-event.php', 'user_theme_event', 'User changeable theme event');
