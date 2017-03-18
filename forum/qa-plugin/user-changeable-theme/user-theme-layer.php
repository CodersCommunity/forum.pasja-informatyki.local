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
            $this->content['head_lines'][] = '<style>

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

    public function nav_item($key, $navlink, $class, $level = null)
    {
        qa_html_theme_base::nav_item($key, $navlink, $class, $level = null);
        if (qa_opt('user_theme_enable')) {
            if (isset($_POST['select_theme'])) {
                $this->save_theme($_POST['select_theme']);
            }
            if ($class === 'nav-user' && ($key === 'logout' || $key === 'register')) {
                $this->output('<li class="qa-'.$class.'-item">Motyw:
                    <form action="/'.qa_request().'" method="post">
                        <button name="select_theme" value="0">Jasny</button>
                        <button name="select_theme" value="1">Ciemny</button>
                    </form>
                </li>');
            }
        }
    }
}
