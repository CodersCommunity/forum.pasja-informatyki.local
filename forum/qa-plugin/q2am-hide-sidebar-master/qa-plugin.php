<?php
/*
	Plugin Name: Q2AM Hide Sidebar
	Plugin URI: http://store.q2amarket.com/store/products/hide-sidebar-plugin/
	Plugin Update Check URI: https://github.com/q2amarket/q2am-hide-sidebar/raw/master/qa-plugin.php
	Plugin Description: Add recent questions widget on sidebar or template area
	Plugin Version: 1.2
	Plugin Date: 2013-07-19
	Plugin Author: Q2A Market
	Plugin Author URI: http://www.q2amarket.com
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5.4
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_layer('qam-sidebar-layer.php', 'Q2AM Hide Sidebar');
	qa_register_plugin_module('module', 'qam-sidebar-admin-form.php', 'qam_sidebar_admin_form', 'Q2AM Hide Sidebar Settings');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/