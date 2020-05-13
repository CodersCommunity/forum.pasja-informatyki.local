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
        $newData =  $this->getFlagData();

        $questionId = (int) $newData['questionid'];
        $postId     = (int) $newData['postid'];
        $postType   = $newData['posttype'];
        $reasonId   = (int) $newData['reasonid'];

        $parentId = empty($newData['parentid']) ? null : (int) $newData['parentid']; // only C
        $notice = empty($newData['notice']) ? null : trim($newData['notice']);
        $userId = qa_get_logged_in_userid();

        require_once QA_INCLUDE_DIR . 'app/votes.php';
        require_once QA_INCLUDE_DIR . 'app/posts.php';
        require_once QA_INCLUDE_DIR . 'pages/question-view.php';

//        $processingFlagError = processFlag($userId, $postId, $questionId, $reasonId, $notice);

        if ('q' === $postType) {
            $processingFlagError = $this->processFlagToQuestion($userId, $postId,$questionId, $reasonId, $notice);
        } elseif ('a' === $postType) {
            $processingFlagError = $this->processFlagToAnswer($userId, $postId, $questionId, $reasonId, $notice);
        } elseif ('c' === $postType) {
            $processingFlagError = $this->processFlagToComment($userId, $postId, $questionId, $reasonId, $notice);
        }

        $reply = $processingFlagError ? ['processingFlagError' => $processingFlagError] : ['currentFlags' => q2apro_count_postflags_output($postId)];

//            var_dump('current flags:' . q2apro_count_postflags_output($postId));

        echo json_encode($reply);
    }

    private function processFlag($userId, $postId, $questionId, $reasonId, $notice) {
        $processingFlagError = '';

        if ('answerId') {
            $processingFlagError = $this->processFlagToAnswer($userId, $postId, $questionId, $reasonId, $notice);
        } else if ('commentId') {
            $processingFlagError = $this->processFlagToComment($userId, $postId, $questionId, $reasonId, $notice);
        } else {
            $processingFlagError = $this->processFlagToQuestion($userId, $postId, $questionId, $reasonId, $notice);
        }

        return $processingFlagError;
    }

    private function processFlagToQuestion($userId, $postId, $questionId, $reasonId, $notice) {
        $questionData = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_a_child_posts_selectspec($userId, $questionId),
            qa_db_post_parent_q_selectspec($questionId),
            false
        );
        list(
            $question, $childPosts, $aChildPosts,
            $closePost, $duplicatePosts
        ) = $questionData;

        $questionFlagError = qa_flag_error_html($question, $userId, $questionId);
        if ($questionFlagError) {
            return $questionFlagError;
        }

        $answers         = qa_page_q_load_as($question, $childPosts);
        $commentsFollows = qa_page_q_load_c_follows($question, $childPosts, $aChildPosts, $duplicatePosts);

        if (qa_flag_set_tohide($question, $userId, qa_userid_to_handle($userId), qa_cookie_get(), $question)) {
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

    private function processFlagToAnswer($userId, $postId, $questionId, $reasonId, $notice) {
        $answerData = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $postId),
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $postId)
        );
        list($answer, $question, $qChildPosts, $aChildPosts) = $answerData;

        // TODO: might not be needed
        // $commentsFollows = qa_page_q_load_c_follows($question, $qChildPosts, $aChildPosts);

        $answerFlagError = qa_flag_error_html($answer, $userId, $questionId);
        if ($answerFlagError) {
            return $answerFlagError;
        }

        if (qa_flag_set_tohide($answer, $userId, qa_userid_to_handle($userId), qa_cookie_get(), $question)) {
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

    private function processFlagToComment($userId, $postId, $questionId, $reasonId, $notice) {
        $comment = qa_db_select_with_pending(qa_db_full_post_selectspec($userId, $postId));

        $commentFlagError = qa_flag_error_html($comment, $userId, $questionId);
        if ($commentFlagError) {
            return $commentFlagError;
        }

        if (qa_flag_set_tohide($comment, $userId, qa_userid_to_handle($userId), qa_cookie_get(), $comment)) {
            qa_post_set_hidden($comment);
        }

        qa_db_query_sub(
            '
            INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
            VALUES (#, #, #, $)
        ', $userId, $postId, $reasonId, $notice
        );
    }

    private function getFlagData() {
        $flagData = qa_post_text('flagData');
        var_dump('$flagData:' . $flagData);

        $this->exitIfInvalidEssentials($flagData);

        $flagData = str_replace('&quot;', '"', json_decode($flagData, true)); // see stackoverflow.com/questions/3110487/
        return $flagData;
    }

    private function exitIfInvalidEssentials($flagData) {
        if (!qa_is_logged_in()) {
            echo('Error: Player is logged out!');
            exit();
        }

        function hasRequiredProps($obj) {
            $optionalKey = 'notice';

            foreach ($obj as $key => $value) {
                if ($key !== $optionalKey && empty($value)) {
                    echo json_encode(['processingFlagError' => 'missing data']);
                    exit();
                }
            }

            return true;
        }

        if (empty($flagData) || !hasRequiredProps($flagData)) {
            echo 'Error: ajax data is empty or not have required data!';
            exit();
        }
    }
}
