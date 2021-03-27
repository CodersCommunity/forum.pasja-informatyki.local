<?php

class async_lists_event
{
    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if (empty(QA_WS_URL) || empty(QA_WS_TOKEN)) {
            return;
        }

        $this->send_to_websocket($event);
    }

    private function send_to_websocket($action)
    {
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n" . "token: " . QA_WS_TOKEN . "\r\n",
                'method' => 'POST',
                'content' => json_encode(['action' => $action], JSON_UNESCAPED_UNICODE)
            ]
        ];

        $context = stream_context_create($options);
        file_get_contents(QA_WS_URL, false, $context);
    }
}
