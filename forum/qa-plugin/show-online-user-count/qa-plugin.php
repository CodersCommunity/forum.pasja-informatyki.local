<?php

/*
	Plugin Name: show online user count
	Plugin URI: http://question2answer-farsi.com/
	Plugin Description: Members and guests will display with username on the widget
	Plugin Version: 1.0
	Plugin Date: 2013-12-16
	Plugin Author: Ali sayahiyan
	Plugin Author URI: http://question2answer-farsi.com
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.6
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}
qa_register_plugin_phrases('show-online-user-count-lang.php', 'show_online_user_count_lang');
qa_register_plugin_module('page', 'show-online-user-count-page.php', 'show_online_user_count_page', 'Show online user count');
qa_register_plugin_module('widget', 'show-online-user-count-widget.php', 'show_online_user_count_widget', 'Show online user count');


?>