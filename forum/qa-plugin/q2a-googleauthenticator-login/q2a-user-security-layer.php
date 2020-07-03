<?php

require_once QA_INCLUDE_DIR . 'db/users.php';

class qa_html_theme_layer extends qa_html_theme_base
{
    public function nav_list($navigation, $class, $level = null)
    {
        $isLoggedUser = qa_get_logged_in_userid();
        $this->prepareNavigation($class, $isLoggedUser, $navigation);

        parent::nav_list($navigation, $class, $level);
    }

    private function prepareNavigation(string $class, ?int $isLoggedUser, array &$navigation): void
    {
        if ($class === 'nav-sub'
            && isset($isLoggedUser)
            && true === (bool) qa_opt('googleauthenticator_login')
            && !$this->isExcludedPage()
        ) {
            $navigation[] = [
                'label' => '<b class="color: red">Ustawienia zabezpieczeń konta</b>',
                'url' => qa_path_html('account/security'),
                'selected' => 'account/security' === qa_request()
            ];
        }
    }

    private function isExcludedPage(): bool
    {
        return in_array(explode('/', qa_request())[0], [
            'admin', 'messages', 'users', 'unanswered', 'updates'
        ]);
    }
}
