<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    private $isLogged;
    
    public function nav_list($navigation, $class, $level=null) // cannot use `: void` type-hint
    {
        $user = qa_request_parts()[1] ?? '';
        $dbUser = qa_db_select_with_pending(qa_db_user_account_selectspec($user, false));

        if (qa_clicked('douserblock') || qa_clicked('douserunblock')) {
            $this->performFormAction(qa_get_logged_in_userid(), $dbUser['userid']);
        }

        $this->prepareNavigation($class, $dbUser['handle'], $navigation);
        $this->prepareProfileButtons($class, qa_get_logged_in_userid(), $dbUser);
        $this->changePrivateMessageButton(qa_get_logged_in_userid(), $dbUser);
        
        parent::nav_list($navigation, $class, $level);
    }
    
    private function performFormAction($loggedInId, $profileUserId): void
    {
        if (qa_clicked('douserblock')) {
            qa_db_query_sub('INSERT INTO `^blockedpw` VALUES (#, #)', $loggedInId, $profileUserId);
        } else if (qa_clicked('douserunblock')) {
            qa_db_query_sub('DELETE FROM `^blockedpw` WHERE `from_user_id` = # AND `to_user_id` = #', $loggedInId, $profileUserId);
        }
    }
    
    private function prepareProfileButtons($class, $loggedInId, $dbUser): void
    {
        $allowedToSeeButtons = $dbUser !== qa_get_logged_in_handle() && strpos(qa_request(), 'user/') !== false;
        
        if (!empty($dbUser) && $dbUser['level'] == QA_USER_LEVEL_BASIC) {
            if ($class === 'nav-sub' && $allowedToSeeButtons) {
                if (!checkIfUserIsBlocked($dbUser['userid'], qa_get_logged_in_userid())) {
                    $this->content['form_profile']['buttons']['douserblock'] = [
                        'label' => 'Zablokuj użytkownika',
                        'tags' => 'name="douserblock"'
                    ];
                } else {
                    $isBlocker = qa_db_query_sub('SELECT `from_user_id`, `to_user_id` FROM ^blockedpw WHERE from_user_id = # AND to_user_id = #', $loggedInId, $dbUser['userid']);
                    if ($isBlocker->num_rows != 0) {
                        $this->content['form_profile']['buttons']['douserunblock'] = [
                            'label' => 'Odblokuj użytkownika',
                            'tags' => 'name="douserunblock"'
                        ];
                    }
                }
            }
        }
    }
    
    private function prepareNavigation($class, $userHandle, &$navigation): void
    {
        if (
          ($class === 'nav-sub' || $class === 'nav-sub') &&
          ((!empty($userHandle) && $userHandle === qa_get_logged_in_handle()) || qa_request() === 'blocked-users')
        ) {
            $navigation[] = [
                'label' => 'Zablokowani użytkownicy',
                'url' => qa_path_html('blocked-users'),
                'selected' => 'blocked-users' === qa_request()
            ];
        }
    }
    
    private function changePrivateMessageButton($loggedInId, $profileUser): void
    {
        if (checkIfUserIsBlocked($loggedInId, $profileUser['userid']) && strpos(qa_request(), 'user/') !== false) {
            $valueArray = explode('<a href', $this->content['form_profile']['fields']['level']['value']);
            $value = '';
            
            if (qa_get_logged_in_level() > QA_USER_LEVEL_BASIC) {
                $value = $valueArray[0] . strtr('^1^2^3', [
                    '^1' => '<dfn class="pw-link-admins" data-info="' . qa_lang_html('block_pw/admin_info_blockade') . '"><a href="' . qa_path_html('message/' . $profileUser['handle']) .'">',
                    '^2' => qa_lang_html('block_pw/see_pm_history_button'),
                    '^3' => '</a></dfn>',
                ]);
            } else {
                $value = $valueArray[0] . strtr('^1^2^3', [
                    '^1' => '<a href="' . qa_path_html('message/' . $profileUser['handle']) . '">',
                    '^2' => qa_lang_html('block_pw/see_pm_history_button'),
                    '^2' => '</a>',
                ]);
            }
            
            $this->content['form_profile']['fields']['level']['value'] = $value;
        }
    }
}
