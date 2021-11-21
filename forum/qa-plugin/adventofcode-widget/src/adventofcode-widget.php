<?php

class adventofcode_widget
{
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

        $users = json_decode(qa_opt('adventofcode_widget_content'), true);
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

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://adventofcode.com/{$year}/leaderboard/private/view/{$leaderboard}.json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIE, 'session=' . $session);
        $aocResponse = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($code !== 200) {
            return false;
        }

        $content = $this->parseAocResponse($aocResponse);
        if ($content) {
            qa_opt('adventofcode_widget_content', $content);
        }

        qa_opt('adventofcode_widget_update_date', (new DateTime())->format('Y-m-d H'));

        return true;
    }

    private function parseAocResponse($aocResponseData)
    {
        $data = json_decode($aocResponseData, true);
        if (!$data) {
            return null;
        }

        $users = [];
        foreach ($data['members'] as $member) {
            $stars = [];
            foreach ($member['completion_day_level'] as $day => $dayScore) {
                $stars[$day] = count($dayScore);
            }

            $users[] = [
                'id' => $member['id'],
                'name' => $member['name'] ?? ('Anonim '.$member['id']),
                'score' => $member['local_score'],
                'stars' => $stars,
            ];
        }

        usort($users, function($userA, $userB) {
            return $userB['score'] <=> $userA['score'];
        });

        return json_encode($users);
    }
}
