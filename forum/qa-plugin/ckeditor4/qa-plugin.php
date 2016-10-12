<?php
/*
	Plugin Name: CKEditor
	Plugin URI: 
	Plugin Description: New Editor for Forum.
	Plugin Version: 1.6
	Plugin Date: 2016-10-10
	Plugin Author:  CodersCommunity
	Plugin Author https://github.com/CodersCommunity
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5.1
	Plugin Update Check URI:
    Oryginal Author: sama55@CMSBOX (http://www.cmsbox.jp/) Based on his work.
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

qa_register_plugin_phrases('qa-lang/qa-ckeditor4-lang-*.php', 'ck4');
qa_register_plugin_module('editor', 'CkEditor.php', 'CkEditor', 'CKEditor4');
qa_register_plugin_module('page', 'qa-ckeditor4-upload.php', 'qa_ckeditor4_upload', 'CKEditor4 Upload');
qa_register_plugin_layer('qa-ckeditor4-layer.php', 'CKEditor4 Layer');
qa_register_plugin_overrides('qa-ckeditor4-overrides.php');
