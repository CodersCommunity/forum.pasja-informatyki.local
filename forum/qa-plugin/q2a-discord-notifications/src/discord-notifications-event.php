<?php

class discord_notifications_event
{
    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if ($event !== 'q_post' || empty(qa_opt('discord_notifications_config'))) {
            return;
        }

        $webhook = $this->get_webhook_url($params['categoryid']);
        if (empty($webhook)) {
            return;
        }

        $body = $this->prepare_body($handle, $params);
        $this->send_to_webhook($webhook, $body);
    }

    private function get_webhook_url(int $category)
    {
        $config = json_decode(qa_opt('discord_notifications_config'), true);
        if (isset($config[$category])) {
            return $config[$category];
        }

        return null;
    }

    private function prepare_body(string $username, array $params)
    {
        $category = qa_db_read_one_value(
            qa_db_query_sub('SELECT title FROM ^categories WHERE categoryid = $', $params['categoryid'])
        );
        $content = str_replace(["\r", "\n"], ' ', $params['text']);

        return [
            'content' => qa_lang('discord_notifications/message'),
            'embeds' => [
                [
                    'title' => $params['title'],
                    'url' => qa_q_path_html($params['postid'], $params['title'], true),
                    'description' => mb_strimwidth($content, 0, 200, "..."),
                    'fields' => [
                        [
                            'name' => qa_lang('discord_notifications/author'),
                            'value' => $username,
                            'inline' => true
                        ],
                        [
                            'name' => qa_lang('discord_notifications/category'),
                            'value' => $category,
                            'inline' => true
                        ],
                        [
                            'name' => qa_lang('discord_notifications/tags'),
                            'value' => str_replace(',', ', ', $params['tags']),
                            'inline' => true
                        ]
                    ]
                ]
            ]
        ];
    }

    private function send_to_webhook(string $url, array $body)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($curl);
        curl_close($curl);
    }
}
