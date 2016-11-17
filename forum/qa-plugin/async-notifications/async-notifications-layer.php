<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    function head_script()
    {
        if (qa_get_logged_in_userid()) {
            $this->content['script'][] = '<script src="' . qa_html(QA_HTML_THEME_LAYER_URLTOROOT . 'js/asyncNotifications.js') . '"></script>';
        }
        qa_html_theme_base::head_script();
    }
}