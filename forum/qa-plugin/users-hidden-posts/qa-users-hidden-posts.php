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
            'tags' => 'method="post" action="' . qa_self_html() . '" id="search-form"',
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

            'buttons' => [
                'submit' => [
                    'tags' => 'name="search"',
                    'label' => qa_lang_html('users-hidden-posts/search-label'),
                ],
            ],
        ];

        $qa_content['custom'] = $this->showUsersHiddenPosts();
        return $qa_content;
    }

    private function getUsersHiddenPosts()
    {
        if(isset($_POST['search'])){
            $comments = isset($_POST['comments']) ? 'C': false;
            $answers = isset($_POST['answers']) ? 'A' : false;
            $questions = isset($_POST['questions']) ? 'Q' : false;
            $userID = is_numeric(qa_handle_to_userid($this->userHandle)) ? qa_handle_to_userid($this->userHandle) : 0;
            $sufix = "_HIDDEN";
            
            $sql = "SELECT * FROM `qa_posts` WHERE `userid` = ".$userID." AND (`type`=";

            if($comments){
                $sql .=  "'".$comments.$sufix."'";
            }

            
            if($answers){
                $sql .= $comments ? ' OR `type`=':'`type`=';
                $sql .= "'".$answers.$sufix."'";
            }

            if($questions){
                $sql .= ($comments || $answers) ? ' OR `type`=':'`type`=';
                $sql .= "'".$questions.$sufix."'";
            }

            $sql .= ")";
            $results = qa_db_read_all_assoc(qa_db_query_sub($sql));

            return $results ? $results : false;
        }
    }

    private function showUsersHiddenPosts()
    {
        $hidden = $this->getUsersHiddenPosts();
        $postsHtml ="";

        if(!$hidden){
            return qa_lang_html("users-hidden-posts/no-results");
        }
        

        foreach($hidden as $post){
            $title = $this->getParentsTitle($post['parentid'], $post['title']);
            $id = !empty($post['parentid']) ? $post['parentid'] : $post['postid'];

            $postsHtml .= '<div class = "post">
                            <a class = "postsHeader" href="'.qa_q_path($id, $title, false, $post['type'][0], $post['postid']).'">'.$title.'</a>
                            <p>Dodano: '.date("Y-m-d", strtotime($post['created'])).' </p>
                        </div>';

           
        }

        return $postsHtml;
    }

    private function getParentsTitle($id, $title)
    {
        if(empty($id)){return $title;}

        $sql = "SELECT `title` FROM `qa_posts` WHERE `postid` = $";
        $results = qa_db_read_one_assoc(
            qa_db_query_sub($sql, $id)
        );
        return $results ? $results['title'] : $title;

        //TODO: Naprawić 
    }

}
