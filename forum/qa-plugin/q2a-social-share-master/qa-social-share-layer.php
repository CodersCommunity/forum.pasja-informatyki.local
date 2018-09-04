<?php
    if ( !defined( 'QA_VERSION' ) ) { // don't allow this page to be requested directly from browser
        exit;
    }

    class qa_html_theme_layer extends qa_html_theme_base
    {
        public function head_metas()
        {
            parent::head_metas();

            if ( qa_opt( qa_sss_opt::ENABLE_OPEN_GRAPH_SUPPORT ) && $this->template != 'admin' ) {
                if ( in_array( $this->template, array( 'question', 'blog' ) ) ) {
                    $content = @$this->content['q_view']['raw']['content'];
                    $image_url = ami_social_get_first_image_from_html( $content );
                    $description = @$this->content['description'];
                } else if ( $this->template == 'user' ) {
                    $image_html = @$this->content['form_profile']['fields']['avatar']['html'];
                    $image_url  = ami_social_get_first_image_from_html( $image_html );
                    $description = @$this->content['form_profile']['fields']['about']['value'];
                    $first_name  = @$this->content['form_profile']['fields']['name']['value'];
                    $username = qa_request_part( 1 );
                } else {
                    $description = qa_opt( qa_sss_opt::WEBSITE_DESCRIPTION );

                    if ( empty( $description ) )
                        $description = @$this->content['sidebar'];
                }

                if ( empty( $image_url ) ) {
                    //if the image URL is not found then take the default from the admin panel
                    $image_url = qa_opt( qa_sss_opt::DEFAULT_SHARE_IMAGE );
                }

                if ( empty( $image_url ) ) {
                    //if still empty then consider the logo URL
                    $image_url = qa_opt( 'logo_url' );
                }

                $image_url = htmlspecialchars_decode( urldecode( $image_url ) );
                $site_lang = qa_opt( 'site_language' );
                $locale = $site_lang ? $site_lang : 'en_US';

                $ogp = new OpenGraphProtocol();
                $ogp->setLocale( $locale );
                $ogp->setSiteName( qa_opt( 'site_title' ) );
                
                if ( isset($this->content['title']) ) {
                    $ogp->setTitle( strip_tags( $this->content['title'] ) );
                }
                
                $ogp->setDescription( strip_tags( $description ) );
                $ogp->setType( 'website' );
                $ogp->setURL( qa_path_absolute( qa_request() ) );
                $ogp->setDeterminer( 'the' );

                if ( $this->template == 'user' ) {
                    $ogp->setType( 'profile' );
                    $profile = new OpenGraphProtocolProfile();
                    $profile->setFirstName( $first_name );
                    $profile->setUsername( $username );

                    $ogp_html_arr = explode( PHP_EOL, $profile->toHTML() );
                    
                    if(count($ogp_html_arr)) {
                        foreach ($ogp_html_arr as $key => $ogp_html) {
                            $this->output_raw( $ogp_html );
                        }
                    }
                }

                if ( !empty( $image_url ) ) {
                    $image_data = @getimagesize( $image_url );
                    $imageOg = new OpenGraphProtocolImage();
                    $imageOg->setURL( $image_url );
                    $imageOg->setWidth( !empty( $image_data[0] ) ? $image_data[0] : 1200 );
                    $imageOg->setHeight( !empty( $image_data[0] ) ? $image_data[0] : 630 );

                    if ( !empty( $image_data['mime'] ) ) {
                        $imageOg->setType( $image_data['mime'] );
                    }

                    $ogp->addImage( $imageOg );
                }

                $ogp_html_arr = explode( PHP_EOL, $ogp->toHTML() );
                
                if(count($ogp_html_arr)) {
                    foreach ($ogp_html_arr as $key => $ogp_html) {
                        $this->output_raw( $ogp_html );
                    }
                }
                
                $facebook_app_id = qa_opt( qa_sss_opt::FACEBOOK_APP_ID );

                if ( !empty( $facebook_app_id ) ) {
                    $this->output_raw( '<meta property="fb:app_id" content="' . $facebook_app_id . '">' );
                }

                $twitter_username = trim( qa_opt( qa_sss_opt::TWITTER_HANDLE ) );

                if ( !empty( $twitter_username ) ) {
                    
                    if ( strpos( $twitter_username, '@' ) !== 0 )
                        $twitter_username = '@' . $twitter_username;

                    $this->output_raw( '<meta name="twitter:card" content="summary">' );
                    $this->output_raw( '<meta name="twitter:site" content=' . $twitter_username . '>' );
                    $this->output_raw( '<meta name="twitter:creator" content="' . $twitter_username . '">' );
                }

            }
        }

        function head_css()
        {
            parent::head_css();

            //$this->output( '<link rel="stylesheet" href="' . qa_path_to_root() . 'qa-plugin/' . SOCIAL_SHARE_PLUGIN_DIR_NAME . '/css/icons.css?'.SOCIAL_SHARE_PLUGIN_VERSION.'">' );
            $this->output( '<link rel="stylesheet" href="' . qa_path_to_root() . 'qa-plugin/' . SOCIAL_SHARE_PLUGIN_DIR_NAME . '/css/social-share.css?'.SOCIAL_SHARE_PLUGIN_VERSION.'">' );

            $style_open = '<style type="text/css">';
            $style_close = '</style>';
            $style_final = $style_open . qa_opt( qa_sss_opt::CUSTOM_CSS ) ;

            if(strtolower(qa_opt('site_theme')) == 'snow'){
                $style_final .= ".qa-widget-side .btn-share.with-icon .btn {width: 145px; }" ;
            }

            if(strtolower(qa_opt('site_theme')) == 'candy'){
                $style_final .= ".qa-widget-side .btn-share.with-icon .btn {width: 90px; }" ;
            }

            if(strtolower(qa_opt('site_theme')) == 'classic'){
                $style_final .= ".qa-widget-side .btn-share.with-icon .btn {width: 105px; }" ;
            }

            $style_final .= $style_close;
            $this->output( $style_final );

        }

        function q_view_buttons( $q_view )
        {
            if ( (int) qa_opt( qa_sss_opt::BUTTON_STATUS ) ) {
                $page_url = qa_path_absolute( qa_request() );
                $page_title = $q_view['raw']['title'];

                $args = array(
                    'title'       => $page_title,
                    'url'         => $page_url,
                    'template'    => $this->template,
                    'themeobject' => $this,
                    'target'      => '_blank',
                    'style'       => qa_opt( qa_is_mobile_probably() ? qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION : qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION ),
                );

                $social_share = new Ami_SocialShare( $args );

                $this->output( '<div class="social-wrapper">' );
                $social_share->generateShareButtons();
                $this->output( '</div>' );
            }
            
            parent::q_view_buttons( $q_view );
        }

    }

    /*
        Omit PHP closing tag to help avoid accidental output
    */
