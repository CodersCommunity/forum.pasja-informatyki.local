<?php
/*
    Question2Answer by Gideon Greenspan and contributors
    http://www.question2answer.org/

    File: qa-include/qa-page-users.php
    Description: Controller for top scoring users page


    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    More about this license: http://www.question2answer.org/license.php
*/

    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
        header('Location: ../');
        exit;
    }

    require_once QA_INCLUDE_DIR.'qa-page.php';
    require_once QA_INCLUDE_DIR.'app/users.php';
    require_once QA_INCLUDE_DIR.'db/users.php';
    require_once QA_INCLUDE_DIR.'db/selects.php';

    $qa_content = qa_content_prepare();
    $qa_content['title'] = 'Lista zablokowanych użytkowników';
    
    $userId = qa_get_logged_in_userid();
    if (empty($userId)) {
        $qa_content['error'] = 'Musisz być zalogowany aby wykonać tą czynność';
        
        return $qa_content;
    }
    
    if (qa_post_text('userid')) {
        qa_db_query_sub('DELETE FROM `^blockedpw` WHERE `fromUserId` = # AND `toUserId` = #', $userId, (int) qa_post_text('userid'));
    }
    
    $blockedUsers = qa_db_select_with_pending([
                'columns' => ['^users.userid', '^users.handle',  '^users.flags', '^users.email', 'avatarblobid' => 'BINARY avatarblobid', '^users.avatarwidth', '^users.avatarheight'],
                'source' => '^users JOIN (SELECT toUserId FROM ^blockedpw WHERE fromUserId = #) s ON ^users.userid=s.toUserId',//'^users JOIN (SELECT userid FROM ^userpoints ORDER BY points DESC LIMIT #,#) y ON ^users.userid=y.userid JOIN ^userpoints ON ^users.userid=^userpoints.userid',
                'arguments' => [$userId],
                'arraykey' => 'userid',
            ]);
            
    $pageContent = '';
    
    if (0 === count($blockedUsers)) {
        $pageContent = 'Nikogo jeszcze nie zablokowałeś, ale gdy zajdzie taka potrzeba, nie wahaj się';
    } else {
    
        $qa_content['ranking'] = [
            'items' => [],
            'rows' => 2,
            'type' => 'users'
        ];
        
        $userHtml = qa_userids_handles_html($blockedUsers);
            
        foreach ($blockedUsers as $user) {
            $avatar = qa_get_user_avatar_html($user['flags'], $user['email'], $user['handle'], $user['avatarblobid'], $user['avatarwidth'], $user['avatarheight'], qa_opt('avatar_users_size'), true);
            $label = $user['handle'];
            $score = ((qa_db_query_sub('SELECT `points` FROM ^userpoints WHERE userid = #', $user['userid']))->fetch_assoc())['points'];
            $raw = $label;
            
            $qa_content['ranking']['items'][] = [
                'avatar' => $avatar,
                'label' => $userHtml[$user['userid']],
                'score' => '<form method="post" style="margin: 0; padding: 0;"><input type="hidden" style="display: none;" name="userid" value="' . $user['userid'] . '"><input type="submit" style="margin: 0; cursor: pointer; background-color: rgba(0,0,0,0); border: none; color: white;" value="Odblokuj użytkownika"></form>',
                'raw' => $raw,
            ];
        }
        
        $qa_content['custom_head'] = '<style>.qam-user-score-icon::before { display: none; } .qam-user-score-icon { padding: 2px 6px 2px 6px; }</style>';            
    }    
    
    if ('' !== $pageContent) {
        $qa_content['custom'] = $pageContent;
    }
    
    $qa_content['navigation']['sub'] = qa_user_sub_navigation(qa_get_logged_in_handle(), 'blocklist', true);
    
    
    return $qa_content;