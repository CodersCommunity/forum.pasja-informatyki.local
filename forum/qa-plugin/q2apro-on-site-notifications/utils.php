<?php

class ThreadMuteChecker
{
    private $muted_users;
    private $cache = [];

    public function __construct(int $postid)
    {
        $this->muted_users = $this->getUsersWhoMutedThread($postid);
    }

    public function hasUserMutedThread(int $userid)
    {
        if (!isset($this->cache[$userid])) {
            $this->cache[$userid] = in_array($userid, $this->muted_users);
        }

        return $this->cache[$userid];
    }

    protected function getUsersWhoMutedThread(int $postid)
    {
        return qa_db_read_all_values(
            qa_db_query_sub('SELECT userid FROM ^muted_threads
                WHERE postid = #
            ', $postid),
        );
    }
}

class ThreadParticipantsObtainer
{
    public $participants;

    public function __construct(int $postid)
    {
        $this->participants = $this->getThreadParticipants($postid);
    }

    public function isParticipant(int $userid)
    {
        return in_array("$userid", $this->participants);
    }

    protected function getThreadParticipants(int $postid)
    {
        $query = implode(' UNION ', [
            'SELECT userid FROM ^posts WHERE postid = #',
            'SELECT DISTINCT userid FROM ^posts WHERE parentid = #',
        ]);

        return qa_db_read_all_values(
            qa_db_query_sub($query, $postid, $postid),
        );
    }
}

function mute_thread(int $userid, int $postid)
{
    qa_db_query_sub(
        "INSERT INTO ^muted_threads (userid, postid) VALUES (#, #)",
        $userid,
        $postid
    );
}

function unmute_thread(int $userid, int $postid)
{
    qa_db_query_sub(
        "DELETE FROM ^muted_threads WHERE userid = # AND postid = #",
        $userid,
        $postid
    );    
}
