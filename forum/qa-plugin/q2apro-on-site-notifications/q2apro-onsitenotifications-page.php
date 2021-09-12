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

class q2apro_onsitenotifications_page
{
    public function match_request($request)
    {
        return $request === 'eventnotify';
    }

    public function process_request($request)
    {
        $transferString = qa_post_text('ajax');
        $userid = qa_get_logged_in_userid();

        if ($transferString !== 'receiveNotify' || empty($userid)) {
            return;
        }

        $last_visit = qa_db_read_one_value(
            qa_db_query_sub(
                'SELECT UNIX_TIMESTAMP(meta_value) FROM ^usermeta WHERE user_id=# AND meta_key="visited_profile"',
                $userid
            ),
            true
        );
        $maxEvents = qa_opt('q2apro_onsitenotifications_maxevshow'); // maximal events to show

        // query all new events of user
        $event_query = qa_db_query_sub(
            'SELECT e.event, e.userid, BINARY e.params as params, UNIX_TIMESTAMP(e.datetime) AS datetime
            FROM ^eventlog AS e
            WHERE FROM_UNIXTIME(#) <= datetime AND (e.userid=# AND e.event LIKE "in_%")
            OR ((e.event LIKE "u_message" OR e.event LIKE "u_wall_post") AND e.params LIKE "userid=#\t%")
            ORDER BY datetime DESC LIMIT #',
            qa_opt('q2apro_onsitenotifications_maxage'), // events of last x days
            $userid,
            $userid,
            $maxEvents
        );

        $events = [];
        $postids = [];
        $count = 0;
        while (($event = qa_db_read_one_assoc($event_query, true)) !== null) {
            if (preg_match('/postid=([0-9]+)/', $event['params'], $matches) === 1) {
                $event['postid'] = (int)$matches[1];
                $postids[] = (int)$matches[1];
                $events[$matches[1] . '_' . $count++] = $event;
            }

            if ($event['event'] === 'u_message' || $event['event'] === 'u_wall_post') {
                $ustring = $event['params'];

                // get messageid
                if (preg_match('/messageid=([0-9]+)/', $ustring, $matches) === 1) {
                    $event['messageid'] = (int)$matches[1];
                }

                $event['handle'] = qa_userid_to_handle($event['userid']);

                // get message preview by cutting out the string
                $length = $event['event'] === 'u_message' ? 8 : 5;
                $key = $event['event'] === 'u_message' ? 'message=' : 'text=';
                $event['message'] = substr(
                    $ustring,
                    strpos($ustring, $key) + $length,
                    strlen($ustring) - strpos($ustring, $key) + $length
                );

                $events[$matches[1] . '_' . $count++] = $event;
            }
        }

        // get post info, also make sure that post exists
        $posts = null;
        if (!empty($postids)) {
            $post_query = qa_db_read_all_assoc(
                qa_db_query_sub(
                    'SELECT postid, type, parentid, BINARY title as title FROM ^posts
                    WHERE postid IN (' . implode(',', $postids) . ')'
                )
            );
            foreach ($post_query as $post) {
                // save postids as index in array $posts with the $post content
                $posts[(string)$post['postid']] = $post;
            }
        }

        // List all events
        $notifyBoxEvents = '<div id="nfyWrap" class="nfyWrap">
            <div class="nfyTop">' . qa_lang('q2apro_onsitenotifications_lang/my_notifications') . ' <a id="nfyReadClose">' . qa_lang('q2apro_onsitenotifications_lang/close') . ' | × |</a> </div>
            <div class="nfyContainer">
            <div id="nfyContainerInbox">';

        foreach ($events as $postid_string => $event) {
            // $postid_string, e.g. 32_1 (32 is postid, 1 is global event count)

            if (array_key_exists('handle', $event) && $event['handle'] === null) {
                continue;
            }

            $type = $event['event'];
            if ($type == 'u_message') {
                $eventName = qa_lang('q2apro_onsitenotifications_lang/you_received') . ' ';
                $itemIcon = '<div class="nicon nmessage"></div>';
                $activity_url = qa_path_absolute('message') . '/' . $event['handle'];
                $linkTitle = qa_lang('q2apro_onsitenotifications_lang/message_from') . ' ' . $event['handle'];
            } elseif ($type == 'u_wall_post') {
                $eventName = qa_lang('q2apro_onsitenotifications_lang/you_received') . ' ';
                $itemIcon = '<div class="nicon nwallpost"></div>';
                $userhandle = qa_userid_to_handle($userid);
                $activity_url = qa_path_absolute('user') . '/' . $userhandle . '/wall';
                $linkTitle = qa_lang('q2apro_onsitenotifications_lang/wallpost_from') . ' ' . $event['handle'];
            } else {
                // a_post, c_post, q_vote_up, a_vote_up, q_vote_down, a_vote_down

                $postid = preg_replace('/_.*/', '', $postid_string);
                $post = $posts[$postid] ?? null;

                $params = [];
                // explode string to array with values (memo: leave "\t", '\t' will cause errors)
                $paramsa = explode("\t", $event['params']);
                foreach ($paramsa as $param) {
                    $parama = explode('=', $param);
                    if (isset($parama[1])) {
                        $params[$parama[0]] = $parama[1];
                    } else {
                        $params[$param] = $param;
                    }
                }

                $link = '';
                $linkTitle = '';
                $activity_url = '';

                // comment or answer
                if (isset($post) && strpos($event['event'], 'q_') !== 0 && strpos($event['event'], 'in_q_') !== 0) {
                    if (!isset($params['parentid'])) {
                        $params['parentid'] = $post['parentid'];
                    }

                    $parent = qa_db_select_with_pending(
                        qa_db_full_post_selectspec($userid, $params['parentid'])
                    );
                    if ($parent['type'] == 'A') {
                        $parent = qa_db_select_with_pending(
                            qa_db_full_post_selectspec($userid, $parent['parentid'])
                        );
                    }

                    $anchor = qa_anchor(
                        (strpos($event['event'], 'a_') === 0 || strpos($event['event'], 'in_a_') === 0 ? 'A' : 'C'),
                        $params['postid']
                    );
                    $activity_url = qa_path_absolute(
                        qa_q_request($parent['postid'], $parent['title']),
                        null,
                        $anchor
                    );
                    $linkTitle = $parent['title'];
                    $link = '<a target="_blank" href="' . $activity_url . '">' . $parent['title'] . '</a>';
                } elseif (isset($post)) { // question
                    if (!isset($params['title'])) {
                        $params['title'] = $posts[$params['postid']]['title'];
                    }
                    if ($params['title'] !== null) {
                        $qTitle = qa_db_read_one_value(qa_db_query_sub(
                            "SELECT title FROM `^posts` WHERE `postid` = " . $params['postid'] . " LIMIT 1"
                        ), true);
                        if (!isset($qTitle)) {
                            $qTitle = '';
                        }
                        $activity_url = qa_path_absolute(qa_q_request($params['postid'], $qTitle), null, null);
                        $linkTitle = $qTitle;
                        $link = '<a target="_blank" href="' . $activity_url . '">' . $qTitle . '</a>';
                    }
                }

                switch ($type) {
                    case 'in_c_question':
                    case 'in_c_answer':
                    case 'in_c_comment':
                        $eventName = qa_lang('q2apro_onsitenotifications_lang/in_comment');
                        $itemIcon = '<div class="nicon ncomment"></div>';
                        break;
                    case 'in_q_vote_up':
                    case 'in_a_vote_up':
                        $eventName = qa_lang('q2apro_onsitenotifications_lang/in_upvote');
                        $itemIcon = '<div class="nicon nvoteup"></div>';
                        break;
                    case 'in_q_vote_down':
                    case 'in_a_vote_down':
                        $eventName = qa_lang('q2apro_onsitenotifications_lang/in_downvote');
                        $itemIcon = '<div class="nicon nvotedown"></div>';
                        break;
                    case 'in_a_question':
                        $eventName = qa_lang('q2apro_onsitenotifications_lang/in_answer');
                        $itemIcon = '<div class="nicon nanswer"></div>';
                        break;
                    case 'in_a_select':
                        $eventName = qa_lang('q2apro_onsitenotifications_lang/in_bestanswer');
                        $itemIcon = '<div class="nicon nbestanswer"></div>';
                        break;
                    default:
                        continue;
                }
            }

            $eventtime = $event['datetime'];
            $whenhtml = qa_html(qa_time_to_string(qa_opt('db_time') - $eventtime));
            $when = qa_lang_html_sub('main/x_ago', $whenhtml);

            // extra CSS for highlighting new events
            $cssNewEv = '';
            if ($eventtime > $last_visit) {
                $cssNewEv = '-new';
            }

            // if post has been deleted (no url) or hidden (title is null), dont output
            if ($activity_url === '' || $linkTitle === null) {
                continue;
            }

            $notifyBoxEvents .= '<div class="itemBox' . $cssNewEv . '">
                ' . $itemIcon . '
                <div class="nfyItemLine">
                    <p class="nfyWhat">' . $eventName . '
                        <a ' . ($type == 'u_message' || $type == 'u_wall_post' ? 'title="' . htmlspecialchars($event['message'], ENT_QUOTES, "UTF-8") . '" ' : '') . 'href="' . $activity_url . '"' . (qa_opt('q2apro_onsitenotifications_newwindow') ? ' target="_blank"' : '') . '>' . htmlspecialchars($linkTitle, ENT_QUOTES, "UTF-8") . '</a>
                    </p>
                    <p class="nfyTime">' . $when . '</p>
                </div>
            </div>';
        }

        $notifyBoxEvents .= '</div>
            </div>
            <div class="nfyFooter">
                <a href="http://www.q2apro.com/">by q2apro.com</a>
            </div>
        </div>';

        header('Access-Control-Allow-Origin: ' . qa_path(null));
        echo $notifyBoxEvents;

        // update database entry so that all user notifications are seen as read
        qa_db_query_sub(
            'INSERT INTO ^usermeta (user_id,meta_key,meta_value) VALUES(#,$,NOW()) ON DUPLICATE KEY UPDATE meta_value=NOW()',
            $userid,
            'visited_profile'
        );
    }
}
