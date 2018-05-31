<?php
/*
    Plugin Name: Google Authenticator 2-factor authentication for Q2A
    Plugin URI:
    Plugin Description: This plugin provides a Google 2FA security for users on forum
    Plugin Version: 1.0
    Plugin Date: 2018-05-30
    Plugin Author: Marek Woś
    Plugin Author URI: http://github.com/event15
    Plugin License: GPLv2
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Update Check URI:
*/

use CodersCommunity\q2a_googleauthenticator_admin;

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}

define('GOOGLEAUTHENTICATOR_BASIC_PATH', __DIR__);

qa_register_plugin_phrases('src/i18n/q2a-googleauthenticator-lang-*.php', 'plugin_2fa');
qa_register_plugin_layer('q2a-googleauthenticator-layer.php', 'Google 2FA Layer');
qa_register_plugin_module(
    'module',
    'src/q2a-googleauthenticator-admin.php',
    q2a_googleauthenticator_admin::class,
    'q2a Google Authenticator Admin'
);

//qa_register_plugin_overrides('qa-open-overrides.php');
//qa_register_plugin_module('page', 'qa-open-page-logins.php', 'qa_open_logins_page', 'Open Login Configuration');
//qa_register_plugin_module('widget', 'qa-open-widget.php', 'qa_open_logins_widget', 'Open Login Providers');
