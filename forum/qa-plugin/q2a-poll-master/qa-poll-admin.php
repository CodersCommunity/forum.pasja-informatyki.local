<?php
	class qa_poll_admin {

	function option_default($option) {
		
		switch($option) {
		case 'permit_post_poll':
			return QA_PERMIT_USERS;
		case 'permit_vote_poll':
			return QA_PERMIT_USERS;
		case 'poll_enable_subnav':
			return true;


		case 'poll_css':
			return '#qa-poll-div {
	background-color: #D9E3EA;
	border: 1px solid #658296;
	font-size: 12px;
	padding: 10px;
}
#qa-poll-choices-title {
	font-weight:bold;
	margin-bottom:8px;
}
.qa-poll-choice {
	clear:both;
	padding:5px 0 5px 5px;
}
#qa-poll-choices > div:last-child  {
	padding-bottom:0px;
}
#qa-poll-choices > div:first-child  {
	padding-top:0px;
}

.qa-poll-choice-title {
	line-height:12px;
	margin-left:10px;
}
.qa-poll-votes {
	max-width:500px;
	height:10px;
	margin-left:22px; 
	margin:5px 0 5px 22px;
}
.qa-poll-vote-block {
	width:10px;
	height:10px;
	background-color:green;
}
.qa-poll-vote-block-empty {
	width:10px;
	height:10px;
}
.qa-poll-voted-button, .qa-poll-vote-button {
	cursor:pointer;
	width:12px;
	height:12px;
	float:left;
	margin-top: 1px;
}
.qa-poll-disabled-button {
	width:12px;
	height:12px;
	float:left;
	margin-top: 1px;
	background-image:url(^button_vote.png);
}
.qa-poll-voted-button {
	background-image:url(^button_voted.png);
}
.qa-poll-vote-button {
	background-image:url(^button_vote.png);
}
.qa-poll-vote-button:hover, .qa-poll-voted-button:hover {
	background-image:url(^button_voting.png);
}';
		default:
			return null;				
		}
		
	}
		
		function allow_template($template)
		{
			return ($template!='admin');
		}	   
			
		function admin_form(&$qa_content)
		{					   
							
		// Process form input
			
			$ok = null;
			
			if (qa_clicked('poll_save')) {
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
				qa_opt('poll_enable',(bool)qa_post_text('poll_enable'));
				qa_opt('poll_enable_subnav',(bool)qa_post_text('poll_enable_subnav'));
				qa_opt('poll_votes_hide',(bool)qa_post_text('poll_votes_hide'));
				qa_opt('poll_votes_percent',(bool)qa_post_text('poll_votes_percent'));
				qa_opt('poll_vote_change',(bool)qa_post_text('poll_vote_change'));
				qa_opt('poll_update_on_vote',(bool)qa_post_text('poll_update_on_vote'));

				qa_opt('poll_css',qa_post_text('poll_css'));
				$ok = qa_lang('admin/options_saved');
			}
			else if (qa_clicked('poll_reset')) {
				foreach($_POST as $i => $v) {
					$def = $this->option_default($i);
					if($def !== null) qa_opt($i,$def);
				}
				$ok = qa_lang('admin/options_reset');
			}
  
		// Create the form for display
			
			$fields = array();
			
			$fields[] = array(
				'label' => 'Enable polls',
				'tags' => 'NAME="poll_enable"',
				'value' => qa_opt('poll_enable'),
				'type' => 'checkbox',
			);

			$fields[] = array(
				'label' => 'Show poll link in questions sub-nav',
				'tags' => 'NAME="poll_enable_subnav"',
				'value' => qa_opt('poll_enable_subnav'),
				'type' => 'checkbox',
			);

			$fields[] = array(
				'label' => 'Allow users to change their votes',
				'tags' => 'NAME="poll_vote_change"',
				'value' => qa_opt('poll_vote_change'),
				'type' => 'checkbox',
			);

			$fields[] = array(
				'label' => 'Hide poll votes from users who haven\'t voted yet',
				'tags' => 'NAME="poll_votes_hide"',
				'value' => qa_opt('poll_votes_hide'),
				'type' => 'checkbox',
			);

			$fields[] = array(
				'label' => 'Show percent on poll votes',
				'tags' => 'NAME="poll_votes_percent"',
				'value' => qa_opt('poll_votes_percent'),
				'type' => 'checkbox',
			);

			$fields[] = array(
				'label' => 'Poll question stylesheet',
				'tags' => 'NAME="poll_css"',
				'value' => qa_opt('poll_css'),
				'rows' => 20,
				'type' => 'textarea',
				'note' => '^ will be replaced by location of this plugin directory',
			);

			return array(		   
				'ok' => ($ok && !isset($error)) ? $ok : null,
					
				'fields' => $fields,
			 
				'buttons' => array(
					array(
						'label' => qa_lang_html('main/save_button'),
						'tags' => 'NAME="poll_save"',
					),
					array(
						'label' => qa_lang_html('admin/reset_options_button'),
						'tags' => 'NAME="poll_reset"',
					),
				),
			);
		}
	}

