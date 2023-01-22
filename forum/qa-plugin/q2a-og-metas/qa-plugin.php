<?php

/*
    Plugin Name: OG Metas
    Plugin URI:
    Plugin Description: Add OG meta tags
    Plugin Version: 1.0.0
    Plugin Date: 2022-10-26
    Plugin Author: Arkadiusz Waluk
    Plugin Author URI: https://waluk.pl
    Plugin License:
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Minimum PHP Version: 7.0
    Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit();
}

qa_register_plugin_layer('src/og-metas-layer.php', 'OG Metas layer');
qa_register_plugin_module('module', 'src/og-metas-admin.php', 'og_metas_admin', 'OG Metas admin');
qa_register_plugin_phrases('lang/*.php', 'og_metas');
