<?php

require_once GOOGLEAUTHENTICATOR_BASIC_PATH . '/src/Init.php';
require_once QA_INCLUDE_DIR . 'app/users.php';
require_once QA_INCLUDE_DIR . 'db/users.php';

class q2a_googleauthenticator_page_login
{
	public function match_request($request): bool
	{
		return '2fa-auth' === $request;
	}

	public function process_request()
	{
		if (empty(qa_post_text('login')) || empty(qa_post_text('password'))) {
			qa_redirect('');

			return;
		}

		if (empty(qa_post_text('2fa_code'))) {
			$content = qa_content_prepare();

			$content['title'] = 'Logowanie dwuetapowe';
			$content['form'] = $this->prepareTwoFactorAuthForm();

			return $content;
		}

		$secret = qa_db_read_all_assoc(
			qa_db_query_sub(
				'SELECT 2fa_secret FROM ^users WHERE handle = $',
				qa_post_text('login')
			)
		);

		if (empty($secret)) {
			// that would never occur...

			echo 'Invalid secret.';
			die;
		}
		$init = new Init($secret[0]['2fa_secret']);

		$code = qa_post_text('2fa_code');
		$login = qa_post_text('login');
		$password = qa_post_text('password');

		// @TODO: Fix it.

		if ($init->verifyCode($code)) {
			if ($this->checkLogin($login, $password)) {

				$userId = qa_db_read_all_assoc(
					qa_db_query_sub(
						'SELECT userid FROM ^users WHERE handle = $',
						$login
					)
				)[0]['userid'];

				$this->login($userId, $login, (bool) qa_post_text('remember'), qa_post_text('redirect'));
				return;
			}
		}

		$recoveryCode = qa_db_read_all_assoc(
			qa_db_query_sub(
				'SELECT 2fa_recovery_code FROM ^users WHERE handle = $',
				$login
			)
		)[0]['2fa_recovery_code'];

		if ($code == $recoveryCode) {
			// logged in with recovery code

			if ($this->checkLogin($login, $password)) {
				qa_db_query_sub(
					'UPDATE ^users SET 2fa_recovery_code = NULL WHERE handle = $',
					$login
				);

				$userId = qa_db_read_all_assoc(
					qa_db_query_sub(
						'SELECT userid FROM ^users WHERE handle = $',
						$login
					)
				)[0]['userid'];
				
				$this->login($userId, $login, (bool) qa_post_text('remember'), 'NOTHING');

				$content = qa_content_prepare();
				$content['title'] = qa_lang_html('plugin_2fa/title');
				$content['custom'] = qa_lang_html('plugin_2fa/recover_code_page_info');

				return $content;
			}
		}

		$content = qa_content_prepare();

		$content['title'] = qa_lang_html('plugin_2fa/title');
		$content['form'] = $this->prepareTwoFactorAuthForm();
		$content['error'] = qa_lang_html('plugin_2fa/invalid_code');

		return $content;
	}

	private function checkLogin($login, $password): bool
	{
		if (false !== strpos($login, '@')) {
			$matchUsers = qa_db_user_find_by_email($login);
		} else {
			$matchUsers = qa_db_user_find_by_handle($login);
		}

		if (1 !== count($matchUsers)) {
			return false;
		}

		$userInfo = qa_db_select_with_pending(qa_db_user_account_selectspec($matchUsers[0], true));

		// I dont know what it does, it's copied from Q2A core
		if (strtolower(qa_db_calc_passcheck($password, $userInfo['passsalt'])) == strtolower($userInfo['passcheck'])) {
			return true;
		}

		return false;
	}

	private function login($userId, $login, $remember = false, $redirectPath = null): void
	{
		qa_set_logged_in_user($userId, $login, $remember, '2fa');

		if (null === $redirectPath) {
			qa_redirect('');
		} else if ('NOTHING' != $redirectPath) {
			qa_redirect_raw(qa_path_to_root() . $redirectPath);
		}
	}

	private function prepareTwoFactorAuthForm(): array
	{
		return [
            'tags'    => 'method="post" action="' . qa_self_html() . '"',
            'style'   => 'wide',
            'title'   => qa_lang_html('plugin_2fa/title'),
            'fields'  => [
                'old' => [
                    'label' => qa_lang_html('plugin_2fa/code_input'),
                    'tags'  => 'name="2fa_code"',
           	        'type'  => 'input'
               	],
            ],
            'buttons' => [
                'send' => [
                    'label' => qa_lang_html('plugin_2fa/send')
                ]
            ],
            'hidden'  => [
            	'login'        => qa_post_text('login'),
            	'password'     => qa_post_text('password'),
            	'remember'     => qa_post_text('remember'),
            	'redirect'     => qa_post_text('redirect'),
                'code'         => qa_get_form_security_code('2faform'),
            ]
        ];
	}
}