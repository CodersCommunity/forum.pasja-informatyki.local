<?php

    class Ami_GooglePlusButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 'gp';
        }

        function getClass()
        {
            return 'gp';
        }

        function getIcon()
        {
            return 'social-icon-google-plus';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::GP_URL_TEMPLATE ;
        }
    }
