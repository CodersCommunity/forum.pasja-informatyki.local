<?php
        
/*              
        Plugin Name: Polls
        Plugin URI: https://github.com/NoahY/q2a-poll
        Plugin Update Check URI: https://raw.github.com/NoahY/q2a-poll/master/qa-plugin.php
        Plugin Description: Ask poll questions
        Plugin Version: 2.3
        Plugin Date: 2011-09-05
        Plugin Author: NoahY
        Plugin Author URI:                              
        Plugin License: GPLv2                           
        Plugin Minimum Question2Answer Version: 1.5
*/                      
                        
                        
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
                    header('Location: ../../');
                    exit;   
    }               

    qa_register_plugin_module('module', 'qa-poll-admin.php', 'qa_poll_admin', 'Poll Admin');
    qa_register_plugin_module('event', 'qa-poll-check.php', 'qa_poll_event', 'Poll Admin');
    qa_register_plugin_module('page', 'qa-poll-page.php', 'qa_poll_page', 'Poll page');
    
    qa_register_plugin_layer('qa-poll-layer.php', 'Poll Layer');
                    
    if(function_exists('qa_register_plugin_phrases')) {
        qa_register_plugin_overrides('qa-poll-overrides.php');
        qa_register_plugin_phrases('qa-poll-lang-*.php', 'polls');
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
                          

