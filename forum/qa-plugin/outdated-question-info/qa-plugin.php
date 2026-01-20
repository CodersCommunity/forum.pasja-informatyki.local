<?php

//Don't let this page to be available directly from browser
if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_layer('qa-outdated-question-info-layer.php', 'Outdated Question Info');