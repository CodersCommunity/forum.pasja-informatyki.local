<?php

/*
	Plugin Name: On-Site-Notifications
	Plugin URI: http://www.q2apro.com/plugins/on-site-notifications
	Plugin Description: Facebook-like / Stackoverflow-like notifications on your question2answer forum that can replace all email-notifications.
	Plugin Version: → see qa-plugin.php
	Plugin Date: → see qa-plugin.php
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com/
	Plugin License: GPLv3
	Plugin Minimum Question2Answer Version: → see qa-plugin.php
	Plugin Update Check URI: https://raw.githubusercontent.com/q2apro/q2apro-on-site-notifications/master/qa-plugin.php

	This program is free software. You can redistribute and modify it
	under the terms of the GNU General Public License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.gnu.org/licenses/gpl.html

*/

/* The following code originates from q2a plugin "History" by NoahY and has been modified by q2apro.com
 * It is licensed under GPLv3 http://www.gnu.org/licenses/gpl.html
 * Link to plugin file: https://github.com/NoahY/q2a-history/blob/master/qa-history-check.php
 */

class q2apro_history_check
{
    function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if (!qa_opt('event_logger_to_database')) {
            return;
        }

        require_once QA_INCLUDE_DIR . 'qa-app-posts.php';

        switch ($event) {
            case 'a_select':
            case 'q_vote_up':
            case 'a_vote_up':
            case 'q_vote_down':
            case 'a_vote_down':
                $this->handle_standard_event($event, $userid, $cookieid, $params);
                break;
            case 'a_post':
                $this->handle_answer_comment_create($event, $userid, $cookieid, $params);
                break;
            case 'c_post':
                $this->handle_answer_comment_create($event, $userid, $cookieid, $params);
                $this->handle_comments_thread($cookieid, $params);
        }
    }

    private function handle_standard_event($event, $loggedUserId, $cookieId, $params)
    {
        if (strpos($event, 'u_') === 0) {
            $eventUserId = $params['userid'];
        } else {
            $eventUserId = qa_db_read_one_value(
                qa_db_query_sub('SELECT userid FROM ^posts WHERE postid=#', $params['postid']),
                true
            );
        }

        if ($eventUserId != $loggedUserId) {
            $postUserHandle = qa_userid_to_handle($eventUserId);
            $logEvent = 'in_' . $event;
            $paramString = $this->params_to_string($params);

            qa_db_query_sub(
                'INSERT INTO ^eventlog (datetime, ipaddress, userid, handle, cookieid, event, params) ' .
                'VALUES (NOW(), $, $, $, #, $, $)',
                qa_remote_ip_address(), $eventUserId, $postUserHandle, $cookieId, $logEvent, $paramString
            );
        }
    }

    private function handle_answer_comment_create($event, $loggedUserId, $cookieId, $params)
    {
        $parentUserId = qa_db_read_one_value(
            qa_db_query_sub('SELECT userid FROM ^posts WHERE postid=#', $params['parentid']),
            true
        );

        if ($parentUserId != $loggedUserId) {
            $parentUserHandle = qa_userid_to_handle($parentUserId);
            $paramString = $this->params_to_string($params);

            if ($event === 'a_post') {
                $logEvent = 'in_a_question';
            } elseif ($params['parenttype'] === 'Q') {
                $logEvent = 'in_c_question';
            } else {
                $logEvent = 'in_c_answer';
            }

            qa_db_query_sub(
                'INSERT INTO ^eventlog (datetime, ipaddress, userid, handle, cookieid, event, params) ' .
                'VALUES (NOW(), $, $, $, #, $, $)',
                qa_remote_ip_address(), $parentUserId, $parentUserHandle, $cookieId, $logEvent, $paramString
            );
        }
    }

    private function handle_comments_thread($cookieId, $params)
    {
        $postUserId = qa_db_read_one_value(
            qa_db_query_sub('SELECT userid FROM ^posts WHERE postid=#', $params['postid']),
            true
        );
        $parentUserId = qa_db_read_one_value(
            qa_db_query_sub('SELECT userid FROM ^posts WHERE postid=#', $params['parentid']),
            true
        );
        $paramString = $this->params_to_string($params);

        // DISTINCT: if a user has more than 1 comment just select him unique to inform him only once
        $commentsQuery = qa_db_query_sub('SELECT DISTINCT userid FROM `^posts` WHERE `parentid` = #
            AND `type` = "C" AND `userid` IS NOT NULL', $params['parentid']);

        while (($comment = qa_db_read_one_assoc($commentsQuery, true)) !== null) {
            $commentUserId = $comment['userid'];

            // don't inform user that comments, and don't inform user that comments on his own question/answer
            if ($commentUserId != $postUserId && $commentUserId != $parentUserId) {
                $commentUserHandle = qa_userid_to_handle($commentUserId);

                qa_db_query_sub(
                    'INSERT INTO ^eventlog (datetime, ipaddress, userid, handle, cookieid, event, params) ' .
                    'VALUES (NOW(), $, $, $, #, $, $)',
                    qa_remote_ip_address(), $commentUserId, $commentUserHandle, $cookieId, 'in_c_comment', $paramString
                );
            }
        }
    }

    private function params_to_string($params)
    {
        $paramString = '';
        foreach ($params as $key => $value) {
            $paramString .= (strlen($paramString) ? "\t" : '') . $key . '=' . $this->param_value_to_string($value);
        }

        return $paramString;
    }

    private function param_value_to_string($value)
    {
        if (is_array($value)) {
            $text = 'array(' . count($value) . ')';
        } elseif (strlen($value) > 40) {
            $text = substr($value, 0, 38) . '...';
        } else {
            $text = $value;
        }

        return strtr($text, "\t\n\r", '   ');
    }
}
