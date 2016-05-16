<?php

    class Ami_FacebookButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 'fb';
        }

        function getClass()
        {
            return 'fb';
        }

        function getIcon()
        {
            return 'social-icon-facebook';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::FB_URL_TEMPLATE ;
        }
    }
