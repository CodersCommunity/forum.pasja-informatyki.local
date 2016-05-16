<?php
/*
*	Q2AM Hide Sidebar
*	
*	Adds element to the template file
*	
*	@author			Q2A Market
*	@category		Plugin
*	@Version 		1.2
*	@URL			http://www.q2amarket.com
*	
*	@Q2A Version	1.5.2
*
*	Modify this file as per your need, especially if you need to change css
*/

class qa_html_theme_layer extends qa_html_theme_base {
    
    function qa_hide_on_custom()
    {
        /**
         * additional function to process custom pages
         * url slugs and returning one value of the
         * active page and than passing this to the
         * hide sidebar function below
         */
        
        $custom_pages = qa_opt('q2am_sidebar_custom_pages');
        
        if (isset($custom_pages) && !empty($custom_pages)) {
            
        $custom_pages = explode("\n", str_replace(' ', '', $custom_pages));
          
            foreach($custom_pages as $custom_page)
            {
                if(qa_request() == $custom_page)
                    return $custom_page;
            }          
        }        
    }    

	function head_custom()
	{
        $hascustomhome=qa_has_custom_home();
		qa_html_theme_base:: head_custom();
		
		if(
			((qa_opt('q2am_sidebar_qa')) && ($this->template == 'qa') ||
			(qa_opt('q2am_sidebar_activity')) && ($this->template == 'activity') ||
			(qa_opt('q2am_sidebar_questions')) && ($this->template == 'questions') ||
			(qa_opt('q2am_sidebar_question')) && ($this->template == 'question') ||
			(qa_opt('q2am_sidebar_hot')) && ($this->template == 'hot') ||
			(qa_opt('q2am_sidebar_unanswered')) && ($this->template == 'unanswered') ||
			(qa_opt('q2am_sidebar_tags')) && ($this->template == 'tags') ||
			(qa_opt('q2am_sidebar_categories')) && ($this->template == 'categories') ||
			(qa_opt('q2am_sidebar_users')) && ($this->template == 'users') ||
			(qa_opt('q2am_sidebar_admin')) && ($this->template == 'admin') ||
			(qa_opt('q2am_sidebar_ask')) && ($this->template == 'ask')) || 
            (qa_request() == $this->qa_hide_on_custom() && qa_request() !== '') ||
            (qa_opt('q2am_custom_home_content') && qa_opt($hascustomhome) && qa_request() == '')
            
                                 
		)
			$this->output('<style type="text/css">
				.qa-main{
					width:100%;
					padding-right:10px;
					-moz-box-sizing:border-box;
					-webkit-box-sizing:border-box;
					box-sizing:border-box;
				}
				.qa-q-item-main{width:738px}
			</style>');
		
	}

	function sidepanel()
	{
	   $hascustomhome=qa_has_custom_home();
       
		if(!(
			(qa_opt('q2am_sidebar_qa')) && ($this->template == 'qa') ||
			(qa_opt('q2am_sidebar_activity')) && ($this->template == 'activity') ||
			(qa_opt('q2am_sidebar_questions')) && ($this->template == 'questions') ||
			(qa_opt('q2am_sidebar_question')) && ($this->template == 'question') ||
			(qa_opt('q2am_sidebar_hot')) && ($this->template == 'hot') ||
			(qa_opt('q2am_sidebar_unanswered')) && ($this->template == 'unanswered') ||
			(qa_opt('q2am_sidebar_tags')) && ($this->template == 'tags') ||
			(qa_opt('q2am_sidebar_categories')) && ($this->template == 'categories') ||
			(qa_opt('q2am_sidebar_users')) && ($this->template == 'users') ||
			(qa_opt('q2am_sidebar_admin')) && ($this->template == 'admin') ||
			(qa_opt('q2am_sidebar_ask')) && ($this->template == 'ask') ||
            (qa_request() == $this->qa_hide_on_custom() && qa_request() !== '') ||
            (qa_opt('q2am_custom_home_content') && qa_opt($hascustomhome) && qa_request() == '')
		  )        
        )   
			qa_html_theme_base::sidepanel();

	}

}



/*
	Omit PHP closing tag to help avoid accidental output
*/