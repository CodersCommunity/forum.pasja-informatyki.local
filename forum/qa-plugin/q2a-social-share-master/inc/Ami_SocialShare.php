<?php

    class Ami_SocialShare extends BasicModel
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
                'text'        => null,
                'target'      => null,
                'title'       => null,
                'style'       => '',
            );

            return $this;
        }

        public function generateShareButtons()
        {
            $request = qa_request() ;
            $share_text = '' ;

            if (empty($request)) {
                $share_text = qa_opt(qa_sss_opt::SHARE_TEXT_HOME);
            } else if($this->template == 'question'){
                $share_text = qa_opt(qa_sss_opt::SHARE_TEXT);
            } else if($this->template == 'blog'){
                $share_text = qa_opt(qa_sss_opt::SHARE_TEXT_BLOG_POST);
            }

            $this->themeobject->output( '<div class="social-share-text">' );
            $this->themeobject->output( $share_text );
            $this->themeobject->output( '</div>' );

            $enabled_share = qa_get_options(
                array(
                    qa_sss_opt::FB_BUTTON,
                    qa_sss_opt::GP_BUTTON,
                    qa_sss_opt::TW_BUTTON,
                    qa_sss_opt::LI_BUTTON,
                    qa_sss_opt::RE_BUTTON,
                    qa_sss_opt::ST_BUTTON,
                    qa_sss_opt::EM_BUTTON,
                    qa_sss_opt::VK_BUTTON,
                    qa_sss_opt::WA_BUTTON,
                    qa_sss_opt::TG_BUTTON)
            );

            foreach ( $enabled_share as $key => $value ) {
                if ( !$value ) unset( $enabled_share[$key] );
            }

            $buttonTemplate = $this->getStyleButtonTemplates( $this->style );

            foreach ( $enabled_share as $share_Key => $value ) {

                $social_share = Ami_SocialButtonFactory::getShareButtonByName( substr( $share_Key, -2 ) );

                if ( !empty( $social_share ) ) {

                    $url = $social_share->getShareLink( array( '{{page_url}}' => urlencode( $this->url ), '{{page_title}}' => urlencode( html_entity_decode( $this->title, ENT_COMPAT, 'UTF-8' ) ) ) );
                    $replace_pairs = array(
                        '{{url}}'    => $url,
                        '{{target}}' => $this->target,
                        '{{class}}'  => $social_share->getClass(),
                        '{{title}}'  => $social_share->getTitle(),
                        '{{text}}'   => $social_share->getText(),
                        '{{icon}}'   => ami_social_icon( $social_share->getIcon() ),
                    );

                    if ( $this->style == qa_sss_opt::SHARE_TYPE_TEXT ) {
                        $replace_pairs['{{separator}}'] = !is_last_key( $enabled_share, $share_Key ) ? ',' : '';
                    }

                    $btn = strtr( $buttonTemplate, $replace_pairs );
                    $this->themeobject->output( $btn );
                }
            }

        }

        public function getStyleButtonTemplates( $style )
        {
            $styles = array(
                qa_sss_opt::SHARE_TYPE_IMAGE                  => '<span class="img-sh"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="btn-img {{class}}" title="{{title}}">{{text}}</a></span>',
                qa_sss_opt::SHARE_TYPE_TEXT                   => '<span class="text-sh"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="share-link {{class}}" title="{{title}}">{{text}}</a></span>{{separator}}',
                qa_sss_opt::SHARE_TYPE_COLORED_BTNS           => '<span class="btn-share"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="btn btn-social btn-no-icon btn-{{class}}" title="{{title}}">{{text}}</a></span>',
                qa_sss_opt::SHARE_TYPE_COLORED_BTNS_WITH_ICON => '<span class="btn-share with-icon"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="btn btn-social btn-{{class}}" title="{{title}}">{{icon}} {{text}}</a></span>',
                qa_sss_opt::SHARE_TYPE_FI_SQ                  => '<span class="btn-share only-icon"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="btn btn-social btn-social-icon btn-sqr btn-{{class}}" title="{{title}}">{{icon}}</a></span>',
                qa_sss_opt::SHARE_TYPE_FI_SEMI_ROUNDED        => '<span class="btn-share only-icon semi-rounded"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="btn btn-social btn-social-icon btn-semi-rounded btn-{{class}}" title="{{title}}">{{icon}}</a></span>',
                qa_sss_opt::SHARE_TYPE_FI_ROUNDED             => '<span class="btn-share only-icon rounded"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="btn btn-social btn-social-icon btn-rounded btn-{{class}}" title="{{title}}">{{icon}}</a></span>',
                qa_sss_opt::SHARE_TYPE_ANIMATED_FI            => '<span class="btn-share only-icon amimated"><a href="{{url}}" target="{{target}}" rel="external nofollow" class="btn btn-social btn-social-icon btn-sqr-animated btn-{{class}}" title="{{title}}">{{icon}}</a></span>',
            );

            return $styles[$style];
        }
    }






