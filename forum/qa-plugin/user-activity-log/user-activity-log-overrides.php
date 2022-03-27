<?php

function qa_admin_sub_navigation()
{
    $nav = qa_admin_sub_navigation_base();
    if(qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR){
        $nav['user-activity-log'] = [
            'label' => qa_lang_html('user-activity-log/linkLabel'),
            'url' => qa_path('user-activity-log'),
            'selected' => (qa_request_part(1) == 'user-activity-log'), 
        ];
    }

    return $nav;
}