<?php

namespace CodersCommunity;

require_once __DIR__ . '/../vendor/autoload.php';

class q2a_googleauthenticator_admin
{
    public function init_queries()
    {
        $isActive = qa_opt('googleauthenticator_login');
        $result = null;

        if (1 === $isActive) {
            return $result;
        }

        $queries = [];

        $columns = qa_db_read_all_values(qa_db_query_sub('describe ^users'));
        if(!in_array('2fa_enabled', $columns)) {
            $queries[] = 'ALTER TABLE ^users ADD `2fa_enabled` SMALLINT (1) DEFAULT 0';
        }

        if(!in_array('2fa_change_date', $columns)) {
            $queries[] = 'ALTER TABLE ^users ADD `2fa_change_date` VARCHAR (80) DEFAULT 0';
        }

        if(!in_array('2fa_secret', $columns)) {
            $queries[] =
                'ALTER TABLE ^users ADD `2fa_secret` VARCHAR ( 80 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL';
        }

        if(count($queries)) {
            $result = $queries;
        }

        // we're already set up
        qa_opt('googleauthenticator_login', 1);

        return $result;
    }

    public function admin_form()
    {
        $saved = false;

        if (qa_clicked('2fa_save_button')) {
            $enabled = qa_post_text('googleauthenticator_enable_plugin');
            qa_opt('googleauthenticator_login', empty($enabled) ? 0 : 1);

            $saved = true;
        }

            $form = [
            'ok' => $saved ? 'Two factor preferences saved' : null,
            'fields' => [[
                'type' => 'checkbox',
                'label' => qa_lang_html('plugin_2fa/disable_plugin_title'),
                'value' => qa_opt('googleauthenticator_login') ? true : false,
                'tags' => 'NAME="googleauthenticator_enable_plugin"'
                ]
            ],
            'buttons' => [[
                'label' => 'Save Changes',
                'tags' => 'NAME="2fa_save_button"'
                ]
            ]
        ];

        return $form;
    }
}