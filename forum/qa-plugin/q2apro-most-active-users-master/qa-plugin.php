<?php
/*
	Plugin Name: Most active users (per time interval)
	Plugin URI: https://www.github.com/echteinfachtv/q2a-most-active-users/
	Plugin Description: Displays the most active users of the current week or month in a widget
	Plugin Version: 1.2
	Plugin Date: 2013-02-07
	Plugin Author: echteinfachtv
	Plugin Author URI: http://www.echteinfach.tv/
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.4
	Plugin Update Check URI: https://raw.github.com/echteinfachtv/q2a-most-active-users/master/qa-plugin.php

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html
	
*/

	if ( !defined('QA_VERSION') )
	{
		header('Location: ../../');
		exit;
	}
	
	// language file
	qa_register_plugin_phrases('qa-most-active-users-lang.php', 'qa_most_active_users_lang');
	
	// widget
	qa_register_plugin_module('widget', 'qa-most-active-users.php', 'qa_most_active_users', 'Most active users per week/month');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/