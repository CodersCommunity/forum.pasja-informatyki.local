<?php
/*
	Question2Answer by Gideon Greenspan and contributors
	http://www.question2answer.org/

	File: qa-include/qa-page.php
	Description: Routing and utility functions for page requests


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

	require_once QA_INCLUDE_DIR.'app/cookies.php';
	require_once QA_INCLUDE_DIR.'app/format.php';
	require_once QA_INCLUDE_DIR.'app/users.php';
	require_once QA_INCLUDE_DIR.'app/options.php';
	require_once QA_INCLUDE_DIR.'db/selects.php';


//	Functions which are called at the bottom of this file

	function qa_page_db_fail_handler($type, $errno=null, $error=null, $query=null)
/*
	Standard database failure handler function which bring up the install/repair/upgrade page
*/
	{
		if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }

		$pass_failure_type=$type;
		$pass_failure_errno=$errno;
		$pass_failure_error=$error;
		$pass_failure_query=$query;

		require_once QA_INCLUDE_DIR.'qa-install.php';

		qa_exit('error');
	}


	function qa_page_queue_pending()
/*
	Queue any pending requests which are required independent of which page will be shown
*/
	{
		if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }

		qa_preload_options();
		$loginuserid=qa_get_logged_in_userid();

		if (isset($loginuserid)) {
			if (!QA_FINAL_EXTERNAL_USERS)
				qa_db_queue_pending_select('loggedinuser', qa_db_user_account_selectspec($loginuserid, true));

			qa_db_queue_pending_select('notices', qa_db_user_notices_selectspec($loginuserid));
			qa_db_queue_pending_select('favoritenonqs', qa_db_user_favorite_non_qs_selectspec($loginuserid));
			qa_db_queue_pending_select('userlimits', qa_db_user_limits_selectspec($loginuserid));
			qa_db_queue_pending_select('userlevels', qa_db_user_levels_selectspec($loginuserid, true));
		}

		qa_db_queue_pending_select('iplimits', qa_db_ip_limits_selectspec(qa_remote_ip_address()));
		qa_db_queue_pending_select('navpages', qa_db_pages_selectspec(array('B', 'M', 'O', 'F')));
		qa_db_queue_pending_select('widgets', qa_db_widgets_selectspec());
	}


	function qa_load_state()
/*
	Check the page state parameter and then remove it from the $_GET array
*/
	{
		global $qa_state;

		$qa_state=qa_get('state');
		unset($_GET['state']); // to prevent being passed through on forms
	}


	function qa_check_login_modules()
/*
	If no user is logged in, call through to the login modules to see if they want to log someone in
*/
	{
		if ((!QA_FINAL_EXTERNAL_USERS) && !qa_is_logged_in()) {
			$loginmodules=qa_load_modules_with('login', 'check_login');

			foreach ($loginmodules as $loginmodule) {
				$loginmodule->check_login();
				if (qa_is_logged_in()) // stop and reload page if it worked
					qa_redirect(qa_request(), $_GET);
			}
		}
	}


	function qa_check_page_clicks()
/*
	React to any of the common buttons on a page for voting, favorites and closing a notice
	If the user has Javascript on, these should come through Ajax rather than here.
*/
	{
		if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }

		global $qa_page_error_html;

		if (qa_is_http_post())
			foreach ($_POST as $field => $value) {
				if (strpos($field, 'vote_')===0) { // voting...
					@list($dummy, $postid, $vote, $anchor)=explode('_', $field);

					if (isset($postid) && isset($vote)) {
						if (!qa_check_form_security_code('vote', qa_post_text('code')))
							$qa_page_error_html=qa_lang_html('misc/form_security_again');

						else {
							require_once QA_INCLUDE_DIR.'app/votes.php';
							require_once QA_INCLUDE_DIR.'db/selects.php';

							$userid=qa_get_logged_in_userid();

							$post=qa_db_select_with_pending(qa_db_full_post_selectspec($userid, $postid));
							$qa_page_error_html=qa_vote_error_html($post, $vote, $userid, qa_request());

							if (!$qa_page_error_html) {
								qa_vote_set($post, $userid, qa_get_logged_in_handle(), qa_cookie_get(), $vote);
								qa_redirect(qa_request(), $_GET, null, null, $anchor);
							}
							break;
						}
					}

				} elseif (strpos($field, 'favorite_')===0) { // favorites...
					@list($dummy, $entitytype, $entityid, $favorite)=explode('_', $field);

					if (isset($entitytype) && isset($entityid) && isset($favorite)) {
						if (!qa_check_form_security_code('favorite-'.$entitytype.'-'.$entityid, qa_post_text('code')))
							$qa_page_error_html=qa_lang_html('misc/form_security_again');

						else {
							require_once QA_INCLUDE_DIR.'app/favorites.php';

							qa_user_favorite_set(qa_get_logged_in_userid(), qa_get_logged_in_handle(), qa_cookie_get(), $entitytype, $entityid, $favorite);
							qa_redirect(qa_request(), $_GET);
						}
					}

				} elseif (strpos($field, 'notice_')===0) { // notices...
					@list($dummy, $noticeid)=explode('_', $field);

					if (isset($noticeid)) {
						if (!qa_check_form_security_code('notice-'.$noticeid, qa_post_text('code')))
							$qa_page_error_html=qa_lang_html('misc/form_security_again');

						else {
							if ($noticeid=='visitor')
								setcookie('qa_noticed', 1, time()+86400*3650, '/', QA_COOKIE_DOMAIN, QA_COOKIE_SECURE, QA_COOKIE_HTTPONLY);

							elseif ($noticeid=='welcome') {
								require_once QA_INCLUDE_DIR.'db/users.php';
								qa_db_user_set_flag(qa_get_logged_in_userid(), QA_USER_FLAGS_WELCOME_NOTICE, false);

							} else {
								require_once QA_INCLUDE_DIR.'db/notices.php';
								qa_db_usernotice_delete(qa_get_logged_in_userid(), $noticeid);
							}

							qa_redirect(qa_request(), $_GET);
						}
					}
				}
			}
	}


	/**
	 *	Run the appropriate qa-page-*.php file for this request and return back the $qa_content it passed
	 */
	function qa_get_request_content()
	{
		if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }

		$requestlower = strtolower(qa_request());
		$requestparts = qa_request_parts();
		$firstlower = strtolower($requestparts[0]);
		$routing = qa_page_routing();

		if (isset($routing[$requestlower])) {
			qa_set_template($firstlower);
			$qa_content = require QA_INCLUDE_DIR.$routing[$requestlower];

		} elseif (isset($routing[$firstlower.'/'])) {
			qa_set_template($firstlower);
			$qa_content = require QA_INCLUDE_DIR.$routing[$firstlower.'/'];

		} elseif (is_numeric($requestparts[0])) {
			qa_set_template('question');
			$qa_content = require QA_INCLUDE_DIR.'pages/question.php';

		} else {
			qa_set_template(strlen($firstlower) ? $firstlower : 'qa'); // will be changed later
			$qa_content = require QA_INCLUDE_DIR.'pages/default.php'; // handles many other pages, including custom pages and page modules
		}

		if ($firstlower == 'admin') {
			$_COOKIE['qa_admin_last'] = $requestlower; // for navigation tab now...
			setcookie('qa_admin_last', $_COOKIE['qa_admin_last'], 0, '/', QA_COOKIE_DOMAIN, QA_COOKIE_SECURE, QA_COOKIE_HTTPONLY); // ...and in future
		}

		if (isset($qa_content))
			qa_set_form_security_key();

		return $qa_content;
	}


	/**
	 *	Output the $qa_content via the theme class after doing some pre-processing, mainly relating to Javascript
	 */
	function qa_output_content($qa_content)
	{
		if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }

		global $qa_template;

		$requestlower = strtolower(qa_request());

	//	Set appropriate selected flags for navigation (not done in qa_content_prepare() since it also applies to sub-navigation)

		foreach ($qa_content['navigation'] as $navtype => $navigation) {
			if (!is_array($navigation) || $navtype == 'cat') {
				continue;
			}

			foreach ($navigation as $navprefix => $navlink) {
				$selected =& $qa_content['navigation'][$navtype][$navprefix]['selected'];
				if (isset($navlink['selected_on'])) {
					// match specified paths
					foreach ($navlink['selected_on'] as $path) {
						if (strpos($requestlower.'$', $path) === 0)
							$selected = true;
					}
				}
				elseif ($requestlower === $navprefix || $requestlower.'$' === $navprefix) {
					// exact match for array key
					$selected = true;
				}
			}
		}

	//	Slide down notifications

		if (!empty($qa_content['notices']))
			foreach ($qa_content['notices'] as $notice) {
				$qa_content['script_onloads'][]=array(
					"qa_reveal(document.getElementById(".qa_js($notice['id'])."), 'notice');",
				);
			}

		$page = explode('/', qa_request())[0];
		$activPages = [
			'activity' => 'nav1',
			'unanswered' => 'nav2',
			'ask' => 'nav3',
			'categories' => 'nav4',
			'tags' => 'nav5',
			'user' => 'nav6',
			'users' => 'nav6',
			'zasluzeni-pasjonaci-hall-of-fame' => 'nav7',
			'chat-discord' => 'nav8',
			'faq' => 'nav9',
			'regulamin-forum' => 'nav10',
			'ksiazki-informatyczne-warte-uwagi' => 'nav11',
		];
		if (isset($activPages[$page])) {
			$qa_content['navigation']['main'][$activPages[$page]]['selected'] = true;
		}

	//	Handle maintenance mode

		if (qa_opt('site_maintenance') && ($requestlower!='login')) {
			if (qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) {
				if (!isset($qa_content['error']))
					$qa_content['error']=strtr(qa_lang_html('admin/maintenance_admin_only'), array(
						'^1' => '<a href="'.qa_path_html('admin/general').'">',
						'^2' => '</a>',
					));

			} else {
				$qa_content=qa_content_prepare();
				$qa_content['error']=qa_lang_html('misc/site_in_maintenance');
			}
		}

	//	Handle new users who must confirm their email now, or must be approved before continuing

		$userid=qa_get_logged_in_userid();
		if (isset($userid) && ($requestlower!='confirm') && ($requestlower!='account')) {
			$flags=qa_get_logged_in_flags();

			if ( ($flags & QA_USER_FLAGS_MUST_CONFIRM) && (!($flags & QA_USER_FLAGS_EMAIL_CONFIRMED)) && qa_opt('confirm_user_emails') ) {
				$qa_content=qa_content_prepare();
				$qa_content['title']=qa_lang_html('users/confirm_title');
				$qa_content['error']=strtr(qa_lang_html('users/confirm_required'), array(
					'^1' => '<a href="'.qa_path_html('confirm').'">',
					'^2' => '</a>',
				));

			} elseif ( ($flags & QA_USER_FLAGS_MUST_APPROVE) && (qa_get_logged_in_level()<QA_USER_LEVEL_APPROVED) && qa_opt('moderate_users') ) {
				$qa_content=qa_content_prepare();
				$qa_content['title']=qa_lang_html('users/approve_title');
				$qa_content['error']=strtr(qa_lang_html('users/approve_required'), array(
					'^1' => '<a href="'.qa_path_html('account').'">',
					'^2' => '</a>',
				));
			}
		}

	//	Combine various Javascript elements in $qa_content into single array for theme layer

		$script = array('<script>');

		if (isset($qa_content['script_var']))
			foreach ($qa_content['script_var'] as $var => $value)
				$script[] = 'var '.$var.' = '.qa_js($value).';';

		if (isset($qa_content['script_lines']))
			foreach ($qa_content['script_lines'] as $scriptlines) {
				$script[] = '';
				$script = array_merge($script, $scriptlines);
			}

		if (isset($qa_content['focusid']))
			$qa_content['script_onloads'][] = array(
				"var elem = document.getElementById(".qa_js($qa_content['focusid']).");",
				"if (elem) {",
				"\telem.select();",
				"\telem.focus();",
				"}",
			);

		if (isset($qa_content['script_onloads'])) {
			array_push($script,
				'',
				'var qa_oldonload = window.onload;',
				'window.onload = function() {',
				"\tif (typeof qa_oldonload == 'function')",
				"\t\tqa_oldonload();"
			);

			foreach ($qa_content['script_onloads'] as $scriptonload) {
				$script[] = "\t";

				foreach ((array)$scriptonload as $scriptline)
					$script[] = "\t".$scriptline;
			}

			$script[] = '};';
		}

		$script[] = '</script>';

        if (isset($qa_content['script_rel'])) {
            $uniquerel = array_unique($qa_content['script_rel']); // remove any duplicates
            foreach ($uniquerel as $script_rel) {
                $path = qa_html(qa_path_to_root() . $script_rel) . '?v=' . QA_RESOURCE_VERSION;
                $script[] = '<script src="' . $path . '"></script>';
            }
        }

        if (isset($qa_content['script_src'])) {
            $uniquesrc = array_unique($qa_content['script_src']); // remove any duplicates
            foreach ($uniquesrc as $script_src) {
                $line = '<script src="' . qa_html($script_src);
                if (substr($script_src, 0, 4) !== 'http') {
                    $line .= '?v=' . QA_RESOURCE_VERSION;
                }
                $line .= '" defer></script>';

                $script[] = $line;
            }
        }

		$qa_content['script'] = $script;

	//	Load the appropriate theme class and output the page

		$tmpl = substr($qa_template, 0, 7) == 'custom-' ? 'custom' : $qa_template;
		$themeclass = qa_load_theme_class(qa_get_site_theme(), $tmpl, $qa_content, qa_request());
		$themeclass->initialize();

		header('Content-type: '.$qa_content['content_type']);
		http_response_code($qa_content['http_status'] ?? Q2A_Response::STATUS_OK);

		$themeclass->doctype();
		$themeclass->html();
		$themeclass->finish();
	}


	function qa_do_content_stats($qa_content)
/*
	Update any statistics required by the fields in $qa_content, and return true if something was done
*/
	{
		if (isset($qa_content['inc_views_postid'])) {
			require_once QA_INCLUDE_DIR.'db/hotness.php';
			qa_db_hotness_update($qa_content['inc_views_postid'], null, true);
			return true;
		}

		return false;
	}


//	Other functions which might be called from anywhere

	function qa_page_routing()
/*
	Return an array of the default Q2A requests and which qa-page-*.php file implements them
	If the key of an element ends in /, it should be used for any request with that key as its prefix
*/
	{
		if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }

		return array(
			'account' => 'pages/account.php',
			'activity/' => 'pages/activity.php',
			'admin/' => 'pages/admin/admin-default.php',
			'admin/approve' => 'pages/admin/admin-approve.php',
			'admin/categories' => 'pages/admin/admin-categories.php',
			'admin/flagged' => 'pages/admin/admin-flagged.php',
			'admin/hidden' => 'pages/admin/admin-hidden.php',
			'admin/layoutwidgets' => 'pages/admin/admin-widgets.php',
			'admin/moderate' => 'pages/admin/admin-moderate.php',
			'admin/pages' => 'pages/admin/admin-pages.php',
			'admin/plugins' => 'pages/admin/admin-plugins.php',
			'admin/points' => 'pages/admin/admin-points.php',
			'admin/recalc' => 'pages/admin/admin-recalc.php',
			'admin/stats' => 'pages/admin/admin-stats.php',
			'admin/userfields' => 'pages/admin/admin-userfields.php',
			'admin/usertitles' => 'pages/admin/admin-usertitles.php',
			'answers/' => 'pages/answers.php',
			'ask' => 'pages/ask.php',
			'categories/' => 'pages/categories.php',
			'comments/' => 'pages/comments.php',
			'confirm' => 'pages/confirm.php',
			'favorites' => 'pages/favorites.php',
			'favorites/questions' => 'pages/favorites-list.php',
			'favorites/users' => 'pages/favorites-list.php',
			'favorites/tags' => 'pages/favorites-list.php',
			'feedback' => 'pages/feedback.php',
			'forgot' => 'pages/forgot.php',
			'hot/' => 'pages/hot.php',
			'ip/' => 'pages/ip.php',
			'login' => 'pages/login.php',
			'logout' => 'pages/logout.php',
			'messages/' => 'pages/messages.php',
			'message/' => 'pages/message.php',
			'questions/' => 'pages/questions.php',
			'register' => 'pages/register.php',
			'reset' => 'pages/reset.php',
			'search' => 'pages/search.php',
			'tag/' => 'pages/tag.php',
			'tags' => 'pages/tags.php',
			'unanswered/' => 'pages/unanswered.php',
			'unsubscribe' => 'pages/unsubscribe.php',
			'updates' => 'pages/updates.php',
			'user/' => 'pages/user.php',
			'users' => 'pages/users.php',
			'users/blocked' => 'pages/users-blocked.php',
			'users/special' => 'pages/users-special.php',
		);
	}


	function qa_set_template($template)
/*
	Sets the template which should be passed to the theme class, telling it which type of page it's displaying
*/
	{
		global $qa_template;
		$qa_template=$template;
	}


	function qa_content_prepare($voting=false, $categoryids=null)
/*
	Start preparing theme content in global $qa_content variable, with or without $voting support,
	in the context of the categories in $categoryids (if not null)
*/
	{
		if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }

		global $qa_template, $qa_page_error_html;

		if (QA_DEBUG_PERFORMANCE) {
			global $qa_usage;
			$qa_usage->mark('control');
		}

		$request=qa_request();
		$requestlower=qa_request();
		$navpages=qa_db_get_pending_result('navpages');
		$widgets=qa_db_get_pending_result('widgets');

        // accept old-style parameter
		if (isset($categoryids) && !is_array($categoryids)) {
            $categoryids = [$categoryids];
            $lastcategoryid = count($categoryids) ? end($categoryids) : null;
        }
        $charset = 'utf-8';

		$qa_content=array(
			'content_type' => 'text/html; charset='.$charset,
			'charset' => $charset,

			'direction' => qa_opt('site_text_direction'),

			'site_title' => qa_html(qa_opt('site_title')),

			'head_lines' => array(),

			'navigation' => array(
				'user' => array(),

				'main' => array(),

				'footer' => array(
					'feedback' => array(
						'url' => qa_path_html('feedback'),
						'label' => qa_lang_html('main/nav_feedback'),
					),
				),

			),

			'sidebar' => qa_opt('show_custom_sidebar') ? qa_opt('custom_sidebar') : null,

			'sidepanel' => qa_opt('show_custom_sidepanel') ? qa_opt('custom_sidepanel') : null,

			'widgets' => array(),
		);

		// add meta description if we're on the home page
		if ($request === '' || $request === array_search('', qa_get_request_map())) {
			$qa_content['description'] = qa_html(qa_opt('home_description'));
		}

		if (qa_opt('show_custom_in_head'))
			$qa_content['head_lines'][]=qa_opt('custom_in_head');

		if (qa_opt('show_custom_header'))
			$qa_content['body_header']=qa_opt('custom_header');

		if (qa_opt('show_custom_footer'))
			$qa_content['body_footer']=qa_opt('custom_footer');

		if (isset($categoryids))
			$qa_content['categoryids']=$categoryids;

/*		foreach ($navpages as $page)
			if ($page['nav']=='B')
				qa_navigation_add_page($qa_content['navigation']['main'], $page);
*/

		$qa_content['navigation']['main']['nav1']=array(
			'url' => qa_path_html('activity'),
			'label' => '<dfn data-info="Ostatnia aktywność"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav1.png" alt="Najnowsze pytania"/></dfn>',
		);

		$qa_content['navigation']['main']['nav2']=array(
			'url' => qa_path_html('unanswered'),
			'label' => '<dfn data-info="Bez odpowiedzi"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav2.png" alt="Bez odpowiedzi"/></dfn>',
		);

		$qa_content['navigation']['main']['nav3']=array(
			'url' => qa_path_html('ask'),
			'label' => '<dfn data-info="Zadaj pytanie"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav3.png" alt="Zadaj pytanie"/></dfn>',
		);

		$qa_content['navigation']['main']['nav4']=array(
			'url' => qa_path_html('categories'),
			'label' => '<dfn data-info="Kategorie pytań"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav4.png" alt="Kategorie"/></dfn>',
		);

		$qa_content['navigation']['main']['nav5']=array(
			'url' => qa_path_html('tags'),
			'label' => '<dfn data-info="Wszystkie tagi"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav5.png" alt="Tagi"/></dfn>',
		);

		$qa_content['navigation']['main']['nav6']=array(
			'url' => qa_path_html('users'),
			'label' => '<dfn data-info="Ranking punktowy"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav6.png" alt="Zdobyte punkty"/></dfn>',
		);

		$qa_content['navigation']['main']['nav7']=array(
			'url' => qa_path_html('zasluzeni-pasjonaci-hall-of-fame'),
			'label' => '<dfn data-info="Ekipa ninja"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav7.png" alt="Ekipa ninja"/></dfn>',
		);

		$qa_content['navigation']['main']['nav8']=array(
			'url' => qa_path_html('chat-discord'),
			'label' => '<dfn data-info="Chat Discord"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav8.png" alt="IRC"/></dfn>',
		);

		$qa_content['navigation']['main']['nav9']=array(
			'url' => qa_path_html('faq'),
			'label' => '<dfn data-info="Pomoc (FAQ)"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav9.png" alt="FAQ"/></dfn>',
		);

		$qa_content['navigation']['main']['nav10']=array(
			'url' => qa_path_html('regulamin-forum'),
			'label' => '<dfn data-info="Regulamin"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav10.png" alt="Regulamin"/></dfn>',
		);

		$qa_content['navigation']['main']['nav11']=array(
			'url' => qa_path_html('ksiazki-informatyczne-warte-uwagi'),
			'label' => '<dfn data-info="Książki warte uwagi"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav11.png" alt="Książki warte uwagi"/></dfn>',
		);

		// TODO linkownia
		// $qa_content['navigation']['main']['nav12']=array(
		// 	'url' => qa_path_html('linkownia-ciekawe-linki'),
		// 	'label' => '<dfn data-info="Linkownia"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/nav12.png" alt="Linkownia - ciekawe linki"/></dfn>',
		// );

		// Only the 'level' permission error prevents the menu option being shown - others reported on qa-page-ask.php

		if (qa_opt('nav_ask') && (qa_user_maximum_permit_error('permit_post_q')!='level'))
			$qa_content['navigation']['main']['ask']=array(
				'url' => qa_path_html('ask', (qa_using_categories() && strlen($lastcategoryid)) ? array('cat' => $lastcategoryid) : null),
				'label' => qa_lang_html('main/nav_ask'),
			);


		if (
			(qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) ||
			(!qa_user_maximum_permit_error('permit_moderate')) ||
			(!qa_user_maximum_permit_error('permit_hide_show')) ||
			(!qa_user_maximum_permit_error('permit_delete_hidden'))
		)
			$qa_content['navigation']['main']['admin']=array(
				'url' => qa_path_html('admin'),
				'label' => '<dfn data-info="Ustawienia"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/settings.png" alt="Ustawienia"/></dfn>',
				'selected_on' => array('admin/'),
			);


		$qa_content['search']=array(
			'form_tags' => 'method="get" action="'.qa_path_html('search').'"',
			'form_extra' => qa_path_form_html('search'),
			'title' => qa_lang_html('main/search_title'),
			'field_tags' => 'name="q"',
			'button_label' => qa_lang_html('main/search_button'),
		);

		if (!qa_opt('feedback_enabled'))
			unset($qa_content['navigation']['footer']['feedback']);

		foreach ($navpages as $page)
			if ( ($page['nav']=='M') || ($page['nav']=='O') || ($page['nav']=='F') )
				qa_navigation_add_page($qa_content['navigation'][($page['nav']=='F') ? 'footer' : 'main'], $page);

		$regioncodes=array(
			'F' => 'full',
			'M' => 'main',
			'S' => 'side',
		);

		$placecodes=array(
			'T' => 'top',
			'H' => 'high',
			'L' => 'low',
			'B' => 'bottom',
		);

		foreach ($widgets as $widget)
			if (is_numeric(strpos(','.$widget['tags'].',', ','.$qa_template.',')) || is_numeric(strpos(','.$widget['tags'].',', ',all,'))) { // see if it has been selected for display on this template
				$region=@$regioncodes[substr($widget['place'], 0, 1)];
				$place=@$placecodes[substr($widget['place'], 1, 2)];

				if (isset($region) && isset($place)) { // check region/place codes recognized
					$module=qa_load_module('widget', $widget['title']);

					if (
						isset($module) &&
						method_exists($module, 'allow_template') &&
						$module->allow_template((substr($qa_template, 0, 7)=='custom-') ? 'custom' : $qa_template) &&
						method_exists($module, 'allow_region') &&
						$module->allow_region($region) &&
						method_exists($module, 'output_widget')
					)
						$qa_content['widgets'][$region][$place][]=$module; // if module loaded and happy to be displayed here, tell theme about it
				}
			}

		$logoshow=qa_opt('logo_show');
		$logourl=qa_opt('logo_url');
		$logowidth=qa_opt('logo_width');
		$logoheight=qa_opt('logo_height');

		$path = qa_request();
		$qa_content['logo']='<a href="'.qa_path_html('').'" class="qa-logo-link'.(empty($path) ? ' qa-logo-active' : '').'">'.
 		'<dfn data-info="Najnowsze pytania"><img src="'.qa_html(is_numeric(strpos($logourl, '://')) ? $logourl : qa_path_to_root().$logourl).'"'.
 		($logowidth ? (' width="'.$logowidth.'"') : '').($logoheight ? (' height="'.$logoheight.'"') : '').
 		' border="0" alt="'.qa_html(qa_opt('site_title')).'"/></dfn></a>';


		$topath=qa_get('to'); // lets user switch between login and register without losing destination page

		$userlinks=qa_get_login_links(qa_path_to_root(), isset($topath) ? $topath : qa_path($request, $_GET, ''));

		$qa_content['navigation']['user']=array();

		if (qa_is_logged_in()) {
			$qa_content['loggedin']=qa_lang_html_sub_split('main/logged_in_x', QA_FINAL_EXTERNAL_USERS
				? qa_get_logged_in_user_html(qa_get_logged_in_user_cache(), qa_path_to_root(), false)
				: qa_get_one_user_html(qa_get_logged_in_handle(), false)
			);

			$qa_content['navigation']['user']['messages']=array(
				'url' => qa_path_html('messages'),
				'label' => qa_lang_html('main/nav_messages'),
			);

			$qa_content['navigation']['user']['updates']=array(
				'url' => qa_path_html('updates'),
				'label' => qa_lang_html('main/nav_updates'),
			);

			if (!empty($userlinks['logout']))
				$qa_content['navigation']['user']['logout']=array(
					'url' => qa_html(@$userlinks['logout']),
					'label' => qa_lang_html('main/nav_logout'),
				);

			if (!QA_FINAL_EXTERNAL_USERS) {
				$source=qa_get_logged_in_source();

				if (strlen($source)) {
					$loginmodules=qa_load_modules_with('login', 'match_source');

					foreach ($loginmodules as $module)
						if ($module->match_source($source) && method_exists($module, 'logout_html')) {
							ob_start();
							$module->logout_html(qa_path('logout', array(), qa_opt('site_url')));
							$qa_content['navigation']['user']['logout']=array('label' => ob_get_clean());
						}
				}
			}

			$notices=qa_db_get_pending_result('notices');
			foreach ($notices as $notice)
				$qa_content['notices'][]=qa_notice_form($notice['noticeid'], qa_viewer_html($notice['content'], $notice['format']), $notice);

		} else {
			require_once QA_INCLUDE_DIR.'util/string.php';

			if (!QA_FINAL_EXTERNAL_USERS) {
				$loginmodules=qa_load_modules_with('login', 'login_html');

				foreach ($loginmodules as $tryname => $module) {
					ob_start();
					$module->login_html(isset($topath) ? (qa_opt('site_url').$topath) : qa_path($request, $_GET, qa_opt('site_url')), 'menu');
					$label=ob_get_clean();

					if (strlen($label))
						$qa_content['navigation']['user'][implode('-', qa_string_to_words($tryname))]=array('label' => $label);
				}
			}

			if (!empty($userlinks['login']))
				$qa_content['navigation']['user']['login']=array(
					'url' => qa_html(@$userlinks['login']),
					'label' => qa_lang_html('main/nav_login'),
				);

			if (!empty($userlinks['register']))
				$qa_content['navigation']['user']['register']=array(
					'url' => qa_html(@$userlinks['register']),
					'label' => qa_lang_html('main/nav_register'),
				);
		}

		if (QA_FINAL_EXTERNAL_USERS || !qa_is_logged_in()) {
			if (qa_opt('show_notice_visitor') && (!isset($topath)) && (!isset($_COOKIE['qa_noticed'])))
				$qa_content['notices'][]=qa_notice_form('visitor', qa_opt('notice_visitor'));

		} else {
			setcookie('qa_noticed', 1, time()+86400*3650, '/', QA_COOKIE_DOMAIN, QA_COOKIE_SECURE, QA_COOKIE_HTTPONLY); // don't show first-time notice if a user has logged in

			if (qa_opt('show_notice_welcome') && (qa_get_logged_in_flags() & QA_USER_FLAGS_WELCOME_NOTICE) )
				if ( ($requestlower!='confirm') && ($requestlower!='account') ) // let people finish registering in peace
					$qa_content['notices'][]=qa_notice_form('welcome', qa_opt('notice_welcome'));
		}

        $qa_content['script_rel'] = [
            'qa-content/jquery-1.11.3.min.js',
            'qa-content/qa-page.js',
            'qa-content/javascript/imgpre.js'
        ];

		if ($voting)
			$qa_content['error']=@$qa_page_error_html;

		$qa_content['script_var']=array(
			'qa_root' => qa_path_to_root(),
			'qa_request' => $request,
		);

		return $qa_content;
	}


	function qa_get_start()
/*
	Get the start parameter which should be used, as constrained by the setting in qa-config.php
*/
	{
		return min(max(0, (int)qa_get('start')), QA_MAX_LIMIT_START);
	}


	function qa_get_state()
/*
	Get the state parameter which should be used, as set earlier in qa_load_state()
*/
	{
		global $qa_state;
		return $qa_state;
	}


//	Below are the steps that actually execute for this file - all the above are function definitions

	global $qa_usage;

	qa_report_process_stage('init_page');
	qa_db_connect('qa_page_db_fail_handler');

	qa_page_queue_pending();
	qa_load_state();
	qa_check_login_modules();

	if (QA_DEBUG_PERFORMANCE)
		$qa_usage->mark('setup');

	qa_check_page_clicks();

	$qa_content = qa_get_request_content();

	if (is_array($qa_content)) {
		if (QA_DEBUG_PERFORMANCE)
			$qa_usage->mark('view');

		qa_output_content($qa_content);

		if (QA_DEBUG_PERFORMANCE)
			$qa_usage->mark('theme');

		if (qa_do_content_stats($qa_content) && QA_DEBUG_PERFORMANCE)
			$qa_usage->mark('stats');

		if (QA_DEBUG_PERFORMANCE)
			$qa_usage->output();
	}

	qa_db_disconnect();


/*
	Omit PHP closing tag to help avoid accidental output
*/
