<?php

class adventofcode_widget
{
    private $content;

    public function __construct()
    {
        $this->content = new adventofcode_content();
    }

    public function allow_template($template)
    {
        return $this->is_enabled();
    }

    public function allow_region($region)
    {
        return $this->is_enabled();
    }

    public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        if ($this->should_update_results()) {
            $this->update_results();
        }

        $users = $this->content->csvToResult(qa_opt('adventofcode_widget_content'));
        $year = qa_opt('adventofcode_widget_year');

        $themeobject->output('<div class="aoc-widget">');
        $themeobject->output('<h2 class="aoc-widget__title">Advent of Code '.$year.'</h2>');
        $themeobject->output('<p class="aoc-widget__top-users">Top 15 '.qa_lang_html('adventofcode_widget/top_users').'</p>');
        $themeobject->output('<ol class="aoc-widget__ol">');
        foreach(array_slice($users, 0, 15) as $index => $user) {
            $themeobject->output('<li><b>'.$user['score'].'p.</b> - '.$user['name'].'</li>');
        }
        $themeobject->output('</ol>');
        $themeobject->output('<a href="/advent-of-code">'.qa_lang_html('adventofcode_widget/details_and_full_scores').'</a>');
        $themeobject->output('</div>');

        return $qa_content;
    }

    public function admin_form()
    {
        $saved = qa_clicked('adventofcode_widget_save');
        if ($saved) {
            qa_opt('adventofcode_widget_enabled', empty(qa_post_text('adventofcode_widget_enabled')) ? 0 : 1);
            qa_opt('adventofcode_widget_year', qa_post_text('adventofcode_widget_year'));
            qa_opt('adventofcode_widget_leaderboard_id', qa_post_text('adventofcode_widget_leaderboard_id'));
            qa_opt('adventofcode_widget_leaderboard_code', qa_post_text('adventofcode_widget_leaderboard_code'));
            qa_opt('adventofcode_widget_session_id', qa_post_text('adventofcode_widget_session_id'));
            qa_opt('adventofcode_widget_update_date', ''); // reset last date to update results immediately
        }

        return [
            'ok' => $saved ? qa_lang_html('adventofcode_widget/admin_saved') : null,
            'fields' => [
                [
                    'label' => qa_lang_html('adventofcode_widget/admin_enabled'),
                    'type' => 'checkbox',
                    'value' => qa_opt('adventofcode_widget_enabled'),
                    'tags' => 'name="adventofcode_widget_enabled"',
                ],
                [
                    'label' => qa_lang_html('adventofcode_widget/admin_year'),
                    'type' => 'number',
                    'value' => qa_opt('adventofcode_widget_year'),
                    'tags' => 'name="adventofcode_widget_year"',
                ],
                [
                    'label' => qa_lang_html('adventofcode_widget/admin_leaderboard_id'),
                    'type' => 'text',
                    'value' => qa_opt('adventofcode_widget_leaderboard_id'),
                    'tags' => 'name="adventofcode_widget_leaderboard_id"',
                ],
                [
                    'label' => qa_lang_html('adventofcode_widget/admin_leaderboard_code'),
                    'type' => 'text',
                    'value' => qa_opt('adventofcode_widget_leaderboard_code'),
                    'tags' => 'name="adventofcode_widget_leaderboard_code"',
                ],
                [
                    'label' => qa_lang_html('adventofcode_widget/admin_session_id'),
                    'type' => 'text',
                    'value' => qa_opt('adventofcode_widget_session_id'),
                    'tags' => 'name="adventofcode_widget_session_id"',
                ]
            ],
            'buttons' => [
                [
                    'label' => qa_lang_html('adventofcode_widget/admin_save'),
                    'tags' => 'name="adventofcode_widget_save"'
                ],
            ]
        ];
    }

    private function is_enabled()
    {
        return qa_opt('adventofcode_widget_enabled') == 1;
    }

    private function should_update_results()
    {
        $date = qa_opt('adventofcode_widget_update_date');
        $now = (new DateTime())->format('Y-m-d H');

        return $date !== $now;
    }

    private function update_results()
    {
        $year = qa_opt('adventofcode_widget_year');
        $leaderboard = qa_opt('adventofcode_widget_leaderboard_id');
        $session = qa_opt('adventofcode_widget_session_id');

        $users = $this->content->loadFromPage($year, $leaderboard, $session);
        $content = $this->content->resultToCsv($users);
        qa_opt('adventofcode_widget_content', $content);

        qa_opt('adventofcode_widget_update_date', (new DateTime())->format('Y-m-d H'));
    }
}
