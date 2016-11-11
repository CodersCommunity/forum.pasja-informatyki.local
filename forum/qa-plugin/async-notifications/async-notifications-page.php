<?php

class async_notifications_page
{
    public function match_request($request)
    {
        return ($request === 'async-notifications');
    }

    public function process_request($request)
    {
        // code from https://github.com/q2apro/q2apro-on-site-notifications/blob/master/q2apro-onsitenotifications-layer.php#L107
        $userid = qa_get_logged_in_userid();
        if(qa_opt('q2apro_onsitenotifications_enabled') && $userid) {
            $last_visit = qa_db_read_one_value(
                qa_db_query_sub(
                    'SELECT UNIX_TIMESTAMP(meta_value) FROM ^usermeta WHERE user_id=# AND meta_key=$',
                    $userid, 'visited_profile'
                ),
                true
            );
            if (is_null($last_visit)) {
                $last_visit = '1981-03-31 06:25:00';
            }
            $eventcount = qa_db_read_one_value(
                qa_db_query_sub(
                    'SELECT COUNT(event) FROM ^eventlog 
                    WHERE FROM_UNIXTIME(#) <= datetime 
                    AND DATE_SUB(CURDATE(),INTERVAL # DAY) <= datetime 
                    AND (
                    (userid=# AND event LIKE "in_%")
                    OR ((event LIKE "u_message" OR event LIKE "u_wall_post") AND params LIKE "userid=#\t%")
                    )',
                    $last_visit,
                    qa_opt('q2apro_onsitenotifications_maxage'),
                    $userid,
                    $userid
                )
            );

            echo $eventcount;
        }
    }
}