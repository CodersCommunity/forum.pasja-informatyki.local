<?php

namespace CodersCommunity;

class q2a_changeusernamelimit_widget
{
    public function allow_template(string $template): bool
    {
        return 'user' === $template;
    }

    public function allow_region(string $region): bool
    {
        return in_array($region, ['main', 'side', 'full']);
    }

    public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        $user = explode('/', $request)[1];

        if (!empty($user)) {
            $history = json_decode(
                qa_db_read_one_assoc(qa_db_query_sub('
                    SELECT username_change_history 
                    FROM ^users 
                    WHERE handle=#',
                    $user))['username_change_history'],
                true);

            if (isset($history)) {
                $themeobject->output('<h2>Historia zmian nazwy użytkownika</h2>');
                $themeobject->output('<ul>');
                foreach ($history as $item) {
                    $themeobject->output('<li>Old handle: ' . $item['old'] . '<br>New handle: ' . $item['new'] . '<br>Date: ' . $item['date']. '</li>');
                }
                $themeobject->output('</ul>');
            }
        }
    }
}
