<?php

class async_lists_event
{
    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if (empty(QA_WS_URL) || empty(QA_WS_TOKEN)) {
            return;
        }

        switch ($event) {
            case 'q_post':
            case 'q_edit':
            case 'q_hide':
            case 'q_reshow':
            case 'q_close':
            case 'q_reopen':
            case 'q_move':
                $this->send_to_websocket($event, ['question_id' => (int)$params['postid']]);
                break;
            case 'a_post':
            case 'a_edit':
            case 'a_hide':
            case 'a_reshow':
            case 'a_select':
            case 'a_unselect':
                $this->send_to_websocket($event, ['question_id' => (int)$params['parentid']]);
                break;
            case 'c_post':
            case 'c_edit':
            case 'c_hide':
            case 'c_reshow':
            case 'a_to_c':
                $this->send_to_websocket($event, ['question_id' => (int)$params['questionid']]);
                break;
        }
    }

    private function send_to_websocket($action, $data = [])
    {
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" . "token: " . QA_WS_TOKEN . "\r\n",
                'method' => 'POST',
                'content' => json_encode(['action' => $action, 'data' => $data], JSON_UNESCAPED_UNICODE)
            ]
        ];

        $context = stream_context_create($options);
        file_get_contents(QA_WS_URL, false, $context);
    }
}
