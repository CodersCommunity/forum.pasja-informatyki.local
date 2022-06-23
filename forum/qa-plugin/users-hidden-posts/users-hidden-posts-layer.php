<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    public function doctype()
    {
    
        //TODO: naprawić margines 
        qa_html_theme_base::doctype();

        if(isset($this->content['navigation']['sub'])){
            $navigation = $this->content['navigation']['sub'];
            global $qa_request;
            if(preg_match('/user[\/]/', $qa_request) && qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR) {
                $navigation['hidden-posts'] = [
                    'label' =>  qa_lang_html('users-hidden-posts/label'),
                    'url' => qa_path_html('hidden-posts', ['user' => qa_request_part(1)]),
                    
                ];
            }

            $this->content['navigation']['sub'] = $navigation;
        }

        return $this->content['navigation'];

    } 
}