<?php

class adventofcode_widget
{
    public function allow_template($template)
    {
        return true;
    }

    public function allow_region($region)
    {
        return true;
    }

    public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        if ($this->should_update_results()) {
            $this->update_results();
        }

        $content = json_decode(qa_opt('adventofcode_widget_content'), true);

        print_r($content); // TODO testing
        $themeobject->output('results…');

        return $qa_content;
    }

    public function admin_form()
    {
        $saved = qa_clicked('adventofcode_widget_save');
        if ($saved) {
            qa_opt('adventofcode_widget_year', qa_post_text('adventofcode_widget_year'));
            qa_opt('adventofcode_widget_leaderboard_id', qa_post_text('adventofcode_widget_leaderboard_id'));
            qa_opt('adventofcode_widget_session_id', qa_post_text('adventofcode_widget_session_id'));
        }

        return [
            'ok' => $saved ? qa_lang_html('adventofcode_widget/admin_saved') : null,
            'fields' => [
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

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://adventofcode.com/{$year}/leaderboard/private/view/{$leaderboard}.json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, 'session=' . $session);
        $content = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($code !== 200) {
            return false;
        }

        qa_opt('adventofcode_widget_content', $content); // TODO move to file?
        qa_opt('adventofcode_widget_update_date', (new DateTime())->format('Y-m-d H'));

        return true;
    }
}
