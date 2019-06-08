<?php

class admin_form
{
    public function __construct()
    {
        $this->set_fields_with_default_values();
    }

    public function generate_view()
    {
        $this->set_fields_with_actual_values();
        $this->updated = qa_clicked('recaptcha_save_button');

        if ($this->updated) {
            $this->publicKey = trim(qa_post_text('recaptcha_public_key_field'));
            $this->privateKey = trim(qa_post_text('recaptcha_private_key_field'));
            $this->minScore = trim(qa_post_text('recaptcha_min_score_field'));

            qa_opt('recaptcha_public_key', $this->publicKey);
            qa_opt('recaptcha_private_key', $this->privateKey);
            qa_opt('recaptcha_min_score', $this->minScore);
        }

        $response = $this->get_response();

        $form = [
            'ok' => $this->updated ? qa_lang_html('admin/options_saved') : null,

            'fields' => [
                'public' => [
                    'label' => qa_lang_html('recaptcha/site_key'),
                    'value' => $this->publicKey,
                    'tags' => 'name="recaptcha_public_key_field"',
                ],

                'private' => [
                    'label' => qa_lang_html('recaptcha/secret_key'),
                    'value' => $this->privateKey,
                    'tags' => 'name="recaptcha_private_key_field"',
                ],

                'min_score' => [
                    'label' => qa_lang_html('recaptcha/min_score'),
                    'value' => $this->minScore,
                    'tags' => 'name="recaptcha_min_score_field"',
                    'error' => $response['message'],
                ],
            ],

            'buttons' => [
                [
                    'label' => qa_lang_html('main/save_button'),
                    'tags' => 'name="recaptcha_save_button"',
                ],
            ],
        ];

        return $form;
    }

    public function get_public_key()
    {
        $this->set_fields_with_actual_values();
        return $this->publicKey;
    }

    public function get_private_key()
    {
        $this->set_fields_with_actual_values();
        return $this->privateKey;
    }

    public function get_min_score()
    {
        $this->set_fields_with_actual_values();
        return $this->minScore;
    }

    public function is_filled()
    {
        $this->set_fields_with_actual_values();
        return !(empty($this->publicKey) || empty($this->privateKey) || empty($this->minScore));
    }


    public static function get_default_value($option)
    {
        $defaultValues = [
            'recaptcha_public_key' => '',
            'recaptcha_private_key' => '',
            'recaptcha_min_score' => 0.5,
        ];

        return isset($defaultValues[$option]) ? $defaultValues[$option] : null;
    }


    private function get_response()
    {
        if ($this->is_filled()) {
            return [
                'success' => true,
                'message' => null,
            ];
        } else {
            $message = strtr(qa_lang_html('recaptcha/form_not_filled'), [
                '$1' => '<a href="https://www.google.com/recaptcha/admin" target="_blank">',
                '$2' => '</a>',
            ]);

            return [
                'success' => false,
                'message' => $message,
            ];
        }
    }

    private function set_fields_with_default_values()
    {
        $this->publicKey = self::get_default_value('recaptcha_public_key');
        $this->privateKey = self::get_default_value('recaptcha_private_key');
        $this->minScore = self::get_default_value('recaptcha_min_score');
    }

    // You shouldn't run this method in constructor, because of an error with maximum function nesting level
    private function set_fields_with_actual_values()
    {
        $this->publicKey = qa_opt('recaptcha_public_key');
        $this->privateKey = qa_opt('recaptcha_private_key');
        $this->minScore = qa_opt('recaptcha_min_score');
    }

    private $updated;

    private $publicKey;
    private $privateKey;
    private $minScore;
}

