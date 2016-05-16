<?php

    class Ami_StumbleUponButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 'st';
        }

        function getClass()
        {
            return 'st';
        }

        function getIcon()
        {
            return 'social-icon-stumbleupon';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::ST_URL_TEMPLATE ;
        }
    }
