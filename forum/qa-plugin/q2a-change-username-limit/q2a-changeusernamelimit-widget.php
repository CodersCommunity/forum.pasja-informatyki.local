<?php

namespace CodersCommunity;

class q2a_changeusernamelimit_widget
{
    public function allow_template(string $template): bool
    {
        return ('user' === $template) && (qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR);
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
                $themeobject->output('<div class="qa-part-message-list">');
                $themeobject->output('<h2><a name="history">' . qa_lang('plugin_username_limit/history_title_label') . '</a></h2>');
                $themeobject->output('<ul class="q2a-change-username-history-list">');
                $this->populateList($history, $themeobject);
                $themeobject->output('</ul>');
                $themeobject->output('</div>');
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
            [$itemDate, $itemTime] = explode(' ', $item['date']);

            $themeobject->output(
                '<li class="q2a-change-username-history-list__item">
                    <div class="q2a-change-username-history-list__entry">
                        <div class="q2a-change-username-history-list__datetime">
                            <time datetime="' . $itemDate . '">' . $itemDate . '</time>
                            <time datetime="' . $itemTime . '">' . $itemTime . '</time>
                        </div>
                        <div class="q2a-change-username-history-list__info">
                            <span>' . $item['old'] . '</span><i>&rarr;</i><span>' . $item['new'] . '</span>
                        </div>
                    </div>
                </li>'
            );
        }
    }
}
