<?php

class adventofcode_page
{
    public function match_request($request)
    {
        return $request === 'advent-of-code';
    }
    
    public function process_request()
    {
        $users = json_decode(qa_opt('adventofcode_widget_content'), true);
        $users = $this->fill_missing_days($users);

        $qa_content = qa_content_prepare();
        $qa_content['title'] = 'Advent of Code '.qa_opt('adventofcode_widget_year');
        $qa_content['head_lines'][] = '<style>'.$this->get_css().'</style>';
        $qa_content['custom'] = $this->get_html($users);

        return $qa_content;
    }

    private function get_html($users)
    {
        $html = '';

        // Users
        $html .= '<div class="aoc-page__users">';
        
            // Days numbers
            $html .= '<ol class="aoc-page__days">';
            foreach(range(1, 25) as $dayNr) {
                $html .= '<li class="aoc-page__day-nr">'.$dayNr.'</li>';
            }
            $html .= '</ol>';
            // e/o Days numbers
            
            // Ranking
            $html .= '<ol class="aoc-page__ranking">';
            foreach($users as ['name' => $username, 'score' => $score, 'stars' => $stars]) {
                $html .= '<li>';
                $html .= '    <span class="aoc-page__score">'.$score.'</span>';
                foreach($stars as $day => $dayScore) {
                    $html .= '<span class="aoc-page__star aoc-page__star--'.$dayScore.'" title="'.$username.' - Dzień: '.$day.'">*</span>';
                }
                $html .= '    <span class="aoc-page__username">'.$username.'</span>';
                $html .= '</li>';
            }
            $html .= '</ol>';
            // e/o Ranking

        $html .= '</div>';
        // e/o Users
        
        return $html;
    }

    private function get_css()
    {
        return '
            .aoc-page__days {
                list-style-type: none;
                margin-left: 64px;
                margin-bottom: 5px;
                line-height: 1.2em;
            }
            .aoc-page__ranking {
                margin-top: 5px;
            }
            .aoc-page__day-nr {
                display: inline-block;
                width: 10px;
                margin-right: 2px;
                word-wrap: break-word;
            }
            .aoc-page__star {
                opacity: 0.2;
                font-size: 20px;
                line-height: 0;
                display: inline-block;
                width: 12px;
                position: relative;
                top: 4px;
            }
            .dark-theme .aoc-page__star {
                font-size: 18px;
            }
            .aoc-page__star--1 {
                opacity: 1;
                color: #3498db;
            }
            .dark-theme .aoc-page__star--1 {
                opacity: 1;
            }
            .aoc-page__star--2 {
                opacity: 1;
                color: #e67e22;
            }
            .dark-theme .aoc-page__star--2 {
                color: gold;
            }
            .aoc-page__score {
                display: inline-block;
                margin-right: 10px;
                width: 50px;
                text-align: right;
            }
            .aoc-page__username {
                display: inline-block;
                margin-left: 8px;
            }

            @media (max-width: 760px) {
                .aoc-page__users {
                    display: none;
                }
            }
        ';
    }

    private function fill_missing_days($users)
    {
        foreach($users as &$user) {
            $stars = &$user['stars'];

            foreach(range(1, 25) as $day) {
                $stars[$day] = $stars[$day] ?? 0;
            }

            ksort($stars);
        }

        return $users;
    }
}
