<?php

class qa_pw_event
{
    public function process_event($event, $userId, $handle, $cookieId, $params)
    {
        if (qa_opt('sendPwMessageOnRegister_enabled') && 'u_register' === $event) {
            require_once QA_INCLUDE_DIR . 'db/messages.php';
            require_once QA_INCLUDE_DIR . 'db/selects.php';
                
            $botId = qa_opt('sendPwMessageOnRegister_botId');
            $messageContent = qa_opt('sendPwMessageOnRegister_messageContent');
                
            $messsageId = qa_db_message_create(
                $botId,
                $userId,
                $messageContent,
                ''
            );
                
            $fromUserHandle = qa_db_query_sub('SELECT `handle` FROM ^users WHERE `userid` = #', $botId);
            $toUserHandle = qa_db_query_sub('SELECT `handle` FROM ^users WHERE `userid` = #', $userId);
                
            qa_report_event('u_message', $botId, $fromUserHandle, qa_cookie_get(), [
                'userid' => $userId,
                'handle' => $toUserHandle,
                'messageid' => $messageId,
                'message' => $messageContent
            ]);
        }
    }
}
