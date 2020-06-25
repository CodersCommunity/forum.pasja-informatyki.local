<?php

require_once GOOGLEAUTHENTICATOR_BASIC_PATH . '/src/Init.php';

class qa_html_theme_layer extends qa_html_theme_base
{
    public function doctype()
    {
        parent::doctype();

        if ('account' === $this->request && true === (bool) qa_opt('googleauthenticator_login')) {
            $content = [
                'tags'    => 'method="post" action="' . qa_self_html() . '"',
                'style'   => 'wide',
                'title'   => qa_lang_html('plugin_2fa/title')
            ];

            $content += $this->enablePlugin();

            $this->content['form_2fa'] = $content;
            qa_html_theme_base::doctype();
        }
    }

    private function getUserQuery($userId)
    {
        // Return the user with the specified userid (should return one user or null)
        $users = qa_db_read_all_assoc(
            qa_db_query_sub(
                'SELECT us.userid, us.2fa_enabled, us.email, us.handle, us.2fa_change_date, up.points FROM ^users us LEFT JOIN ^userpoints up ON us.userid = up.userid WHERE us.userid=$',
                $userId
            )
        );

        return empty($users) ? null : $users[0];
    }

    /**
     * This method enables 2fa on selected user.
     *
     * @param $userId
     * @param $isEnabled
     * @param $secret
     * @param $recoveryCode
     *
     * @return mixed
     */
    private function updateUserEnable2FA($userId, $isEnabled, $secret = null, $recoveryCode = null)
    {
        $time      = new DateTime('now');
        $formatter = new IntlDateFormatter('pl_PL', IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);
        $formatter->setPattern('EEEE, dd MMMM yyyy, HH:mm:ss');

        $result = qa_db_query_sub(
            'UPDATE ^users SET 2fa_enabled=#, 2fa_change_date=$, 2fa_secret=$, 2fa_recovery_code=$ WHERE userid=#',
            $isEnabled,
            $formatter->format($time),
            $secret,
            $recoveryCode,
            $userId
        );

        if (true === $result) {
            return $isEnabled;
        }

        user_error('Nie udało się włączyć autoryzacji dwuetapowej. Zgłoś ten problem administratorowi.');
    }

    private function enablePlugin()
    {
        $userAccount   = $this->getUser();
        $userActive2fa = $userAccount['2fa_enabled'];

        if (qa_clicked('doenable2fa')) {
            $this->init = new Init();
            $this->init->createSecret();
            $recoveryCode = $this->init->getRandomRecoveryCode();
            $secret = $this->init->getSecret();

            $userActive2fa = $this->updateUserEnable2FA($userAccount['userid'], true, $secret, $recoveryCode);
        } elseif (qa_clicked('dodisable2fa')) {
            $userActive2fa = $this->updateUserEnable2FA($userAccount['userid'], false);
        }

        $userAccount = $this->getUser();
        if (true === (bool) $userActive2fa) {

            $result = $this->render2FAEnabledForm($userAccount['2fa_change_date']);

            if (isset($this->init)) {
                $note = qa_lang_html('plugin_2fa/2fa_data_info');
                $note = str_replace('{{ QR_CODE }}', '<br><center><img src="' . $this->init->getQRCode() . '"></center><br>', $note);
                $note = str_replace('{{ SECRET }}', '<code>' . $secret . '</code>', $note);
                $note = str_replace('{{ RECOVERY_CODE }}', '<code>' . $recoveryCode . '</code>', $note);
                $note = str_replace('{{ ERROR_START }}', '<br><div class="qa-error">', $note);
                $note = str_replace('{{ ERROR_END }}', '</div><br>', $note);

                $result['fields'][] = [
                    'style' => 'tall',
                    'type' => 'static',
                    'note' => $note
                ];
            }

        } else {
            $result = $this->render2FADisabledForm($userAccount['2fa_change_date']);
        }
                
        return $result;
    }

    private function render2FAEnabledForm($date)
    {
        return [
            'fields'  => [
                'old' => [
                    'label' => qa_lang_html('plugin_2fa/plugin_is_enabled'),
                    'tags'  => 'name="oldpassword" disabled',
                    'value' => $date,
                    'type'  => 'input'
                ],
            ],
            'buttons' => [
                'enable' => [
                    'label' => qa_lang_html('plugin_2fa/disable_plugin')
                ]
            ],
            'hidden'  => [
                'dodisable2fa' => '1',
                'code'         => qa_get_form_security_code('2faform')
            ]
        ];
    }

    private function render2FADisabledForm($date)
    {
        return [
            'fields'  => [
                'old' => [
                    'label' => qa_lang_html('plugin_2fa/plugin_is_disabled'),
                    'value' => '',
                    'type'  => 'static'
                ]
            ],
            'buttons' => [
                'enable_2fa' => [
                    'label' => qa_lang_html('plugin_2fa/enable_plugin')
                ]
            ],
            'hidden'  => [
                'doenable2fa' => '1',
                'code'        => qa_get_form_security_code('2faform')
            ]
        ];
    }

    /**
     * @return null
     */
    private function getUser()
    {
        $userId = qa_get_logged_in_userid();

        if (!isset($userId)) {
            qa_redirect('login');
        }

        $userAccount = $this->getUserQuery($userId);

        return $userAccount;
    }
}

