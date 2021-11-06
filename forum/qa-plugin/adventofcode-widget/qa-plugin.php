<?php

/*
    Plugin Name: Advent of Code widget
    Plugin URI:
    Plugin Description: Widget to show results from Advent of Code leaderboard
    Plugin Version: 1.0.0
    Plugin Date: 2021-11-05
    Plugin Author: Forum Pasja Informatyki
    Plugin Author URI: https://forum.pasja-informatyki.pl
    Plugin License: MIT
    Plugin Minimum Question2Answer Version: 1.7
    Plugin Minimum PHP Version: 7.0
    Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit();
}

qa_register_plugin_module('widget', 'src/adventofcode-widget.php', 'adventofcode_widget', 'Advent of Code');
qa_register_plugin_layer('src/adventofcode-layer.php', 'Advent of Code layer');
qa_register_plugin_phrases('lang/*.php', 'adventofcode_widget');
