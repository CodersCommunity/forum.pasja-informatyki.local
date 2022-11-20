<?php

/*
    Plugin Name: Autoflag
    Plugin URI:
    Plugin Description: Plugin to automatically flag suspicious posts
    Plugin Version: 1.0.0
    Plugin Date: 2022-10-07
    Plugin Author: Arkadiusz Waluk
    Plugin Author URI: https://waluk.pl
    Plugin License: MIT
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Minimum PHP Version: 7.0
    Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit();
}

qa_register_plugin_module('event', 'src/autoflag-event.php', 'autoflag_event', 'Autoflag event');
qa_register_plugin_module('page', 'src/autoflag-admin.php', 'autoflag_admin', 'Autoflag admin');
qa_register_plugin_phrases('lang/*.php', 'autoflag');
