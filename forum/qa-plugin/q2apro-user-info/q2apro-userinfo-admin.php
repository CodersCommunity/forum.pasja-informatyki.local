<?php

/*
	Plugin Name: User Info
	Plugin URI: http://www.q2apro.com/plugins/user-info
	Plugin Description: Mouse over a username to display user profile information: Avatar image, account age, total points, monthly points, answers, best answers, ratio, questions posted, badges.
	Plugin Version: 1.0
	Plugin Date: 2014-02-20
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: http://www.q2apro.com/pluginupdate?id=3
	
	Licence: Copyright © q2apro.com - All rights reserved

*/

	class q2apro_userinfo_admin {

		// option's value is requested but the option has not yet been set
		function option_default($option) {
			switch($option) {
				case 'q2apro_userinfo_enabled':
					return 1; // true
				case 'q2apro_userinfo_show_avatar':
					return 1; // true
				case 'q2apro_userinfo_show_bonuspoints':
					return 1; // true
				case 'q2apro_userinfo_show_downvotes':
					return 1; // true
				default:
					return null;				
			}
		}
			
		function allow_template($template) {
			return ($template!='admin');
		}       
			
		function admin_form(&$qa_content){                       

			// process the admin form if admin hit Save-Changes-button
			$ok = null;
			if (qa_clicked('q2apro_userinfo_save')) {
				qa_opt('q2apro_userinfo_enabled', (bool)qa_post_text('q2apro_userinfo_enabled')); // empty or 1
				qa_opt('q2apro_userinfo_show_avatar', (bool)qa_post_text('q2apro_userinfo_show_avatar')); // empty or 1
				qa_opt('q2apro_userinfo_show_bonuspoints', (bool)qa_post_text('q2apro_userinfo_show_bonuspoints')); // empty or 1
				qa_opt('q2apro_userinfo_show_downvotes', (bool)qa_post_text('q2apro_userinfo_show_downvotes')); // empty or 1
				$ok = qa_lang('admin/options_saved');
			}
			
			// form fields to display frontend for admin
			$fields = array();
			
			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('q2apro_userinfo_lang/enable_plugin'),
				'tags' => 'name="q2apro_userinfo_enabled"',
				'value' => qa_opt('q2apro_userinfo_enabled'),
			);
			
			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('q2apro_userinfo_lang/show_avatar'),
				'tags' => 'name="q2apro_userinfo_show_avatar"',
				'value' => qa_opt('q2apro_userinfo_show_avatar'),
			);
			
			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('q2apro_userinfo_lang/show_bonuspoints'),
				'tags' => 'name="q2apro_userinfo_show_bonuspoints"',
				'value' => qa_opt('q2apro_userinfo_show_bonuspoints'),
			);
			
			$fields[] = array(
				'type' => 'checkbox',
				'label' => qa_lang('q2apro_userinfo_lang/show_downvotes'),
				'tags' => 'name="q2apro_userinfo_show_downvotes"',
				'value' => qa_opt('q2apro_userinfo_show_downvotes'),
			);
			
			// link to q2apro.com
			$fields[] = array(
				'type' => 'static',
				'note' => '<span style="font-size:75%;color:#789;">'.strtr( qa_lang('q2apro_userinfo_lang/contact'), array( 
							'^1' => '<a target="_blank" href="http://www.q2apro.com/plugins/user-info">',
							'^2' => '</a>'
						  )).'</span>',
			);
			
			return array(           
				'ok' => ($ok && !isset($error)) ? $ok : null,
				'fields' => $fields,
				'buttons' => array(
					array(
						'label' => qa_lang_html('main/save_button'),
						'tags' => 'name="q2apro_userinfo_save"',
					),
				),
			);
		}
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/