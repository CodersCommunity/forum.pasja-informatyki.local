<?php

class user_activity_search{
    
    private $searchArr;

    public function match_request($request) 
    {
        $this->userLevel = qa_get_logged_in_level();
        if($this->userLevel >= QA_USER_LEVEL_EDITOR){
            $this->searchArr = $_POST;
            return $request == 'admin/user-activity-log-search';
        }else{
            return false;
        }
    }

    public function process_request($request)
    {
        $qa_content=qa_content_prepare();
        $qa_content['title']= qa_lang_html('user-activity-log/title');
        
        
        $qa_content['navigation']['sub']=qa_admin_sub_navigation();
        return $qa_content;
    }
}