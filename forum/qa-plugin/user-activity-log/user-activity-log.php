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

        public function match_request($request) 
        {
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
                        'tags' => 'name="request" required',
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
                        'tags' => 'placeholder=YYYY-MM-DD name=date value=""',
                    ),

                    'resultsCount' => array(
                        'label'=> qa_lang_html('user-activity-log/count'),
                        'tags'=> 'name=resultsCount required',
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
            return $qa_content;
        }
    }
