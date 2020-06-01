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
                'title'   => 'Report flag', // title of page
                'request' => 'report-flag', // request name
                'nav'     => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
            ],
        ];
    }

    public function match_request($request)
    {
        return 'report-flag' === $request;
    }

    public function process_request()
    {
        $requestJSONData = file_get_contents('php://input');
        $this->validateJSON($requestJSONData);
        $flagData = $this->getFlagData($requestJSONData);

        $questionId = (int) $flagData['questionId'];
        $postId     = (int) $flagData['postId'];
        $postType   = $flagData['postType'];
        $reasonId   = (int) $flagData['reasonId'];

        $parentId = empty($flagData['parentid']) ? null : (int) $flagData['parentid']; // only C
        $notice = empty($flagData['notice']) ? null : trim($flagData['notice']);
        $userId = qa_get_logged_in_userid();

        require_once QA_INCLUDE_DIR . 'app/votes.php';
        require_once QA_INCLUDE_DIR . 'app/posts.php';
        require_once QA_INCLUDE_DIR . 'pages/question-view.php';

        $processingFlagReasonError = $this->processFlag($postType, $userId, $postId, $questionId, $reasonId, $notice);

        $reply = $processingFlagReasonError ?
            ['processingFlagReasonError' => $processingFlagReasonError] :
            ['currentFlags' => $this->wrapOutput(q2apro_count_postflags_output($postId), $postType, $postId)];

        echo json_encode($reply);
    }

    private function processFlag($postType, ...$flagParams) {
        $processingFlagReasonError = '';

        switch ($postType) {
            case 'q': {
                $processingFlagReasonError = call_user_func_array([$this, 'processFlagToQuestion'], $flagParams);
                break;
            }
            case 'a': {
                $processingFlagReasonError = call_user_func_array([$this, 'processFlagToAnswer'], $flagParams);
                break;
            }
            case 'c': {
                $processingFlagReasonError = call_user_func_array([$this, 'processFlagToComment'], $flagParams);
                break;
            }
            default: {
                $processingFlagReasonError = 'Incorrect $postType:' . $postType;
            }
        }

        return $processingFlagReasonError;
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

    private function validateJSON($requestJSONData) {
        if (strlen($requestJSONData) > 0) {
            json_decode($requestJSONData);

            if (json_last_error() != JSON_ERROR_NONE) {
                echo json_encode(['processingFlagReasonError' => 'Request is not valid JSON!']);
                exit();
            }
        } else {
            echo json_encode(['processingFlagReasonError' => 'Request is empty!']);
            exit();
        }
    }

    private function getFlagData($requestJSONData) {
        $this->exitIfInvalidEssentials($requestJSONData);

        $flagData = str_replace('&quot;', '"', json_decode($requestJSONData, true)); // see stackoverflow.com/questions/3110487/

        return $flagData;
    }

    private function exitIfInvalidEssentials($flagData) {
        if (!qa_is_logged_in()) {
            echo json_encode(['processingFlagReasonError' => 'Player is logged out!']);
            exit();
        }

        function hasRequiredProps($obj) {
            $optionalKey = 'notice';

            foreach ($obj as $key => $value) {
                if ($key !== $optionalKey && empty($value)) {
                    echo json_encode(['processingFlagReasonError' => 'missing data']);
                    exit();
                }
            }

            return true;
        }

        if (empty($flagData) || !hasRequiredProps($flagData)) {
            echo json_encode(['processingFlagReasonError' => 'Ajax data is empty or not have required data!']);
            exit();
        }
    }

    private function wrapOutput($currentFlags, $postType, $postId) {
        require QA_INCLUDE_DIR . 'qa-theme-base.php';
        require QA_PLUGIN_DIR . 'report-reason/q2apro-flag-reasons-layer.php';

        $postFlagsCount = count(q2apro_get_postflags($postId));

        if ($postFlagsCount) {
            $userHasPrivilege = qa_get_logged_in_level() >= QA_USER_LEVEL_EXPERT && qa_user_level_for_post(qa_post_get_full($postId));
            $flagReasonsInfo = $userHasPrivilege ? ('<br>' .  '<span class="qa-' . $postType . '-item-flags-pad">' . $currentFlags) : '';

            return '<span class="qa-' . $postType . '-item-flags">' .
                    qa_html_theme_layer::prepareFlagSuffix($postFlagsCount) . $flagReasonsInfo .
                    '</span></span>';
        }

        return '';
    }
}
