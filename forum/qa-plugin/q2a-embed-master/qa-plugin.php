<?php
        
/*              
        Plugin Name: Embed
        Plugin URI: https://github.com/NoahY/q2a-embed
        Plugin Update Check URI: https://raw.github.com/NoahY/q2a-embed/master/qa-plugin.php
        Plugin Description: Embed Video, Images and MP3 files
        Plugin Version: 1.7
        Plugin Date: 2011-07-30
        Plugin Author: NoahY
        Plugin Author URI:                              
        Plugin License: GPLv2                           
        Plugin Minimum Question2Answer Version: 1.3
*/                      
                        
                        
        if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
                        header('Location: ../../');
                        exit;   
        }               

        qa_register_plugin_module('module', 'qa-embed-admin.php', 'qa_embed_admin', 'Embed Admin');
        
        qa_register_plugin_layer('qa-embed-layer.php', 'Embed Replacement Layer');
                        
                        
/*                              
        Omit PHP closing tag to help avoid accidental output
*/                              
                          

