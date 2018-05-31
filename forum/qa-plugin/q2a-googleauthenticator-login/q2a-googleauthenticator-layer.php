<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    public function doctype()
    {
        parent::doctype();

        $content = [];

        if (qa_clicked('general_save_button')) {
        }

            if ('account' === $this->request && true === (bool) qa_opt('googleauthenticator_login')) {
            $content = [
                'tags'    => 'method="post" action="' . qa_self_html() . '"',
                'style'   => 'wide',
                'title'   => qa_lang_html('plugin_2fa/title')
                ];

            $content += $this->enablePlugin();
        }

        $this->content['form_2fa'] = $content;

        qa_html_theme_base::doctype();
    }

    private function enablePlugin()
    {
        $userActive2fa = false;
        if ($userActive2fa) {
            return [
                'fields'  => [
                    'old' => [
                        'label' => qa_lang_html('plugin_2fa/plugin_is_enabled'),
                        'tags'  => 'name="oldpassword" disabled',
                        'value' => 'piątek, 21 marca 2018, 16:18',
                        'type'  => 'input'
                    ]
                ],
                'buttons' => [
                    'enable' => [
                        'label' => qa_lang_html('plugin_2fa/enable_plugin')
                    ]
                ]
            ];
        }

        return [
            'fields'  => [
                'old' => [
                    'label' => qa_lang_html('plugin_2fa/plugin_is_disabled'),
                    'value' => '',
                    'type'  => 'static'
                ]
            ],
            'buttons' => [
                'enable' => [
                    'label' => qa_lang_html('plugin_2fa/enable_plugin')
                ]
            ]
        ];
    }
}

