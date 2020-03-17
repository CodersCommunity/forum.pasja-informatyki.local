<?php

function qa_get_request_content(): ?array
{
    $requestparts = qa_request_parts();
    $firstlower   = strtolower($requestparts[0]);
    $routing      = qa_page_routing();
    $page         = $firstlower . '/';

    // todo: to wiadomo, że jest do poprawy, ale tu dalem koncept tylko
    if (isset($routing[$page]) && $requestparts[0] === 'message') {
        qa_set_template($firstlower !== '' ? $firstlower : 'qa'); // will be changed later
        $qa_content = require QA_INCLUDE_DIR . 'pages/default.php';

        if (isset($qa_content)) {
            qa_set_form_security_key();
        }

        return $qa_content;
    }

    return qa_get_request_content_base();
}

function qa_user_permit_error($permitoption=null, $limitaction=null, $userlevel=null, $checkblocks=true)
{
    if (qa_post_text('domessage')) {
        $toUserId = qa_request_parts()[1] ?? '';
        $loggedIn = qa_get_logged_in_userid();
        
        if (empty($toUserId)) {
            return;
        }
        
        $blockedPrivateMessages = qa_db_query_sub('SELECT `from_user_id`, `to_user_id` FROM ^blockedpw WHERE (from_user_id = # AND to_user_id = #) OR (from_user_id = # AND to_user_id = #)', $loggedIn, $toUserId, $toUserId, $loggedIn);
        $allowedPrivateMessages = qa_opt('allow_private_messages');
        $blockedPrivateMessageBool = 0 != $blockedPrivateMessages->num_rows;
        
        if (checkIfUserIsBlocked($loggedIn, $toUserId)) {
            return 'userblock'; // user is blocked so return missing permissions error
        }
    }
}