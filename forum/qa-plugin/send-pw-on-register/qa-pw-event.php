<?php

class qa_pw_event
{
    public function process_event($event, $userId, $handle, $cookieId, $params)
    {
        if (qa_opt('sendPwMessageOnRegister_enabled') && 'u_register' === $event) {
                require_once QA_INCLUDE_DIR.'db/messages.php';
                $messsageId = qa_db_message_create(
                    qa_opt('sendPwMessageOnRegister_botId'),
                     $userId,
                    qa_opt('sendPwMessageOnRegister_messageContent'),
                    ''
                );
                $paramString = 'userid=' . $userId . ' handle=' . $handle . ' messageid=' . $messageId . ' message=' . qa_opt('sendPwMessageOnRegister_messageContent');
                qa_db_query_sub(
                    'INSERT INTO ^eventlog (datetime, ipaddress, userid, handle, cookieid, event, params) '.
                    'VALUES (NOW(), $, $, $, #, $, $)',
                    qa_remote_ip_address(),
                    qa_opt('sendPwMessageOnRegister_botId'),
                    $handle,
                    $cookieId,
                    'u_message',
                    $paramString
                );
        }
    }
}
