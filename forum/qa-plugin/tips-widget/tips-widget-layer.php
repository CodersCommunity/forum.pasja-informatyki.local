<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    const DAY = 86400;
    
    public function initialize()
    {
        if (qa_opt('tips-enable')) {
            $widget_module = qa_load_module('widget', 'Tips Widget Widget');
            if (!is_null($widget_module)) {
                $widget_module->init();
                setcookie('prev_random', $widget_module->random, time() + self::DAY, '/', QA_COOKIE_DOMAIN, QA_COOKIE_SECURE, QA_COOKIE_HTTPONLY);
            }
        }
        
        qa_html_theme_base::initialize();
    }
    
    public function head_css()
    {
        if (qa_opt('tips-enable')) {
            $this->content['css_src'][] = QA_HTML_THEME_LAYER_URLTOROOT . 'styles/style.css';
        }
        
        qa_html_theme_base::head_css();
    }
}
