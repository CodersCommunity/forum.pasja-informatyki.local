<?php

class adventofcode_page
{
    public function match_request($request)
    {
        return $request === 'advent-of-code';
    }

    public function process_request()
    {
        $qa_content = qa_content_prepare();
        $qa_content['head_lines'][] = '<style></style>'; // TODO css
        $qa_content['title'] = 'Advent of Code ' . qa_opt('adventofcode_widget_year');

        $output = '<ol>';
        $users = json_decode(qa_opt('adventofcode_widget_content'), true);
        foreach($users as $user) {
            $output .= '<li><b>' . $user['score'] . 'p.</b> - ' . $user['name'] . '</li>';
        }
        $output .= '</ol>';

        $qa_content['custom'] = $output;

        return $qa_content;
    }
}
