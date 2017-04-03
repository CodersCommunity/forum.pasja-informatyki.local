<?php

namespace Xandros15;

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

require_once __DIR__ . '/functions.php';

const CKE_MODULE_NAME = 'CKEditor4 Upload';

//@todo replace it with 'qa_register_plugin_module' after they fix multiple request for one pages
register_before(CKE_MODULE_NAME, [
    'type'    => 'page',
    'include' => 'original-images-page.php',
    'class'   => original_images_page::class,
    'name'    => 'original images page'
]);
