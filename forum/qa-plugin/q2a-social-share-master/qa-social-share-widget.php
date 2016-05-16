<?php
    if ( !defined( 'QA_VERSION' ) ) { // don't allow this page to be requested directly from browser
        exit;
    }

    class qa_social_share_widget
    {

        function allow_template( $template )
        {
            return ( $template == 'question' || $template == 'blog' || $template == 'qa' );
        }

        function allow_region( $region )
        {
            return true;
        }

        function output_widget( $region, $place, $themeobject, $template, $request, $qa_content )
        {
            $page_url = qa_path_absolute( qa_request() ) ;
            if ( ( $template == 'question' || $template == 'blog' ) && isset( $qa_content["q_view"] ) ) {
                $page_title = $qa_content["q_view"]["raw"]["title"];
            } else {
                $page_title = qa_opt( 'site_title' );
            }

            $args = array(
                'title'       => $page_title,
                'url'         => $page_url,
                'template'    => $template,
                'themeobject' => $themeobject,
                'target'      => '_blank',
                'style'       => qa_opt( qa_is_mobile_probably() ? qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION : qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION ),
            );

            $social_share = new Ami_SocialShare( $args );

            $themeobject->output( '<div class="social-wrapper">' );
            $social_share->generateShareButtons();
            $themeobject->output( '</div>' );
        }

    }

    /*
        Omit PHP closing tag to help avoid accidental output
    */
