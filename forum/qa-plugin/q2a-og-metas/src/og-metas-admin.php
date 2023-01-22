<?php

class og_metas_admin
{
    public function admin_form()
    {
        $saved = qa_clicked('og_metas_save');
        if ($saved) {
            qa_opt('og_metas_image_url', qa_post_text('og_metas_image_url'));
        }

        return [
            'ok' => $saved ? qa_lang_html('og_metas/admin_saved_info') : null,
            'fields' => [
                [
                    'label' => qa_lang_html('og_metas/admin_image_url_label'),
                    'type' => 'text',
                    'value' => qa_opt('og_metas_image_url'),
                    'tags' => 'name="og_metas_image_url"',
                ],
            ],
            'buttons' => [
                [
                    'label' => qa_lang_html('og_metas/admin_save_button'),
                    'tags' => 'name="og_metas_save"'
                ],
            ]
        ];
    }
}
