<?php

/*
    Plugin Name: Discord integration
    Plugin URI: https://github.com/awaluk/q2a-discord-integration
    Plugin Description: Plugin to link Discord account with Q2A account
    Plugin Version: 1.0
    Plugin Date: 2019-08-15
    Plugin Author: Arkadiusz Waluk
    Plugin Author URI: https://waluk.pl
    Plugin License: MIT
    Plugin Minimum Question2Answer Version: 1.5
    Plugin Update Check URI: https://raw.githubusercontent.com/awaluk/q2a-discord-integration/master/metadata.json
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module('page', 'src/discord-integration-page.php', 'discord_integration_page', 'Discord integration');
qa_register_plugin_module('page', 'src/discord-integration-admin.php', 'discord_integration_admin', 'Discord integration admin');
qa_register_plugin_module('event', 'src/discord-integration-event.php', 'discord_integration_event', 'Discord integration event');
qa_register_plugin_phrases('lang/*.php', 'discord_integration');

require_once 'src/discord-api.php';
