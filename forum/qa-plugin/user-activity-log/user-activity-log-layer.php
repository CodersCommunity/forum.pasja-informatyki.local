<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    function head_script()
    {
        parent::head_script();
        $this->output(
            '<link rel = "stylesheet" type = "text/css" href = "'. QA_HTML_THEME_LAYER_URLTOROOT .'events-window/css/styles.css" />
            <script src = "'. QA_HTML_THEME_LAYER_URLTOROOT .'events-window/events-info-scripts.js"></script>
        ');
    }
}