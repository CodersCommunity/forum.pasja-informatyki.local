<?php
/*
    Question2Answer by Gideon Greenspan and contributors
    http://www.question2answer.org/

    File: index.php
    Description: A stub that only sets up the Q2A root and includes qa-index.php


    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    More about this license: http://www.question2answer.org/license.php
*/

require_once 'vendor/autoload.php';

// Set base path here so this works with symbolic links for multiple installations
define('QA_BASE_DIR', dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']) . '/');

if (file_exists(QA_BASE_DIR . 'qa-config.php')) {
    require_once QA_BASE_DIR . 'qa-config.php';
}

global $client;
$client = new Raven_Client(SENTRY_DSN ?? '', [
    'environment' => SENTRY_ENVIRONMENT ?? 'local'
]);
$error_handler = new Raven_ErrorHandler($client);
$error_handler->registerExceptionHandler();
$error_handler->registerErrorHandler();
$error_handler->registerShutdownFunction();

require 'qa-include/qa-index.php';
