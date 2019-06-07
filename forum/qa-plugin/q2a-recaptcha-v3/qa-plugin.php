<?php

/*
    Plugin Name: reCAPTCHA v3
    Plugin URI: https://github.com/qwercik/q2a-recaptcha-v3
    Plugin Description: Provides reCAPTCHA v3 services
    Plugin Version: 1.0
    Plugin Date: 2019-06-07
    Plugin Author: Eryk Andrzejewski
    Plugin Author URI: github.com/qwercik
    Plugin License: GPLv2
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Update Check URI: https://raw.githubusercontent.com/qwercik/q2a-recaptcha-v3/master/metadata.json
*/


if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}


qa_register_plugin_module('captcha', 'src/qa-recaptcha-captcha.php', 'qa_recaptcha_captcha', 'reCAPTCHA');
qa_register_plugin_phrases('src/lang/qa-recaptcha-captcha-lang-*.php', 'recaptcha');
