<?php
/*
	Plugin Name: Warn On Leave
	Plugin URI: http://www.q2apro.com/plugins/warn-on-leave
	Plugin Description: Warns the user after he entered text in textarea or CKEditor and is leaving the page
	Plugin Version: 1.0
	Plugin Date: 2014-02-26
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: https://raw.github.com/q2apro/q2a-comment-to-answer/master/qa-plugin.php
	
	This program is free software. You can redistribute and modify it 
	under the terms of the GNU General Public License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html

*/

	return array(
		// default
		'enable_plugin' => 'Warn-On-Leave Plugin aktivieren', // Enable Plugin (checkbox)
		'minimum_level' => 'Auf Seite zugreifen und Posts bearbeiten können:', // Level to access this page and edit posts:
		'plugin_disabled' => 'Dieses Plugin wurde deaktiviert.', // Plugin has been disabled.
		'access_forbidden' => 'Zugriff nicht erlaubt.', // Access forbidden.
		'plugin_page_url' => 'Seite im Forum öffnen:', // Open page in forum:
		'contact' => 'Bei Fragen bitte ^1q2apro.com^2 besuchen.', // For questions please visit ^1q2apro.com^2
		
		// plugin
		'warnmsg' => 'Dein Text wurde nicht gespeichert. Alle Eingaben gehen verloren!',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/