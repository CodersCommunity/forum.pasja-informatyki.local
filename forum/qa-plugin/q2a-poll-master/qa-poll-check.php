<?php

class qa_poll_event
{
    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        if (qa_opt('poll_enable') && $event === 'q_post' && qa_post_text('is_poll') === '1') {
            qa_db_query_sub(
                'INSERT INTO ^postmeta (post_id,meta_key,meta_value) VALUES (#,$,$)',
                $params['postid'],
                'is_poll',
                (qa_post_text('poll_multiple') ? '2' : '1')
            );

            $count = 0;
            while (isset($_POST['poll_answer_' . (++$count)])) {
                if (!qa_post_text('poll_answer_' . $count)) {
                    continue;
                }

                qa_db_query_sub(
                    'INSERT INTO ^polls (parentid,content) VALUES (#,$)',
                    $params['postid'],
                    qa_post_text('poll_answer_' . $count)
                );
            }
        }
    }

    public function init_queries($tableslc)
    {
        $queries = [];

        if (!in_array(qa_db_add_table_prefix('postmeta'), $tableslc)) {
            $queries[] = 'CREATE TABLE IF NOT EXISTS ^postmeta (
                meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                post_id bigint(20) unsigned NOT NULL,
                meta_key varchar(255) DEFAULT \'\',
                meta_value longtext,
                PRIMARY KEY (meta_id),
                KEY post_id (post_id),
                KEY meta_key (meta_key)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8';
        }

        if (!in_array(qa_db_add_table_prefix('polls'), $tableslc)) {
            $queries[] = 'CREATE TABLE IF NOT EXISTS ^polls (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                parentid bigint(20) unsigned NOT NULL,
                votes longtext,
                content varchar(255) DEFAULT \'\',
                PRIMARY KEY (id)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8';
        }

        return $queries;
    }
}
