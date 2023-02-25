<?php

//Don't let this page be accessed directly
if (!defined('QA_VERSION')) {
    header('Location: ../../../');
    exit;
}



qa_register_plugin_layer('users-hidden-posts-layer.php', 'User Hidden Posts Layer');

qa_register_plugin_module('page', 'qa-users-hidden-posts.php', 'qa_users_hidden_posts', 'Users hidden posts');
qa_register_plugin_phrases('lang/*.php', 'users-hidden-posts');