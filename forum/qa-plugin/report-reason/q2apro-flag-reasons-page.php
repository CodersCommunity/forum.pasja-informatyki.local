<?php

class q2apro_flag_reasons_page extends q2apro_flag_reasons_validation {

    private $directory;
    private $urlToRoot;

    protected $CUSTOM_REPORT_REASON_ID = 0;
    protected $_reportReasonRequestJSON = null;

    public function __construct() {
        $REASON_LIST_KEYS = array_keys(qa_lang('q2apro_flagreasons_lang/REASON_LIST'));
        $this->CUSTOM_REPORT_REASON_ID = end($REASON_LIST_KEYS);
    }

    public function load_module($directory, $urlToRoot) {
        $this->directory = $directory;
        $this->urlToRoot = $urlToRoot;
    }

    public function suggest_requests() {
        return [
            [
                'title'   => 'Report flag', // title of page
                'request' => 'report-flag', // request name
                'nav'     => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
            ],
        ];
    }

    public function match_request($request) {
        return 'report-flag' === $request;
    }

    public function process_request() {
        $this->_reportReasonRequestJSON = file_get_contents('php://input');

        if (!qa_is_logged_in()) {
            $this->handleReportErrorAndExit('USER_LOGGED_OUT');
        }

        $flagData = $this->parseFlagData();

        $questionId = (int) $flagData['questionId'];
        $postId     = (int) $flagData['postId'];
        $postType   = $flagData['postType'];
        $reasonId   = (int) $flagData['reasonId'];

        $notice = empty($flagData['notice']) ? null : trim($flagData['notice']);
        $userId = qa_get_logged_in_userid();

        require_once QA_INCLUDE_DIR . 'app/votes.php';
        require_once QA_INCLUDE_DIR . 'app/posts.php';
        require_once QA_INCLUDE_DIR . 'pages/question-view.php';

        $processingFlagReasonError = $this->processFlag($postType, $userId, $postId, $questionId, $reasonId, $notice);

        $reply = $processingFlagReasonError ?
            $this->wrapFlagReasonError($processingFlagReasonError) :
            ['newFlags' => $this->wrapOutput(q2apro_count_postflags_output($postId), $postType, $postId)];

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
                $processingFlagReasonError = 'UNRECOGNIZED_POST_TYPE:' . $postType;
            }
        }

        return $processingFlagReasonError;
    }

    private function processFlagToQuestion($userId, $postId, $questionId, $reasonId, $notice) {
        list($question, $childPosts, $aChildPosts, $closePost, $duplicatePosts) = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_a_child_posts_selectspec($userId, $questionId),
            qa_db_post_parent_q_selectspec($questionId),
            false
        );

        $questionFlagError = qa_flag_error_html($question, $userId, $questionId);
        if ($questionFlagError) {
            return $questionFlagError;
        }

        $answers = qa_page_q_load_as($question, $childPosts);
        $commentsFollows = qa_page_q_load_c_follows($question, $childPosts, $aChildPosts, $duplicatePosts);

        if (qa_flag_set_tohide($question, $userId, qa_userid_to_handle($userId), qa_cookie_get(), $question)) {
            qa_question_set_status(
                $question, QA_POST_STATUS_HIDDEN, null, null, null, $answers, $commentsFollows, $closePost
            ); // hiding not really by this user so pass nulls
        }

//        var_dump('<br>$reasonId <= $this->CUSTOM_REPORT_REASON_ID: ', $reasonId <= $this->CUSTOM_REPORT_REASON_ID, ' /$this->CUSTOM_REPORT_REASON_ID: ', $this->CUSTOM_REPORT_REASON_ID);
        if($reasonId >= 0 && $reasonId <= $this->CUSTOM_REPORT_REASON_ID) {
            qa_db_query_sub(
            '
                INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
                VALUES (#, #, #, $)
            ', $userId, $postId, $reasonId, $notice
            );
        } else {
             $this->handleReportErrorAndExit('INVALID_REASON_ID');
         }
    }

    private function processFlagToAnswer($userId, $postId, $questionId, $reasonId, $notice) {
        list($answer, $question, $qChildPosts, $aChildPosts) = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $postId),
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $postId)
        );

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
        } else {
            $this->handleReportErrorAndExit('EMPTY_ANSWER_PARAM');
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

    private function parseFlagData() {
        $flagData = json_decode($this->_reportReasonRequestJSON, true);

        if (
                !$this->isValidJSON() ||
                !$this->isRequestSecureCodeValid($flagData['relativeParentPostId'], $flagData['code']) ||
                !$this->isDataSet($flagData) ||
                !$this->isValidData($flagData)
            ) {
            exit();
        }

        return $flagData;
    }

    private function wrapOutput($newFlags, $postType, $postId) {
        require QA_INCLUDE_DIR . 'qa-theme-base.php';
        require QA_PLUGIN_DIR . 'report-reason/q2apro-flag-reasons-layer.php';

        $postFlagsCount = count(q2apro_get_postflags($postId));

        if ($postFlagsCount) {
            $userHasPrivilege = qa_get_logged_in_level() >= QA_USER_LEVEL_EXPERT && qa_user_level_for_post(qa_post_get_full($postId));
            $relativeClassNamePart = $postType === 'q' ? '-view' : '-item';
            $flagReasonsInfo = $userHasPrivilege ?
                ('<span class="qa-' . $postType . $relativeClassNamePart . '-flags-pad">' . $newFlags) :
                '<br>';

            return '<span class="qa-' . $postType . $relativeClassNamePart . '-flags">' .
                    qa_html_theme_layer::prepareFlagSuffix($postFlagsCount) . $flagReasonsInfo .
                    '</span></span>';
        }

        return '';
    }
}
