<?php

/*
    Plugin Name: Async lists
    Plugin URI:
    Plugin Description: Asynchronous lists - home and activity
    Plugin Version: 1.0
    Plugin Date: 2021-03-27
    Plugin Author: CodersCommunity
    Plugin Author URI: https://forum.pasja-informatyki.pl
    Plugin License:
    Plugin Minimum Question2Answer Version: 1.5
    Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit();
}

qa_register_plugin_layer('async-lists-layer.php', 'async lists layer');
