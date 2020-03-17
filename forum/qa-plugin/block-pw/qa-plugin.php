<?php
/*
    Plugin Name: Block pw
    Plugin URI: https://forum.pasja-informatyki.pl
    Plugin Description: Very powerful and useful plugin for blocking pw from unpleasant users :)
    Plugin Version: 1.0
    Plugin Date: 2020-03-16
    Plugin Author: https://forum.pasja-informatyki.pl/user/Mariusz08
    Plugin License: GPLv3
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Update Check URI: 

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    More about this license: http://www.gnu.org/licenses/gpl.html
    
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_phrases('block-pw-lang-*.php', 'block_pw');
qa_register_plugin_module('page', 'block-pw-page.php', 'block_pw_page', 'Block pw page');
qa_register_plugin_module('page', 'block-pw-user-list-page.php', 'block_pw_user_list_page', 'Block pw user list page');
qa_register_plugin_overrides('block-pw-override.php');
qa_register_plugin_layer('block-pw-layer.php', 'Block pw layer');

/**
 * Parameters order does not matter.
 */
function checkIfUserIsBlocked($fromUserId, $toUserId): bool
{
    $blockedPrivateMessages = qa_db_query_sub('SELECT `from_user_id`, `to_user_id` FROM ^blockedpw WHERE (from_user_id = # AND to_user_id = #) OR (from_user_id = # AND to_user_id = #)', $fromUserId, $toUserId, $toUserId, $fromUserId);
    $allowedPrivateMessages = qa_opt('allow_private_messages');
    $blockedPrivateMessageBool = 0 != $blockedPrivateMessages->num_rows;
    $userLevel = qa_get_logged_in_level();
    $toUserDb = qa_db_select_with_pending(qa_db_user_account_selectspec($toUserId, false));

    if (($blockedPrivateMessageBool || !$allowedPrivateMessages) && $toUserDb['level'] < QA_USER_LEVEL_EDITOR) {
        return true;
    }
    
    return false;
}
