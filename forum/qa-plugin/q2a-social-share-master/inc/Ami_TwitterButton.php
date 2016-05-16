<?php

    class Ami_TwitterButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 'tw';
        }

        function getClass()
        {
            return 'tw';
        }

        function getIcon()
        {
            return 'social-icon-twitter';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::TW_URL_TEMPLATE ;
        }
    }
