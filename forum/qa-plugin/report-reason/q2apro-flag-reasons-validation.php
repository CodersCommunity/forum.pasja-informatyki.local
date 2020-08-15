<?php

class q2apro_flag_reasons_validation
{
    private $propsModel = [
        'notice' => '',
        'postId' => -1,
        'postType' => '',
        'questionId' => -1,
        'relativeParentPostId' => -1,
        'code' => '',
        'reasonId' => -1,
        'reportType' => 'addFlag'
    ];

    protected function wrapFlagReasonError($errorCode)
    {
        return ['processingFlagReasonError' => $errorCode];
    }

    protected function handleReportError($errorCode)
    {
        $wrappedErrorCode = $this->wrapFlagReasonError($errorCode);
        echo json_encode($wrappedErrorCode);

        $wrappedErrorCode['_reportReasonRequestJSON'] = $this->reportReasonRequestJSON;
        error_log(json_encode($wrappedErrorCode));
    }

    protected function handleReportErrorAndExit($errorCode)
    {
        $this->handleReportError($errorCode);
        exit();
    }

    protected function isValidJSON()
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->handleReportError('REQUEST_IS_NOT_VALID_JSON');
            return false;
        }

        return true;
    }

    protected function isDataSet($data)
    {
        if (empty($data)) {
            $this->handleReportError('EMPTY_REQUEST');
            return false;
        }

        return true;
    }

    protected function isRequestSecureCodeValid($relativeParentPostId, $code)
    {
        $postAction = 'buttons-' . $relativeParentPostId;

        if (!qa_check_form_security_code($postAction, $code)) {
            $this->handleReportError('INVALID_SECURITY_CODE');
            return false;
        }

        return true;
    }

    protected function isValidData($props)
    {
        $propsKeys = array_keys($props);
        $matchedPropsKeysNum = 0;
        $noticeProp = 'notice';
        $reportTypeProp = 'reportType';
        $reasonIdValue = $props['reasonId'] ?? $this->propsModel['reasonId'];

        foreach ($this->propsModel as $key => $value) {
            $propValue = $props[$key] ?? null;

            if (!$this->isRequestPropMatched($key, $propsKeys) ||
                !$this->isRequestPropSet($propValue, $key, $noticeProp, $reasonIdValue) ||
                !$this->hasRequestPropCorrectType($propValue, $this->propsModel[$key]) ||
                !$this->hasRequestReportTypeCorrectValue($key, $reportTypeProp, $propValue, $this->propsModel[$key])
            ) {
                return false;
            }

            $matchedPropsKeysNum++;
        }

        if (!$this->isCorrectReasonId($props['reasonId'], $props['notice'])
            || !$this->isRequestPropsCorrectSize($propsKeys, $matchedPropsKeysNum)
        ) {
            return false;
        }

        return true;
    }

    protected function isRequestPropMatched($key, $propsKeys)
    {
        if (!in_array($key, $propsKeys)) {
            $this->handleReportError('UNMATCHED_REQUEST_PROP');
            return false;
        }

        return true;
    }

    protected function isRequestPropSet($propValue, $key, $noticeProp, $reasonIdValue)
    {
        if ($propValue === null || $propValue === '') {
            if ($key !== $noticeProp) {
                $this->handleReportError('NO_REASON_CHECKED');
                return false;
            } else {
                if ($reasonIdValue === $this->customReportReasonId) {
                    $this->handleReportError('CUSTOM_REASON_EMPTY');
                    return false;
                }
            }
        }

        return true;
    }

    protected function hasRequestPropCorrectType($propValue, $propsModelValue)
    {
        if (gettype($propValue) !== gettype($propsModelValue)) {
            $this->handleReportError('INCORRECT_REQUEST_PROP_TYPE');
            return false;
        }

        return true;
    }

    protected function hasRequestReportTypeCorrectValue($key, $reportTypeProp, $propValue, $propsModelValue)
    {
        if ($key === $reportTypeProp && $propValue !== $propsModelValue) {
            $this->handleReportError('INCORRECT_REPORT_TYPE');
            return false;
        }

        return true;
    }

    protected function isCorrectReasonId($reasonId, $noticeValue)
    {
        if ($reasonId < 0 || $reasonId > $this->customReportReasonId) {
            $this->handleReportError('INVALID_REASON_ID');
            return false;
        } else {
            if ($noticeValue && $reasonId !== $this->customReportReasonId) {
                $this->handleReportError('INAPPROPRIATE_CUSTOM_REASON_USAGE');
                return false;
            }
        }

        return true;
    }

    protected function isRequestPropsCorrectSize($propsKeys, $matchedPropsKeysNum)
    {
        if (count($propsKeys) !== $matchedPropsKeysNum) {
            $this->handleReportError('REQUEST_NOT_CONTAIN_ALL_PROPS');
            return false;
        }

        return true;
    }

    protected function isAlreadyFlaggedByLogged($postId)
    {
        $userId = qa_get_logged_in_userid();
        $postsCount = qa_db_read_one_value(qa_db_query_sub(
            'SELECT COUNT(userid) FROM `^flagreasons` WHERE userid=# AND postid=#',
            $userId,
            $postId
        ));

        return $postsCount > 0;
    }

    protected function isPostHidden($post)
    {
        return strpos($post['type'], 'HIDDEN') !== false;
    }
}
