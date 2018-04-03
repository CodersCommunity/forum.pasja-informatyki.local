<?php

class q2apro_flagreasons_event
{
    public function process_event($event, $userId, $handle, $cookieId, $params)
    {
        $flagEvents = ['q_unflag', 'a_unflag', 'c_unflag'];
        
        if(in_array($event, $flagEvents)) {
            $postId = $params['postid'];

            qa_db_query_sub('
                DELETE FROM `^flagreasons` 
                WHERE userid = #
                AND postid = #
            ', $userId, $postId);
        }
        $flagEvents2 = ['q_clearflags', 'a_clearflags', 'c_clearflags'];
        
        if(in_array($event, $flagEvents2)) {
            $userLevel = qa_get_logged_in_level();
            if($userLevel >= QA_USER_LEVEL_EDITOR) {
            $postId = $params['postid'];
            }
            qa_db_query_sub('
                DELETE FROM `^flagreasons` 
                WHERE postid = #
            ', $postId);
        }
    }
}
