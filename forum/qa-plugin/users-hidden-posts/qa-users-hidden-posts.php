<?php
//Don't let this page be accessed directly
if (!defined('QA_VERSION')) {
    header('Location: ../../../');
    exit;
}

class qa_users_hidden_posts
{
    private $userHandle;

    public function match_request(string $request)
    {
        return qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR && qa_request_part(0)==='hidden-posts';
    }

    public function process_request(string $request)
    {
        $this->userHandle = explode("/", $request)[1];
        $qa_content = qa_content_prepare();
        
        $qa_content['navigation']['sub'] = qa_user_sub_navigation($this->userHandle, '', false);
        $qa_content['title'] = qa_lang_html('users-hidden-posts/title');
        $qa_content['form'] = [
            'tags' => 'method="post" action="' . qa_self_html() . '"',
            'style' => 'wide',
            'title' => qa_lang_html('users-hidden-posts/form-title'),
            'fields' => [
                'comments' => [
                    'type' => 'checkbox',
                    'label' => qa_lang_html('users-hidden-posts/comments'),
                    'tags' => 'name=comments',
                    'value' => isset($_POST['comments']) ? true : false,
                ],
                'answers' => [
                    'type' => 'checkbox',
                    'label' => qa_lang_html('users-hidden-posts/answers'),
                    'tags' => 'name=answers',
                    'value' => isset($_POST['answers']) ? true : false,

                ],
                'qusetions' => [
                    'type' => 'checkbox',
                    'label' => qa_lang_html('users-hidden-posts/questions'),
                    'tags' => 'name=questions',
                    'value' => isset($_POST['questions']) ? true : false,
                ],
            ],

            'buttons' => array(
                'submit' => array(
                    'tags' => 'name="search"',
                    'label' => qa_lang_html('user-activity-log/search-label'),
                ),
            ),
        ];
        
        $qa_content['custom'] = $this->showUsersHiddenPosts();


        return $qa_content;
    }

    private function showUsersHiddenPosts()
    {
        if(isset($_POST['search'])){
            $comments = isset($_POST['comments']) ? true : false;
            $answers = isset($_POST['answers']) ? true : false;
            $questions = isset($_POST['questions']) ? true : false;
            $userId = qa_handle_to_userid($this->userHandle);
            
        }
    }

}