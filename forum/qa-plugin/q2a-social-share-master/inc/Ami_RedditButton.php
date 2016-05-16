<?php

    class Ami_RedditButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 're';
        }

        function getClass()
        {
            return 're';
        }

        function getIcon()
        {
            return 'social-icon-reddit';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::RE_URL_TEMPLATE ;
        }
    }
