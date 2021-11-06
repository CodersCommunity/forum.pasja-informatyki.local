<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    function head_css()
    {
        // TODO css file
        $this->content['css_src'][] = QA_HTML_THEME_LAYER_URLTOROOT . 'css/style.css';

        // TODO or inline CSS
        $this->output('<style></style>');

        parent::head_css();
    }
}
