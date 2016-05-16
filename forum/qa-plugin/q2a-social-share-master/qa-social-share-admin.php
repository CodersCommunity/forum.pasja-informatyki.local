<?php
    if ( !defined( 'QA_VERSION' ) ) { // don't allow this page to be requested directly from browser
        exit;
    }

    class qa_social_share_admin
    {

        function allow_template( $template )
        {
            return ( $template != 'admin' );
        }

        function option_default( $option )
        {
            switch ( $option ) {
                case qa_sss_opt::SHARE_TEXT:
                case qa_sss_opt::SHARE_TEXT_HOME:
                case qa_sss_opt::SHARE_TEXT_BLOG_POST:
                    return;
                    break;

                case qa_sss_opt::FB_BUTTON:
                case qa_sss_opt::GP_BUTTON:
                case qa_sss_opt::TW_BUTTON:
                case qa_sss_opt::WA_BUTTON:
                case qa_sss_opt::BUTTON_STATUS:
                    return true;
                    break;

                case qa_sss_opt::LI_BUTTON:
                case qa_sss_opt::RE_BUTTON:
                case qa_sss_opt::VK_BUTTON:
                case qa_sss_opt::EM_BUTTON:
                case qa_sss_opt::ST_BUTTON:
                case qa_sss_opt::TG_BUTTON:
                    return false;
                    break;

                case qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION:
                case qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION:
                case qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION:
                    return qa_sss_opt::SHARE_TYPE_COLORED_BTNS_WITH_ICON;
                    break;

                case qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION:
                    return qa_sss_opt::SHARE_TYPE_FI_SEMI_ROUNDED;
                    break;
            }
        }

        function admin_form()
        {
            //require_once QA_INCLUDE_DIR.'qa-util-sort.php';

            $saved = false;

            if ( qa_clicked( qa_sss_opt::ADMIN_SAVE_BTN ) ) {

                $trimchars = "=;\"\' \t\r\n"; // prevent common errors by copying and pasting from Javascript
                qa_opt( qa_sss_opt::SHARE_TEXT, trim( qa_post_text( qa_sss_opt::SHARE_TEXT ), $trimchars ) );
                qa_opt( qa_sss_opt::SHARE_TEXT_HOME, trim( qa_post_text( qa_sss_opt::SHARE_TEXT_HOME ), $trimchars ) );
                qa_opt( qa_sss_opt::SHARE_TEXT_BLOG_POST, trim( qa_post_text( qa_sss_opt::SHARE_TEXT_BLOG_POST ), $trimchars ) );
                qa_opt( qa_sss_opt::FB_BUTTON, (bool) qa_post_text( qa_sss_opt::FB_BUTTON ) );
                qa_opt( qa_sss_opt::GP_BUTTON, (bool) qa_post_text( qa_sss_opt::GP_BUTTON ) );
                qa_opt( qa_sss_opt::TW_BUTTON, (bool) qa_post_text( qa_sss_opt::TW_BUTTON ) );
                qa_opt( qa_sss_opt::LI_BUTTON, (bool) qa_post_text( qa_sss_opt::LI_BUTTON ) );
                qa_opt( qa_sss_opt::RE_BUTTON, (bool) qa_post_text( qa_sss_opt::RE_BUTTON ) );
                qa_opt( qa_sss_opt::VK_BUTTON, (bool) qa_post_text( qa_sss_opt::VK_BUTTON ) );
                qa_opt( qa_sss_opt::EM_BUTTON, (bool) qa_post_text( qa_sss_opt::EM_BUTTON ) );
                qa_opt( qa_sss_opt::WA_BUTTON, (bool) qa_post_text( qa_sss_opt::WA_BUTTON ) );
                qa_opt( qa_sss_opt::ST_BUTTON, (bool) qa_post_text( qa_sss_opt::ST_BUTTON ) );
                qa_opt( qa_sss_opt::TG_BUTTON, (bool) qa_post_text( qa_sss_opt::TG_BUTTON ) );
                qa_opt( qa_sss_opt::BUTTON_STATUS, (bool) qa_post_text( qa_sss_opt::BUTTON_STATUS ) );
                qa_opt( qa_sss_opt::ENABLE_OPEN_GRAPH_SUPPORT, (bool) qa_post_text( qa_sss_opt::ENABLE_OPEN_GRAPH_SUPPORT ) );
                qa_opt( qa_sss_opt::FACEBOOK_APP_ID, qa_post_text( qa_sss_opt::FACEBOOK_APP_ID ) );
                qa_opt( qa_sss_opt::TWITTER_HANDLE, qa_post_text( qa_sss_opt::TWITTER_HANDLE ) );
                qa_opt( qa_sss_opt::WEBSITE_DESCRIPTION, qa_post_text( qa_sss_opt::WEBSITE_DESCRIPTION ) );
                qa_opt( qa_sss_opt::DEFAULT_SHARE_IMAGE, qa_post_text( qa_sss_opt::DEFAULT_SHARE_IMAGE ) );

                qa_opt( qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION, qa_post_text( qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION ) );
                qa_opt( qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION, qa_post_text( qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION ) );
                qa_opt( qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION, qa_post_text( qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION ) );
                qa_opt( qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION, qa_post_text( qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION ) );
                qa_opt( qa_sss_opt::CUSTOM_CSS, qa_post_text( qa_sss_opt::CUSTOM_CSS ) );

                $saved = true;
            }

            $social_share_types = array(
                qa_sss_opt::SHARE_TYPE_IMAGE                  => 'Image',
                qa_sss_opt::SHARE_TYPE_TEXT                   => 'Textual sharing',
                qa_sss_opt::SHARE_TYPE_COLORED_BTNS           => 'Colored buttons',
                qa_sss_opt::SHARE_TYPE_COLORED_BTNS_WITH_ICON => 'Colored buttons with icon',
                qa_sss_opt::SHARE_TYPE_FI_SQ                  => 'Squared icons',
                qa_sss_opt::SHARE_TYPE_FI_SEMI_ROUNDED        => 'Semi-rounded buttons with icon',
                qa_sss_opt::SHARE_TYPE_FI_ROUNDED             => 'Rounded buttons with icon',
                qa_sss_opt::SHARE_TYPE_ANIMATED_FI            => 'Animated buttons with icon',
            );

            $form = array(
                'ok'      => $saved ? qa_lang( 'sss_lang/sss_settings_saved' ) : null,

                'fields'  => array(
                    qa_sss_opt::SHARE_TEXT_HOME                  => $this->get_share_text_home_field(),
                    qa_sss_opt::SHARE_TEXT                       => $this->get_share_text_field(),
                    qa_sss_opt::SHARE_TEXT_BLOG_POST             => $this->get_share_text_blog_post_field(),
                    qa_sss_opt::FB_BUTTON                        => $this->get_fb_button_field(),
                    qa_sss_opt::GP_BUTTON                        => $this->get_gp_button_field(),
                    qa_sss_opt::TW_BUTTON                        => $this->get_tw_button_field(),
                    qa_sss_opt::LI_BUTTON                        => $this->get_li_button_field(),
                    qa_sss_opt::RE_BUTTON                        => $this->get_re_button_field(),
                    qa_sss_opt::VK_BUTTON                        => $this->get_vk_button_field(),
                    qa_sss_opt::ST_BUTTON                        => $this->get_st_button_field(),
                    qa_sss_opt::EM_BUTTON                        => $this->get_em_button_field(),
                    qa_sss_opt::WA_BUTTON                        => $this->get_wa_button_field(),
                    qa_sss_opt::TG_BUTTON                        => $this->get_tg_button_field(),
                    qa_sss_opt::BUTTON_STATUS                    => $this->get_button_status_field(),
                    qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION   => $this->get_share_type_q_desktop_button( $social_share_types ),
                    qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION    => $this->get_share_type_q_mobile_button( $social_share_types ),
                    qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION => $this->get_share_type_w_desktop_button( $social_share_types ),
                    qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION  => $this->get_share_type_w_mobile_button( $social_share_types ),
                    qa_sss_opt::CUSTOM_CSS                       => $this->get_custom_css_field(),
                    qa_sss_opt::ENABLE_OPEN_GRAPH_SUPPORT        => $this->get_enable_opengraph_support_field(),
                    qa_sss_opt::DEFAULT_SHARE_IMAGE              => $this->get_default_image_field(),
                    qa_sss_opt::FACEBOOK_APP_ID                  => $this->get_facebook_app_id_field(),
                    qa_sss_opt::TWITTER_HANDLE                   => $this->get_twitter_handle_field(),
                    qa_sss_opt::WEBSITE_DESCRIPTION              => $this->get_site_description_field(),
                ),

                'buttons' => array(
                    array(
                        'label' => qa_lang( 'sss_lang/save_changes' ),
                        'tags'  => 'name="' . qa_sss_opt::ADMIN_SAVE_BTN . '"',
                    ),
                ),
            );

            return $form;
        }

        /**
         * @return array
         */
        public function get_share_text_home_field()
        {
            return array(
                'id'    => qa_sss_opt::SHARE_TEXT_HOME,
                'label' => qa_lang( 'sss_lang/enter_share_text_for_home' ),
                'value' => qa_html( qa_opt( qa_sss_opt::SHARE_TEXT_HOME ) ),
                'tags'  => 'name="' . qa_sss_opt::SHARE_TEXT_HOME . '"',
            );
        }

        /**
         * @return array
         */
        public function get_share_text_field()
        {
            return array(
                'id'    => qa_sss_opt::SHARE_TEXT,
                'label' => qa_lang( 'sss_lang/enter_share_text' ),
                'value' => qa_html( qa_opt( qa_sss_opt::SHARE_TEXT ) ),
                'tags'  => 'name="' . qa_sss_opt::SHARE_TEXT . '"',
                'note'  => qa_lang( 'sss_lang/choose_buttons_from_below' ),
            );
        }

        /**
         * @return array
         */
        public function get_share_text_blog_post_field()
        {
            return array(
                'id'    => qa_sss_opt::SHARE_TEXT_BLOG_POST,
                'label' => qa_lang( 'sss_lang/enter_share_text_for_blog_post' ),
                'value' => qa_html( qa_opt( qa_sss_opt::SHARE_TEXT_BLOG_POST ) ),
                'tags'  => 'name="' . qa_sss_opt::SHARE_TEXT_BLOG_POST . '"',
                'note'  => qa_lang( 'sss_lang/choose_buttons_from_below' ),
            );
        }

        /**
         * @return array
         */
        public function get_fb_button_field()
        {
            return array(
                'id'    => qa_sss_opt::FB_BUTTON,
                'label' => qa_lang( 'sss_lang/fb' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::FB_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::FB_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_gp_button_field()
        {
            return array(
                'id'    => qa_sss_opt::GP_BUTTON,
                'label' => qa_lang( 'sss_lang/gp' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::GP_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::GP_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_tw_button_field()
        {
            return array(
                'id'    => qa_sss_opt::TW_BUTTON,
                'label' => qa_lang( 'sss_lang/tw' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::TW_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::TW_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_li_button_field()
        {
            return array(
                'id'    => qa_sss_opt::LI_BUTTON,
                'label' => qa_lang( 'sss_lang/li' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::LI_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::LI_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_re_button_field()
        {
            return array(
                'id'    => qa_sss_opt::RE_BUTTON,
                'label' => qa_lang( 'sss_lang/reddit' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::RE_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::RE_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_vk_button_field()
        {
            return array(
                'id'    => qa_sss_opt::VK_BUTTON,
                'label' => qa_lang( 'sss_lang/vk' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::VK_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::VK_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_st_button_field()
        {
            return array(
                'id'    => qa_sss_opt::ST_BUTTON,
                'label' => qa_lang( 'sss_lang/st' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::ST_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::ST_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_em_button_field()
        {
            return array(
                'id'    => qa_sss_opt::EM_BUTTON,
                'label' => qa_lang( 'sss_lang/email' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::EM_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::EM_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_wa_button_field()
        {
            return array(
                'id'    => qa_sss_opt::WA_BUTTON,
                'label' => qa_lang( 'sss_lang/whatsapp' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::WA_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::WA_BUTTON . '"',
            );
        }

        /**
         * @return array
         */
        public function get_tg_button_field()
        {
            return array(
                'id'    => qa_sss_opt::TG_BUTTON,
                'label' => qa_lang( 'sss_lang/telegram' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::TG_BUTTON ),
                'tags'  => 'name="' . qa_sss_opt::TG_BUTTON . '"',
                'note'  => qa_lang( 'sss_lang/sharing_btn_enable_note' ),
            );
        }

        /**
         * @return array
         */
        public function get_button_status_field()
        {
            return array(
                'id'    => qa_sss_opt::BUTTON_STATUS,
                'label' => (int) qa_opt( qa_sss_opt::BUTTON_STATUS ) ? qa_lang( 'sss_lang/currently_enabled' ) : qa_lang( 'sss_lang/currently_disabled' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::BUTTON_STATUS ),
                'tags'  => 'name="' . qa_sss_opt::BUTTON_STATUS . '"',
            );
        }

        /**
         * @param $social_share_types
         *
         * @return array
         */
        public function get_share_type_q_desktop_button( $social_share_types )
        {
            return array(
                'id'       => qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION,
                'label'    => qa_lang( 'sss_lang/choose_share_type_for_q_desktop' ),
                'type'     => 'select',
                'value'    => qa_opt( qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION ),
                'tags'     => 'name="' . qa_sss_opt::SHARE_TYPE_POST_DESKTOP_OPTION . '"',
                'options'  => $social_share_types,
                'match_by' => 'key',
            );
        }


        /**
         * @param $social_share_types
         *
         * @return array
         */
        public function get_share_type_q_mobile_button( $social_share_types )
        {
            return array(
                'id'       => qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION,
                'label'    => qa_lang( 'sss_lang/choose_share_type_for_q_mobile' ),
                'type'     => 'select',
                'value'    => qa_opt( qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION ),
                'tags'     => 'name="' . qa_sss_opt::SHARE_TYPE_POST_MOBILE_OPTION . '"',
                'options'  => $social_share_types,
                'match_by' => 'key',
            );
        }


        /**
         * @param $social_share_types
         *
         * @return array
         */
        public function get_share_type_w_desktop_button( $social_share_types )
        {
            return array(
                'id'       => qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION,
                'label'    => qa_lang( 'sss_lang/choose_share_type_for_w_desktop' ),
                'type'     => 'select',
                'value'    => qa_opt( qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION ),
                'tags'     => 'name="' . qa_sss_opt::SHARE_TYPE_WIDGET_DESKTOP_OPTION . '"',
                'options'  => $social_share_types,
                'match_by' => 'key',
            );
        }


        /**
         * @param $social_share_types
         *
         * @return array
         */
        public function get_share_type_w_mobile_button( $social_share_types )
        {
            return array(
                'id'       => qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION,
                'label'    => qa_lang( 'sss_lang/choose_share_type_for_w_mobile' ),
                'type'     => 'select',
                'value'    => qa_opt( qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION ),
                'tags'     => 'name="' . qa_sss_opt::SHARE_TYPE_WIDGET_MOBILE_OPTION . '"',
                'options'  => $social_share_types,
                'match_by' => 'key',
            );
        }

        /**
         * @return array
         */
        public function get_custom_css_field()
        {
            return array(
                'id'    => qa_sss_opt::CUSTOM_CSS,
                'label' => qa_lang( 'sss_lang/custom_css' ),
                'type'  => 'textarea',
                'rows'  => 6,
                'value' => qa_opt( qa_sss_opt::CUSTOM_CSS ),
                'tags'  => 'name="' . qa_sss_opt::CUSTOM_CSS . '"',
            );
        }

        /**
         * @return array
         */
        public function get_enable_opengraph_support_field()
        {
            return array(
                'id'    => qa_sss_opt::ENABLE_OPEN_GRAPH_SUPPORT,
                'label' => ami_social_share_lang( 'enable_opengraph_support' ),
                'type'  => 'checkbox',
                'value' => (int) qa_opt( qa_sss_opt::ENABLE_OPEN_GRAPH_SUPPORT ),
                'tags'  => 'name="' . qa_sss_opt::ENABLE_OPEN_GRAPH_SUPPORT . '"',
            );
        }

        /**
         * @return array
         */
        public function get_facebook_app_id_field()
        {
            return array(
                'id'    => qa_sss_opt::FACEBOOK_APP_ID,
                'label' => ami_social_share_lang( 'facebook_app_id' ),
                'value' => qa_html( qa_opt( qa_sss_opt::FACEBOOK_APP_ID ) ),
                'tags'  => 'name="' . qa_sss_opt::FACEBOOK_APP_ID . '"',
            );
        }

        /**
         * @return array
         */
        public function get_twitter_handle_field()
        {
            return array(
                'id'    => qa_sss_opt::TWITTER_HANDLE,
                'label' => ami_social_share_lang( 'twitter_handle' ),
                'value' => qa_html( qa_opt( qa_sss_opt::TWITTER_HANDLE ) ),
                'tags'  => 'name="' . qa_sss_opt::TWITTER_HANDLE . '"',
            );
        }

        /**
         * @return array
         */
        public function get_default_image_field()
        {
            return array(
                'id'    => qa_sss_opt::DEFAULT_SHARE_IMAGE,
                'label' => ami_social_share_lang( 'default_share_image' ),
                'value' => qa_html( qa_opt( qa_sss_opt::DEFAULT_SHARE_IMAGE ) ),
                'tags'  => 'name="' . qa_sss_opt::DEFAULT_SHARE_IMAGE . '"',
            );
        }

        /**
         * @return array
         */
        public function get_site_description_field()
        {
            return array(
                'id'    => qa_sss_opt::WEBSITE_DESCRIPTION,
                'label' => ami_social_share_lang( 'website_desc' ),
                'type'  => 'textarea',
                'rows'  => 6,
                'value' => qa_opt( qa_sss_opt::WEBSITE_DESCRIPTION ),
                'tags'  => 'name="' . qa_sss_opt::WEBSITE_DESCRIPTION . '"',
            );
        }

    }


    /*
        Omit PHP closing tag to help avoid accidental output
    */