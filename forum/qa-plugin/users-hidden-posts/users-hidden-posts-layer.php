<?php
class qa_html_theme_layer extends qa_html_theme_base
{
    public function doctype()
    {
    
        qa_html_theme_base::doctype();

        if($this->template === 'user') {
            $this->content['navigation']['sub']['hidden'] = [
                'url' => qa_path_html("hidden-posts/".$this->qa_get_user_handle($this->request), qa_opt('site_url')),
                'label' => qa_lang_html('users-hidden-posts/label'),
            ];
        }

        return $this->content['navigation'];

    } 

    public function head_script()
    {
        parent::head_script();
        if($this->template === 'user'){
            $this->output(
                '<link rel = "stylesheet" type = "text/css" href = "'. QA_HTML_THEME_LAYER_URLTOROOT .'css/styles.css" />'
            );
        }
    }
    
    public function qa_get_user_handle($request)
    {
        preg_match( '#user/([^/]+)#', $request, $matches );
        return !empty($matches[1]) ? $matches[1] : null;
    }

}