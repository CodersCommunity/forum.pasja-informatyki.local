<?php

function qa_page_routing()
{
    $base =  qa_page_routing_base();

    if ($base['account'] != 'pages/account.php') {
        qa_fatal_error('plugin_username_limit/routing_currently_overwritten');
    }

    $base['account'] = '../qa-plugin/q2a-change-username-limit/page/account.php';

    return $base;
}
