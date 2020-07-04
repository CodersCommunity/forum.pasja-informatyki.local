<?php

require_once GOOGLEAUTHENTICATOR_BASIC_PATH . '/src/GoogleAuthenticator.php';
require_once QA_INCLUDE_DIR . 'db/users.php';

class qa_html_theme_layer extends qa_html_theme_base
{
    public function nav_list($navigation, $class, $level = null)
    {
        $isLoggedUser = qa_get_logged_in_userid();
        $this->prepareNavigation($class, $isLoggedUser, $navigation);

        parent::nav_list($navigation, $class, $level);
    }

    public function doctype()
    {
        parent::doctype();

        if ('account/security' === $this->request && (bool) qa_opt('googleauthenticator_login')) {
            $content = [
                'tags'    => 'method="post" action="' . qa_self_html() . '"',
                'style'   => 'wide',
                'title'   => qa_lang('plugin_2fa/title')
            ];

            $content += $this->switchPluginState();

            $this->content['form_2fa'] = $content;
            qa_html_theme_base::doctype();
        }

        if ('account' === $this->request) {
            $url = qa_path_html('account/security');
            $this->content['custom_2fa'] = <<<EOF
<div class="qa-custom-center">
    <h2>Ustawienia zabezpieczeń konta</h2>
    <div class="qa-custom-2fa-text">
        <p>Przejdź do strony <a href="{$url}">zabezpieczeń</a></p>
    </div>
</div>
EOF;

            qa_html_theme_base::doctype();
        }
    }

    private function switchPluginState(): array
    {
        $userAccount   = $this->getUser();
        [$userActive2fa, $recoveryCode, $secret] = $this->prepareGoogleAuthenticator($userAccount);

        if (!(bool) $userActive2fa) {
            return $this->renderFormIsDisabled();
        }

        $result = $this->renderFormIsEnabled(
            $userAccount['2fa_change_date'],
            (bool) $userAccount['2fa_enabled']
        );

        if (isset($this->init) && false === (bool) $userAccount['2fa_enabled']) {
            $result = $this->renderQRCode($secret, $recoveryCode, $result);
        }

        return $result;
    }

    private function renderFormIsEnabled($date, bool $showDateField): array
    {
        if ($showDateField) {
            $field = ['old' => [
                'label' => qa_lang('plugin_2fa/plugin_is_enabled'),
                'tags'  => 'name="oldpassword" disabled',
                'value' => (string) $date,
                'type'  => 'input'
            ]];
        }

        return [
            'fields'  => $field ?? [],
            'buttons' => [
                'enable' => [
                    'label' => qa_lang('plugin_2fa/disable_plugin')
                ]
            ],
            'hidden'  => [
                'dodisable2fa' => '1',
                'code'         => qa_get_form_security_code('2faform')
            ]
        ];
    }

    private function renderFormIsDisabled(): array
    {
        return [
            'fields'  => [
                'old' => [
                    'label' => qa_lang('plugin_2fa/plugin_is_disabled'),
                    'value' => '',
                    'type'  => 'static'
                ]
            ],
            'buttons' => [
                'enable_2fa' => [
                    'label' => qa_lang('plugin_2fa/enable_plugin')
                ]
            ],
            'hidden'  => [
                'doenable2fa' => '1',
                'code'        => qa_get_form_security_code('2faform')
            ]
        ];
    }

    private function renderQRCode($secret, $recoveryCode, array $result): array
    {
        $note = qa_lang_html('plugin_2fa/2fa_data_info');
        $note = str_replace(
            ['{{ QR_CODE }}', '{{ SECRET }}', '{{ RECOVERY_CODE }}', '{{ ERROR_START }}', '{{ ERROR_END }}'],
            [
                '<div class="qa-custom-2fa-qrcode"><img src="' . $this->init->getQRCode() . '"></div>',
                '<div class="qa-custom-2fa-code"><code>' . chunk_split($secret, 4, ' ') . '</code></div>',
                '<div class="qa-warning qa-custom-2fa-code"><code>' . $recoveryCode . '</code></div><br>',
                '<div class="qa-error qa-custom-2fa-text-bottom">',
                '</div>'
            ],
            $note
        );
        $result['fields'][] = [
            'style' => 'tall',
            'type'  => 'static',
            'note'  => $note
        ];

        return $result;
    }

    private function prepareNavigation(string $class, ?int $isLoggedUser, array &$navigation): void
    {
        if ($class === 'nav-sub'
            && isset($isLoggedUser)
            && (bool) qa_opt('googleauthenticator_login')
            && !$this->isExcludedPage()
        ) {
            $navigation[] = [
                'label' => 'Ustawienia zabezpieczeń konta',
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

    private function getUserQuery($userId): ?array
    {
        $users = qa_db_read_all_assoc(
            qa_db_query_sub(
                'SELECT us.userid, us.2fa_enabled, us.email, us.handle, us.2fa_change_date, up.points FROM ^users us LEFT JOIN ^userpoints up ON us.userid = up.userid WHERE us.userid=$',
                $userId['userid'] ?? $userId
            )
        );

        return empty($users) ? null : $users[0];
    }

    private function updateUserEnable2FA($userId, $isEnabled, $secret = null, $recoveryCode = null): ?bool
    {
        $time = $this->setTime();
        $result = qa_db_query_sub(
            'UPDATE ^users SET 2fa_enabled=#, 2fa_change_date=$, 2fa_secret=$, 2fa_recovery_code=$ WHERE userid=#',
            $isEnabled,
            $isEnabled ? $time : 0,
            $secret,
            $recoveryCode,
            $userId['userid']
        );

        if ((bool) $result) {
            return $isEnabled;
        }

        user_error(qa_lang('plugin_2fa/2fa_setup_error'));
    }

    private function getUser(): ?array
    {
        $userId = qa_get_logged_in_userid();

        if (!isset($userId)) {
            qa_redirect('login');
        }

        return $this->getUserQuery($userId);
    }

    private function setTime()
    {
        $time      = new DateTime('now');
        $formatter = new IntlDateFormatter('pl_PL', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
        $formatter->setPattern('EEEE, dd MMMM yyyy, HH:mm:ss');

        return $formatter->format($time);
    }

    private function prepareGoogleAuthenticator(?array $userAccount): array
    {
        $userActive2fa = $userAccount['2fa_enabled'];

        if (!empty(qa_post_text('code')) && (!qa_check_form_security_code('2faform', qa_post_text('code')))) {
            throw new \Exception('Invalid CSRF code');
        }

        if (false === (bool) $userAccount['2fa_enabled'] && qa_clicked('doenable2fa')) {
            $this->init = new GoogleAuthenticator;
            $this->init->createSecret();
            $recoveryCode = $this->init->getRandomRecoveryCode();
            $secret       = $this->init->getSecret();
            $userActive2fa = $this->updateUserEnable2FA($userAccount, true, $secret, $recoveryCode);
        } elseif (qa_clicked('dodisable2fa') && (bool) $userAccount['2fa_enabled'] ) {
            $userActive2fa = $this->updateUserEnable2FA($userAccount, false);
        }

        return [$userActive2fa ?? false, $recoveryCode ?? false, $secret ?? false];
    }
}
