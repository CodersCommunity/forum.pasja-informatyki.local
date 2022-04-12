<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    public function head_script(){
        parent::head_script();
        $this->output('
            <script src = "'. QA_HTML_THEME_LAYER_URLTOROOT .'frontend/show-outdated-question-info.js?v=" defer></script>
            <link rel = "stylesheet" href = "'. QA_HTML_THEME_LAYER_URLTOROOT .'frontend/css/styles.css?v=" />
        ');
    }
}