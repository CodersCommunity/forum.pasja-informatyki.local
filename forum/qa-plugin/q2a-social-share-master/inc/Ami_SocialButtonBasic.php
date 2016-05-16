<?php

    abstract class Ami_SocialButtonBasic extends BasicModel implements Ami_SocialButton
    {
        public function __construct( $params )
        {
            $this->_init();
            $this->copy_from( $params );

            return $this;
        }

        public function _init()
        {
            $this->_data = array(
                'themeobject' => null,
                'template'    => null,
                'url'         => null,
                'title'       => null,
            );

            return $this;
        }

        function getShareLink( $args )
        {
            return strtr( $this->getUrlTemplate(), $args );
        }

        function getTitle()
        {
            return ami_social_share_lang( $this->getName() );
        }

        function getText()
        {
            return ami_social_share_lang( $this->getName() );
        }
    }