<?php

/*
    Plugin Name: Socket integration
    Plugin URI:
    Plugin Description: Integration for Node server and socket
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

qa_register_plugin_layer('layer.php', 'Socket integration layer');
qa_register_plugin_module('event', 'event.php', 'socket_integration_event', 'Socket integration event');
qa_register_plugin_module('page', 'page-user.php', 'socket_integration_user_page', 'Socket integration user');
qa_register_plugin_module(
    'page',
    'page-comments.php',
    'socket_integration_comments_page',
    'Socket integration comments'
);
