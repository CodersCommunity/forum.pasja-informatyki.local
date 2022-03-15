<?php
    if (!defined('QA_VERSION')) {
        header('Location: ../../');
        exit;
    }

    require_once QA_INCLUDE_DIR.'app/admin.php';

    class user_activity_log 
    {
        private $directory;
        private $urltoroot;
        private $request;
        private $date;
        private $resultsCount;
        private $sortBy;

        public function match_request($request) 
        {
            isset($_SESSION['query']) ? $this->request = $_SESSION['query'] : null;
            isset($_SESSION['date']) ? $this->date = $_SESSION['date'] : null;
            isset($_SESSION['resultsCount']) ? $this->resultsCount = $_SESSION['resultsCount'] : null;
            isset($_SESSION['sortBy']) ? $this->sortBy = $_SESSION['sortBy'] : null;
            
            return qa_get_logged_in_level() >= QA_USER_LEVEL_EDITOR && $request === 'user-activity-log';
        }

        public function process_request($request) 
        {
            $qa_content=qa_content_prepare();
            $qa_content['title']= qa_lang_html('user-activity-log/title');
            $qa_content['form']=array(
                'tags' => 'method="post" action="user-activity-log-search"',
                'style' => 'wide',
                'title' => qa_lang_html('user-activity-log/formTitle'),
                

                'fields' => array(
                    'query' => array(
                        'label' => qa_lang_html('user-activity-log/condition'),
                        'tags' => 'name="request" value="'.$this->request.'"',
                    ),
                    
                    'filter' => array(
                        'type' => 'select',
                        'tags'=>'name=condition',
                        'options' => [
                                'username' => qa_lang('user-activity-log/username'),
                                'type' => qa_lang('user-activity-log/eventType'),
                        ]
                        ,
                        'label' => qa_lang_html('user-activity-log/filters'),
                    ),

                    'date' => array(
                        'label' => qa_lang_html('user-activity-log/date'),
                        'tags' => 'placeholder=YYYY-MM-DD name=date value="'.$this->date.'"',
                    ),

                    'fromOldest' => [
                        'type' => 'select',
                        'tags'=>'name=fromOldest value="'.$this->sortBy.'"',
                        'options' => [
                                0 => qa_lang('user-activity-log/oldest'),
                                1 => qa_lang('user-activity-log/newest'),
                        ]
                        ,
                        'label' => qa_lang_html('user-activity-log/from'),
                    ],

                    'resultsCount' => array(
                        'label'=> qa_lang_html('user-activity-log/count'),
                        'tags'=> 'name=resultsCount value="'.$this->resultsCount.'"',
                    )
                ),

                

                'buttons' => array(
                    'submit' => array(
                        'tags' => 'name="search"',
                        'label' => qa_lang_html('user-activity-log/search-label'),
                        'value' => '1',
                    ),
                ),

            );

            if(qa_get_logged_in_level() >= QA_USER_LEVEL_MODERATOR){
                $qa_content['form']['fields']['filter']['options']['ip'] = qa_lang_html('user-activity-log/ipAdress');
            }
            $qa_content['navigation']['sub']= qa_admin_sub_navigation();
            $qa_content['custom_2'] = 
            '<div class = "modal-content">
                <h1>'.qa_lang_html('user-activity-log/EventModalHeader').'</h1>
                <p>
                    <table>
                        <tr>
                            <th>'.qa_lang_html('user-activity-log/users').'</th>
                            <th>'.qa_lang_html('user-activity-log/questions').'</th>
                            <th>'.qa_lang_html('user-activity-log/answers').'</th>
                        </tr>
                        '.$this->genereateEventsTable().'
                        </table>
                </p>
                <a href = "https://docs.question2answer.org/plugins/modules-event/">'.qa_lang_html('user-activity-log/more').'</a>
                <br/>
                <button class = "qa-form-wide-button close-modal">Zamknij</button>
            </div>
            <button class = "open-modal qa-form-wide-button">Otwórz informację o Eventach</button>';
        
            return $qa_content;
        }

        private function genereateEventsTable()
        {
            return '
                <tr>
                    <td>u_login - '.qa_lang_html("user-activity-log/u_login").'</td>
                    <td>q_post - '.qa_lang_html("user-activity-log/q_post").'</td>
                    <td>a_post - '.qa_lang_html("user-activity-log/a_post").'</td>
                </tr>
                <tr>
                    <td>u_register - '.qa_lang_html("user-activity-log/u_register").'</td>
                    <td>q_edit - '.qa_lang_html("user-activity-log/q_edit").'</td>
                    <td>a_select - '.qa_lang_html("user-activity-log/a_select").'</td>
                </tr>
                <tr>
                    <td>u_edit - '.qa_lang_html("user-activity-log/u_edit").'</td>
                    <td>q_close - '.qa_lang_html("user-activity-log/q_close").'</td>
                    <td>in_a_question - '.qa_lang_html('user-activity-log/in_a_question').'</td>
                </tr>
                <tr>
                    <td>u_message - '.qa_lang_html("user-activity-log/u_message").'</td>
                    <td>q_delete - '.qa_lang_html("user-activity-log/q_delete").'</td>
                    <td>a_delete - '.qa_lang_html("user-activity-log/a_delete").'</td>
                </tr>
            ';
        }
    }
