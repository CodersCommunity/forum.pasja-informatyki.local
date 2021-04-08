<?php

class discord_integration_admin
{
    public function admin_form()
    {
        $saved = false;
        if (qa_clicked('save')) {
            $client_id = qa_post_text('client_id');
            $client_secret = qa_post_text('client_secret');
            $bot_token = qa_post_text('bot_token');
            $guild_id = qa_post_text('guild_id');
            $top_info = qa_post_text('top_info');
            $bottom_info = qa_post_text('bottom_info');

            qa_opt('discord_integration_client_id', $client_id);
            qa_opt('discord_integration_client_secret', $client_secret);
            qa_opt('discord_integration_bot_token', $bot_token);
            qa_opt('discord_integration_guild_id', $guild_id);
            qa_opt('discord_integration_top_info', $top_info);
            qa_opt('discord_integration_bottom_info', $bottom_info);
            $saved = true;
        }

        return [
            'ok' => $saved ? qa_lang_html('discord_integration/admin_ok_info') : null,
            'fields' => [
                'input1' => [
                    'type' => 'text',
                    'label' => qa_lang_html('discord_integration/client_id') . ': *',
                    'value' => qa_html(qa_opt('discord_integration_client_id')),
                    'tags' => 'name="client_id"'
                ],
                'input2' => [
                    'type' => 'text',
                    'label' => qa_lang_html('discord_integration/client_secret') . ': *',
                    'value' => qa_html(qa_opt('discord_integration_client_secret')),
                    'tags' => 'name="client_secret"'
                ],
                'input3' => [
                    'type' => 'text',
                    'label' => qa_lang_html('discord_integration/bot_token') . ': *',
                    'value' => qa_html(qa_opt('discord_integration_bot_token')),
                    'tags' => 'name="bot_token"'
                ],
                'input4' => [
                    'type' => 'text',
                    'label' => qa_lang_html('discord_integration/guild_id') . ': *',
                    'value' => qa_html(qa_opt('discord_integration_guild_id')),
                    'tags' => 'name="guild_id"'
                ],
                'input5' => [
                    'type' => 'textarea',
                    'label' => qa_lang_html('discord_integration/top_info') . ':',
                    'value' => qa_html(qa_opt('discord_integration_top_info')),
                    'tags' => 'name="top_info"',
                    'rows' => 5
                ],
                'input6' => [
                    'type' => 'textarea',
                    'label' => qa_lang_html('discord_integration/bottom_info') . ':',
                    'value' => qa_html(qa_opt('discord_integration_bottom_info')),
                    'tags' => 'name="bottom_info"',
                    'rows' => 5
                ],
            ],
            'buttons' => [
                [
                    'label' => qa_lang_html('discord_integration/admin_save_button'),
                    'tags' => 'name="save"'
                ]
            ]
        ];
    }

    public function init_queries($tableslc)
    {
        $table = qa_db_add_table_prefix('discord_integrations');

        if (in_array($table, $tableslc)) {
            return null;
        }

        return 'CREATE TABLE ^discord_integrations (
            id_integration INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            id_user INT(11) NOT NULL,
            discord_id VARCHAR(64) NOT NULL,
            discord_username VARCHAR(32) NOT NULL,
            discord_discriminator VARCHAR(4) NOT NULL,
            connected_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
            disconnected_date TIMESTAMP NULL
        ) ENGINE=InnoDB CHARSET=utf8';
    }
}
