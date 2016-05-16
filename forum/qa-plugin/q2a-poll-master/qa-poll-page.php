<?php

class qa_poll_page {

	function suggest_requests() { // for display in admin interface
		return array(
			array(
				'title' => qa_lang('polls/page_title'),
				'request' => 'polls',
				'nav' => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
			),
		);
	}

	function match_request($request) {
		return $request === 'polls';
	}

	function process_request($request) {
		require_once QA_INCLUDE_DIR . 'qa-db-selects.php';
		require_once QA_INCLUDE_DIR . 'qa-app-format.php';
		require_once QA_INCLUDE_DIR . 'qa-app-q-list.php';

		//	Get list of questions, plus category information

		$nonetitle = qa_lang_html('main/no_questions_found');

		$selectspec = array(
			'columns' => array(
				'^posts.postid', '^posts.categoryid', '^posts.type', 'basetype' => 'LEFT(^posts.type,1)', 'hidden' => "INSTR(^posts.type, '_HIDDEN')>0",
				'^posts.acount', '^posts.selchildid', '^posts.upvotes', '^posts.downvotes', '^posts.netvotes', '^posts.views', '^posts.hotness',
				'^posts.flagcount', 'title' => 'BINARY ^posts.title', 'tags' => 'BINARY ^posts.tags', 'created' => 'UNIX_TIMESTAMP(^posts.created)',
				'^posts.name', 'categoryname' => 'BINARY ^categories.title', 'categorybackpath' => "BINARY ^categories.backpath",
			),
			'arraykey' => 'postid',
			'source' => '^posts LEFT JOIN ^categories ON ^categories.categoryid=^posts.categoryid JOIN ^postmeta ON ^posts.postid=^postmeta.post_id AND ^postmeta.meta_key=$ AND ^postmeta.meta_value>0 AND ^posts.type=$',
			'arguments' => array('is_poll', 'Q'),
		);
		$selectspec['columns']['content'] = '^posts.content';
		$selectspec['columns']['notify'] = '^posts.notify';
		$selectspec['columns']['updated'] = 'UNIX_TIMESTAMP(^posts.updated)';
		$selectspec['columns']['updatetype'] = '^posts.updatetype';
		$selectspec['columns'][] = '^posts.format';
		$selectspec['columns'][] = '^posts.lastuserid';
		$selectspec['columns']['lastip'] = 'INET_NTOA(^posts.lastip)';
		$selectspec['columns'][] = '^posts.parentid';
		$selectspec['columns']['lastviewip'] = 'INET_NTOA(^posts.lastviewip)';

		$selectspec['columns'][] = '^posts.userid';
		$selectspec['columns'][] = '^posts.cookieid';
		$selectspec['columns']['createip'] = 'INET_NTOA(^posts.createip)';
		$selectspec['columns'][] = '^userpoints.points';

		if (!QA_FINAL_EXTERNAL_USERS) {
			$selectspec['columns'][] = '^users.flags';
			$selectspec['columns'][] = '^users.level';
			$selectspec['columns']['email'] = 'BINARY ^users.email';
			$selectspec['columns']['handle'] = 'CONVERT(^users.handle USING BINARY)'; // because of MySQL bug #29205
			$selectspec['columns'][] = '^users.avatarblobid';
			$selectspec['columns'][] = '^users.avatarwidth';
			$selectspec['columns'][] = '^users.avatarheight';
			$selectspec['source'].=' LEFT JOIN ^users ON ^posts.userid=^users.userid';

			$selectspec['columns']['lasthandle'] = 'CONVERT(lastusers.handle USING BINARY)'; // because of MySQL bug #29205
			$selectspec['source'].=' LEFT JOIN ^users AS lastusers ON ^posts.lastuserid=lastusers.userid';
		}
		$selectspec['source'] .= ' LEFT JOIN ^userpoints ON ^posts.userid=^userpoints.userid';
		$selectspec['source'] .= ' ORDER BY ^posts.created DESC';

		$questions = qa_db_select_with_pending($selectspec);
		
		$poczatek = qa_get_start();
		$koniec = qa_get_start()+20;
		if ($koniec>count($questions)) $koniec = count($questions) - $poczatek;
			
		$ankiety = array_slice($questions, $poczatek, $koniec);
		

		global $qa_start;
		//	Prepare and return content for theme

		$qa_content = qa_q_list_page_content(
				$ankiety, // questions
				qa_opt('page_size_qs'), // questions per page
				qa_get_start(), // start offset
				count($questions), // total count
				qa_lang('polls/page_title'), // title if some questions
				$nonetitle, // title if no questions
				null, // categories for navigation
				null, // selected category id
				false, // show question counts in category navigation
				null, // prefix for links in category navigation
				null, // prefix for RSS feed paths
				null, // suggest what to do next
				null // extra parameters for page links
		);

		return $qa_content;
	}

}