<?php

namespace Xandros15;

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

require_once __DIR__ . '/functions.php';

const CKE_MODULE_NAME = 'CKEditor4 Upload';

register_before(CKE_MODULE_NAME, [
    'type'    => 'page',
    'include' => 'original-images-page.php',
    'class'   => original_images_page::class,
    'name'    => 'original images page'
]);
