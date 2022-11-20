<?php

/*
    Plugin Name: Discord notifications
    Plugin URI:
    Plugin Description: Notifications about new questions to Discord channels
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

qa_register_plugin_module('event', 'src/discord-notifications-event.php', 'discord_notifications_event', 'Discord notifications event');
qa_register_plugin_module('admin', 'src/discord-notifications-admin.php', 'discord_notifications_admin', 'Discord notifications admin');
qa_register_plugin_phrases('lang/*.php', 'discord_notifications');
