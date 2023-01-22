<?php

class discord_notifications_admin
{
    public function admin_form()
    {
        $saved = false;
        if (qa_clicked('save')) {
            $config = qa_post_text('config');
            qa_opt('discord_notifications_config', $config);
            $saved = true;
        }

        return [
            'ok' => $saved ? qa_lang('discord_notifications/admin_ok_info') : null,
            'fields' => [
                [
                    'type' => 'textarea',
                    'label' => qa_lang('discord_notifications/admin_config_label'),
                    'value' => qa_html(qa_opt('discord_notifications_config')),
                    'tags' => 'name="config"',
                    'rows' => 5
                ],
            ],
            'buttons' => [
                [
                    'label' => qa_lang('discord_notifications/admin_save_button'),
                    'tags' => 'name="save"'
                ]
            ]
        ];
    }
}
