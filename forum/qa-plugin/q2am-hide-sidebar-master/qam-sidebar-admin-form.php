<?php
/*
*	Q2AM Hide Sidebar
*	
*	Adds required options fields to the plugin options
*	
*	@author			Q2A Market
*	@category		Plugin
*	@Version 		1.2
*	@URL			http://www.q2amarket.com
*	
*	@Q2A Version	1.5.2
*
*	Modifing this file will affect plugin options
*   you can add additional field as per your need
*/

class qam_sidebar_admin_form
{

	function admin_form(&$qa_content)
	{
		$saved=false;
		
		if (qa_clicked('q2am_sidebar_save_button')) {

			qa_opt('q2am_sidebar_qa', (bool)qa_post_text('q2am_sidebar_qa'));
            qa_opt('q2am_custom_home_content', (bool)qa_post_text('q2am_custom_home_content'));
			qa_opt('q2am_sidebar_activity', (bool)qa_post_text('q2am_sidebar_activity'));
			qa_opt('q2am_sidebar_questions', (bool)qa_post_text('q2am_sidebar_questions'));
			qa_opt('q2am_sidebar_question', (bool)qa_post_text('q2am_sidebar_question'));
			qa_opt('q2am_sidebar_hot', (bool)qa_post_text('q2am_sidebar_hot'));
			qa_opt('q2am_sidebar_unanswered', (bool)qa_post_text('q2am_sidebar_unanswered'));
			qa_opt('q2am_sidebar_tags', (bool)qa_post_text('q2am_sidebar_tags'));
			qa_opt('q2am_sidebar_categories', (bool)qa_post_text('q2am_sidebar_categories'));
			qa_opt('q2am_sidebar_users', (bool)qa_post_text('q2am_sidebar_users'));
			qa_opt('q2am_sidebar_admin', (bool)qa_post_text('q2am_sidebar_admin'));
			qa_opt('q2am_sidebar_custom', (bool)qa_post_text('q2am_sidebar_custom'));
			qa_opt('q2am_sidebar_ask', (bool)qa_post_text('q2am_sidebar_ask'));
            qa_opt('q2am_sidebar_custom_pages', qa_post_text('q2am_sidebar_custom_pages'));

			$saved=true;
		}
		
		return array(
			'ok' => $saved ? 'Q2AM Sidebar settings saved' : null,
			
			'fields' => array(

				array(
					'label' => 'Home',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_qa'),
					'tags' => 'NAME="q2am_sidebar_qa" ID="q2am_sidebar_qa"',
				),
                
				array(
					'label' => 'Custom Home Content',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_custom_home_content'),
					'tags' => 'NAME="q2am_custom_home_content" ID="q2am_custom_home_content"',
				),                

				array(
					'label' => 'All Activity',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_activity'),
					'tags' => 'NAME="q2am_sidebar_activity" ID="q2am_sidebar_activity"',
				),

				array(
					'label' => 'Questions',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_questions'),
					'tags' => 'NAME="q2am_sidebar_questions" ID="q2am_sidebar_questions"',
				),

				array(
					'label' => 'Question',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_question'),
					'tags' => 'NAME="q2am_sidebar_question" ID="q2am_sidebar_question"',
				),

				array(
					'label' => 'Hot',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_hot'),
					'tags' => 'NAME="q2am_sidebar_hot" ID="q2am_sidebar_hot"',
				),

				array(
					'label' => 'Unanswered',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_unanswered'),
					'tags' => 'NAME="q2am_sidebar_unanswered" ID="q2am_sidebar_unanswered"',
				),

				array(
					'label' => 'Tags',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_tags'),
					'tags' => 'NAME="q2am_sidebar_tags" ID="q2am_sidebar_tags"',
				),

				array(
					'label' => 'Categories',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_categories'),
					'tags' => 'NAME="q2am_sidebar_categories" ID="q2am_sidebar_categories"',
				),

				array(
					'label' => 'Users',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_users'),
					'tags' => 'NAME="q2am_sidebar_users" ID="q2am_sidebar_users"',
				),

				array(
					'label' => 'Admin',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_admin'),
					'tags' => 'NAME="q2am_sidebar_admin" ID="q2am_sidebar_admin"',
				),

				array(
					'label' => 'Ask a Question',
					'type' => 'checkbox',
					'value' => qa_opt('q2am_sidebar_ask'),
					'tags' => 'NAME="q2am_sidebar_ask" ID="q2am_sidebar_ask"',
				),                
                
				array(
					'label' => 'Custom Pages URL Slug (e.g. featured-question)',
					'type' => 'textarea',
					'value' => qa_opt('q2am_sidebar_custom_pages'),
					'tags' => 'NAME="q2am_sidebar_custom_pages" ID="q2am_sidebar_custom_pages" PLACEHOLDER="new line for each custom page slug"',
                    'rows' => 10,
				),                                
                                               
				
			),
			
			'buttons' => array(
				array(
					'label' => 'Save Changes',
					'tags' => 'NAME="q2am_sidebar_save_button"',
				),
			),
		);
	}

}

/*
	Omit PHP closing tag to help avoid accidental output
*/	