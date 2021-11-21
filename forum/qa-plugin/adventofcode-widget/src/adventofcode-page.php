<?php

class adventofcode_page
{
    public function match_request($request)
    {
        return $request === 'advent-of-code' && qa_opt('adventofcode_widget_enabled') == 1;
    }

    public function process_request()
    {
        $users = json_decode(qa_opt('adventofcode_widget_content'), true);
        $users = $this->fill_missing_days($users);
        $year = qa_opt('adventofcode_widget_year');
        $leaderboard = qa_opt('adventofcode_widget_leaderboard_id');
        $code = qa_opt('adventofcode_widget_leaderboard_code');

        $qa_content = qa_content_prepare();
        $qa_content['title'] = 'Advent of Code '.$year;
        $qa_content['head_lines'][] = '<style>'.$this->get_css().'</style>';
        $qa_content['custom'] = $this->get_html($users, $year, $leaderboard, $code);

        return $qa_content;
    }

    private function get_html($users, $year, $leaderboard, $code)
    {
        $html = '';

        $html .= '<h2>Zapraszamy do wspólnej rywalizacji!</h2>';
        $html .= '<p><a href="https://adventofcode.com/'.$year.'/about">Advent of Code</a> to coroczne zadania programistyczne, które pojawiają się codziennie przez okres adwentu. Możesz rozwiązywać je w <strong>dowolnym języku programowania</strong>. Nie jest wymagana żadna specjalistyczna wiedza - wystarczą chęci i podstawowa znajomość języka angielskiego.</p>';

        $html .= '<p>Aby umilić rozgrywkę, przygotowaliśmy prywatną tablicę wyników dla naszej społeczności.</p>';

        $html .= '<p class="aoc-page__join-info">';
            $html .= '<a href="https://adventofcode.com/'.$year.'/leaderboard/private/view/' . $leaderboard . '">Dołącz do tablicy</a>, używając kodu:<br>';
            $html .= '<span class="aoc-page__lead-board-code">' . $code . '</span>';
        $html .= '</p>';

        $html .= '<h2>Ranking</h2>';
        $html .= 'Każdego dnia jest do zdobycia <b><span class="aoc-page__star aoc-page__star--1">*</span> gwiazdka</b> za rozwiązanie pierwszej części zadania,<br> oraz <b><span class="aoc-page__star aoc-page__star--2">*</span> gwiazdka</b> za rozwiązanie całości. Punkty w rankingu liczone są na podstawie liczby zdobytych gwiazdek i kolejności zgłoszeń.';

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

        $html .= '<h2>Discord</h2>';
        $html .= '<p>Zajrzyj na <a href="/chat-discord">serwer Discorda</a>, gdzie posiadamy specjalny kanał do dyskusji o zadaniach. Prosimy jednak, aby nie wrzucać gotowych rozwiązań i nie oczekiwać, że takie zostaną podane - pozwólmy wszystkim dobrze się bawić, a w razie problemów nakierujmy na właściwy tok myślenia.</p>';

        $html .= '<p>Powodzenia!</p>';

        return $html;
    }

    private function get_css()
    {
        return '
            .aoc-page__join-info {
                text-align: center;
                margin: 30px 0 20px;
            }
            .aoc-page__users {
                margin: 40px 0;
            }
            .aoc-page__lead-board-code {
                font-family: monospace;
                font-size: 2rem;
            }
            .aoc-page__days {
                list-style-type: none;
                margin-left: 60px;
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
                .aoc-page__star, .aoc-page__days {
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
