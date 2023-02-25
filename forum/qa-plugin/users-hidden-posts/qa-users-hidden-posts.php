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
        return qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR && 
               qa_request_part(0)==='hidden-posts' && 
               qa_handle_to_userid(htmlentities(qa_request_part(1), ENT_QUOTES, "UTF-8"));
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
                    'value' => isset($_POST['comments']),
                ],
                'answers' => [
                    'type' => 'checkbox',
                    'label' => qa_lang_html('users-hidden-posts/answers'),
                    'tags' => 'name=answers',
                    'value' => isset($_POST['answers']),

                ],
                'questions' => [
                    'type' => 'checkbox',
                    'label' => qa_lang_html('users-hidden-posts/questions'),
                    'tags' => 'name=questions',
                    'value' => isset($_POST['questions']),
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
        if(isset($_POST['search'])) {
            $comments = isset($_POST['comments']) ? 'C': false;
            $answers = isset($_POST['answers']) ? 'A' : false;
            $questions = isset($_POST['questions']) ? 'Q' : false;
            $userID = qa_handle_to_userid($this->userHandle);
            $sufix = "_HIDDEN";

            if((!$comments && !$answers && !$questions) || !is_numeric($userID)) {
                return false;
            }
            
            $sql = "SELECT org.title, org.parentid, org.postid, org.created, org.type, par.title AS parentTitle FROM ^posts as org 
                    LEFT JOIN ^posts as par ON org.parentid=par.postid WHERE org.userid = $ AND (org.type =";

            if($comments) {
                $sql .=  "'".$comments.$sufix."'";
            }

            
            if($answers) {
                $sql .= $comments ? ' OR org.type=':'';
                $sql .= "'".$answers.$sufix."'";
            }

            if($questions) {
                $sql .= ($comments || $answers) ? ' OR org.type=':'';
                $sql .= "'".$questions.$sufix."'";
            }

            $sql .= ") ORDER BY org.postid DESC"; 
            $results = qa_db_read_all_assoc(qa_db_query_sub($sql, $userID));

            return $results ?? false;
        }
    }

    private function showUsersHiddenPosts()
    {
        $hidden = $this->getUsersHiddenPosts();
        $postsHtml ="<ol>";

        if(!$hidden) {
            return isset($_POST['search']) ? qa_lang_html("users-hidden-posts/no-results") : "";
        }
        

        foreach($hidden as $post) {
            $title = htmlentities($post['parentTitle'] ?? $post['title']);
            $id = !empty($post['parentid']) ? $post['parentid'] : $post['postid'];

            $postsHtml .= '<li>
                            '.$this->matchPrefixToName($post['type'][0]).':
                            <a href="'.
                                    qa_q_path($id, $title, false, $post['type'][0], $post['postid']).'">'
                                    .$title.'</a>
                            <p>Dodano: <time>'.date("Y-m-d", strtotime($post['created'])).'</time> </p>
                        </li>';

           
        }

        return $postsHtml.="</ol>";
    }

    private function matchPrefixToName($prefix)
    {
        switch($prefix) {
            case "Q": 
                return qa_lang_html("users-hidden-posts/question");
            case "C": 
                return qa_lang_html("users-hidden-posts/comment");
            case "A": 
                return qa_lang_html("users-hidden-posts/answer");
            default:  
                return qa_lang_html("users-hidden-posts/bad-prefix");
        }
    }

}
