<?php

    interface Ami_SocialButton
    {
        function getName();

        function getTitle();

        function getText();

        function getClass();

        function getIcon();

        function getShareLink( $args );

        function getUrlTemplate();
    }