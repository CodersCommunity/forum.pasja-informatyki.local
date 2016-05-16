<?php

	class qa_html_theme_layer extends qa_html_theme_base {

		var $featured_questions;

		function doctype(){
			
			$featured = qa_opt('featured_questions_list');
			
			if($featured && (!$this->request || $this->request=='questions') && !qa_get('sort') && isset($this->content['q_list'])) {
				$featured = explode(',',$featured);
				foreach($this->content['q_list']['qs'] as $idx => $question) {
					if(in_array($question['raw']['postid'],$featured)) {
						unset($this->content['q_list']['qs'][$idx]);
					}
				}
				foreach($featured as $id) {
					$userid = qa_get_logged_in_userid();

					$selectspec=qa_db_posts_basic_selectspec($userid,true);
					$selectspec['source'].=" JOIN (SELECT postid FROM ^posts WHERE postid=$) y ON ^posts.postid=y.postid";
					$selectspec['arguments'][] = $id;
					
					$question = qa_db_select_with_pending($selectspec);
					$usershtml=qa_userids_handles_html(qa_any_get_userids_handles($question));
					$options=qa_post_html_defaults('Q');

					$q_item = qa_any_to_q_html_fields($question[$id], $userid, qa_cookie_get(), $usershtml, null, $options);
					
					array_unshift($this->content['q_list']['qs'],$q_item);
				}
				$this->featured_questions = count($featured);
			}
			qa_html_theme_base::doctype();
		}
		

	// theme replacement functions

		function q_list($q_list) {
			if(isset($q_list['qs']))
				foreach ($q_list['qs'] as $idx => $q_item)
					if($idx < $this->featured_questions)
						$q_list['qs'][$idx]['classes'] = @$q_list['qs'][$idx]['classes'].' qa-q-list-item-featured';
					else
						break;
			qa_html_theme_base::q_list($q_list);
		}
		function head_custom() {
			$this->output('<style>',qa_opt('featured_question_css'),'</style>');
			qa_html_theme_base::head_custom();
		}


	}

