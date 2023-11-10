<?php
/*
	Snow Theme for Question2Answer Package
	Copyright (C) 2014 Q2A Market <http://www.q2amarket.com>

	File:           qa-theme.php
	Version:        Snow 1.4
	Description:    Q2A theme class

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
*/

/**
 * Snow theme extends
 *
 * Extends the core theme class <code>qa_html_theme_base</code>
 *
 * @package qa_html_theme_base
 * @subpackage qa_html_theme
 * @category Theme
 * @since Snow 1.0
 * @version 1.4
 * @author Q2A Market <http://www.q2amarket.com>
 * @copyright (c) 2014, Q2A Market
 * @license http://www.gnu.org/copyleft/gpl.html
 */
class qa_html_theme extends qa_html_theme_base
{
    private $js_dir;
    private $img_url;
    private $icon_url;

    /**
     * @since Snow 1.4
     * @param string $template
     * @param string $content
     * @param string $rooturl
     * @param string $request
     */
    public function __construct($template, $content, $rooturl, $request)
    {
        parent::__construct($template, $content, $rooturl, $request);

        // theme subdirectories
        $this->js_dir = 'js/';
        $this->img_url = 'images/';
        $this->icon_url = $this->img_url . 'icons/';

        /**
         * Below condition only loads the require class if Q2A set
         * the Snow theme as site theme.
         * If you change the theme name from `Snow` to anything, make sure to
         * change in below condition
         */
        if (qa_opt('site_theme') === 'SnowFlat') {
            require_once('inc/qam-snow-theme.php');
        }
    }

    /**
     * Adding aditional meta for responsive design
     *
     * @since Snow 1.4
     * @global string $qam_snow
     */
    public function head_metas()
    {
        $this->output('<meta name="viewport" content="width=device-width, initial-scale=1"/>');
        qa_html_theme_base::head_metas();
    }

    /**
     * Adding theme stylesheets
     *
     * @since Snow 1.4
     */
    public function head_css()
    {
        // add RTL CSS file
        if ($this->isRTL) {
            $this->content['css_src'][] = $this->rooturl . 'qa-styles-rtl.css';
        }

        // add Ubuntu font CSS file
        $this->content['css_src'][] = 'https://fonts.googleapis.com/css?family=Ubuntu:400,700,400italic,700italic';

        qa_html_theme_base::head_css();

        // output some dynamic CSS inline
        $this->head_inline_css();
    }

    /**
     * Dynamic <code>CSS</code> based on options and other interaction with Q2A.
     *
     * @since Snow 1.4
     * @version 1.0
     * @return string The CSS code
     */
    private function head_inline_css()
    {
        $css = ['<style>'];

        if (!qa_is_logged_in()) {
            $css[] = '.qa-nav-user { margin: 0 !important; }';
        }

        if (qa_request_part(1) !== qa_get_logged_in_handle()) {
            $css[] = '@media (max-width: 1179px) {';
            $css[] = ' body.qa-template-user.fixed, body[class*="qa-template-user-"].fixed { padding-top: 118px !important; }';
            $css[] = ' body.qa-template-users.fixed { padding-top: 95px !important; }';

            // hotfix/sidebar-bug: Issue#14
            $css[] = '.qa-sidepanel { display:none; }';

            $css[] = '}';
            $css[] = '@media (min-width: 1180px) {';
            $css[] = ' body.qa-template-users.fixed { padding-top: 105px !important;}';
            $css[] = '}';
        }

        // sidebar styles for desktop (must use server-side UA detection, not media queries)
        if (!qa_is_mobile_probably()) {
            $css[] = '.qa-sidepanel { width: 25%; padding: 0px; float: right; overflow: hidden; *zoom: 1; }';
        }

        $css[] = '</style>';

        $this->output_array($css);
    }

    /**
     * Adding theme javascripts
     *
     * @since Snow 1.4
     */
    public function head_script()
    {
        $pathPrefix = $this->rooturl . $this->js_dir;
        $pathSuffix = '?v=' . QA_RESOURCE_VERSION;
        $this->content['script'][] = '<script src="' . $pathPrefix . 'snow-core.js' . $pathSuffix . '"></script>';
        $this->content['script'][] = '<script src="' . $pathPrefix . 'selectLanguage.js' . $pathSuffix . '"></script>';

        qa_html_theme_base::head_script();
    }

    /**
     * Adding point count for logged in user
     *
     * @since Snow 1.4
     * @global array $qam_snow
     */
    public function logged_in()
    {
        global $qam_snow;
        qa_html_theme_base::logged_in();

        $this->output($qam_snow->headers['user_points']);
    }

    /**
     * Adding sidebar for mobile device
     *
     * @since Snow 1.4
     */
    public function body()
    {
        if (qa_is_mobile_probably()) {

            $this->output('<div id="qam-sidepanel-toggle"><i class="icon-left-open-big"></i></div>');
            $this->output('<div id="qam-sidepanel-mobile">');
            qa_html_theme_base::sidepanel();
            $this->output('</div>');
        }
        qa_html_theme_base::body();
    }

    /**
     * Adding body class dynamically
     *
     * override to add class on admin/approve-users page
     *
     * @since Snow 1.4
     * @return string body class
     */
    public function body_tags()
    {
        global $qam_snow;

        $class = 'qa-template-' . qa_html($this->template);

        if (isset($this->content['categoryids'])) {
            foreach ($this->content['categoryids'] as $categoryid) {
                $class .= ' qa-category-' . qa_html($categoryid);
            }
        }

        // add class if admin/appovoe-users page
        if (($this->template === 'admin') && (qa_request_part(1) === 'approve')) {
            $class .= ' qam-approve-users';
        }

        if (isset($qam_snow->fixed_topbar)) {
            $class .= ' qam-body-' . $qam_snow->fixed_topbar;
        }

        $this->output('class="' . $class . ' qa-body-js-off"');
    }

    /**
     * The method has been overridden to remove the '-' from the note for the category page (notes).
     *
     * @since Snow 1.4
     * @param string $navlink
     * @param string $class
     */
    public function nav_link($navlink, $class)
    {
        if (isset($navlink['note']) && !empty($navlink['note'])) {
            $search = [' - <', '> - '];
            $replace = [' <', '> '];
            $navlink['note'] = str_replace($search, $replace, $navlink['note']);
        }

        parent::nav_link($navlink, $class);
    }

    /**
     * Rearranges the layout:
     * - Swaps the <tt>main()</tt> and <tt>sidepanel()</tt> functions
     * - Moves the header and footer functions outside qa-body-wrapper
     * - Keeps top/high and low/bottom widgets separated
     *
     * @since Snow 1.4
     */
    public function body_content()
    {
        $this->body_prefix();
        $this->notices();

        $this->widgets('full', 'top');
        $this->header();

        $request = explode('/', qa_request());
        $pages = [
            '',
            'polls',
            'updates',
            'unanswered',
            'messages',
            'message'
        ];
        if (in_array($request[0], $pages)
        || ($request[0] === 'users' && qa_get_logged_in_level() >= QA_USER_LEVEL_MODERATOR)
        || ($request[0] === 'user' && qa_get_logged_in_handle() !== $request[1])
        || ($request[0] === 'questions' && !isset($request[1]))) {
            $this->output('<div class="qa-body-wrapper" style="margin: -3px auto !important;">', '');
        } else {
            $this->output('<div class="qa-body-wrapper">', '');
        }


        $this->output('<div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.4&appId=1423095607985502";  fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script>');
        $this->widgets('full', 'high');

        $this->output('<div class="qa-main-wrapper">', '');
        $this->main();
        $this->sidepanel();
        $this->output('</div> <!-- END main-wrapper -->');

        $this->widgets('full', 'low');
        $this->output('</div> <!-- END body-wrapper -->');

        $this->footer();

        $this->body_suffix();
    }

    /**
     * Header in full width top bar
     *
     * @since Snow 1.4
     */
    public function header()
    {
        global $qam_snow;

        $class = isset($qam_snow->fixed_topbar) ? ' ' . $qam_snow->fixed_topbar : '';
        $this->output('<div id="qam-topbar" class="clearfix' . $class . '">');

        $this->nav_main_sub();
        $this->output('</div><!-- END qam-topbar -->');

        $this->output($qam_snow->headers['ask_button']);
        $this->qam_search('the-top', 'the-top-search');
    }

    /**
     * modifying markup for topbar
     *
     * @since Snow 1.4
     */
    public function nav_main_sub()
    {
        $this->output('<div class="qam-main-nav-wrapper clearfix">');
        $this->output('<div class="sb-toggle-left qam-menu-toggle"><i class="icon-th-list"></i></div>');
        $this->nav_user_search();
        $this->output('<div class="qa-header-menu">');
        $this->logo();
        $this->nav('main');
        $this->output('</div></div> <!-- END qam-main-nav-wrapper -->');
        $this->nav('sub');


        $adres = qa_request();
        if ($adres === '') {
            $this->output(<<<'TAG'
<div class="qa-nav-sub">
					<ul class="qa-nav-sub-list">
						<li class="qa-nav-sub-item qa-nav-sub-recent">
							<a href="./questions" class="qa-nav-sub-link qa-nav-sub-selected">Najnowsze</a>
						</li>
						<li class="qa-nav-sub-item qa-nav-sub-hot">
							<a href="./questions?sort=hot" class="qa-nav-sub-link">Gorące</a>
						</li>
						<li class="qa-nav-sub-item qa-nav-sub-votes">
							<a href="./questions?sort=votes" class="qa-nav-sub-link">Najwięcej głosów</a>
						</li>
						<li class="qa-nav-sub-item qa-nav-sub-answers">
							<a href="./questions?sort=answers" class="qa-nav-sub-link">Najwięcej odpowiedzi</a>
						</li>
						<li class="qa-nav-sub-item qa-nav-sub-views">
							<a href="./questions?sort=views" class="qa-nav-sub-link">Najwięcej odwiedzin</a>
						</li>
						<li class="qa-nav-sub-item qa-nav-sub-polls">
							<a href="./faq" class="qa-nav-sub-link">Pierwszy raz tutaj?</a>
						</li>
					</ul>
					<div class="qa-nav-sub-clear">
					</div>
				</div>
TAG
            );
        }

    }

    /**
     * login form
     *
     * @since Snow 1.4
     * @global array $qam_snow
     */
    public function nav_user_search()
    {
        // outputs login form if user not logged in
        global $qam_snow;

        $this->output('<div class="qam-account-items-wrapper">');

        $this->qam_user_account();

        $this->output('<div class="qam-account-items clearfix">');

        if (!qa_is_logged_in()) {
            if (isset($this->content['navigation']['user']['login']) && !QA_FINAL_EXTERNAL_USERS) {
                $login = $this->content['navigation']['user']['login'];
                $this->output(
                    '<!--[Begin: login form]-->',
                    '<form id="qa-loginform" action="' . $login['url'] . '" method="post">',
                    '<input type="text" id="qa-userid" name="emailhandle" placeholder="' . trim(qa_lang_html('users/email_handle_label'),
                        ':') . '" />',
                    '<input type="password" id="qa-password" name="password" placeholder="' . trim(qa_lang_html('users/password_label'),
                        ':') . '" />',
                    '<div id="qa-rememberbox"><input type="checkbox" name="remember" id="qa-rememberme" value="1" />',
                    '<label for="qa-rememberme" id="qa-remember">' . qa_lang_html('users/remember') . '</label></div>',
                    '<input type="hidden" name="code" value="' . qa_html(qa_get_form_security_code('login')) . '" />',
                    '<input type="submit" value="' . $login['label'] . '" id="qa-login" name="dologin" />',
                    '</form>',
                    '<!--[End: login form]-->'
                );

                // removes regular navigation link to log in page
                unset($this->content['navigation']['user']['login']);
            }
        }

        $this->nav('user');
        $this->output('</div> <!-- END qam-account-items -->');
        $this->output('</div> <!-- END qam-account-items-wrapper -->');
    }

    /**
     * User account navigation item. This will return based on login information.
     * If user is logged in, it will populate user avatar and account links.
     * If user is guest, it will populate login form and registration link.
     *
     * @since Snow 1.4
     */
    private function qam_user_account()
    {
        $avatarsize = 54;

        // get logged-in user avatar
        if (qa_is_logged_in()) {
            $handle = qa_get_logged_in_user_field('handle');
            $toggleClass = 'qam-logged-in';

            if (QA_FINAL_EXTERNAL_USERS) {
                $tobar_avatar = qa_get_external_avatar_html(qa_get_logged_in_user_field('userid'), $avatarsize, true);
            } else {
                $tobar_avatar = qa_get_user_avatar_html(
                    qa_get_logged_in_user_field('flags'),
                    qa_get_logged_in_user_field('email'),
                    $handle,
                    qa_get_logged_in_user_field('avatarblobid'),
                    qa_get_logged_in_user_field('avatarwidth'),
                    qa_get_logged_in_user_field('avatarheight'),
                    $avatarsize,
                    false
                );
            }
            // display login icon and label
            $auth_icon = strip_tags($tobar_avatar, '<img>');
        }
        else {
            $handle = $this->content['navigation']['user']['login']['label'];
            $toggleClass = 'qam-logged-out';
            $auth_icon = '<i class="icon-key qam-auth-key"></i>';
        }

        // finally output avatar with div tag
        $this->output(
            '<div id="qam-account-toggle" class="' . $toggleClass . '">',
            $auth_icon,
            '<div class="qam-account-handle">' . qa_html($handle) . '</div>',
            '</div>'
        );
    }

    /**
     * To add search-box wrapper with extra class for color scheme
     *
     * @since Snow 1.4
     * @version 1.0
     */
    private function qam_search($addon_class = false, $ids = false)
    {
        $default_color = 'turquoise';

        $id = (($ids) ? ' id="' . $ids . '"' : null);

        $this->output('<div class="qam-search ' . $default_color . ' ' . $addon_class . '" ' . $id . ' >');
        $this->search();
        $this->output('</div>');
    }

    /**
     * Overridden to customize layout and styling
     *
     * @since Snow 1.4
     */
    public function sidepanel()
    {
        // removes sidebar for user profile pages
        if (($this->template !== 'user') && !qa_is_mobile_probably()) {
            $this->output('<div class="qa-sidepanel">');
            $this->qam_search();
            $this->widgets('side', 'top');
            $this->sidebar();
            $this->widgets('side', 'high');
            $this->nav('cat', 1);
            $this->widgets('side', 'low');
            if (isset($this->content['sidepanel'])) {
                $this->output_raw($this->content['sidepanel']);
            }
            $this->feed();
            $this->widgets('side', 'bottom');
            $this->output('</div>', '');
        }
    }

    /**
     * To provide various color option
     *
     * @since Snow 1.4
     * @global array $qam_snow
     */
    public function sidebar()
    {
        global $qam_snow;
        $sidebar = '';
        $adres = explode('/', qa_path_absolute(qa_request()));

        $this->output('<div class="qa-sidebar wet-asphalt ' . $qam_snow->welcome_widget_color . '">');

        $sidebar .= $this->setMainCategoryHeader();
        $categories = $this->getMainCategories();
        $sidebar = $this->prepareSidebar($categories, $adres, $sidebar);

        $sidebar .= '<div style="height:3px;"></div><div style="width:100%; text-align: center; font-size:1em; padding:5px; border-bottom: 1px solid white;">Programowanie</div>';

        $sidebar = $sidebar . '<div style="padding:10px; text-align:justify; line-height: 115%;">' .
            '<a href="/questions/programowanie/c-plus-plus" class="sidebarnav">C i C++</a> ' .
            '<a href="/questions/programowanie/html-css" class="sidebarnav">HTML i CSS</a> ' .
            '<a href="/questions/programowanie/javascript" class="sidebarnav">JavaScript</a> ' .
            '<a href="/questions/programowanie/php" class="sidebarnav">PHP</a> ' .
            '<a href="/questions/programowanie/sql-bazy-danych" class="sidebarnav">SQL, bazy danych</a> ' .
            '<a href="/questions/programowanie/c-sharp" class="sidebarnav">C#</a> ' .
            '<a href="/questions/programowanie/java" class="sidebarnav">Java</a> ' .
            '<a href="/questions/programowanie/spoj" class="sidebarnav">SPOJ</a> ' .
            '<a href="/questions/programowanie/python" class="sidebarnav">Python</a> ' .
            '<a href="/questions/programowanie/ruby-i-ruby-on-rails" class="sidebarnav">Ruby</a> ' .
            '<a href="/questions/programowanie/assembler" class="sidebarnav">Assembler</a> ' .
            '<a href="/questions/programowanie/visual-basic" class="sidebarnav">Visual Basic</a> ' .
            '<a href="/questions/programowanie/android-ios-swift-symbian" class="sidebarnav">Android, Swift, Symbian</a> ' .
            '<a href="/questions/programowanie/opengl-unity" class="sidebarnav">OpenGL, Unity</a> ' .
            '<a href="/questions/programowanie/inne-jezyki" class="sidebarnav">Inne języki</a> ' .
            '<a href="/questions/programowanie/algorytmy" class="sidebarnav">Algorytmy</a> ' .
            '<a href="/questions/programowanie/systemy-cms" class="sidebarnav">Systemy CMS</a> ' .
            '<a href="/questions/programowanie/mikrokontrolery" class="sidebarnav">Mikrokontrolery</a>';

        $sidebar .= '</div>' . $this->output_raw($sidebar);
        $this->output('</div></div>', '');
    }

    /**
     * Footer in full width bottom bar
     *
     * @since Snow 1.4
     */
    public function footer()
    {
        // to replace standard Q2A footer
        global $qam_snow;

        $this->output($qam_snow->footer_custom_content);
        $this->output('<div class="qam-footer-box">');

        $this->output('<div class="qam-footer-row"><div class="qam-footer-col"><h3 class="qam-footer-col-heading">O działaniu forum</h3><ul style="list-style-type:circle; font-size:15px;"><li><a href="/faq"> Jak korzystać z forum? Częste pytania</a></li><li><a href="/manifest-pasjonata">Po co nam forum? Manifest</a></li><li><a href="/zasluzeni-pasjonaci-hall-of-fame">Zasłużeni Pasjonaci - Ekipa ninja</a></li><li><a href="/zasady-przyznawania-punktow">Zasady przyznawania punktów</a></li><li><a href="/ksiazki-informatyczne-warte-uwagi">Lista książek wartych uwagi</a></li><li><a href="https://github.com/CodersCommunity/" target="_blank">GitHub CodersCommunity</a></li><li><a href="/chat-discord">Nasz forumowy Discord</a></li><li><a href="/regulamin-forum">Regulamin tego miejsca</a></li><li><a href="/polityka-prywatnosci">Polityka prywatności</a></li></ul></div><div class="qam-footer-col"><h3 class="qam-footer-col-heading">Pasja informatyki w internecie</h3><ul style="list-style-type:circle; font-size:15px;"><li><a href="https://www.youtube.com/pasjainformatykitutoriale" target="_blank">Kanał YouTube</a></li><li><a href="https://www.facebook.com/pasjainformatykifanpage" target="_blank">Fanpage Facebook</a></li><li><a href="https://pasja-informatyki.pl" target="_blank">Pasja informatyki</a></li><li><a href="https://miroslawzelent.pl" target="_blank">Blog informatyczny</a></li><li><a href="https://egzamin-informatyk.pl" target="_blank">Egzamin technik informatyk</a></li><li><a href="https://egzamin-programista.pl" target="_blank">Egzamin technik programista</a></li><li><a href="https://patronite.pl/pasja-informatyki" target="_blank">Profil Patronite</a></li><li><a href="https://buycoffee.to/pasja-informatyki" target="_blank">Wsparcie twórczości</a></li><li><a href="https://podcast.pasja-informatyki.pl" target="_blank">Witryna podcastowa</a></li></ul></div><div class="qam-footer-col"><h3 class="qam-footer-col-heading">Polecane miejsca w sieci</h3><ul style="list-style-type:circle; font-size:15px;"><li><a href="https://www.phpdevs.pl/" target="_blank">Kurs PHPDevs.pl</a></li><li><a href="https://blog.comandeer.pl/" target="_blank">Blog comandeer.pl</a></li><li><a href="https://gynvael.coldwind.pl" target="_blank">Blog gynvael.coldwind.pl</a></li><li><a href="https://pagedout.institute/" target="_blank">Community zin: Paged Out</a></li><li><a href="https://sekurak.pl/" target="_blank">Blog ITsec: sekurak.pl</a></li></ul></div></div> <!-- END qam-footer-row -->');
        $this->widgets('full', 'bottom');
        $this->output('</div> <!-- END qam-footer-row -->');

        qa_html_theme_base::footer();
        $this->output('</div> <!-- END qam-footer-box -->', '');
    }

    /**
     * To add close icon
     *
     * @since Snow 1.4
     * @param array $q_item
     */
    public function q_item_title($q_item)
    {
        $this->output(
            '<div class="qa-q-item-title">',
            // add closed note in title
            empty($q_item['closed']) ? '' : '<img src="/qa-theme/SnowFlat/images/icons/closed-q-list.png" class="qam-q-list-close-icon" alt="question-closed" title="' . qa_lang('main/closed') . '" />',
            '<a href="' . $q_item['url'] . '">' . $q_item['title'] . '</a>', '</div>'
        );
    }

    /**
     * To add RSS feeds icon and closed icon for closed questions
     *
     * @since Snow 1.4
     */
    public function title()
    {
        $q_view = isset($this->content['q_view']) ? $this->content['q_view'] : null;

        // RSS feed link in title
        if (isset($this->content['feed']['url'])) {
            $feed = $this->content['feed'];
            $label = isset($feed['label']) ? $feed['label'] : '';
            $this->output('<a href="' . $feed['url'] . '" title="' . $label . '"><i class="icon-rss qam-title-rss"></i></a>');
        }

        // link title where appropriate
        $url = isset($q_view['url']) ? $q_view['url'] : false;

        // add closed image
        $closed = (!empty($q_view['closed']) ?
            '<img src="' . $this->rooturl . $this->icon_url . '/closed-q-view.png" class="qam-q-view-close-icon" alt="question-closed" width="24" height="24" title="' . qa_lang('main/closed') . '" />' : null);

        if (isset($this->content['title'])) {
            $this->output(
                $closed, $url ? '<a href="' . $url . '">' : '', $this->content['title'], $url ? '</a>' : ''
            );
        }
    }

    /**
     * To add view counter
     *
     * @since Snow 1.4
     * @param array $q_item
     */
    public function q_item_stats($q_item)
    { // add view count to question list
        $this->output('<div class="qa-q-item-stats">');

        $this->voting($q_item);
        $this->a_count($q_item);
        qa_html_theme_base::view_count($q_item);

        $this->output('</div>');
    }

    /**
     * Prevent display view counter on usual place
     *
     * @since Snow 1.4
     * @param string $q_item
     */
    public function view_count($q_item)
    { // Prevent display view counter on usual place
    }

    /**
     * To add view counter
     *
     * @since Snow 1.4
     * @param string $q_view
     */
    public function q_view_stats($q_view)
    {
        $this->output('<div class="qa-q-view-stats">');

        $this->voting($q_view);
        $this->a_count($q_view);
        qa_html_theme_base::view_count($q_view);

        $this->output('</div>');
    }

    /**
     * To modify user whometa, move to top
     *
     * @since Snow 1.4
     * @param string $q_view
     */
    public function q_view_main($q_view)
    {
        $this->output('<div class="qa-q-view-main">');

        if (isset($q_view['main_form_tags'])) {
            $this->output('<form ' . $q_view['main_form_tags'] . '>');
        } // form for buttons on question

        $this->post_avatar_meta($q_view, 'qa-q-view');
        $this->q_view_content($q_view);
        $this->q_view_extra($q_view);
        $this->q_view_follows($q_view);
        $this->q_view_closed($q_view);
        $this->post_tags($q_view, 'qa-q-view');

        $this->q_view_buttons($q_view);
        $this->c_list(isset($q_view['c_list']) ? $q_view['c_list'] : null, 'qa-q-view');

        if (isset($q_view['main_form_tags'])) {
            if (isset($q_view['buttons_form_hidden'])) {
                $this->form_hidden_elements($q_view['buttons_form_hidden']);
            }
            $this->output('</form>');
        }

        $this->c_form(isset($q_view['c_form']) ? $q_view['c_form'] : null);

        $this->output('</div> <!-- END qa-q-view-main -->');
    }

    /**
     * To move user whometa to top in answer
     *
     * @since Snow 1.4
     * @param string $a_item
     */
    public function a_item_main($a_item)
    {
        $this->output('<div class="qa-a-item-main">');

        $this->post_avatar_meta($a_item, 'qa-a-item');

        if (isset($a_item['main_form_tags'])) {
            $this->output('<form ' . $a_item['main_form_tags'] . '>');
        } // form for buttons on answer

        if ($a_item['hidden']) {
            $this->output('<div class="qa-a-item-hidden">');
        } elseif ($a_item['selected']) {
            $this->output('<div class="qa-a-item-selected">');
        }

        $this->a_selection($a_item);
        if (isset($a_item['error'])) {
            $this->error($a_item['error']);
        }
        $this->a_item_content($a_item);

        if ($a_item['hidden'] || $a_item['selected']) {
            $this->output('</div>');
        }

        $this->a_item_buttons($a_item);

        if (isset($a_item['c_list'])) {
            $this->c_list($a_item['c_list'], 'qa-a-item');
        }

        if (isset($a_item['main_form_tags'])) {
            if (isset($a_item['buttons_form_hidden'])) {
                $this->form_hidden_elements($a_item['buttons_form_hidden']);
            }
            $this->output('</form>');
        }

        $this->c_form(isset($a_item['c_form']) ? $a_item['c_form'] : null);

        $this->output('</div> <!-- END qa-a-item-main -->');
    }

    /**
     * To move user whometa to top in comment
     *
     * @since Snow 1.4
     * @param string $c_item
     */
    public function c_item_main($c_item)
    {
        $this->post_avatar_meta($c_item, 'qa-c-item');

        if (isset($c_item['error'])) {
            $this->error($c_item['error']);
        }

        if (isset($c_item['expand_tags'])) {
            $this->c_item_expand($c_item);
        } elseif (isset($c_item['url'])) {
            $this->c_item_link($c_item);
        } else {
            $this->c_item_content($c_item);
        }

        $this->output('<div class="qa-c-item-footer">');
        $this->c_item_buttons($c_item);
        $this->output('</div>');
    }

    /**
     * Q2A Market attribution.
     * I'd really appreciate you displaying this link on your Q2A site. Thank you - Jatin
     *
     * @since Snow 1.4
     * @global array $qam_snow
     */
    public function attribution()
    {
        // floated right
        $this->output(
            '<div class="qa-attribution">',
            'Snow Theme by <a href="http://www.q2amarket.com">Q2A Market</a>',
            '</div>'
        );

        qa_html_theme_base::attribution();
    }

    /**
     * Question2Answer system icons info bar
     *
     * @since Snow 1.4
     * @return string Info icons HTML
     */
    private function icons_info()
    {
        $icons = [
            'answer', 'comment', 'hide', 'reshow', 'close', 'reopen', 'flag', 'unflag', 'edit', 'delete', 'approve', 'reject', 'reply'
        ];

        $icons_info = '<div class="qam-icons-info">';

        foreach ($icons as $icon) {
            $label = ucwords(qa_lang_html('question/' . $icon . '_button'));
            $icons_info .= '<div class="qam-icon-item"><span class="' . $icon . '"></span> ' . $label . '</div>';
        }
        $icons_info .= '</div> <!-- END qam-icons-info -->';

        return $icons_info;
    }

    /**
     * Metoda pozwalająca na dodawanie kolejnych kategorii, w sposób niezadowalający, aczkolwiek działający.
     * @return array
     */
    private function getMainCategories()
    {
        return [
            'programowanie' => ['tooltip' => 'Programowanie', 'icon' => 'code'],
            'sprzet-komputerowy' => ['tooltip' => 'Sprzęt komputerowy', 'icon' => 'net'],
            'systemy-operacyjne-programy' => ['tooltip' => 'Systemy operacyjne, programy', 'icon' => 'os'],
            'sieci-komputerowe-internet' => ['tooltip' => 'Sieci komputerowe', 'icon' => 'eth'],
            'hostingi-domeny-uslugi' => ['tooltip' => 'Hostingi, domeny, usługi', 'icon' => 'server'],
            'urzadzenia-mobilne' => ['tooltip' => 'Urządzenia mobilne', 'icon' => 'ph'],
            'bezpieczenstwo-hacking' => ['tooltip' => 'Bezpieczeństwo, hacking', 'icon' => 'key'],
            'rozwoj-zawodowy' => ['tooltip' => 'Rozwój zawodowy, nauka, praca', 'icon' => 'stu'],
            'egzaminy-zawodowe' => ['tooltip' => 'Egzaminy zawodowe', 'icon' => 'egz'],
            'matematyka-fizyka-logika' => ['tooltip' => 'Matematyka, fizyka, logika', 'icon' => 'ruler'],
            'grafika-i-multimedia' => ['tooltip' => 'Grafika i multimedia', 'icon' => 'brush'],
            'ogloszenia-zlecenia' => ['tooltip' => 'Ogłoszenia, zlecenia', 'icon' => 'note'],
            'nasze-projekty' => ['tooltip' => 'Nasze projekty', 'icon' => 'new'],
            'nasze-poradniki' => ['tooltip' => 'Nasze poradniki', 'icon' => 'tut'],
            'sprawy-forum' => ['tooltip' => 'Sprawy forum', 'icon' => 'for'],
            'offtop' => ['tooltip' => 'Offtop', 'icon' => 'off']
        ];
    }

    /**
     * @return string
     */
    private function setMainCategoryHeader()
    {
        return '<div style="width:100%; text-align: center; font-size:1em; padding:5px; border-bottom: 1px solid white; margin-bottom: 10px;">Kategorie pytań</div>';
    }

    /**
     * @param $adres
     * @param $category
     * @return array
     */
    private function isActiveCategory($adres, $category)
    {
        if(isset($adres[4]) && $adres[4] === $category) {
            $result = [
                'selected' => 'qa-nav-main-selected',
                'hover' => '1'
            ];
        } else {
            $result = [
                'selected' => '',
                'hover' => '0'
            ];
        }

        return $result;
    }

    /**
     * @param array $categories
     * @param $adres
     * @param $sidebar
     * @return string
     */
    private function prepareSidebar($categories, $adres, $sidebar)
    {
        $currentPosition = 0;
        foreach ($categories as $category => $value) {
            $isActiveCategory = $this->isActiveCategory($adres, $category);
            $currentPosition++;
            $tooltipPosition = (!in_array($currentPosition, [3, 4, 7, 8, 11, 12, 15, 16]) ? '' : 'tooltip-right-position');

            $sidebar .= <<<EOF
<div class="qa-nav-main-item">
    <a href="/questions/{$category}" class="qa-nav-main-link {$isActiveCategory['selected']} {$currentPosition}">
        <dfn class="{$tooltipPosition}" data-info="{$value['tooltip']}">
            <img src="/qa-theme/SnowFlat/icons/{$value['icon']}{$isActiveCategory['hover']}.png" alt="{$value['tooltip']}" onmouseover="this.src='/qa-theme/SnowFlat/icons/{$value['icon']}1.png'" onmouseout="this.src='/qa-theme/SnowFlat/icons/{$value['icon']}0.png'" />
        </dfn>
    </a>
</div>
EOF;
        }
        return $sidebar;
    }
}
