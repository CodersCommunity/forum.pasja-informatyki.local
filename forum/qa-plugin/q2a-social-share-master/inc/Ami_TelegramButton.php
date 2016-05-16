<?php

    class Ami_TelegramButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 'tg';
        }

        function getClass()
        {
            return 'tg';
        }

        function getIcon()
        {
            return 'social-icon-paper-plane';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::TG_URL_TEMPLATE ;
        }
    }
