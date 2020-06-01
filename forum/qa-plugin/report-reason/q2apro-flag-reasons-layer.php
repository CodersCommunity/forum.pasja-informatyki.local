<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    private $isLogged;

    public function head_script()
    {
        parent::head_script();
        $this->isLogged = qa_is_logged_in();

        if ($this->isLogged && 'question' === $this->template) {
            require_once QA_PLUGIN_DIR . 'report-reason/q2apro-flag-reasons-admin.php';

            $flagReasonNoticeLength = ['NOTICE_LENGTH' => q2apro_flagreasons_admin::NOTICE_LENGTH];
            $reportFlagList = ['REASON_LIST' => qa_lang('q2apro_flagreasons_lang/REASON_LIST')];
            $reportPopupLabels = ['POPUP_LABELS' => qa_lang('q2apro_flagreasons_lang/POPUP_LABELS')];

            $flagReasonsMetadata = json_encode(array_merge($flagReasonNoticeLength, $reportFlagList, $reportPopupLabels));

            $this->output(
            '
                <link rel="stylesheet" href="' . QA_HTML_THEME_LAYER_URLTOROOT . 'frontend/style.css">
                <script>
                    const FLAG_REASONS_METADATA = Object.freeze(' . $flagReasonsMetadata . ');
                </script>
                <script type="text/javascript" src="' . QA_HTML_THEME_LAYER_URLTOROOT . 'frontend/dist/script.js"></script>
            '
            );
        }
    }

    public function post_meta_flags($post, $class)
    {
        if (in_array($class, ['qa-a-item', 'qa-c-item', 'qa-q-view'])
            || ($class === 'qa-q-item' && isset($post['form']))) {
            $postId = $post['raw']['opostid'] ?? $post['raw']['postid'];
            $flagInfo = q2apro_count_postflags_output($postId);

            if (!empty($flagInfo) && qa_get_logged_in_level() >= QA_USER_LEVEL_EXPERT && qa_user_level_for_post($post['raw'])) {
                $flagsCount = count(q2apro_get_postflags($postId));

                unset($post['flags']);

                $post['flags']['data'] = self::prepareFlagSuffix($flagsCount);
                $post['flags']['suffix'] = $flagInfo;
            }
        }

        parent::post_meta_flags($post, $class);
    }

    public static function prepareFlagSuffix($flagsCount)
    {
        $flagsCountText = '';

        if (1 === $flagsCount) {
            $flagsCountText = ' zgłoszenie';
        } elseif ((1 === $flagsCount%10 && 1 !== $flagsCount) || (4 < $flagsCount%10)) {
            $flagsCountText = ' zgłoszeń';
        } elseif (1 < $flagsCount%10 && 5 > $flagsCount%10) {
            $flagsCountText = ' zgłoszenia';
        }

        return $flagsCount . $flagsCountText;
    }

    private function checkPostData($item)
    {
        return $this->isLogged && isset($item['form']['buttons']['flag'], $item['raw']['postid']);
    }
}
