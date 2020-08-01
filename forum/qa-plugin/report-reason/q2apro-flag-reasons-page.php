<?php

class q2apro_flag_reasons_page extends q2apro_flag_reasons_validation
{
    private $directory;
    private $urlToRoot;

    protected $customReportReasonId = 0;
    protected $reportReasonRequestJSON = null;

    public function __construct()
    {
        $reasonListKeys = array_keys(qa_lang('q2apro_flagreasons_lang/REASON_LIST'));
        $this->customReportReasonId = end($reasonListKeys);
    }

    public function load_module($directory, $urlToRoot)
    {
        $this->directory = $directory;
        $this->urlToRoot = $urlToRoot;
    }

    public function suggest_requests()
    {
        return [
            [
                'title' => 'Report flag',
                'request' => 'report-flag',
                'nav' => 'M', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
            ],
        ];
    }

    public function match_request($request)
    {
        return 'report-flag' === $request;
    }

    public function process_request()
    {
        $this->reportReasonRequestJSON = file_get_contents('php://input');

        if (!qa_is_logged_in()) {
            $this->handleReportErrorAndExit('USER_LOGGED_OUT');
        }

        $flagData = $this->parseFlagData();

        $questionId = (int) $flagData['questionId'];
        $parentId = (int) $flagData['relativeParentPostId'];
        $postId = (int) $flagData['postId'];
        $postType = $flagData['postType'];
        $reasonId = (int) $flagData['reasonId'];

        $notice = empty($flagData['notice']) ? null : trim($flagData['notice']);
        $userId = qa_get_logged_in_userid();

        require_once QA_INCLUDE_DIR . 'app/votes.php';
        require_once QA_INCLUDE_DIR . 'app/posts.php';
        require_once QA_INCLUDE_DIR . 'pages/question-view.php';
        require_once QA_INCLUDE_DIR . 'pages/question-submit.php';

        $processingFlagReasonError = $this->processFlag($postType, $parentId, $userId, $postId, $questionId, $reasonId, $notice);

        $reply = $processingFlagReasonError ?
            $this->wrapFlagReasonError($processingFlagReasonError) :
            ['newFlags' => $this->wrapOutput(q2apro_count_postflags_output($postId), $postType, $postId)];

        echo json_encode($reply);
    }

    private function processFlag($postType, $parentId, ...$flagParams)
    {
        $processingFlagReasonError = '';

        switch ($postType) {
            case 'q':
                $processingFlagReasonError = call_user_func_array([$this, 'processFlagToQuestion'], $flagParams);
                break;
            case 'a':
                $processingFlagReasonError = call_user_func_array([$this, 'processFlagToAnswer'], $flagParams);
                break;
            case 'c':
                array_unshift($flagParams, $parentId);
                $processingFlagReasonError = call_user_func_array([$this, 'processFlagToComment'], $flagParams);
                break;
            default:
                $processingFlagReasonError = 'UNRECOGNIZED_POST_TYPE:' . $postType;
        }

        return $processingFlagReasonError;
    }

    private function processFlagToQuestion($userId, $postId, $questionId, $reasonId, $notice)
    {
        [$question, $closePost, $childPosts, $aChildPosts, $duplicatePosts] = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_post_parent_q_selectspec($questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_a_child_posts_selectspec($userId, $questionId),
            false
        );
        $answers = qa_post_get_question_answers($questionId);
        $commentsFollows = qa_page_q_load_c_follows($question, $childPosts, $aChildPosts, $duplicatePosts);

        $questionFlagError = qa_flag_error_html($question, $userId, qa_request());
        if ($questionFlagError) {
            return $questionFlagError;
        }

        if ($this->isPostHidden($question)) {
            $this->handleReportErrorAndExit('POST_HIDDEN');
        }

        if (qa_flag_set_tohide($question, $userId, qa_userid_to_handle($userId), qa_cookie_get(), $question)) {
//            $answers = qa_page_q_load_as($question, $childPosts);
            qa_question_set_hidden($question, true, null, null, null, $answers, $commentsFollows, $closePost);
        }

        $pageError = [];
        if (qa_page_q_single_click_q($question, $answers, $commentsFollows, $closePost, $pageError)) {
            $this->handleReportErrorAndExit('PAGE_NEEDS_RELOAD');
        }

        if ($question != null) {
            qa_db_query_sub('
                INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
                VALUES (#, #, #, $)
            ', $userId, $postId, $reasonId, $notice);
        } else {
            $this->handleReportErrorAndExit('REPORTED_QUESTION_NOT_FOUND');
        }
    }

    private function processFlagToAnswer($userId, $postId, $questionId, $reasonId, $notice)
    {
        [$answer, $question, $qChildPosts, $aChildPosts] = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $postId),
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $questionId),
            qa_db_full_child_posts_selectspec($userId, $postId)
        );
        $commentsFollows = qa_page_q_load_c_follows($question, $qChildPosts, $aChildPosts);
        $answerFlagError = qa_flag_error_html($answer, $userId, qa_request());

        if ($answerFlagError) {
            return $answerFlagError;
        }

        if ($this->isPostHidden($answer) || $this->isPostHidden($question)) {
            $this->handleReportErrorAndExit('POST_HIDDEN');
        }

        if (qa_flag_set_tohide($answer, $userId, qa_userid_to_handle($userId), qa_cookie_get(), $question)) {
            qa_answer_set_hidden($answer, true, null, null, null, $question, $commentsFollows);
        }

        $answers = qa_post_get_question_answers($questionId);
        $pageError = [];

        if (qa_page_q_single_click_a($answer, $question, $answers, $commentsFollows, true, $pageError)) {
            $this->handleReportErrorAndExit('PAGE_NEEDS_RELOAD');
        }

        if ($answer != null) {
            qa_db_query_sub('
                INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
                VALUES (#, #, #, $)
            ', $userId, $answer['postid'], $reasonId, $notice);
        } else {
            $this->handleReportErrorAndExit('REPORTED_ANSWER_NOT_FOUND');
        }
    }

    private function processFlagToComment($parentId, $userId, $postId, $questionId, $reasonId, $notice)
    {
        [$question, $parent, $comment] = qa_db_select_with_pending(
            qa_db_full_post_selectspec($userId, $questionId),
            qa_db_full_post_selectspec($userId, $parentId),
            qa_db_full_post_selectspec($userId, $postId)
        );
        $commentFlagError = qa_flag_error_html($comment, $userId, qa_request());

        if ($commentFlagError) {
            return $commentFlagError;
        }

        if ($this->isPostHidden($comment) || $this->isPostHidden($question) || $this->isPostHidden($parent)) {
            $this->handleReportErrorAndExit('POST_HIDDEN');
        }

        if (qa_flag_set_tohide($comment, $userId, qa_userid_to_handle($userId), qa_cookie_get(), $comment)) {
            qa_comment_set_hidden($comment, true, null, null, null, $question, $parent);
        }

        $pageError = [];

        if (qa_page_q_single_click_c($comment, $question, $parent, $pageError)) {
            $this->handleReportErrorAndExit('PAGE_NEEDS_RELOAD');
        }

        if ($comment != null) {
            qa_db_query_sub('
                INSERT INTO `^flagreasons` (`userid`, `postid`, `reasonid`, `notice`)
                VALUES (#, #, #, $)
            ', $userId, $postId, $reasonId, $notice);
        } else {
            $this->handleReportErrorAndExit('REPORTED_COMMENT_NOT_FOUND');
        }
    }

    private function parseFlagData()
    {
        $flagData = json_decode($this->reportReasonRequestJSON, true);

        if (!$this->isValidJSON() ||
            !$this->isRequestSecureCodeValid($flagData['relativeParentPostId'], $flagData['code']) ||
            !$this->isDataSet($flagData) ||
            !$this->isValidData($flagData)
        ) {
            exit();
        }

        if ($this->isAlreadyFlaggedByLogged($flagData['postId'])) {
            $this->handleReportErrorAndExit('ALREADY_FLAGGED');
        }

        return $flagData;
    }

    private function wrapOutput($newFlags, $postType, $postId)
    {
        require QA_INCLUDE_DIR . 'qa-theme-base.php';
        require QA_PLUGIN_DIR . 'report-reason/q2apro-flag-reasons-layer.php';

        $postFlagsCount = count(q2apro_get_postflags($postId));

        if ($postFlagsCount) {
            $userHasPrivilege = qa_get_logged_in_level() >= QA_USER_LEVEL_EXPERT
                && qa_user_level_for_post(qa_post_get_full($postId));
            $relativeClassNamePart = $postType === 'q' ? '-view' : '-item';
            $flagReasonsInfo = $userHasPrivilege ?
                ('<span class="qa-' . $postType . $relativeClassNamePart . '-flags-pad">' . $newFlags) :
                '<br>';

            return '<span class="qa-' . $postType . $relativeClassNamePart . '-flags">'
                . qa_html_theme_layer::prepareFlagSuffix($postFlagsCount) . $flagReasonsInfo . '</span></span>';
        }

        return '';
    }
}
