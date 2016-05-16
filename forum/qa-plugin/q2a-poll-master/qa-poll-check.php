<?php

	class qa_poll_event {
		function process_event($event, $userid, $handle, $cookieid, $params) {
			if (qa_opt('poll_enable')) {
				switch ($event) {
					case 'q_post':
						if(qa_post_text('is_poll')) {
							qa_db_query_sub(
								'CREATE TABLE IF NOT EXISTS ^postmeta (
								meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
								post_id bigint(20) unsigned NOT NULL,
								meta_key varchar(255) DEFAULT \'\',
								meta_value longtext,
								PRIMARY KEY (meta_id),
								KEY post_id (post_id),
								KEY meta_key (meta_key)
								) ENGINE=MyISAM  DEFAULT CHARSET=utf8'
							);			
							qa_db_query_sub(
								'CREATE TABLE IF NOT EXISTS ^polls (
								id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
								parentid bigint(20) unsigned NOT NULL,
								votes longtext,
								content varchar(255) DEFAULT \'\',
								PRIMARY KEY (id)
								) ENGINE=MyISAM  DEFAULT CHARSET=utf8'
							);			
							qa_db_query_sub(
								'INSERT INTO ^postmeta (post_id,meta_key,meta_value) VALUES (#,$,$)',
								$params['postid'],'is_poll',(qa_post_text('poll_multiple')?'2':'1')
							);

							$c = 0;
							while(isset($_POST['poll_answer_'.(++$c)])) {
								if(!qa_post_text('poll_answer_'.$c)) continue; // empty
								qa_db_query_sub(
									'INSERT INTO ^polls (parentid,content) VALUES (#,$)',
									$params['postid'],qa_post_text('poll_answer_'.$c)
								);								
							}
						}
						break;
					default:
						break;
				}
			}
		}
	}

/*

					// buddypress integration
						if (qa_opt('buddypress_integration_enable')) {
						
							$parent = qa_db_single_select(qa_db_full_post_selectspec(null, $answer['parentid']));
							
							require_once QA_INCLUDE_DIR.'qa-app-users.php';
							
							$publictohandle=qa_get_public_from_userids(array($userid));
							$handle=@$publictohandle[$userid];

							$anchor = qa_anchor('A', $params['postid']);
							$suffix = '<a href="'.qa_path_html(qa_q_request($parent['postid'], $parent['title']), null, qa_opt('site_url'),null,$anchor).'">'.$parent['title'].'</a>';

							$activity_url = qa_path_html(qa_q_request($parent['postid'], $parent['title']), null, qa_opt('site_url'));
							
							$action = '<a href="' . bp_core_get_user_domain($userid) . '" rel="nofollow">'.$handle.'</a> voted in the poll "'.$suffix.'"';

							qa_buddypress_activity_post(
								array(
									'action' => $action,
									'content' => null,
									'primary_link' => $activity_url,
									'component' => 'bp-qa',
									'type' => 'activity_qa',
									'user_id' => $userid,
									'item_id' => null
								)
							);
						}
						
*/					