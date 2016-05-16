<?php

	function qa_get_permit_options() {
		$permits = qa_get_permit_options_base();
		$permits[] = 'permit_post_poll';
		$permits[] = 'permit_vote_poll';
		$permits[] = 'permit_close_poll';
		$permits[] = 'permit_delete_poll';
		return $permits;
	}
	function qa_get_request_content() {
		$qa_content = qa_get_request_content_base();

		// permissions

		if(isset($qa_content['form_profile']['fields']['permits'])) {

				$ov = $qa_content['form_profile']['fields']['permits']['value'];
				$ov = str_replace('[profile/permit_post_poll]',qa_lang('polls/permit_post_poll'),$ov);
				$ov = str_replace('[profile/permit_vote_poll]',qa_lang('polls/permit_vote_poll'),$ov);
				$ov = str_replace('[profile/permit_close_poll]',qa_lang('polls/permit_close_poll'),$ov);
				$ov = str_replace('[profile/permit_delete_poll]',qa_lang('polls/permit_delete_poll'),$ov);
				$qa_content['form_profile']['fields']['permits']['value'] = $ov;
		}
		return $qa_content;
	}
/*
		Omit PHP closing tag to help avoid accidental output
*/
