<?php

class autoflag_admin
{
    public function admin_form()
    {
        $saved = false;
        if (qa_clicked('save')) {
            $pointsLimit = qa_post_text('points_limit');
            $userId = qa_post_text('user_id');
            $allowedDomains = qa_post_text('allowed_domains');
            qa_opt('autoflag_points_limit', $pointsLimit);
            qa_opt('autoflag_user_id', $userId);
            qa_opt('autoflag_allowed_domains', $allowedDomains);
            $saved = true;
        }

        return [
            'ok' => $saved ? qa_lang('autoflag/admin_ok_info') : null,
            'fields' => [
                [
                    'type' => 'number',
                    'label' => qa_lang('autoflag/admin_points_limit_label'),
                    'value' => qa_html(qa_opt('autoflag_points_limit')),
                    'tags' => 'name="points_limit"',
                ],
                [
                    'type' => 'number',
                    'label' => qa_lang('autoflag/admin_user_id_label'),
                    'value' => qa_html(qa_opt('autoflag_user_id')),
                    'tags' => 'name="user_id"',
                ],
                [
                    'type' => 'textarea',
                    'label' => qa_lang('autoflag/admin_allowed_domains_label'),
                    'value' => qa_html(qa_opt('autoflag_allowed_domains')),
                    'tags' => 'name="allowed_domains"',
                    'rows' => 5
                ],
            ],
            'buttons' => [
                [
                    'label' => qa_lang('autoflag/admin_save_button'),
                    'tags' => 'name="save"'
                ]
            ]
        ];
    }
}
