<?php

class socket_integration_comments_page
{
    public function match_request($request)
    {
        return $request === 'socket/comments';
    }

    public function process_request()
    {
        require_once QA_INCLUDE_DIR . 'pages/question-view.php';

        $userId = qa_get_logged_in_userid();
        $postId = qa_get('post_id');
        $lastId = qa_get('last_id');

        $post = qa_db_select_with_pending(qa_db_full_post_selectspec($userId, $postId));
        if ($post === null || !in_array($post['basetype'], ['Q', 'A'])) {
            http_response_code(404);
            return;
        }

        if ($post['basetype'] === 'Q') {
            $question = $post;
        } else {
            $question = qa_db_select_with_pending(qa_db_full_post_selectspec($userId, $post['parentid']));
        }

        $commentsSelect = qa_db_full_child_posts_selectspec($userId, $post['postid']);
        if ($lastId !== null) {
            $commentsSelect['source'] .= ' AND ^posts.postid > #';
            $commentsSelect['arguments'][] = (int)$lastId;
        }
        $comments = qa_db_select_with_pending($commentsSelect);
        qa_sort_by($comments, 'created');
        $post = array_merge($post, qa_page_q_post_rules($post, null, null, $comments));

        foreach ($comments as $id => $comment) {
            $comments[$id] = array_merge($comment, qa_page_q_post_rules($comment, $post, $comments, null));
        }
        $usersHtml = qa_userids_handles_html($comments, true);
        $commentsList = qa_page_q_comment_follow_list($question, $post, $comments, true, $usersHtml, false, null);

        $themeClass = qa_load_theme_class(qa_get_site_theme(), 'ajax-comments', null, null);
        $themeClass->initialize();
        $themeClass->c_list_items($commentsList['cs']);
    }
}
