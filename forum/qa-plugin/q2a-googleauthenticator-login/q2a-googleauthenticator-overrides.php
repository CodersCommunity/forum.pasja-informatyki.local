<?php

function qa_set_logged_in_user($userId, $handle = '', $remember = false, $source = null)
{
	require_once QA_INCLUDE_DIR . 'app/cookies.php';

	qa_start_session();

	if (!isset($userId)) {
		// logout

		qa_report_event(
			'u_logout',
			qa_get_logged_in_userid(),
			qa_get_logged_in_handle(),
			qa_cookie_get()
		);

		qa_clear_session_cookie();
		qa_clear_session_user();

		return;
	}

	// login

	require_once QA_INCLUDE_DIR . 'db/selects.php';

	$result = qa_db_read_all_assoc(qa_db_query_sub(
		'SELECT 2fa_enabled FROM ^users WHERE userid = #',
		$userId
	));

	if (count($result) != 1) {
		echo 'Invalid num_rows';
		die;
	}

	$usingTwoFactorAuth = (bool) $result[0]['2fa_enabled'];

	if ($usingTwoFactorAuth && '2fa' !== $source) {
		echo '
<form method="post" id="form" action="./2fa-auth">
	<input name="login" type="hidden" value="' . qa_post_text('emailhandle') . '">
	<input name="password" type="hidden" value="' . qa_post_text('password') . '">
	<input name="remember" type="hidden" value="' . qa_post_text('remember') . '">
	<input name="redirect" type="hidden" value="' . $_GET['to'] . '">
</form>
Trwa przekierowanie...
<script>
document.getElementById("form").submit();
</script>
';
		exit;
	}

	$userInfo = qa_db_single_select(
		qa_db_user_account_selectspec(
			$userId,
			true
		)
	);

	if (empty($userInfo['sessioncode']) || $source !== $userInfo['sessionsource'] && '2fa' !== $source) {
		$sessionCode = qa_db_user_rand_sessioncode();
		
		qa_db_user_set(
			$userId,
			[
				'sessioncode' => $sessionCode,
				'sessionsource' => $sessionSource,
			]
		);
	} else {
		$sessionCode = $userInfo['sessioncode'];
	}

	qa_db_user_logged_in($userId, qa_remote_ip_address());
	qa_set_session_cookie($handle, $sessionCode, $remember);

	qa_report_event('u_login', $userId, $userInfo['handle'], qa_cookie_get());
}