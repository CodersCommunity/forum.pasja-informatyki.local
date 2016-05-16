<?php
		
/*			  
		Plugin Name: Comment Voting
		Plugin URI: https://github.com/NoahY/q2a-comment-voting
		Plugin Update Check URI: https://raw.github.com/NoahY/q2a-comment-voting/master/qa-plugin.php
		Plugin Description: Vote on comments
		Plugin Version: 1.4
		Plugin Date: 2011-08-15
		Plugin Author: NoahY
		Plugin Author URI:							  
		Plugin License: GPLv2						   
		Plugin Minimum Question2Answer Version: 1.3
*/					  
						
						
	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;   
	}			   

	qa_register_plugin_module('module', 'qa-comment-voting-admin.php', 'qa_comment_voting_admin', 'Comment Voting Admin');

	qa_register_plugin_layer('qa-comment-voting-layer.php', 'Comment Voting Layer');
						
	if(function_exists('qa_register_plugin_phrases')) {
		qa_register_plugin_overrides('qa-cv-overrides.php');
		qa_register_plugin_phrases('qa-cv-lang-*.php', 'comment_voting');
	}

	if(!function_exists('qa_permit_check')) {
		function qa_permit_check($opt) {
			if(qa_opt($opt) == QA_PERMIT_POINTS)
				return qa_get_logged_in_points() >= qa_opt($opt.'_points');
			return !qa_permit_value_error(qa_opt($opt), qa_get_logged_in_userid(), qa_get_logged_in_level(), qa_get_logged_in_flags());
		}
	}						
/*							  
		Omit PHP closing tag to help avoid accidental output
*/							  
						  

