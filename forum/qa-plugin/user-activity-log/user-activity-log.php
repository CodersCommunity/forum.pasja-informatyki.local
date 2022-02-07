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
        private $userLevel;

        public function load_module($directory, $urltoroot) 
        {
            $this->directory=$directory;
            $this->urltoroot=$urltoroot;
        }
        public function match_request($request) 
        {
            $this->userLevel = qa_get_logged_in_level();
            if($this->userLevel >= QA_USER_LEVEL_EDITOR){
                return $request == 'admin/user-activity-log';
            }else{
                return false;
            }
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
                        'tags' => 'name="request"',
                    ),
                    
                    'filter' => array(
                        'type' => 'select',
                        'options' => [
                            'ip' => $this->userLevel >= QA_USER_LEVEL_MODERATOR ? qa_lang_html('user-activity-log/ipAdress') : null,
                            'username' => qa_lang('user-activity-log/username'),
                            'type' => qa_lang('user-activity-log/eventType'),
                        ],
                        'label' => qa_lang_html('user-activity-log/filters'),
                    ),
                ),

                

                'buttons' => array(
                    'submit' => array(
                        'tags' => 'name="search"',
                        'label' => qa_lang_html('user-activity-log/search'),
                        'value' => '1',
                    ),
                ),

            );
            $qa_content['navigation']['sub']=qa_admin_sub_navigation();
            return $qa_content;
        }
    }