<?php

class q2apro_flag_reasons_page
{

    private $directory;
    private $urlToRoot;

    public function load_module($directory, $urlToRoot)
    {
        $this->directory = $directory;
        $this->urlToRoot = $urlToRoot;
    }

    public function suggest_requests()
    {
        return [
            [
                'title'   => 'Ajax Flagger', // title of page
                'request' => 'ajaxflagger', // request name
                'nav'     => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
            ],
        ];
    }

    public function match_request($request)
    {
        return 'ajaxflagger' === $request;
    }

    public function process_request($request)
    {
        /***
         * Incoming reasonid for flags:
         * 1 - spam
         * 2 - quality
         * 3 - rude
         * 4 - edit
         * 5 - migrate
         * 6 - other
         */

        $logged = qa_is_logged_in();
        if (!$logged) {
            exit();
        }

        $transferString = qa_post_text('ajaxdata');

        if (!empty($transferString)) {
            $newData = json_decode($transferString, true);
            $newData = str_replace('&quot;', '"', $newData); // see stackoverflow.com/questions/3110487/

            $questionId = (int) $newData['questionid'];
            $postId     = (int) $newData['postid'];
            $postType   = $newData['posttype'];
            $parentId   = empty($newData['parentid']) ? null : (int) $newData['parentid']; // only C
            $reasonId   = (int) $newData['reasonid'];
            $notice     = empty($newData['notice']) ? null : trim($newData['notice']);

            if (empty($questionId) || empty($postId) || empty($postType) || empty($reasonId)) {
                $reply = ['error' => 'missing'];
                echo json_encode($reply);

                return;
            }

            $userId = qa_get_logged_in_userid();

            $error = '';

            require_once QA_INCLUDE_DIR . 'app/votes.php';
            require_once QA_INCLUDE_DIR . 'pages/question-view.php';

            if ('q' === $postType) {
                $questionData = qa_db_select_with_pending(
                    qa_db_full_post_selectspec($userId, $questionId),
                    qa_db_full_child_posts_selectspec($userId, $questionId),
                    qa_db_full_a_child_posts_selectspec($userId, $questionId),
                    qa_db_post_parent_q_selectspec($questionId),
                    qa_db_post_close_post_selectspec($questionId),
                    false,
                    qa_db_post_meta_selectspec($questionId, 'qa_q_extra'),
                    qa_db_category_nav_selectspec($questionId, true, true, true),
                    isset($userId) ? qa_db_is_favorite_selectspec($userId, QA_ENTITY_QUESTION, $questionId) : null
                );

                // TODO: $parentQuestion, $extraValue, $categories, $favorite - to zmienne nieuzywane w ogole.
                list($question, $childPosts, $aChildPosts,
                    $parentQuestion, $closePost, $duplicatePosts,
                    $extraValue, $categories, $favorite
                    ) = $questionData;

                $error = qa_flag_error_html($question, $userId, $questionId);

                if (!$error) {
                    $handle   = qa_userid_to_handle($userId);
                    $cookieId = qa_cookie_get();

                    $answers         = qa_page_q_load_as($question, $childPosts);
                    $commentsFollows = qa_page_q_load_c_follows($question, $childPosts, $aChildPosts, $duplicatePosts);

                    if (qa_flag_set_tohide($question, $userId, $handle, $cookieId, $question)) {
                        qa_question_set_status(
                            $question, QA_POST_STATUS_HIDDEN, null, null, null, $answers, $commentsFollows, $closePost
                        ); // hiding not really by this user so pass nulls
                    }

                    qa_db_query_sub(
                        '
                        INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`) 
                        VALUES (#, #, #, $)
                    ', $userId, $postId, $reasonId, $notice
                    );
                }
            } elseif ('a' === $postType) {
                $answerId = $postId;

                list($answer, $question, $qChildPosts, $aChildPosts) = qa_db_select_with_pending(
                    qa_db_full_post_selectspec($userId, $answerId),
                    qa_db_full_post_selectspec($userId, $questionId),
                    qa_db_full_child_posts_selectspec($userId, $questionId),
                    qa_db_full_child_posts_selectspec($userId, $answerId)
                );

                $answers         = qa_page_q_load_as($question, $qChildPosts); // todo: dead code - answers nie jest uzywane.
                $commentsFollows = qa_page_q_load_c_follows($question, $qChildPosts, $aChildPosts);

                $error = qa_flag_error_html($answer, $userId, $questionId);

                if (!$error) {
                    $handle   = qa_userid_to_handle($userId);
                    $cookieId = qa_cookie_get();

                    if (qa_flag_set_tohide($answer, $userId, $handle, $cookieId, $question)) {
                        qa_answer_set_status(
                            $answer, QA_POST_STATUS_HIDDEN, null, null, null, $question, $commentsFollows
                        ); // hiding not really by this user so pass nulls
                    }

                    qa_db_query_sub(
                        '
                        INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`) 
                        VALUES (#, #, #, $)
                    ', $userId, $postId, $reasonId, $notice
                    );
                }
            } elseif ('c' === $postType) {

                $commentId = $postId;

                // todo: comment i children nie jest uzywane.
                list($comment, $question, $parent, $children) = qa_db_select_with_pending(
                    qa_db_full_post_selectspec($userId, $commentId),
                    qa_db_full_post_selectspec($userId, $questionId),
                    qa_db_full_post_selectspec($userId, $parentId),
                    qa_db_full_child_posts_selectspec($userId, $parentId)
                );

                $comment = qa_db_select_with_pending(qa_db_full_post_selectspec($userId, $commentId));
                $error   = qa_flag_error_html($comment, $userId, $questionId);

                if (!$error) {
                    $handle   = qa_userid_to_handle($userId);
                    $cookieId = qa_cookie_get();

                    if (qa_flag_set_tohide($comment, $userId, $handle, $cookieId, $question)) {
                        qa_comment_set_status(
                            $comment, QA_POST_STATUS_HIDDEN, null, null, null, $question, $parent
                        ); // hiding not really by this user so pass nulls
                    }

                    qa_db_query_sub(
                        '
                        INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`) 
                        VALUES (#, #, #, $)
                    ', $userId, $postId, $reasonId, $notice
                    );
                }
            }

            if ($error) {
                $reply = ['error' => $error];
                echo json_encode($reply);

                return;
            }

            $reply = ['success' => '1'];
            echo json_encode($reply);

            return;

        } else {
            echo 'Unexpected problem detected. No transfer string.';
            exit();
        }

        return $qa_content;
    }

}
