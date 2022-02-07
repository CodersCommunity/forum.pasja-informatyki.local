<?php
if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}
function qa_admin_sub_navigation() {
    $navigation = qa_admin_sub_navigation_base();
    if(qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR) {
        $navigation['user-activity-log'] = array(
            'label' => qa_lang_html('user-activity-log/linkLabel'),
            'url' => qa_path('admin/user-activity-log'),
            'selected' => (qa_request_part(1) == 'user-activity-log')? true : null,
        );
    }
    return $navigation;
}