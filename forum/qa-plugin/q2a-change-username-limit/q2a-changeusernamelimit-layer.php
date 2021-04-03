<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    function head_css()
    {
        parent::head_css();

        $this->output( '<link rel="stylesheet" href="' . qa_path_to_root() . 'qa-plugin/q2a-change-username-limit/css/style.css?v=' . QA_RESOURCE_VERSION . '">' );
    }

    public function head_script()
    {
        if (in_array($this->template, ['user'])) {
            $path = qa_html(qa_path_to_root() . 'qa-plugin/q2a-change-username-limit/js/timeline.js?v=' . QA_RESOURCE_VERSION);
            $this->content['script'][] = '<script src="' . $path . '" defer></script>';
        }

        qa_html_theme_base::head_script();
    }
}
