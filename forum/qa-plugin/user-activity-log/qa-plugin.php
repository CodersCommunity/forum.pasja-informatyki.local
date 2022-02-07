<?php
if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}
qa_register_plugin_overrides('user-activity-log-overrides.php');
qa_register_plugin_module('page', 'user-activity-log.php', 'user_activity_log', 'User activity log');
qa_register_plugin_module('page', 'user-activity-log-search.php', 'user_activity_search', 'User activity log search results');
qa_register_plugin_phrases('lang/*.php', 'user-activity-log');