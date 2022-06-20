<?php

//Don't let this page be accessed directly
if (!defined('QA_VERSION')) {
    header('Location: ../../../');
    exit;
}

class qa_users_hidden_posts
{
    private $userHandle;

    public function match_request( string $request)
    {
        if(qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR){
            $this->userHandle = qa_request_part(1);
            return qa_request_part(0)  == 'hidden-posts' && isset($request[1]);
        }
    }

    public function process_request(string $request)
    {
        $qa_content = qa_content_prepare();
        
        $qa_content['navigation']['sub'] = qa_user_sub_navigation($this->userHandle, 'users-hidden-posts');
        $qa_content['title'] = qa_lang_html('users-hidden-posts/title');
        $qa_content['form'] = [
            'tags' => 'method="post" action="' . qa_self_html() . '"',
            'title' => qa_lang_html('users-hidden-posts/form-title'),
            'hidden' => [
                'code' => qa_get_form_security_code('user_hidden_posts'),
                'qa_1' => qa_request_part(1),
            ] 
        ];
        
        $qa_content['custom'] = $this->showUsersHiddenPosts();


        return $qa_content;
    }

    private function showUsersHiddenPosts()
    {
        
    }

}