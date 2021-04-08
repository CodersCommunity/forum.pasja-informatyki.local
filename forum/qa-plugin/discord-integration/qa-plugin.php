<?php

/*
    Plugin Name: Discord integration
    Plugin URI: https://github.com/awaluk/q2a-discord-integration
    Plugin Description: Link Discord accounts with Q2A accounts
    Plugin Version: 1.0.1
    Plugin Date: 2020-08-29
    Plugin Author: Arkadiusz Waluk
    Plugin Author URI: https://waluk.pl
    Plugin License: MIT
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Minimum PHP Version: 7.0
    Plugin Update Check URI: https://raw.githubusercontent.com/awaluk/q2a-discord-integration/master/metadata.json
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit();
}

qa_register_plugin_module('page', 'src/discord-integration-page.php', 'discord_integration_page', 'Discord integration');
qa_register_plugin_module('page', 'src/discord-integration-admin.php', 'discord_integration_admin', 'Discord integration admin');
qa_register_plugin_module('event', 'src/discord-integration-event.php', 'discord_integration_event', 'Discord integration event');
qa_register_plugin_phrases('lang/*.php', 'discord_integration');

require_once 'src/discord-api.php';
