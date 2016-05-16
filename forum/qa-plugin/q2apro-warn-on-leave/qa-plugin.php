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

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	// language file
	qa_register_plugin_phrases('q2apro-warnonleave-lang-*.php', 'q2apro_warnonleave_lang');

	// layer
	qa_register_plugin_layer('q2apro-warnonleave-layer.php', 'Warn-On-Leave Layer');

	// admin
	qa_register_plugin_module('module', 'q2apro-warnonleave-admin.php', 'q2apro_warnonleave_admin', 'Warn-On-Leave Admin');
        

/*
	Omit PHP closing tag to help avoid accidental output
*/