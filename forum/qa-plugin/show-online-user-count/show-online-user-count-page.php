<?php
class show_online_user_count_page {
	function init_queries($tableslc) {
			$tablename=qa_db_add_table_prefix('online_user');
			
			if(!in_array($tablename, $tableslc)) {
				return "CREATE TABLE IF NOT EXISTS `".$tablename."` (
 							`id` int(11) NOT NULL AUTO_INCREMENT,
  							`user_id` int(1) NOT NULL,
  							`ip` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  							`last_activity` datetime NOT NULL,
  							PRIMARY KEY (`id`)
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
			}
		}	
	function admin_form()
		{
			require_once QA_INCLUDE_DIR.'qa-util-sort.php';
			
			$saved=false;
			
			if (qa_clicked('SAVE_BUTTON')) {
				qa_opt('show_online_user_list', (int)qa_post_text('show_online_user_list_field'));
				qa_opt('activity_time_out', ((int)qa_post_text('activity_time_out_field')=='')?5:(int)qa_post_text('activity_time_out_field'));
				$saved=true;
			}
			
			$form=array(
				'ok' => $saved ? qa_lang_html('show_online_user_count_lang/change_ok') : null,

				'fields' => array(
					'question1' => array(
						'label' => qa_lang_html('show_online_user_count_lang/show_online_members'),
						'type' => 'checkbox',
						'value' => (int)qa_opt('show_online_user_list'),
						'tags' => 'name="show_online_user_list_field"',
					),
					'question2' => array(
					    'type' =>'number',
						'label' => qa_lang_html('show_online_user_count_lang/activity_time_out'),
						'value' => qa_html(qa_opt('activity_time_out')),
						'tags' => 'name="activity_time_out_field"',
					),
				),
				
				'buttons' => array(
					array(
						'label' => qa_lang_html('show_online_user_count_lang/save_button'),
						'tags' => 'name="SAVE_BUTTON"',
					),
				),
			);
			
			return $form;
		}

	
	
}

?>