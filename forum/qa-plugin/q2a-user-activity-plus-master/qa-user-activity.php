<?php
/*
	Question2Answer User Activity Plus plugin
	License: http://www.gnu.org/licenses/gpl.html
*/

class qa_user_activity
{
	private $directory;
	private $urltoroot;
	private $user;
	private $reqmatch = '#user-activity/(questions|answers)/([^/]+)#';
	private $opt_css = 'useract_css';

	function load_module( $directory, $urltoroot )
	{
		$this->directory = $directory;
		$this->urltoroot = $urltoroot;
	}

	function suggest_requests() // for display in admin interface
	{
		return array(
			array(
				'title' => 'User Activity Plus',
				'request' => 'user-activity',
				'nav' => null,
			),
		);
	}

	function match_request( $request )
	{
		return preg_match( $this->reqmatch, $request ) > 0;
	}

	// set admin options
	function admin_form()
	{
		$saved_msg = null;

		// save options
		if ( qa_clicked('user_activity_save') )
		{
			$hidecss = qa_post_text('ua_hidecss') ? '1' : '0';
			qa_opt($this->opt_css, $hidecss);

			$saved_msg = 'Options saved.';
		}

		$css_field = array(
			'type' => 'checkbox',
			'label' => qa_lang_html('useractivity/admin_nocss'),
			'tags' => 'NAME="ua_hidecss"',
			'value' => qa_opt($this->opt_css) === '1',
			'note' => qa_lang_html('useractivity/admin_nocss_note'),
		);

		return array(
			'ok' => $saved_msg,
			'style' => 'wide',

			'fields' => array(
				'css' => $css_field,
			),

			'buttons' => array(
				'save' => array(
					'tags' => 'NAME="user_activity_save"',
					'label' => qa_lang_html('admin/save_options_button'),
					'value' => '1',
				),
			),
		);
	}

	function process_request( $request )
	{
		// get all variables
		preg_match($this->reqmatch, $request, $matches);

		// not escaped by Q2A
		$post_type = qa_html($matches[1]);
		$handle_raw = $matches[2];
		$handle = qa_html($matches[2]);

		// options
		$start = (int) qa_get('start');
		$pagesize = qa_opt('page_size_qs');
		$hidecss = qa_opt($this->opt_css) === '1';

		// regular page request
		$qa_content = qa_content_prepare();
		require_once QA_INCLUDE_DIR.'qa-util-string.php';

		// display CSS for stat summary
		$qa_content['custom'] = $hidecss ? '' : uact_css();

		// list of questions by this user
		if ( $post_type === 'questions' )
		{
			list( $userid, $count, $sel_count ) = $this->questions_stats( $handle_raw );
			// show 404 if no such user
			if ( $userid === null || $userid < 1 )
			{
				header('HTTP/1.0 404 Not Found');
				$qa_content['error'] = qa_lang_html('useractivity/no_user');
				return $qa_content;
			}

			$qa_content['title'] = qa_lang_html_sub('useractivity/questions_by', $handle);

			// get questions
			$columns = 'postid, categoryid, type, LEFT(type,1) AS basetype, INSTR(type,"_HIDDEN")>0 AS hidden, acount, selchildid, closedbyid, upvotes, downvotes, netvotes, hotness, flagcount, BINARY title AS title, BINARY tags AS tags, UNIX_TIMESTAMP(created) AS created';
			$sql_questions = 'SELECT '.$columns.' FROM ^posts WHERE type="Q" AND userid=# ORDER BY created DESC LIMIT #,#';
			$result = qa_db_query_sub( $sql_questions, $userid, $start, $pagesize );
			$questions = qa_db_read_all_assoc($result);

			$htmloptions = qa_post_html_defaults('Q');
			$htmloptions['whoview'] = false;
			$htmloptions['avatarsize'] = 0;

			// html for stats
			$qa_content['custom'] .=
				'<div class="qa-useract-stats">' .
				'	<div class="qa-useract-stat"><span class="qa-useract-count">' . $count . '</span><br>' .
					( $count == 1 ? qa_lang_html('useractivity/question') : qa_lang_html('useractivity/questions') ) . '</div>' .
				'	<div class="qa-useract-stat"><span class="qa-useract-count">' . $sel_count . '</span><br>' .
					( $sel_count == 1 ? qa_lang_html('useractivity/best_answer_given') : qa_lang_html('useractivity/best_answers_given') ) . '</div>' .
				'</div>';

			// create html for question list
			$qa_content['q_list']['qs'] = array();
			foreach ( $questions as $question )
				$qa_content['q_list']['qs'][] = qa_any_to_q_html_fields($question, qa_get_logged_in_userid(), qa_cookie_get(), null, null, $htmloptions);

			// pagination
			$qa_content['page_links'] = qa_html_page_links($request, $start, $pagesize, $count, qa_opt('pages_prev_next'), null);

			return $qa_content;
		}
		else if ( $post_type === 'answers' )
		{
			// userid and answer count
			list( $userid, $count, $sel_count ) = $this->answer_stats( $handle_raw );
			// show 404 if no such user
			if ( $userid === null || $userid < 1 )
			{
				header('HTTP/1.0 404 Not Found');
				$qa_content['error'] = qa_lang_html('useractivity/no_user');
				return $qa_content;
			}

			$qa_content['title'] = qa_lang_html_sub('useractivity/answers_by', $handle);

			$qa_content['custom'] .=
				'<div class="qa-useract-stats">' .
				'	<div class="qa-useract-stat"><span class="qa-useract-count">' . $count . '</span><br>' .
					( $count == 1 ? qa_lang_html('useractivity/answer') : qa_lang_html('useractivity/answers') ) . '</div>' .
				'	<div class="qa-useract-stat"><span class="qa-useract-count">' . $sel_count . '</span><br>' .
					( $sel_count == 1 ? qa_lang_html('useractivity/best_answer_received') : qa_lang_html('useractivity/best_answers_received') ) . '</div>' .
				'</div>';

			$qa_content['custom_2'] = '<div class="qa-useract-wrapper">';

			if ( $count > 0 )
			{
				// get answers
				$columns = 'q.postid AS qpostid, BINARY q.title AS qtitle, q.selchildid AS qselid, q.netvotes AS qvotes, a.postid AS apostid, BINARY a.content AS acontent, a.netvotes AS avotes, UNIX_TIMESTAMP(a.created) AS acreated, a.format';
				$sql_answers =
					'SELECT ' . $columns . ' ' .
					'FROM ^posts a, ^posts q ' .
					'WHERE a.parentid=q.postid AND a.type="A" AND q.type="Q" AND a.userid=# ' .
					'ORDER BY a.created DESC LIMIT #,#';

				$result = qa_db_query_sub( $sql_answers, $userid, $start, $pagesize );
				$answers = qa_db_read_all_assoc($result);

				foreach ( $answers as $ans )
				{
					// to avoid ugly content, convert answer to HTML then strip the tags and remove any URLs
					$ans['acontent'] = qa_viewer_html( $ans['acontent'], $ans['format'] );
					$ans['acontent'] = strip_tags( $ans['acontent'] );
					$ans['acontent'] = preg_replace( '#\shttp://[^\s]+#', '', $ans['acontent'] );
					$ans['acontent'] = qa_substr( $ans['acontent'], 0, 100 );
					if ( strlen($ans['acontent']) == 100 )
						$ans['acontent'] .= '...';

					// question url
					$ans['qurl'] = qa_path_html( qa_q_request( $ans['qpostid'], $ans['qtitle'] ) );
					// answer date
					$ans['acreated'] = qa_when_to_html( $ans['acreated'], qa_opt('show_full_date_days') );
					// html content
					$qa_content['custom_2'] .= $this->answer_tmpl( $ans );
				}
			}

			$qa_content['custom_2'] .= '</div>';

			// pagination
			$qa_content['page_links'] = qa_html_page_links($request, $start, $pagesize, $count, qa_opt('pages_prev_next'), null);

			return $qa_content;
		}
	}


	// userid, question count and selected count
	private function questions_stats($handle)
	{
		$sql_count =
			'SELECT u.userid, count(p.postid) AS qs, count(p.selchildid) AS selected
			 FROM ^users u
			   LEFT JOIN ^posts p ON u.userid=p.userid AND p.type="Q"
			 WHERE u.handle=$';
		$result = qa_db_query_sub($sql_count, $handle);
		$row = qa_db_read_one_assoc($result);

		return array( $row['userid'], $row['qs'], $row['selected'] );
	}

	// userid, answer count and selected count
	private function answer_stats($handle)
	{
		$sql_count =
			'SELECT u.userid, COUNT(a.postid) AS qs, SUM(q.selchildid=a.postid) AS selected
			 FROM ^users u
			   LEFT JOIN ^posts a ON u.userid=a.userid AND a.type="A"
			   LEFT JOIN ^posts q ON a.parentid=q.postid AND q.type="Q"
			 WHERE u.handle=$';
		$result = qa_db_query_sub($sql_count, $handle);
		$row = qa_db_read_one_assoc($result);

		if ( $row['selected'] == null )
			$row['selected'] = 0;

		return array( $row['userid'], $row['qs'], $row['selected'] );
	}

	private function answer_tmpl( $ans )
	{
		$qa_html  = '<div class="qa-q-list-item">';
		$qa_html .= '	<span class="qa-a-count' . ( $ans['qselid'] == $ans['apostid'] ? ' qa-a-count-selected' : '' ) . '">';
		$qa_html .= '		<span class="qa-a-count-data">' . qa_html($ans['avotes']) . '</span>';
		$qa_html .= '		<span class="qa-a-count-pad"> ' . ( $ans['avotes'] == 1 ? qa_lang_html('useractivity/vote') : qa_lang_html('useractivity/votes') ) . '</span>';
		$qa_html .= '	</span>';

		$qa_html .= '	<div class="qa-q-item-main">';
		$qa_html .= '		<div class="qa-q-item-title">';
		$qa_html .= '			<a href="' . $ans['qurl'] . '#a' . $ans['apostid'] . '">' . qa_html($ans['qtitle']) . '</a>';
		$qa_html .= '		</div>';

		$qa_html .= '		<span class="qa-q-item-meta">';
		$qa_html .= '			<span class="qa-q-item-what">' . qa_lang_html('main/answered') . '</span>';
		$qa_html .= '			<span class="qa-q-item-when">';
		$qa_html .= '				<span class="qa-q-item-when-data">' . qa_html($ans['acreated']['data']) . '</span>';
		if ( !empty($ans['acreated']['suffix']) )
			$qa_html .= '				<span class="qa-q-item-when-pad">' . qa_html($ans['acreated']['suffix']) . '</span>';
		$qa_html .= '			</span>';
		$qa_html .= '		</span>';

		$qa_html .= '		<div class="qa-a-snippet">';
		$qa_html .= '			' . qa_html($ans['acontent']);
		$qa_html .= '		</div>';
		$qa_html .= '	</div>';
		$qa_html .= '	<div class="qa-q-item-clear"></div>';
		$qa_html .= '</div>';

		return $qa_html;
	}

}
