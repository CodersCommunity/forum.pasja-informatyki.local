<?php

class discord_integration_event
{
    protected $api;

    public function __construct()
    {
        $this->api = new discord_api();
    }

    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if (empty(qa_opt('discord_integration_client_id'))
            || empty(qa_opt('discord_integration_client_secret'))
            || empty(qa_opt('discord_integration_guild_id'))
            || empty(qa_opt('discord_integration_bot_token'))) {
            return;
        }

        switch ($event) {
            case 'u_edit':
                $result = qa_db_query_sub(
                    'SELECT u.handle, d.discord_id FROM ^users u JOIN ^discord_integrations d ON u.userid=d.id_user WHERE u.userid=$ AND d.disconnected_date IS NULL LIMIT 1',
                    $params['userid']
                );
                if ($result->num_rows > 0) {
                    $data = qa_db_read_one_assoc($result);
                    if ($data['handle'] !== $params['handle']) {
                        $this->api->change_user_nick($data['discord_id'], $data['handle']);
                    }
                }

                break;
            case 'u_delete':
                $result = qa_db_query_sub(
                    'SELECT id_integration, discord_id FROM ^discord_integrations WHERE id_user=$ AND disconnected_date IS NULL LIMIT 1',
                    $params['userid']
                );
                if ($result->num_rows > 0) {
                    $data = qa_db_read_one_assoc($result);
                    $this->api->remove_user_from_guild($data['discord_id']);

                    qa_db_query_sub(
                        "UPDATE ^discord_integrations SET disconnected_date=NOW() WHERE id_integration=#",
                        $data['id_integration']
                    );
                }

                break;
        }
    }
}
