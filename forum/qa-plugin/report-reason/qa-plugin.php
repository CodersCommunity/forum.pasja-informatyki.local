<?php
/*
    Plugin Name: Flag Reasons
    Plugin URI: https://forum.pasja-informatyki.pl & https://github.com/q2apro/q2apro-flag-reasons
    Plugin Description: Adds choice of flag reasons and notice option to each flag vote
    Plugin Version: 0.1
    Plugin Date: 2018-04-01
    Plugin Author: https://forum.pasja-informatyki.pl/user/Mariusz08 & http://q2apro.com
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

// language file
qa_register_plugin_phrases('q2apro-flag-reasons-lang-default.php', 'q2apro_flagreasons_lang');

// page
qa_register_plugin_module(
    'page', 'q2apro-flag-reasons-page.php', 'q2apro_flag_reasons_page', 'q2apro flag reasons Page'
);

// layer
qa_register_plugin_layer('q2apro-flag-reasons-layer.php', 'q2apro flag reasons layer');

// admin
qa_register_plugin_module(
    'module', 'q2apro-flag-reasons-admin.php', 'q2apro_flagreasons_admin', 'q2apro flag reasons Admin'
);

// track events
qa_register_plugin_module(
    'event', 'q2apro-flag-reasons-event.php', 'q2apro_flagreasons_event', 'q2apro flag reasons Event'
);

// TODO: secure path from case when folder structure/name will change
//$flagReasonsMapFilePath = dirname(dirname(__FILE__)).'/' . 'report-reason/q2apro-flag-reasons-lang-default.php';
//var_dump('$flagReasonsMapFilePath: ', $flagReasonsMapFilePath);
//$flagReasonsMap = require $flagReasonsMapFilePath;

function q2apro_flag_reasonid_to_readable($reasonId)
{
    // TODO: make a consistent place to put flag reason translations
    $translationArray = [
        'SPAM',
        'Wypowiedź jest obraźliwa',
        'Nieprawidłowy temat/kategoria/otagowanie',
        'Niepełna lub niezrozumiała treść',
        'Kod nie jest umieszczony w odpowiednim bloczku',
        'Duplikat pytania (podaj link)',
        'Inny (podaj własny opis)'
    ];

//    var_dump('$reasonId:', $reasonId, ' /$translationArray[$reasonId]:', $translationArray[$reasonId]);
    return $translationArray[$reasonId];
//    return qa_lang('q2apro_flagreasons_lang/' . $translationArray[$reasonId]);
}

function q2apro_get_postflags($postId)
{
    $arr = qa_db_read_all_assoc(
        qa_db_query_sub(
            '
            SELECT `userid`, `postid`, `reasonid`, `notice`
            FROM ^flagreasons
            WHERE `postid` = #
            ', $postId
        )
    );

//    var_dump('<br>??? q2apro_get_postflags(',$postId,') /$arr:', $arr);
    return $arr;
}

function q2apro_count_postflags_output($postId)
{
    $flags = q2apro_get_postflags($postId);
//var_dump('<br> ???!!! $flags: ', serialize($flags));
    if (empty($flags)) {
        return '';
    }

    $flagOutput = [];
    $flagOutput[] = '<ul>';

    // count reasons
    foreach ($flags as $flag) {
        $handle = qa_userid_to_handle($flag['userid']);
        $flagOutput[] =
            '<li data-flag-author="' . $handle . '">Post zgłoszony z powodu <strong class="flag-reason">' .
            q2apro_flag_reasonid_to_readable($flag['reasonid']) .
            '</strong> przez <a href="' . qa_path('user') . '/' .
            $handle . '">' . $handle . '</a>.'
        ;

        if (!empty($flag['notice'])) {
            $flagOutput[] = ' Treść notatki: ' . $flag['notice'];
        }

        $flagOutput[] = '</li>';
    }
//    unset($flagOutput[count($flagOutput)-1]);

    $flagOutput[] = '</ul>';
    $flagsOutput = implode('', $flagOutput);

//    foreach ($flagOutput as $flag) {
//        $flagsOutput .= $flag;
//    }

    return $flagsOutput;
}
