<?php

class q2apro_flagreasons_event
{
    public function process_event($event, $userId, $handle, $cookieId, $params)
    {
        $this->processUnflagEvent($event, $userId, $params['postid']);
        $this->processClearflagEvent($event, $params['postid']);
    }

    private function processUnflagEvent($event, $userId, $postId)
    {
        $flagEvents = ['q_unflag', 'a_unflag', 'c_unflag'];

        if (in_array($event, $flagEvents, true)) {
//            echo('??? processUnflagEvent() ??? /$userId: ' . $userId . ' /$postId: ' . $postId);

            qa_db_query_sub('
                DELETE FROM `^flagreasons`
                WHERE userid = #
                AND postid = #
            ', $userId, $postId);
        }
    }

    private function processClearflagEvent($event, $postId)
    {
        $flagEvents = ['q_clearflags', 'a_clearflags', 'c_clearflags'];

        if (in_array($event, $flagEvents, true)) {
//            echo('??? processClearflagEvent() ???');

            qa_db_query_sub('
                DELETE FROM `^flagreasons`
                WHERE postid = #
            ', $postId);
        }
    }
}
