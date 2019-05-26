<?php

class qa_html_theme_layer extends qa_html_theme_base
{
    private $cookie = 'qa_user_theme';

    private function check_theme()
    {
        if (isset($_COOKIE[$this->cookie])) {
            return (bool)$_COOKIE[$this->cookie];
        } elseif ($user = qa_get_logged_in_userid()) {
            $sql = 'SELECT `theme` FROM `^users` WHERE `userid` = #';
            $theme = qa_db_read_one_value(qa_db_query_sub($sql, $user));
            setcookie($this->cookie, $theme, time()+31556926, '/', QA_COOKIE_DOMAIN);
            return (bool)$theme;
        }
        return false;
    }

    private function save_theme($theme)
    {
        if ($theme == 0 || $theme == 1) {
            if ($this->check_theme() !== (bool)$theme) {
                if ($user = qa_get_logged_in_userid()) {
                    $sql = 'UPDATE `^users` SET `theme` = # WHERE `userid` = #';
                    qa_db_query_sub($sql, $theme, $user);
                }
                setcookie($this->cookie, $theme, time()+31556926, '/', QA_COOKIE_DOMAIN);
            }
        }
        qa_redirect(qa_request());
    }

    public function head_lines()
    {
        if (qa_opt('user_theme_enable')) {
            if (isset($_POST['select_theme'])) {
                $this->save_theme($_POST['select_theme']);
            }
            if ($this->check_theme()) {
                $this->content['head_lines'][] = '<meta name="theme-color" content="#181a1f">';
            }
            $this->content['head_lines'][] = '<style>
				.widget-select-theme {
					display: -webkit-flex;
					display: -ms-flexbox;
					display: flex;
					-webkit-justify-content: center;
					-ms-flex-pack: center;
					        justify-content: center;
					-webkit-align-items: center;
					-ms-flex-align: center;
					        align-items: center
				}

				.widget-select-theme__text {
					margin: 0 10px 2px 0;
				}

				.widget-select-theme__button {
					background: none;
					color: #34495e;
					background: white;
					border: 1px solid #34495e;
					width: 80px;
					padding: 10px 0;
					transition: all 0.2s;
					outline: none;
				}

				.widget-select-theme__button--dark {
					background: #34495e;
					color: white;
					border-left: none;
				}

				.widget-select-theme__button--dark:hover, .widget-select-theme__button--dark:focus {
					background: #1e2f40;
				}

				.widget-select-theme__button--light {
					pointer-events: none;
				}

				/* force to display theme buttons (mobile) */
				@media (max-width: 1179px) {
					.qa-sidepanel {
						width: 100% !important;
						display: block !important;
					}

					.qa-sidepanel > div:not(.qa-widgets-side-bottom) {
						display: none;
					}
				}
            </style>';
        }
        qa_html_theme_base::head_lines();
    }

    public function head_css()
    {
        if (qa_opt('user_theme_enable')) {
            if ($this->check_theme()) {
                $this->content['css_src'][] = QA_HTML_THEME_LAYER_URLTOROOT.'/themes/dark/build/main.css';
            }
        }
        qa_html_theme_base::head_css();
    }
}
