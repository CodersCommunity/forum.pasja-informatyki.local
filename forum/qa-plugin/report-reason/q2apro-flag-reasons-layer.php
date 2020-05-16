<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    private $isLogged;

    public function head_script()
    {
        parent::head_script();
        $this->isLogged = qa_is_logged_in();

        if ($this->isLogged && 'question' === $this->template) {
            $this->output('
                <script type="text/javascript" src="' . QA_HTML_THEME_LAYER_URLTOROOT . '/dist/script.js"></script>
                <link rel="stylesheet" href="' . QA_HTML_THEME_LAYER_URLTOROOT . 'style.css">
            '
            );
        }
    }

    public function q_view_buttons($q_view)
    {
        if ($this->checkPostData($q_view)) {
//            $q_view['form']['buttons']['flag']['tags'] =
//                'data-postid="' . $q_view['raw']['postid'] . '" data-posttype="q" ';
        }
        parent::q_view_buttons($q_view);
    }

    public function a_item_buttons($a_item)
    {
        if ($this->checkPostData($a_item)) {
//            $a_item['form']['buttons']['flag']['tags'] =
//                'data-postid="' . $a_item['raw']['postid'] . '" data-posttype="a" ';
        }
        parent::a_item_buttons($a_item);
    }

    public function c_item_buttons($c_item)
    {
        if ($this->checkPostData($c_item)) {
//            $c_item['form']['buttons']['flag']['tags'] = 'data-postid="' . $c_item['raw']['postid'] . '" data-posttype="c" data-parentid="' . $c_item['raw']['parentid'] . '" ';
        }
        parent::c_item_buttons($c_item);
    }

    public function body_hidden()
    {
        if ($this->isLogged && 'question' === $this->template) {
            $this->output(
                '
            <div id="flagbox-popup" class="modal-background flagbox-popup" hidden>
                <div class="flagbox-center">
                    <div class="qa-flag-reasons-wrap">
                        <p><b>
                          '
                          . qa_lang('q2apro_flagreasons_lang/reason')
                          . '
                        </b></p>
                        ');
                        for ($i=1; $i<=6; $i++) {
                            $this->output('<label for="qa-spam-reason-radio-' . $i . '">
                            <input type="radio" class="qa-spam-reason-radio" name="qa-spam-reason-radio" id="qa-spam-reason-radio-' . $i . '" value="' . $i . '">
                            '
                            . q2apro_flag_reasonid_to_readable($i)
                            . '
                            </label>');
                        }
                        $this->output('
                        <p><b>
                            '
                            . qa_lang('q2apro_flagreasons_lang/note')
                            . '
                        </b></p>
                        <div class="qa-spam-reason-text-wrap">
                            <input type="text" name="qa-spam-reason-text" class="qa-spam-reason-text" placeholder="'
                . qa_lang('q2apro_flagreasons_lang/enter_details')
                . '">
                        <div id="qa-spam-reason-error" class="qa-error" hidden></div></div>

                        <input type="button" class="qa-form-tall-button qa-form-tall-button-ask qa-form-wide-text qa-go-flag-send-button" value="'
                . qa_lang('q2apro_flagreasons_lang/send')
                . '">

                        <button class="close-preview-btn">X</button>
                    </div>
                </div>
            </div>
            ');
        }
        parent::body_hidden();
    }

    public function post_meta_flags($post, $class)
    {
        if (in_array($class, ['qa-q-item', 'qa-a-item', 'qa-c-item', 'qa-q-view'])) {
            if (isset($post['raw']['postid'])) {
                $postId = (empty(q2apro_count_postflags_output($post['raw']['postid'])) && isset($post['raw']['opostid'])) ? $post['raw']['opostid'] : $post['raw']['postid'];
                $flagInfo = q2apro_count_postflags_output($postId);

                if (!empty($flagInfo) && qa_get_logged_in_level() > QA_USER_LEVEL_EXPERT) {
                    $flagsCount = count(q2apro_get_postflags($postId));

                    unset($post['flags']);

                    $post['flags']['suffix'] = $this->prepareFlagSuffix($flagsCount);
                    $post['flags']['suffix'] .= ': <br>' . $flagInfo;
                }
            }
        }
        parent::post_meta_flags($post, $class);
    }

    private function prepareFlagSuffix($flagsCount)
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
