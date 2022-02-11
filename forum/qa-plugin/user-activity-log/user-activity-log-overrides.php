<?php

function qa_admin_sub_navigation(){
    $nav = qa_admin_sub_navigation_base();
    
    $nav['user-activity-log'] = array(
        'label' => qa_lang_html('user-activity-log/linkLabel'),
        'url' => qa_path('admin/user-activity-log'),
        'selected' => (qa_request_part(1) == 'user-activity-log')? true : null, 
    );

    return $nav;
}