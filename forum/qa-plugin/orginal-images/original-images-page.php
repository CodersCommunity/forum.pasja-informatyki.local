<?php

namespace Xandros15;


class original_images_page
{
    const REQUEST_NAME = 'wysiwyg-editor-upload';

    /**
     * Max file size is chosing depence of value original_images_page::MAX_FILE_SIZE or
     * qa_opt('ckeditor4_upload_max_size') or qa_get_max_upload_size()
     * system chose what is lower
     */
    const MAX_FILE_SIZE = (int) 1024 * 1024 * 1.5; //1.5MB
    const UPLOAD_FUNCTIONS_FILE = \QA_INCLUDE_DIR . 'qa-app-upload.php';

    const CKE_OPT_UPLOAD_IMAGES = 'ckeditor4_upload_images';
    const CKE_OPT_UPLOAD_ALL = 'ckeditor4_upload_all';
    const CKE_OPT_UPLOAD_MAX_SIZE = 'ckeditor4_upload_max_size';

    const QA_OPT_ONLY_IMAGE = 'qa_only_image';

    /**
     * @inheritdoc
     */
    public function match_request($request)
    {
        return $request == self::REQUEST_NAME;
    }

    /**
     * @inheritdoc
     */
    function process_request($request)
    {
        /** @var $module \qa_ckeditor4_upload */
        if ($module = qa_load_module('page', CKE_MODULE_NAME)) {
            $module->process_request($request);
        }


        if (!$_FILES || !qa_opt(self::CKE_OPT_UPLOAD_IMAGES)) {
            return;
        }

        require_once self::UPLOAD_FUNCTIONS_FILE;

        $maxFileSize = min(
            self::MAX_FILE_SIZE,
            qa_opt(self::CKE_OPT_UPLOAD_MAX_SIZE),
            qa_get_max_upload_size()
        );

        $upload = qa_upload_file_one($maxFileSize, true);

        if (!empty($upload['bloburl'])) {
            echo $this->render_js($upload['bloburl']);
        }
    }

    /**
     * Parse js to implements image url as link of thumb image with blank target
     *
     * @param $url
     *
     * @return string
     */
    private function render_js($url)
    {
        $url    = qa_js($url);
        $script = <<<JS
        !function(){
        var editor = window.parent.CKEDITOR;
        if(!editor){
            return;
        }

        var instance = editor.instances[Object.keys(editor.instances)[0]];

        if(!instance){
            return;
        }

        var imageDialog = instance._.storedDialogs.image;
        imageDialog.setValueOf("Link","txtUrl", {$url});  
        imageDialog.setValueOf("Link","cmbTarget", "_blank");
}();
JS;

        return "<script>{$script}</script>";
    }
}
