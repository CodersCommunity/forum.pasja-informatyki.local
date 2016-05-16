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

	class q2apro_userinfo_page {
		
		var $directory;
		var $urltoroot;
		
		function load_module($directory, $urltoroot)
		{
			$this->directory=$directory;
			$this->urltoroot=$urltoroot;
		}
		
		// for display in admin interface under admin/pages
		function suggest_requests() 
		{	
			return array(
				array(
					'title' => 'userinfo-ajax', // title of page
					'request' => 'userinfo-ajax', // request name
					'nav' => null, // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
				),
			);
		}
		
		// for url query
		function match_request($request)
		{
			if ($request=='userinfo-ajax') {
				return true;
			}

			return false;
		}

		function process_request($request) {
		
			// we received post data, it is the ajax call
			$transferString = qa_post_text('ajax');

			if( $transferString !== null ) {
				
				// sanitize
				$handle = qa_sanitize_html($transferString);
				// get userid
				$userids = qa_handles_to_userids(array($handle));
				$userid = $userids[$handle];
				
				// prevent empty userid
				if(empty($userid)) return;
				
				// check for plugin best-users-per-month by checking if the table exists with some data
				$pluginBUinstalled = false;
				$bestusersTable = qa_db_read_one_value(qa_db_query_sub('SHOW TABLES LIKE "^userscores"'),true);
				if(isset($bestusersTable)) {
					$bestusersTabCount = qa_db_read_one_value(qa_db_query_sub('SELECT COUNT(*) FROM ^userscores'),true);
					$pluginBUinstalled = isset($bestusersTabCount);
					$userpointsMonth = 0;
					// threshold 10
					if($pluginBUinstalled && $bestusersTabCount>10) {
						// get userscore per month if best-users-plugin is enabled
						$queryRecentScores = qa_db_query_sub('SELECT ^userpoints.points - COALESCE(^userscores.points,0) AS mpoints 
									FROM `^userpoints`
									LEFT JOIN `^userscores` on ^userpoints.userid=^userscores.userid 
										AND YEAR(^userscores.date) = YEAR(CURDATE()) 
										AND MONTH(^userscores.date) = MONTH(CURDATE())
									WHERE ^userpoints.userid = #', $userid );
						$userpointsMonth = qa_db_read_one_value($queryRecentScores,true); // get first element from array
					}
				}
				
				// get number of questions and answers
				$queryUserpoints = qa_db_query_sub('SELECT points, qposts, aposts, cposts, aselects, aselecteds, upvoteds, downvoteds, bonus
							FROM `^userpoints`
							WHERE userid = #', $userid );
				$userpointsData = qa_db_read_one_assoc($queryUserpoints,true); // get first element from array

				$userpoints = @$userpointsData['points'];
				$aCount = @$userpointsData['aposts']; // number_format
				$answersBest = @$userpointsData['aselecteds'];
				$qCount = @$userpointsData['qposts'];
				$cCount = @$userpointsData['cposts'];
				$bonusPoints = @$userpointsData['bonus'];
				
				// acceptance rate A
				$acceptanceString = '';
				if($aCount>0) {
					$acceptanceString = ' | ' . number_format( 100 * $answersBest / $aCount, 2, ',', '.') . '%';
				}
				
				// upvoteds
				$urlToPage = substr($this->urltoroot,2); // remove ./ from beginning of URL
				$receivedUpvotes = number_format(@$userpointsData['upvoteds']) . ' <img style=\'vertical-align:bottom;\' src=\''.qa_opt('site_url').$urlToPage.'thumbup.png\' />';

				// downvoteds
				$receivedDownvotes = '';
				if(qa_opt('q2apro_userinfo_show_downvotes')) {
					$urlToPage = substr($this->urltoroot,2); // remove ./ from beginning of URL
					$receivedDownvotes = ' | '.number_format(@$userpointsData['downvoteds']) . ' <img style=\'vertical-align:bottom;transform:rotate(180deg);\' src=\''.qa_opt('site_url').$urlToPage.'thumbup.png\' />';
				}

				// return ajax
				header('Content-Type: text/plain; charset=utf-8');
				
				$uAccountCreated = qa_db_read_one_assoc(
									qa_db_query_sub('SELECT created,avatarblobid FROM `^users`
														WHERE userid = #', $userid ));
				// to unix timestamp
				$accountAge = qa_opt('db_time') - strtotime($uAccountCreated['created']);
				$accountAge = round($accountAge / (60*60*24)); // in days
				
				$avatarURL = '';
				if(isset($uAccountCreated['avatarblobid'])) {
					$avatarURL = '<img style="border-radius:5px;min-height:50px;margin-bottom:10px;" src="?qa=image&qa_blobid='.$uAccountCreated['avatarblobid'].'&qa_size=50" />';					
				}

				$output = '';
				if(qa_opt('q2apro_userinfo_show_avatar')=='1') {
					$output .= $avatarURL;
				}
				$langDays = ($accountAge == 1 ? qa_lang('q2apro_userinfo_lang/day') : qa_lang('q2apro_userinfo_lang/days'));
				$output .= '<p style="line-height:140%;">'.qa_lang('q2apro_userinfo_lang/member_since').' '.$accountAge.' '.$langDays.'<br />';
				$output .= qa_lang('q2apro_userinfo_lang/abbr_q').': '.$qCount.' &nbsp; '
							.qa_lang('q2apro_userinfo_lang/abbr_a').': '.$aCount.' &nbsp; '
							.qa_lang('q2apro_userinfo_lang/abbr_c').': '.$cCount.'<br />';
				$output .= qa_lang('q2apro_userinfo_lang/received').': '.$receivedUpvotes.'</p>';
				
				if($pluginBUinstalled) {
					// number_format($userpointsMonth,0,',','.')
					$output .= qa_lang('q2apro_userinfo_lang/this_month').': '.$userpointsMonth.' '.qa_lang('q2apro_userinfo_lang/points').'<br />';
				}
				$output .= qa_lang('q2apro_userinfo_lang/total').': '.$userpoints.' '.qa_lang('q2apro_userinfo_lang/points').'<br />';
				if(qa_opt('q2apro_userinfo_show_bonuspoints')=='1') {
					$output .= qa_lang('q2apro_userinfo_lang/bonuspoints').': '.$bonusPoints.'<br />';
				}
				$output .= qa_lang('q2apro_userinfo_lang/answers').': '.$aCount.' | '.qa_lang('q2apro_userinfo_lang/best').': '.$answersBest . $acceptanceString;
				
				// output of badges, needs badges plugin
				$output .= (function_exists('qa_badge_plugin_user_widget') ? '<br/>Odznaki: '.qa_badge_plugin_user_widget($handle) : '');
				
				// send back to frontend
				echo $output;
				return;
			} // end POST data

		}
		
	};
	

/*
	Omit PHP closing tag to help avoid accidental output
*/