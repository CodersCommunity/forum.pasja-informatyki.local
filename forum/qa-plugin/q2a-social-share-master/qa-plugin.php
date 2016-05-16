<?php

    /*
        Plugin Name: Q2A Social Sharing
        Plugin URI: https://github.com/amiyasahu/q2a-social-share/
        Plugin Description: Adds Clickable Social Sharing Buttons Below Questions
        Plugin Version: 1.7.0
        Plugin Date: 2016-02-07
        Plugin Author: Amiya Sahu
        Plugin Author URI: http://amiyasahu.com
        Plugin License: GPLv2
        Plugin Minimum Question2Answer Version: 1.5
        Plugin Update Check URI: https://raw.githubusercontent.com/amiyasahu/q2a-social-share/master/qa-plugin.php
    */

    if ( !defined( 'QA_VERSION' ) ) { // don't allow this page to be requested directly from browser
        exit;
    }

    @define( 'SOCIAL_SHARE_PLUGIN_DIR', dirname( __FILE__ ) );
    @define( 'SOCIAL_SHARE_PLUGIN_DIR_NAME', basename( dirname( __FILE__ ) ) );
    @define( 'SOCIAL_SHARE_PLUGIN_VERSION', "1.6.1" );

    require_once SOCIAL_SHARE_PLUGIN_DIR . '/qa-social-share-utils.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/qa-social-share-options.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/BasicModel.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_SocialButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_SocialButtonBasic.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_EmailButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_FacebookButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_GooglePlusButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_LinkedInButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_RedditButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_TwitterButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_VkButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_WhatsAppButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_TelegramButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_StumbleUponButton.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_SocialButtonFactory.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/inc/Ami_SocialShare.php';
    require_once SOCIAL_SHARE_PLUGIN_DIR . '/opengraph/open-graph-protocol.php';
    
    qa_register_plugin_layer( 'qa-social-share-layer.php', 'Social Sharing Layer' );
    qa_register_plugin_module( 'module', 'qa-social-share-admin.php', 'qa_social_share_admin', 'Social Sharing Admin' );
    qa_register_plugin_module( 'widget', 'qa-social-share-widget.php', 'qa_social_share_widget', 'Social Sharing Widget' );
    qa_register_plugin_phrases( 'lang/qa-social-share-lang-*.php', 'sss_lang' );

    /*
        Omit PHP closing tag to help avoid accidental output
    */
