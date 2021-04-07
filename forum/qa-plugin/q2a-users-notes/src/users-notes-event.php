<?php

class users_notes_event
{
    function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if ($event === 'u_block' || $event === 'u_unblock') {
            $sql = 'INSERT INTO ^usersnotes (id_user, handle, added_id_user, added_handle, event) VALUES (#, #, #, #, #)';
            qa_db_query_sub($sql, $params['userid'], $params['handle'], qa_get_logged_in_userid(), qa_get_logged_in_handle(), $event);
        }
    }
}
