<?php

namespace CodersCommunity;

class q2a_changeusernamelimit_event
{
    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if ($event == 'u_edit') {
            $oldHandle = $params['handle'];
            $newHandle = qa_post_text('handle');
            $oldUserId = $params['userid'];

            if (($oldHandle !== $newHandle) && qa_get_logged_in_level() >= QA_USER_LEVEL_ADMIN) {
                $this->changeHandle($oldUserId, $oldHandle, $newHandle);
            } else {
                qa_fatal_error('Nie masz uprawnień.');
            }
        }
    }

    private function addAnEntryToTheHandleChangeHistory(
        ?int $userid,
        ?string $oldhandle,
        ?string $newhandle,
        $date
    ): void {
        $history = json_decode(
            qa_db_read_one_assoc(
                qa_db_query_sub('SELECT username_change_history FROM ^users WHERE userid=$', $userid['userid'] ?? $userid)
            )['username_change_history'], true);

        $history[] = [
            'old' => $oldhandle,
            'new' => $newhandle,
            'date' => $date
        ];

        qa_db_query_sub('UPDATE ^users SET username_change_history=$ WHERE userid=#', json_encode($history), $userid);
    }

    private function changeHandle(?int $oldUserId, ?string $oldHandle, ?string $newHandle): void
    {
        qa_db_user_set($oldUserId, 'handle', $newHandle);
        qa_db_user_set($oldUserId, 'username_change_date', date('Y-m-d H:i:s'));

        $this->addAnEntryToTheHandleChangeHistory($oldUserId, $oldHandle, $newHandle, date('Y-m-d H:i:s'));
    }
}
