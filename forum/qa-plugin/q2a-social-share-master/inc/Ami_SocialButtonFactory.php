<?php

    class Ami_SocialButtonFactory
    {
        private static $share_buttons = array();

        public static function register( Ami_SocialButtonBasic $shareButton )
        {
            self::$share_buttons[$shareButton->getName()] = $shareButton;
        }

        public static function unregister( Ami_SocialButtonBasic $shareButton )
        {
            unset( self::$share_buttons[$shareButton->getName()] );
        }

        public static function getShareButtonByName( $name )
        {
            if ( isset( self::$share_buttons[$name] ) ) {
                return clone self::$share_buttons[$name];
            }

            return null;
        }
    }

    /**
     * Register this class to the factory
     */
    Ami_SocialButtonFactory::register( new Ami_FacebookButton( array() ) );
    Ami_SocialButtonFactory::register( new Ami_GooglePlusButton( array() ) );
    Ami_SocialButtonFactory::register( new Ami_VkButton( array() ) );
    Ami_SocialButtonFactory::register( new Ami_EmailButton( array() ) );
    Ami_SocialButtonFactory::register( new Ami_RedditButton( array() ) );
    Ami_SocialButtonFactory::register( new Ami_LinkedInButton( array() ) );
    Ami_SocialButtonFactory::register( new Ami_TwitterButton( array() ) );
    Ami_SocialButtonFactory::register( new Ami_StumbleUponButton( array() ) );

    if(qa_is_mobile_probably()){
        Ami_SocialButtonFactory::register( new Ami_WhatsAppButton( array() ) );
        Ami_SocialButtonFactory::register( new Ami_TelegramButton( array() ) );
    }
