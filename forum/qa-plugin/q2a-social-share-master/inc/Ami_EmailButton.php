<?php

    class Ami_EmailButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 'em';
        }

        function getClass()
        {
            return 'em';
        }

        function getIcon()
        {
            return 'social-icon-envelope';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::EM_URL_TEMPLATE ;
        }
    }
