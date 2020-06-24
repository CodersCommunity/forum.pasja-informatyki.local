<?php

class q2apro_flag_reasons_validation {
    private $propsModel = [
        'notice' => '',
        'postId' => 0,
        'postType' => '',
        'questionId' => 0,
        'relativeParentPostId' => 0,
        'code' => '',
        'reasonId' => 0,
        'reportType' => 'addFlag'
    ];

    protected function wrapFlagReasonError($errorCode) {
        return ['processingFlagReasonError' => $errorCode];
    }

    protected function handleReportError($errorCode) {
        $wrappedErrorCode = $this->wrapFlagReasonError($errorCode);
        echo json_encode($wrappedErrorCode);

        $wrappedErrorCode['_reportReasonRequestJSON'] = $this->_reportReasonRequestJSON;
        error_log(json_encode($wrappedErrorCode));
    }

    protected function outputErrorToResponseAndExit($errorCode) {
        $this->handleReportError(errorCode);
        exit();
    }

    protected function isValidJSON() {
        if (json_last_error() != JSON_ERROR_NONE) {
            $this->handleReportError('REQUEST_IS_NOT_VALID_JSON');
            return false;
        }

        return true;
    }

    protected function isDataSet($data) {
        if (empty($data)) {
            $this->handleReportError('EMPTY_REQUEST');
            return false;
        }

        return true;
    }

    protected function isRequestSecureCodeValid($relativeParentPostId, $code) {
        $postAction = 'buttons-' . $relativeParentPostId;

        if (!qa_check_form_security_code($postAction, $code)) {
            $this->handleReportError('INVALID_SECURITY_CODE');
            return false;
        }

        return true;
    }

    protected function isValidData($props) {
        $propsKeys = array_keys($props);
        $matchedPropsKeysNum = 0;
        $noticeProp = 'notice';
        $reportTypeProp = 'reportType';

//            var_dump('$props: ' ,  $props);

        foreach ($this->propsModel as $key => $value) {
            $propValue = $props[$key] ?? null;
//                echo('$key: '. $key. ' /$propValue: '. $propValue. ' /$this->propsModel[$key]: '.$this->propsModel[$key]);

            /*
                TODO:
                    -   Should validate if specified post (question, answer or comment) is already flagged by the User?
            */
            if (
                !$this->isRequestPropMatched($key, $propsKeys) ||
                !$this->isRequestPropSet($propValue, $key, $noticeProp, $props['reasonId']) ||
                !$this->hasRequestPropCorrectType($propValue, $this->propsModel[$key]) ||
                !$this->hasRequestReportTypeCorrectValue($key, $reportTypeProp, $propValue, $this->propsModel[$key])
            ) {
                return false;
            }

            $matchedPropsKeysNum++;
        }

        if (!$this->isRequestPropsCorrectSize($propsKeys, $matchedPropsKeysNum)) {
            return false;
        }

        return true;
    }

    protected function isRequestPropMatched($key, $propsKeys) {
        if (!in_array($key, $propsKeys)) {
            $this->handleReportError('UNMATCHED_REQUEST_PROP');
            return false;
        }

        return true;
    }

    protected function isRequestPropSet($propValue, $key, $noticeProp, $reasonIdProp) {
//        echo('<br>$propValue: '. $propValue. ' /$key: '. $key. ' /$noticeProp: '. $noticeProp. ' /$reasonIdProp: '. $reasonIdProp. ' /$this->CUSTOM_REPORT_REASON_ID: '. $this->CUSTOM_REPORT_REASON_ID);
        if ($propValue === null || $propValue === '') {
            if ($key !== $noticeProp) {
                $this->handleReportError('NO_REASON_CHECKED');
                return false;
            } else if ($reasonIdProp === $this->CUSTOM_REPORT_REASON_ID) {
                $this->handleReportError('CUSTOM_REASON_EMPTY');
                return false;
            }
        } else if ($key === $noticeProp && $reasonIdProp !== $this->CUSTOM_REPORT_REASON_ID) {
            $this->handleReportError('INAPPROPRIATE_CUSTOM_REASON_USAGE');
            return false;
        }

        return true;
    }

    protected function hasRequestPropCorrectType($propValue, $propsModelValue) {
        if (gettype($propValue) !== gettype($propsModelValue)) {
            $this->handleReportError('INCORRECT_REQUEST_PROP_TYPE');
            return false;
        }

        return true;
    }

    protected function hasRequestReportTypeCorrectValue($key, $reportTypeProp, $propValue, $propsModelValue) {
        if ($key === $reportTypeProp && $propValue !== $propsModelValue) {
            $this->handleReportError('INCORRECT_REPORT_TYPE');
            return false;
        }

        return true;
    }

    protected function isRequestPropsCorrectSize($propsKeys, $matchedPropsKeysNum) {
        if (count($propsKeys) !== $matchedPropsKeysNum) {
            $this->handleReportError('REQUEST_NOT_CONTAIN_ALL_PROPS');
            return false;
        }

        return true;
    }
}
