<?php

/*
    Plugin Name: Users notes
    Plugin URI: https://github.com/awaluk/q2a-users-notes
    Plugin Description: Adds place to store administration notes about users
    Plugin Version: 1.0.0
    Plugin Date: 2020-08-29
    Plugin Author: Arkadiusz Waluk
    Plugin Author URI: https://waluk.pl
    Plugin License: MIT
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Minimum PHP Version: 7.0
    Plugin Update Check URI: https://raw.githubusercontent.com/awaluk/q2a-users-notes/master/metadata.json
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit();
}

qa_register_plugin_module('widget', 'src/users-notes-widget.php', 'users_notes_widget', 'Users notes');
qa_register_plugin_layer('src/users-notes-layer.php', 'Users notes layer');
qa_register_plugin_module('event', 'src/users-notes-event.php', 'users_notes_event', 'Users notes event');

qa_register_plugin_phrases('lang/*.php', 'users_notes');
