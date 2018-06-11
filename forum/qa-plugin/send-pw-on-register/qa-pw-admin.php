<?php

class qa_pw_admin
{
    public function admin_form(&$qa_content)
    {
        $isSaved = false;

        if (qa_clicked('sendPwMessageOnRegister_save')) {
            qa_opt('sendPwMessageOnRegister_messageContent', qa_post_text('messageContent'));
            qa_opt('sendPwMessageOnRegister_enabled', qa_post_text('enablePlugin'));
            qa_opt('sendPwMessageOnRegister_botId', qa_post_text('botId'));
            
            $isSaved = true;
        }
        
        return $this->prepareAdminForm($isSaved);
    }
    
    private function prepareAdminForm($isSaved)
    {
        return [
            'ok' => $isSaved ? 'Settings saved' : null,
            'fields' => [
                [
                    'label' => 'Enable plugin',
                    'tags' => 'name="enablePlugin"',
                    'value' => qa_opt('sendPwMessageOnRegister_enabled'),
                    'type' => 'checkbox'
                ],
                [
                    'label' => '<span style="color: red; font-weight: bold;">DANGER ZONE!</span> Bot for sending messages (id): <span style="color: red; font-weight: bold;">DANGER ZONE!</span>',
                    'tags' => 'name="botId" type="number"',
                    'value' => qa_opt('sendPwMessageOnRegister_botId')
                ],
                [
                    'label' => 'Message content:',
                    'tags' => 'name="messageContent"',
                    'value' => qa_html(qa_opt('sendPwMessageOnRegister_messageContent')),
                    'type' => 'textarea',
                    'rows' => 20
                ]
            ],
            'buttons' => [
                [
                    'label' => 'Save Changes',
                    'tags' => 'name="sendPwMessageOnRegister_save"',
                ]
            ]
        ];        
    }
}
