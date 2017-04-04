<?php

namespace Xandros15;

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

/**
 * Register plugin before the other one
 *
 * @param $beforePluginName
 * @param $newPluginParams
 */
function register_before($beforePluginName, array $newPluginParams)
{
    global $qa_modules;
    if (isset($qa_modules[$newPluginParams['type']][$beforePluginName])) {

        $module = $qa_modules[$newPluginParams['type']][$beforePluginName];
        unset($qa_modules[$newPluginParams['type']][$beforePluginName]);
        
        register_new_plugin();
        
        $qa_modules[$newPluginParams['type']][$beforePluginName] = $module;
    } else {
        register_new_plugin();
    }
    
}

function register_new_plugin()
{
    qa_register_plugin_module(
        $newPluginParams['type'],
        $newPluginParams['include'],
        $newPluginParams['class'],
        $newPluginParams['name']
    );
}
