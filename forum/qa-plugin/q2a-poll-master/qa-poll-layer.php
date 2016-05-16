<?php

	class qa_html_theme_layer extends qa_html_theme_base {

		function doctype(){
			qa_html_theme_base::doctype();
			//qa_error_log($this->content);
			if(qa_post_text('poll_vote')) {
				return;
			}

			if($this->request == 'admin/permissions' && function_exists('qa_register_plugin_phrases') && qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) {
				$permits[] = 'permit_vote_poll';
				$permits[] = 'permit_post_poll';
				$permits[] = 'permit_close_poll';
				$permits[] = 'permit_delete_poll';
				foreach($permits as $optionname) {
					$value = qa_opt($optionname);
					$optionfield=array(
						'id' => $optionname,
						'label' => qa_lang_html('polls/'.$optionname).':',
						'tags' => 'NAME="option_'.$optionname.'" ID="option_'.$optionname.'"',
						'error' => qa_html(@$errors[$optionname]),
					);
					$widest=QA_PERMIT_USERS;
					$narrowest=QA_PERMIT_ADMINS;

					$permitoptions=qa_admin_permit_options($widest, $narrowest, (!QA_FINAL_EXTERNAL_USERS) && qa_opt('confirm_user_emails'));

					if (count($permitoptions)>1)
						qa_optionfield_make_select($optionfield, $permitoptions, $value,
							($value==QA_PERMIT_CONFIRMED) ? QA_PERMIT_USERS : min(array_keys($permitoptions)));
					$this->content['form']['fields'][$optionname]=$optionfield;

					$this->content['form']['fields'][$optionname.'_points']= array(
						'id' => $optionname.'_points',
						'tags' => 'NAME="option_'.$optionname.'_points" ID="option_'.$optionname.'_points"',
						'type'=>'number',
						'value'=>qa_opt($optionname.'_points'),
						'prefix'=>qa_lang_html('admin/users_must_have').'&nbsp;',
						'note'=>qa_lang_html('admin/points')
					);
					$checkboxtodisplay[$optionname.'_points']='(option_'.$optionname.'=='.qa_js(QA_PERMIT_POINTS).') ||(option_'.$optionname.'=='.qa_js(QA_PERMIT_POINTS_CONFIRMED).')';
				}
				qa_set_display_rules($this->content, $checkboxtodisplay);
			}

			if (qa_opt('poll_enable')) {
				global $qa_request;
				if($qa_request == 'polls') {
					$this->content['navigation']['sub'] = array('special'=>1);
				}
				else if($this->template == 'ask' && !qa_user_permit_error('permit_post_q') && !qa_opt('site_maintenance') && qa_permit_check('permit_post_poll')) {
					$this->content['form']['tags'] .= ' onSubmit="pollSubmit(event)"';
					$this->content['form']['fields'][] = array(
						'label' => qa_lang('polls/checkbox_text'),
						'tags' => 'NAME="is_poll" ID="is_poll" onclick="jQuery(\'#qa-poll-ask-div\').toggle()"',
						'type' => 'checkbox',
					);
					$this->content['form']['fields'][] = array(
						'note' => '<div id="qa-poll-ask-div" style="display:none"><p class="qa-form-tall-label"><input type="checkbox" name="poll_multiple">'.qa_lang('polls/multiple_text').'</p><p class="qa-form-tall-label">'.qa_lang('polls/answers_text').'</p><input type="input" class="qa-poll-answer-text" class="qa-poll-answer-text" name="poll_answer_1" id="poll_answer_1">&nbsp;<input type="button" class="qa-poll-answer-add" value="+" onclick="addPollAnswer(poll_answer_index)"></div>',
						'type' => 'static',
					);
				}
				$poll_array = qa_db_read_all_assoc(
					qa_db_query_sub(
						'SELECT * FROM ^postmeta WHERE meta_key=$',
						'is_poll'
					)
				);
				foreach($poll_array as $q) {
					$poll[(int)$q['post_id']] = $q['meta_value'];
				}
				if(isset($this->content['q_view'])) {

					$qid = $this->content['q_view']['raw']['postid'];
					$author = $this->content['q_view']['raw']['userid'];

					if(isset($poll[$qid])) { // is a poll

						$this->poll = $poll[$qid];

						if(qa_post_text('poll_delete') && (!qa_user_permit_error('permit_delete_poll') || qa_get_logged_in_userid() == $author)) {
							$this->deletePoll($qid);
							$this->content['error'] = 'Ankieta usunięta.';
							return;
						}
						else if (qa_post_text('poll_close') && (!qa_user_permit_error('permit_close_poll') || qa_get_logged_in_userid() == $author)) {
							$this->closePoll($qid);
						}
						else if (qa_post_text('poll_reopen') && (!qa_user_permit_error('permit_close_poll') || qa_get_logged_in_userid() == $author)) {
							$this->reopenPoll($qid);
						}


					// add post elements

						// title

						$this->content['title'] .= ' '.qa_lang('polls/question_title');

						// poll div

						$this->content['q_view']['content'] = @$this->content['q_view']['content'].'<div id="qa-poll-div">'.$this->getPollDiv($qid,qa_get_logged_in_userid()).'</div>';

						// css class

						$this->content['q_view']['main_form_tags'] = @$this->content['q_view']['main_form_tags'].' class="qa-poll"';
					}
				}
				if(isset($this->content['q_list'])) {
					foreach($this->content['q_list']['qs'] as $idx => $question) {

						if(isset($poll[$question['raw']['postid']])) {
							$this->content['q_list']['qs'][$idx]['title'] .= ' '.qa_lang('polls/question_title');
						}
					}
				}
			}
		}

		function html() {
			if(qa_post_text('ajax_poll_id')) {
				$this->output_raw($this->getPollDiv((int)qa_post_text('ajax_poll_id'),(int)qa_post_text('ajax_poll_voter'),qa_post_text('ajax_poll_vote'),qa_post_text('ajax_poll_cancel')));
				return;
			}
			qa_html_theme_base::html();
		}

		function head_custom() {
			if(qa_opt('poll_enable')) {
				if($this->template == 'ask') {
					$this->output_raw('<script>
	var poll_answer_index = 2;
	jQuery("document").ready(function(){jQuery("#is_poll").removeAttr("checked")});
	function addPollAnswer(idx) {
		jQuery("#qa-poll-ask-div").append(\'<br/><input type="input" class="qa-poll-answer-text" name="poll_answer_\'+idx+\'" id="poll_answer_\'+idx+\'">&nbsp;<input type="button" class="qa-poll-answer-add" value="+" onclick="addPollAnswer(poll_answer_index)">\');
		poll_answer_index++;
	}
	function pollSubmit(e) {
		if(jQuery("#is_poll").attr("checked")) {
			var idx = 0, count = 0;
			while(jQuery("#poll_answer_"+(++idx)).length) {
				if(jQuery("#poll_answer_"+idx).val().length) {
					count++;
				}
				if (count > 1) return true;
			}
			e.preventDefault();
			alert("'.qa_lang('polls/choice_count_error').'");
			return false;
		}
	}
</script>');
				}
				else if($this->template == 'question' && @$this->poll && !qa_user_permit_error('permit_post_q')) {
					$this->output('<style>',str_replace('^',QA_HTML_THEME_LAYER_URLTOROOT,qa_opt('poll_css')),'</style>');
					if(qa_permit_check('permit_vote_poll'))
						$this->output_raw("<script>
function pollVote(qid,uid,vid,cancel) {
	var dataString = 'ajax_poll_id='+qid+'&ajax_poll_voter='+uid+'&ajax_poll_vote='+vid+(cancel?'&ajax_poll_cancel='+cancel:'');
	jQuery.ajax({
	  type: 'POST',
	  url: '".qa_self_html()."',
	  data: dataString,
	  success: function(data) {
		if(/^[\\t\\n ]*###/.exec(data)) {
			var error = data.replace(/^[\\t\\n ]*### */,'');
			window.alert(error);
		}
		else {
				jQuery('#qa-poll-div').html(data);
		}
	  }
	});
}
</script>");
				}
			}
			qa_html_theme_base::head_custom();
		}

		function nav_list($navigation, $class, $level=null)
		{
			if($class == 'nav-sub' && in_array($this->template, array('plugin','questions')) && qa_opt('poll_enable') && qa_opt('poll_enable_subnav')) {
				if($this->request != 'logins') {
					$navigation['polls'] = array(
						  'label' => 'Pierwszy raz tutaj?',
						  'url' => qa_path_html('./faq'),
					);
				}
				if($this->request == 'polls') {
					unset($navigation['special']);
					$newnav = qa_qs_sub_navigation(null,null);
					$navigation = array_merge($newnav, $navigation);
					unset($navigation['recent']['selected']);
					$navigation['polls']['selected'] = false;
				}
			}
			if(count($navigation) > 1 || $class != 'nav-sub') qa_html_theme_base::nav_list($navigation, $class, $level=null);
		}

	// worker

		function getPollDiv($qid,$uid,$vid=null,$cancel=false) {

			$author = $this->content['q_view']['raw']['userid'];

			if(!$this->poll) {
				$this->poll = qa_db_read_one_value(
					qa_db_query_sub(
						"SELECT meta_value FROM ^postmeta WHERE post_id=# AND meta_key='is_poll'",
						$qid
					),
					true
				);
			}

			$answers = qa_db_read_all_assoc(
				qa_db_query_sub(
					'SELECT BINARY content as content, votes, id FROM ^polls WHERE parentid=#',
					$qid
				)
			);

			$voted = false;

			if($this->poll != 2) {
				foreach ($answers as $idx => $answer) {
					$votes = explode(',',$answer['votes']);
					if(in_array($uid,$votes)) {
						$voted = true;
						break;
					}
				}
			}

			// do voting

			if($vid && $uid && qa_permit_check('permit_vote_poll') && $this->poll < 9 && (qa_opt('poll_vote_change') || !$voted || $this->poll == 2)) { // not closed, note voted or can change vote
				$vid = (int)$vid;
				foreach ($answers as $idx => $answer) {
					$votes = explode(',',$answer['votes']);

					if($answer['id'] == $vid && !$cancel) {

						if(in_array($uid,$votes)) return '### you\'ve already voted, cheater!';
						$answers[$idx]['votes'] = ($answers[$idx]['votes']?$answers[$idx]['votes'].',':'').$uid;
						qa_db_query_sub(
							'UPDATE ^polls SET votes=$ WHERE id=#',
							$answers[$idx]['votes'], $vid
						);
					}
					else if(in_array($uid,$votes) && ($this->poll != 2 || ($cancel && $answer['id'] == $vid))) { // unvoting
						foreach($votes as $i => $vote) {
							if($uid == $vote) {
								unset($votes[$i]);
								break;
							}
						}

						$answers[$idx]['votes'] = implode(',',$votes);
						qa_db_query_sub(
							'UPDATE ^polls SET votes=$ WHERE id=#',
							$answers[$idx]['votes'], $answer['id']
						);
						if($this->poll != 2) // cancel voted on unvoting
							$voted = in_array($uid,$votes);
					}
				}
			}



			if(empty($answers)) {
				$outNoAnswers = '';
				if(!qa_user_permit_error('permit_delete_poll') || qa_get_logged_in_userid() == $author)
					$outNoAnswers .= '<input type="submit" class="qa-poll-button" title="'.qa_lang('polls/delete_poll_hover').'" value="'.qa_lang('polls/delete_poll').'" name="poll_delete">';
				return '<div>Brak odpowiedzi!</div>'.$outNoAnswers;
			}

			$out = '<div id="qa-poll-choices-title">'.qa_lang('polls/answers_text').'</div><div id="qa-poll-choices">';

			// check if voted

			$allow = true;

			$totalvotes = 0;

			foreach ($answers as $idx => $answer) {
				if(!$answer['votes']) $votes = array();
				else $votes = explode(',',$answer['votes']);

				$totalvotes += count($votes);

				if(!$uid || !qa_permit_check('permit_vote_poll') || $this->poll > 9 || ($voted && !qa_opt('poll_vote_change') && !in_array($uid,$votes) && $this->poll != 2)) {
					$answers[$idx]['vote'] = '<div class="qa-poll-disabled-button" title="'.qa_html(qa_lang('polls/disabled_button')).'"></div>';
				}
				else if($voted && !qa_opt('poll_vote_change') && $this->poll != 2) { // voted for this one, can't change, not multiple
					$answers[$idx]['vote'] = '<div class="qa-poll-voted-button" title="'.qa_html(qa_lang('polls/voted_button')).'" onclick="alert(\''.qa_lang('polls/cannot_change').'\')"></div>';
				}
				else if(!in_array($uid,$votes))
					$answers[$idx]['vote'] = '<div class="qa-poll-vote-button" title="'.qa_html(qa_lang('polls/vote_button')).'" onclick="pollVote('.$qid.',\''.$uid.'\','.$answer['id'].')"></div>';
				else {
					$answers[$idx]['vote'] = '<div class="qa-poll-voted-button" title="'.qa_html(qa_lang('polls/voted_button')).'" onclick="pollVote('.$qid.',\''.$uid.'\','.$answer['id'].',1)"></div>';

					$voted = true;
				}
			}

			if ($totalvotes==0) $totalvotes=1;

			foreach ($answers as $answer) {


				if(!$answer['votes']) $votes = array();
				else $votes = explode(',',$answer['votes']);

				$out .= '<div class="qa-poll-choice">'.@$answer['vote'].'<span class="qa-poll-choice-title">'.qa_html($answer['content']).'</span>';

				if(!qa_opt('poll_votes_hide') || $voted || qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) {
					$out .= ' ('.(count($votes)==1?qa_lang('main/1_vote'):str_replace('^',count($votes),qa_lang('main/x_votes'))).(qa_opt('poll_votes_percent') && !empty($votes) > 0? ', '.round(100*count($votes)/$totalvotes).'%' : '').')';
				}

				$out .= '<table class="qa-poll-votes"><tr title="'.(count($votes)==1?qa_lang('main/1_vote'):str_replace('^',count($votes),qa_lang('main/x_votes')));
				if (!empty($answer['votes']) && qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN) {
					$out .= ': ';
					$users = qa_db_read_all_assoc(
						qa_db_query_sub(
							'SELECT handle FROM ^users WHERE userId IN ('.$answer['votes'].') ORDER BY handle'
						)
					);
					foreach ($users as $user) {
						$out .= $user['handle'].', ';
					}
				}
				$out .= '">';

				for($i=1; $i<=100; $i++)
				{
					if($i<=round(100*count($votes)/$totalvotes))
					$out .= '<td class="qa-poll-vote-block"></td>';
					else $out .= '<td class="qa-poll-vote-block-empty"></td>';
				}

				$out .= '</tr></table></div>';
			}
			if($this->poll > 9) { // poll closed
				$out .= '<div class="qa-poll-closed">'.qa_lang('polls/poll_closed').'</div>';
				if(!qa_user_permit_error('permit_close_poll') || qa_get_logged_in_userid() == $author)
					$out .= '<input type="submit" class="qa-poll-button" title="'.qa_lang('polls/reopen_poll_hover').'" value="'.qa_lang('polls/reopen_poll').'" name="poll_reopen">';
			}
			else if(!qa_user_permit_error('permit_close_poll') || qa_get_logged_in_userid() == $author)
				$out .= '<input type="submit" class="qa-poll-button" title="'.qa_lang('polls/close_poll_hover').'" value="'.qa_lang('polls/close_poll').'" name="poll_close">';
			if(!qa_user_permit_error('permit_delete_poll') || qa_get_logged_in_userid() == $author)
				$out .= '<input type="submit" class="qa-poll-button" title="'.qa_lang('polls/delete_poll_hover').'" value="'.qa_lang('polls/delete_poll').'" name="poll_delete">';

			$out .= '</div>';

			return $out;
		}

		function deletePoll($qid) {
			qa_db_query_sub(
				'DELETE FROM ^postmeta WHERE post_id=# AND meta_key=$',
				$qid,'is_poll'
			);
		}
		function closePoll($qid) {
			qa_db_query_sub(
				'UPDATE ^postmeta SET meta_value=# WHERE post_id=# AND meta_key=$',
				10+$this->poll,$qid,'is_poll'
			);
			$this->poll = $this->poll+10;
		}
		function reopenPoll($qid) {
			qa_db_query_sub(
				'UPDATE ^postmeta SET meta_value=# WHERE post_id=# AND meta_key=$',
				$this->poll-10,$qid,'is_poll'
			);
			$this->poll = $this->poll-10;
		}

	}
