<?php

class q2apro_flag_reasons_validation {
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

    protected function isValidData($props) {
        $propsModel = [
            'notice' => '',
            'postId' => 0,
            'postType' => '',
            'questionId' => 0,
            'reasonId' => 0,
            'reportType' => 'addFlag'
        ];
        $propsKeys = array_keys($props);
        $matchedPropsKeysNum = 0;
        $noticeProp = 'notice';
        $reportTypeProp = 'reportType';

//            var_dump('$props: ' ,  $props);

        foreach ($propsModel as $key => $value) {
            $propValue = @$props[$key];
//                echo('$key: '. $key. ' /$propValue: '. $propValue. ' /$propsModel[$key]: '.$propsModel[$key]);

            /*
                TODO:
                    -   Should validate against request URL referrer property?
                        To check if i.e questionId request property is contained by page URL.
                    -   Should validate if specified post (question, answer or comment) is already flagged by the User?
                    -   Should validate post security 'code' contained by <input [hidden]> on page?
            */
            if (
                !$this->isRequestPropMatched($key, $propsKeys) ||
                !$this->isRequestPropSet($propValue, $key, $noticeProp, $props['reasonId']) ||
                !$this->hasRequestPropCorrectType($propValue, $propsModel[$key]) ||
                !$this->hasRequestReportTypeCorrectValue($key, $reportTypeProp, $propValue, $propsModel[$key])
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