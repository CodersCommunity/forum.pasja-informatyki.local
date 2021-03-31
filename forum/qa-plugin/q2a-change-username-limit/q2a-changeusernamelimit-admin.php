<?php

namespace CodersCommunity;

class q2a_changeusernamelimit_admin
{
    public function init_queries()
    {
        $isActive = qa_opt('changeusernamelimit');

        if (1 === $isActive) {
            return null;
        }

        $queries = [];
        $columns = qa_db_read_all_values(qa_db_query_sub('describe ^users'));
        if (!in_array('username_change_date', $columns, true)) {
            $queries[] = 'ALTER TABLE ^users ADD `username_change_date` DATETIME DEFAULT NULL';
        }

        if (!in_array('username_change_history', $columns, true)) {
            $queries[] = 'ALTER TABLE ^users ADD `username_change_history` JSON DEFAULT NULL';
        }

        $result = null;
        if (count($queries)) {
            $result = $queries;
        }

        // we're already set up
        qa_opt('changeusernamelimit', 1);

        return $result;
    }

    public function admin_form()
    {
        $saved = false;

        if (qa_clicked('changeusernamelimit_save_button')) {
            $enabled = qa_post_text('changeusernamelimit_enable_plugin');
            qa_opt('changeusernamelimit', empty($enabled) ? 0 : 1);

            $saved = true;
        }
        return [
            'ok' => $saved ? qa_lang('plugin_username_limit/saved_plugin_settings') : null,
            'fields' => [[
                'type' => 'checkbox',
                'label' => qa_opt('changeusernamelimit') ?
                    qa_lang('plugin_username_limit/enabled_plugin') :
                    qa_lang('plugin_username_limit/disabled_plugin'),
                'value' => qa_opt('changeusernamelimit') ? true : false,
                'tags' => 'NAME="changeusernamelimit_enable_plugin"'
            ]],
            'buttons' => [[
                'label' => qa_lang('plugin_username_limit/save_settings'),
                'tags' => 'NAME="changeusernamelimit_save_button"'
            ]]
        ];
    }
}
