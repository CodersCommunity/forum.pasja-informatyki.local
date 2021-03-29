<?php

function qa_page_routing()
    /*
        Return an array of the default Q2A requests and which qa-page-*.php file implements them
        If the key of an element ends in /, it should be used for any request with that key as its prefix
    */
{
    return [
        'account' =>  '../qa-plugin/q2a-change-username-limit/page/account.php', // nadpisujemy route
        'activity/' => 'pages/activity.php',
        'admin/' => 'pages/admin/admin-default.php',
        'admin/approve' => 'pages/admin/admin-approve.php',
        'admin/categories' => 'pages/admin/admin-categories.php',
        'admin/flagged' => 'pages/admin/admin-flagged.php',
        'admin/hidden' => 'pages/admin/admin-hidden.php',
        'admin/layoutwidgets' => 'pages/admin/admin-widgets.php',
        'admin/moderate' => 'pages/admin/admin-moderate.php',
        'admin/pages' => 'pages/admin/admin-pages.php',
        'admin/plugins' => 'pages/admin/admin-plugins.php',
        'admin/points' => 'pages/admin/admin-points.php',
        'admin/recalc' => 'pages/admin/admin-recalc.php',
        'admin/stats' => 'pages/admin/admin-stats.php',
        'admin/userfields' => 'pages/admin/admin-userfields.php',
        'admin/usertitles' => 'pages/admin/admin-usertitles.php',
        'answers/' => 'pages/answers.php',
        'ask' => 'pages/ask.php',
        'categories/' => 'pages/categories.php',
        'comments/' => 'pages/comments.php',
        'confirm' => 'pages/confirm.php',
        'favorites' => 'pages/favorites.php',
        'favorites/questions' => 'pages/favorites-list.php',
        'favorites/users' => 'pages/favorites-list.php',
        'favorites/tags' => 'pages/favorites-list.php',
        'feedback' => 'pages/feedback.php',
        'forgot' => 'pages/forgot.php',
        'hot/' => 'pages/hot.php',
        'ip/' => 'pages/ip.php',
        'login' => 'pages/login.php',
        'logout' => 'pages/logout.php',
        'messages/' => 'pages/messages.php',
        'message/' => 'pages/message.php',
        'questions/' => 'pages/questions.php',
        'register' => 'pages/register.php',
        'reset' => 'pages/reset.php',
        'search' => 'pages/search.php',
        'tag/' => 'pages/tag.php',
        'tags' => 'pages/tags.php',
        'unanswered/' => 'pages/unanswered.php',
        'unsubscribe' => 'pages/unsubscribe.php',
        'updates' => 'pages/updates.php',
        'user/' => 'pages/user.php',
        'users' => 'pages/users.php',
        'users/blocked' => 'pages/users-blocked.php',
        'users/special' => 'pages/users-special.php',
    ];
}
