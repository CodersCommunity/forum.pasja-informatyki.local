<?php

    class Ami_WhatsAppButton extends Ami_SocialButtonBasic
    {

        public function __construct( $params )
        {
            parent::__construct( $params );

            return $this;
        }

        function getName()
        {
            return 'wa';
        }

        function getClass()
        {
            return 'wa';
        }

        function getIcon()
        {
            return 'social-icon-whatsapp';
        }

        function getUrlTemplate()
        {
            return qa_sss_opt::WA_URL_TEMPLATE ;
        }
    }
