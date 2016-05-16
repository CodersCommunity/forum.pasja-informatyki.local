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
		'enable_plugin' => 'Activer le plugin Warn-On-Leave', // Enable Plugin (checkbox)
		'minimum_level' => 'Niveau pour accéder à cette page et à la fonction de modification des posts :', // Level to access this page and edit posts:
		'plugin_disabled' => 'Le plugin a été désactivé.', // Plugin has been disabled.
		'access_forbidden' => 'Accès interdit.', // Access forbidden.
		'plugin_page_url' => 'Ouvrir la page plugin dans le forum:', // Open page in forum:
		'contact' => 'Si vous avez des questions, visitez  ^1q2apro.com^2.', // For questions please visit ^1q2apro.com^2
		
		// plugin
		'warnmsg' => 'Votre texte n\'a pas été enregistré. Si vous quitez cette page, les données seront perdues.',
	);


/*
	Omit PHP closing tag to help avoid accidental output
*/