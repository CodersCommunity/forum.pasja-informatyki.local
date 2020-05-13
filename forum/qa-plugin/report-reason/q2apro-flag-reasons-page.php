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
        $logged = qa_is_logged_in();
        if (!$logged) {
            exit();
        }

        $transferString = qa_post_text('ajaxdata');
        var_dump('$transferString', $transferString);

        if (empty($transferString)) {
            echo 'Unexpected problem detected. No transfer string.';
            exit();
        }

        $newData = json_decode($transferString, true);
        $newData = str_replace('&quot;', '"', $newData); // see stackoverflow.com/questions/3110487/

        $questionId = (int) $newData['questionid'];
        $postId     = (int) $newData['postid'];
        $postType   = $newData['posttype'];
        $parentId   = empty($newData['parentid']) ? null : (int) $newData['parentid']; // only C
        $reasonId   = (int) $newData['reasonid'];
        $notice     = empty($newData['notice']) ? null : trim($newData['notice']);

        if (empty($questionId) || empty($postId) || empty($postType) || empty($reasonId)) {
            $reply = ['error' => 'missing data'];
            echo json_encode($reply);

            return;
        } else {

        }

        $userId = qa_get_logged_in_userid();

        require_once QA_INCLUDE_DIR . 'app/votes.php';
        require_once QA_INCLUDE_DIR . 'app/posts.php';
        require_once QA_INCLUDE_DIR . 'pages/question-view.php';

        if ('answerId') {
            //$this->processFlagToAnswer();
        } else if ('commentId') {
            //$this->processFlagToComment();
        } else {
            //$this->processFlagToQuestion();
        }

        if ('q' === $postType) {
            $processingFlagError = $this->processFlagToQuestion();
        } elseif ('a' === $postType) {
            $processingFlagError = $this->processFlagToAnswer();
        } elseif ('c' === $postType) {
            $processingFlagError = $this->processFlagToComment();
        }

        $reply = $processingFlagError ? ['processingFlagError' => $processingFlagError] : ['currentFlags' => q2apro_count_postflags_output($postId)];

//            var_dump('current flags:' . q2apro_count_postflags_output($postId));

        echo json_encode($reply);
    }

    private function processFlagToQuestion() {
        $questionData = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_a_child_posts_selectspec($userId, $questionId),
            qa_db_post_parent_q_selectspec($questionId),
            false
        );

        list($question, $childPosts, $aChildPosts,
            $closePost, $duplicatePosts
            ) = $questionData;

        $flagError = qa_flag_error_html($question, $userId, $questionId);

        if ($flagError) {
            return $flagError;
        }

        $handle   = qa_userid_to_handle($userId);
        $cookieId = qa_cookie_get();

        $answers         = qa_page_q_load_as($question, $childPosts);
        $commentsFollows = qa_page_q_load_c_follows($question, $childPosts, $aChildPosts, $duplicatePosts);

        if (qa_flag_set_tohide($question, $userId, $handle, $cookieId, $question)) {
            qa_question_set_status(
                $question, QA_POST_STATUS_HIDDEN, null, null, null, $answers, $commentsFollows, $closePost
            ); // hiding not really by this user so pass nulls
        }
        if($reasonId >= 0 && $reasonId <= 6) {
            qa_db_query_sub(
            '
                INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
                VALUES (#, #, #, $)
            ', $userId, $postId, $reasonId, $notice
            );
        }
    }

    private function processFlagToAnswer() {
        $answerId = $postId;

        list($answer, $question, $qChildPosts, $aChildPosts) = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $answerId),
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $answerId)
        );

        $commentsFollows = qa_page_q_load_c_follows($question, $qChildPosts, $aChildPosts);

        $flagError = qa_flag_error_html($answer, $userId, $questionId);
        if ($flagError) {
            return $flagError;
        }

        $handle   = qa_userid_to_handle($userId);
        $cookieId = qa_cookie_get();

        if (qa_flag_set_tohide($answer, $userId, $handle, $cookieId, $question)) {
            qa_answer_set_status(
                $answer, QA_POST_STATUS_HIDDEN, null, null, null, $question, null//$commentsFollows
            ); // hiding not really by this user so pass nulls
        }

        if ($answer != null) {
            qa_db_query_sub(
                '
                    INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
                    VALUES (#, #, #, $)
                ', $userId, $answer['postid'], $reasonId, $notice
            );
        }
    }

    private function processFlagToComment() {
        $commentId = $postId;
        $comment = qa_db_select_with_pending(qa_db_full_post_selectspec($userId, $commentId));
        $error   = qa_flag_error_html($comment, $userId, $questionId);

        if (!$error) {
            $handle   = qa_userid_to_handle($userId);
            $cookieId = qa_cookie_get();

            if (qa_flag_set_tohide($comment, $userId, $handle, $cookieId, $comment)) {
                qa_post_set_hidden($comment);
            }

            qa_db_query_sub(
                '
                INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
                VALUES (#, #, #, $)
            ', $userId, $postId, $reasonId, $notice
            );
        }
    }
}
