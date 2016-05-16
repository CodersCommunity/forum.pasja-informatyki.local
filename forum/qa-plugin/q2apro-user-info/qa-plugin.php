<?php

/*
	Plugin Name: User Info
	Plugin URI: http://www.q2apro.com/plugins/user-info
	Plugin Description: Mouse over a username to display user profile information: Avatar image, account age, total points, monthly points, answers, best answers, ratio, questions posted, badges.
	Plugin Version: 1.1
	Plugin Date: 2014-10-14
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: http://www.q2apro.com/pluginupdate?id=3
	
	Licence: Copyright © q2apro.com - All rights reserved

*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	// language file
	qa_register_plugin_phrases('q2apro-userinfo-lang-*.php', 'q2apro_userinfo_lang');

	// page for ajax calls
	qa_register_plugin_module('page', 'q2apro-userinfo-page.php', 'q2apro_userinfo_page', 'User Info Page');

	// layer to insert js, and add (i) icon with userpoints meta data to username on top
	qa_register_plugin_layer('q2apro-userinfo-layer.php', 'User Info Layer');

	// admin
	qa_register_plugin_module('module', 'q2apro-userinfo-admin.php', 'q2apro_userinfo_admin', 'User Info Admin');
        

/*
	Omit PHP closing tag to help avoid accidental output
*/