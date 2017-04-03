<?php

namespace Xandros15;


class original_images_page
{
    const REQUEST_NAME = 'wysiwyg-editor-upload';
    const MAX_FILE_SIZE = 1024 * 1024 * 3; //3MB
    const UPLOAD_FUNCTIONS_FILE = \QA_INCLUDE_DIR . 'qa-app-upload.php';

    const CKE_OPT_UPLOAD_IMAGES = 'ckeditor4_upload_images';
    const CKE_OPT_UPLOAD_ALL = 'ckeditor4_upload_all';

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
        //@todo remove it after they fix multiple request for one pages
        /** @var $module \qa_ckeditor4_upload */
        if ($module = qa_load_module('page', CKE_MODULE_NAME)) {
            $module->process_request($request);
        }


        if (!$_FILES || !qa_opt(self::CKE_OPT_UPLOAD_IMAGES)) {
            return;
        }

        require_once self::UPLOAD_FUNCTIONS_FILE;

        $upload = qa_upload_file_one(
            self::MAX_FILE_SIZE,
            qa_get(self::QA_OPT_ONLY_IMAGE) || !qa_opt(self::CKE_OPT_UPLOAD_ALL)
        );

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
