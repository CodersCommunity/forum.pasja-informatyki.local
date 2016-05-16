<?php
	class qa_comment_voting_admin {

		function option_default($option) {
			
			switch($option) {
				case 'permit_vote_c':
					return qa_opt('permit_vote_a');
				case 'permit_vote_c_points':
					return qa_opt('permit_vote_a_points');
				default:
					return null;
			}
			
		}

		function custom_badges() {
			return array(
				'nice_comment' => array('var'=>2, 'type'=>0),
				'good_comment' => array('var'=>5, 'type'=>1),
				'great_comment' => array('var'=>10, 'type'=>2)
			);
		}
		
		
		function custom_badges_rebuild() {
			$awarded = 0;
			
			$posts = qa_db_query_sub(
				'SELECT userid, postid, netvotes FROM ^posts WHERE type=$ AND netvotes>0',
				'C'
			);
			while ( ($post=qa_db_read_one_assoc($posts,true)) !== null ) {
				$badges = array('nice_comment','good_comment','excellent_comment');
				$awarded += count(qa_badge_award_check($badges,(int)$post['netvotes'],$post['userid'],$post['postid'],2));
			}
			return $awarded;
		}
		
		function allow_template($template)
		{
			return ($template!='admin');
		}	   
			
		function admin_form(&$qa_content)
		{					   
							
		// Process form input
			
			$ok = null;
			
			if (qa_clicked('comment_voting_save')) {
				qa_opt('voting_on_cs',(bool)qa_post_text('voting_on_cs'));
				qa_opt('voting_down_cs',(bool)qa_post_text('voting_down_cs'));
				$ok = 'Settings Saved.';
			}
			
					
		// Create the form for display

			
			$fields = array();
			
			$fields[] = array(
				'label' => 'Enable comment voting',
				'tags' => 'NAME="voting_on_cs"',
				'value' => qa_opt('voting_on_cs'),
				'type' => 'checkbox',
			);
			
			$fields[] = array(
				'label' => 'Enable comment down-voting',
				'tags' => 'NAME="voting_down_cs"',
				'value' => qa_opt('voting_down_cs'),
				'type' => 'checkbox',
			);

			return array(		   
				'ok' => ($ok && !isset($error)) ? $ok : null,
					
				'fields' => $fields,
			 
				'buttons' => array(
					array(
						'label' => 'Save',
						'tags' => 'NAME="comment_voting_save"',
					)
				),
			);
		}
	}

