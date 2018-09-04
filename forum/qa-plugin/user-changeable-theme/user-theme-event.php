<?php

class user_theme_event
{
    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if (qa_opt('user_theme_enable') && $event === 'u_login') {
            $sql = 'SELECT `theme` FROM `^users` WHERE `userid` = #';
            $theme = qa_db_read_one_value(qa_db_query_sub($sql, $userid));
            setcookie('qa_user_theme', $theme, time()+31556926, '/', QA_COOKIE_DOMAIN, QA_COOKIE_SECURE, QA_COOKIE_HTTPONLY);
        }
    }

    public function init_queries($tableslc)
	{
        $sql = 'SHOW COLUMNS FROM `^users` WHERE `field` = "theme"';
        if (!qa_db_read_all_assoc(qa_db_query_sub($sql))) {
		    return 'ALTER TABLE `^users` ADD `theme` TINYINT(1) DEFAULT 0';
        }
	}

    function admin_form()
	{
		$saved = false;
		if (qa_clicked('user_theme_save')) {
			$enable = (int)qa_post_text('user_theme_enable');
			qa_opt('user_theme_enable', $enable);
			$saved = true;
		}
		$form = [
			'ok' => ($saved === true) ? 'Saved!' : null,
			'fields' => [
				'enable' => [
				    'type' => 'checkbox',
					'label' => 'Enable changing theme colors for users',
					'value' => qa_opt('user_theme_enable'),
					'tags' => 'name="user_theme_enable"'
				],
			],
			'buttons' => [
				[
					'label' => 'Save',
					'tags' => 'name="user_theme_save"'
				]
			]
		];
		return $form;
	}
}
