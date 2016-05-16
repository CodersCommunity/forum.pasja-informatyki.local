<?php
        
/*              
    Plugin Name: Featured Questions
    Plugin URI: https://github.com/NoahY/q2a-featured
    Plugin Update Check URI: https://raw.github.com/NoahY/q2a-featured/master/qa-plugin.php
    Plugin Description: Keep featured questions at top of list
    Plugin Version: 1
    Plugin Date: 2011-12-28
    Plugin Author: NoahY
    Plugin Author URI:
    Plugin License: GPLv2                           
    Plugin Minimum Question2Answer Version: 1.4
*/                      
                        
                        
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
                    header('Location: ../../');
                    exit;   
    }               

    qa_register_plugin_module('module', 'qa-featured-admin.php', 'qa_featured_admin', 'Featured Questions');
    qa_register_plugin_layer('qa-featured-layer.php', 'Featured Layer');
    
/*                              
    Omit PHP closing tag to help avoid accidental output
*/                              
                          

