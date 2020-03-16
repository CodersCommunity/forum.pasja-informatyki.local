<?php
/*
    Plugin Name: Block pw
    Plugin URI: https://forum.pasja-informatyki.pl
    Plugin Description: Very powerful and useful plugin for blocking pw from unpleasant users :)
    Plugin Version: 1.0
    Plugin Date: 2020-03-16
    Plugin Author: https://forum.pasja-informatyki.pl/user/Mariusz08
    Plugin License: GPLv3
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Update Check URI:
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    More about this license: http://www.gnu.org/licenses/gpl.html

*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module('page', 'block-pw-page.php', block_pw_page::class, 'Block pw page');
//qa_register_plugin_module('page', 'qa-badge-page.php', 'qa_badge_page', 'Badges');
