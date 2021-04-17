<?php
if (!defined('QA_VERSION') || qa_get_logged_in_level() < QA_USER_LEVEL_ADMIN) {
    header('Location: ../');
    exit;
}

require_once QA_INCLUDE_DIR.'db/users.php';


function getUsernotes(): array
{
    return qa_db_read_all_assoc(
        qa_db_query_sub('
            SELECT
                id_user,
                handle,
                added_handle,
                added_date,
                content
            FROM ^usersnotes
            WHERE content
            LIKE \'Zmiana nazwy użytkownika z "%" na "%"\''
        )
    );
}

function extractData(array $fromNotes) {
    $result = [];
    foreach ($fromNotes as $note) {
        $code = explode( 'Zmiana nazwy użytkownika z ', $note['content'])[1];
        $bodytag = str_replace('"', '', $code);
        $end = explode(' na ', $bodytag);
        $result[] = [
            'id' => $note['id_user'],
            'old' => $end[0],
            'new' => $end[1],
            'date' => $note['added_date']
        ];
    }

    return sortHistory($result);
}

function sortHistory(array $history): ?array
{
    array_multisort(array_map('strtotime', array_column($history, 'date')),
        SORT_ASC,
        $history);

    return $history;
}

function addAnEntryToTheHandleChangeHistory(
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

    qa_db_user_set($userid, 'username_change_date', $date);
    qa_db_query_sub('UPDATE ^users SET username_change_history=$ WHERE userid=#', json_encode($history), $userid);
}



$notes = getUsernotes();
echo '<h2>User notes:</h2>' . PHP_EOL;
qa_debug($notes);
$extracted = extractData($notes);
echo '<h2>Extracted from notes for username_change_history:</h2>' . PHP_EOL;
qa_debug($extracted);

/// Odkomentuj, gdy bedziesz pewien zmian
//foreach ($extracted as $entry) {
//    addAnEntryToTheHandleChangeHistory($entry['id'], $entry['old'], $entry['new'], $entry['date']);
//}
