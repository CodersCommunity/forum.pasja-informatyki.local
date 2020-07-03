<?php
declare(strict_types=1);

require_once QA_INCLUDE_DIR . 'db/users.php';

class user_security_page
{
    private $directory;
    private $urltoroot;
    private $requestParts;

    public function load_module(string $directory, string $urltoroot): void
    {
        $this->directory = $directory;
        $this->urltoroot = $urltoroot;
    }

    public function match_request(string $request): bool
    {
        $this->requestParts = $request;

        return $request === 'account/security';
    }

    public function process_request(): ?array
    {
        $qa_content = qa_content_prepare();
        $qa_content['title'] = 'Ustawienia zabezpieczeń konta';

        // logged in user id
        $loggedIn = qa_get_logged_in_userid();

        if (empty($loggedIn)) {
            $qa_content['error'] = qa_lang_html('block_pm/logged_in');

            return $qa_content;
        }

        return $qa_content;
    }
}
