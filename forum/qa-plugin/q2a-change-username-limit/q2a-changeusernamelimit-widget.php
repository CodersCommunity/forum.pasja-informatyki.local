<?php

namespace CodersCommunity;

class q2a_changeusernamelimit_widget
{
    public function allow_template(string $template): bool
    {
        return ('user' === $template) && (qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN);
    }

    public function allow_region(string $region): bool
    {
        return in_array($region, ['main', 'side', 'full']);
    }

    public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        $user = explode('/', $request)[1];

        if (!empty($user)) {
            $history = $this->loadHistoryFromDatabase($user);

            if (isset($history)) {
                $themeobject->output('<h2>' . qa_lang('plugin_username_limit/history_title_label') . '</h2>');
                $themeobject->output('<ul class="q2a-change-username-history-list">');
                $this->populateList($history, $themeobject);
                $themeobject->output('</ul>');
            }
        }
    }

    private function loadHistoryFromDatabase(string $user): ?array
    {
         return json_decode(
            qa_db_read_one_assoc(qa_db_query_sub('
                    SELECT username_change_history 
                    FROM ^users 
                    WHERE handle=#',
                $user))['username_change_history'],
            true);
    }

    private function populateList(array $history, $themeobject): void
    {
        foreach ($history as $item) {
            $themeobject->output(
                '<li>
                    <dl>
                        <div class="q2a-change-username-history-list__entry">
                            <dt>' . qa_lang('plugin_username_limit/old_handle_label') . '</dt>
                            <dd>' . $item['old'] . '</dd>
                        </div>
                        <div class="q2a-change-username-history-list__entry">
                            <dt>' . qa_lang('plugin_username_limit/new_handle_label') . '</dt>
                            <dd>' . $item['new'] . '</dd>
                        </div>
                        <div class="q2a-change-username-history-list__entry">
                            <dt>' . qa_lang('plugin_username_limit/date_label') . '</dt>
                            <dd>' . $item['date'] . '</dd>
                        </div>
                    </dl>
                </li>'
            );
        }
    }
}
