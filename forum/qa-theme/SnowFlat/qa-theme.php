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
	/**
	 * @since Snow 1.4
	 * @param type $template
	 * @param type $content
	 * @param type $rooturl
	 * @param type $request
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
	 * @global type $qam_snow
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
		if ($this->isRTL)
			$this->content['css_src'][] = $this->rooturl . 'qa-styles-rtl.css?' . QA_VERSION;

		// add Ubuntu font CSS file
		$this->content['css_src'][] = 'http://fonts.googleapis.com/css?family=Ubuntu:400,700,400italic,700italic';

		qa_html_theme_base::head_css();

		// output some dynamic CSS inline
		$this->head_inline_css();
	}

	/**
	 * Adding theme javascripts
	 *
	 * @since Snow 1.4
	 */
	public function head_script()
	{
		$jsUrl = $this->rooturl . $this->js_dir . 'snow-core.js?' . QA_VERSION;
		$this->content['script'][] = '<script src="' . $jsUrl . '"></script>';

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
			foreach ($this->content['categoryids'] as $categoryid)
			{
				$class .= ' qa-category-' . qa_html($categoryid);
			}
		}

		// add class if admin/appovoe-users page
		if (($this->template === 'admin') && (qa_request_part(1) === 'approve')) {
			$class .= ' qam-approve-users';
		}

		if (isset($qam_snow->fixed_topbar))
			$class .= ' qam-body-' . $qam_snow->fixed_topbar;

		$this->output('class="' . $class . ' qa-body-js-off"');
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
						'<input type="text" id="qa-userid" name="emailhandle" placeholder="' . trim(qa_lang_html('users/email_handle_label'), ':') . '" />',
						'<input type="password" id="qa-password" name="password" placeholder="' . trim(qa_lang_html('users/password_label'), ':') . '" />',
						'<div id="qa-rememberbox"><input type="checkbox" name="remember" id="qa-rememberme" value="1" />',
						'<label for="qa-rememberme" id="qa-remember">' . qa_lang_html('users/remember') . '</label></div>',
						'<input type="hidden" name="code" value="' . qa_html(qa_get_form_security_code('login')) . '" />',
						'<input type="submit" value="' . $login['label'] . '" id="qa-login" name="dologin" />',
						'</form>',
						'<!--[End: login form]-->'
				);

				unset($this->content['navigation']['user']['login']); // removes regular navigation link to log in page
			}
		}

		$this->nav('user');
		$this->output('</div> <!-- END qam-account-items -->');
		$this->output('</div> <!-- END qam-account-items-wrapper -->');
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
		$this->logo();
		$this->nav('main');
		$this->output('</div> <!-- END qam-main-nav-wrapper -->');
		$this->nav('sub');
		
		
		$adres = qa_path_absolute(qa_request());
		if ($adres=="http://forum.pasja-informatyki.pl/")
		$this->output('<div class="qa-nav-sub">
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
				</div>');
	
	}

	/**
	 * The method has been overridden to remove the '-' from the note for the category page (notes).
	 *
	 * @since Snow 1.4
	 * @param type $navlink
	 * @param type $class
	 */
	public function nav_link($navlink, $class)
	{
		if (isset($navlink['note']) && !empty($navlink['note'])) {
			$search = array(' - <', '> - ');
			$replace = array(' <', '> ');
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

		$adres = qa_path_absolute(qa_request());
		if($adres=="http://forum.pasja-informatyki.pl/" || $adres=="http://forum.pasja-informatyki.pl/users" || $adres=="http://forum.pasja-informatyki.pl/polls" || $adres=="http://forum.pasja-informatyki.pl/messages" || $adres=="http://forum.pasja-informatyki.pl/messages/sent" || $adres=="http://forum.pasja-informatyki.pl/updates" || $adres=="http://forum.pasja-informatyki.pl/questions" || $adres=="http://forum.pasja-informatyki.pl/questions?sort=hot" || $adres=="http://forum.pasja-informatyki.pl/questions?sort=votes" || $adres=="http://forum.pasja-informatyki.pl/questions?sort=answers" || $adres=="http://forum.pasja-informatyki.pl/questions?sort=views" || $adres=="http://forum.pasja-informatyki.pl/questions?sort=hot" || $adres=="http://forum.pasja-informatyki.pl/users/special" || $adres=="http://forum.pasja-informatyki.pl/users/blocked" || (substr($adres, 0, 42)=="http://forum.pasja-informatyki.pl/message/")) 
		{
			$this->output('<div class="qa-body-wrapper" style="margin: -3px auto !important;">', '');	
		}			
		else $this->output('<div class="qa-body-wrapper">', '');
		
		
	
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

		$this->output('<div class="qam-footer-row"><div class="qam-footer-col"><h3 class="qam-footer-col-heading">Fanpage na Facebooku</h3><div style="margin-bottom: 10px;" class="fb-page" data-href="https://www.facebook.com/pasjainformatykifanpage" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/pasjainformatykifanpage"><a href="https://www.facebook.com/pasjainformatykifanpage">miroslawzelent.pl</a></blockquote></div></div></div><div class="qam-footer-col"><h3 class="qam-footer-col-heading">Cenne informacje</h3><a href="http://forum.miroslawzelent.pl/faq"> &rarr; Jak korzystać z Forum? Częste pytania</a><a href="http://forum.miroslawzelent.pl/ksiazki-informatyczne-warte-uwagi"> &rarr; Książki informatyczne warte uwagi</a><a href="http://forum.miroslawzelent.pl/manifest-pasjonata"> &rarr; Po co nam Forum? Manifest</a><a href="http://forum.miroslawzelent.pl/regulamin-forum"> &rarr; Regulamin tego miejsca</a><a href="http://forum.miroslawzelent.pl/zasluzeni-pasjonaci-hall-of-fame"> &rarr; Zasłużeni Pasjonaci</a><a href="http://forum.miroslawzelent.pl/zasady-przyznawania-punktow"> &rarr; Zasady przyznawania punktów</a></div><div class="qam-footer-col"><h3 class="qam-footer-col-heading">Polecane miejsca w sieci</h3><ul class="qam-footer-links qam-social-links"><li><a href="http://egzamin-informatyk.pl/e12-e13-e14-testy-online-egzamin-zawodowy" target="_blank" class="ei"><i class="icon-graduation-cap-1"></i></a></li><li><a href="http://miroslawzelent.pl/" target="_blank" class="linkedin"><i class="icon-medium"></i></a></li><li><a href="https://www.youtube.com/channel/UCzn6vAfspIcagLax1fck_jw" target="_blank" class="gplus"><i class="icon-youtube"></i></a></li><li><a href="https://www.youtube.com/channel/UC2CwpGFeP-8pkswU5q24WXg" target="_blank" class="gplus"><i class="icon-youtube"></i></a></li><li><a href="http://egzamin-e13.pl/" target="_blank" class="wordpress"><i class="icon-signal"></i></a></li><li><a href="http://lenovotechzone.pl/" target="_blank" class="youtube"><i class="icon-users-2"></i></a></li></ul></div></div> <!-- END qam-footer-row -->');
		$this->widgets('full', 'bottom');
		$this->output('</div> <!-- END qam-footer-row -->');

		qa_html_theme_base::footer();
		$this->output('</div> <!-- END qam-footer-box -->', '');
	}

	/**
	 * Overridden to customize layout and styling
	 *
	 * @since Snow 1.4
	 */
	public function sidepanel()
	{
		// removes sidebar for user profile pages
		if (($this->template != 'user') && !qa_is_mobile_probably()) {
			$this->output('<div class="qa-sidepanel">');
			$this->qam_search();
			$this->widgets('side', 'top');
			$this->sidebar();
			$this->widgets('side', 'high');
			$this->nav('cat', 1);
			$this->widgets('side', 'low');
			if (isset($this->content['sidepanel']))
				$this->output_raw($this->content['sidepanel']);
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

		if (true) {
			$sidebar = "";
			
			$this->output('<div class="qa-sidebar wet-asphalt ' . $qam_snow->welcome_widget_color . '">');
				
			$sidebar = $sidebar.'<div style="width:100%; text-align: center; font-size:1em; padding:5px; border-bottom: 1px solid white; margin-bottom: 10px;">Główne kategorie pytań</div>';
			
			$kategoria[1]="programowanie"; $tooltip[1]="Programowanie"; $ikona[1]="code";
			$kategoria[2]="sprzet-komputerowy"; $tooltip[2]="Sprzęt komputerowy"; $ikona[2]="net";
			$kategoria[3]="systemy-operacyjne-programy"; $tooltip[3]="Systemy operacyjne, programy"; $ikona[3]="os";
			$kategoria[4]="sieci-komputerowe-internet"; $tooltip[4]="Sieci komputerowe"; $ikona[4]="eth";
			$kategoria[5]="telefony-tablety"; $tooltip[5]="Telefony i tablety"; $ikona[5]="ph";
			$kategoria[6]="bezpieczenstwo-hacking"; $tooltip[6]="Bezpieczeństwo, hacking"; $ikona[6]="key";
			$kategoria[7]="rozwoj-zawodowy"; $tooltip[7]="Rozwój zawodowy, nauka, praca"; $ikona[7]="stu";
			$kategoria[8]="egzaminy-e12-e13-e14"; $tooltip[8]="Egzaminy zawodowe E12, E13, E14"; $ikona[8]="egz";
			$kategoria[9]="nasze-projekty"; $tooltip[9]="Nasze projekty"; $ikona[9]="new";
			$kategoria[10]="nasze-poradniki"; $tooltip[10]="Nasze poradniki"; $ikona[10]="tut";
			$kategoria[11]="sprawy-forum"; $tooltip[11]="Sprawy forum"; $ikona[11]="for";
			$kategoria[12]="dyskusje"; $tooltip[12]="Dyskusje"; $ikona[12]="tea";
			
			$kategoria[13]="dyskusje/gry-pc-konsole"; $tooltip[13]="Gry PC i konsole"; $ikona[13]="game";
			$kategoria[14]="dyskusje/muzyka"; $tooltip[14]="Muzyka"; $ikona[14]="music";
			$kategoria[15]="dyskusje/filmy-i-seriale"; $tooltip[15]="Filmy i seriale"; $ikona[15]="mov";
			$kategoria[16]="dyskusje/sport-i-zdrowie"; $tooltip[16]="Sport i zdrowie"; $ikona[16]="spo";
			$kategoria[17]="dyskusje/ksiazki"; $tooltip[17]="Książki"; $ikona[17]="book";
			$kategoria[18]="dyskusje/grafika-i-fotografia"; $tooltip[18]="Grafika, fotografia"; $ikona[18]="foto";
			$kategoria[19]="dyskusje/heheszki"; $tooltip[19]="Heheszki"; $ikona[19]="hehe";
			$kategoria[20]="dyskusje/off-topic"; $tooltip[20]="Offtop"; $ikona[20]="off";
			
			$ktory_dfn = "";
			$licznik_dfn = 0;
			
			$adres = qa_path_absolute(qa_request());
			
			for($i=1; $i<=20; $i++)
			{
				if($adres=="http://forum.pasja-informatyki.pl/questions/".$kategoria[$i])
				{
					$zazn = " qa-nav-main-selected"; $kolor="1";
				}
				else 
				{
					$zazn = ""; $kolor = "0"; 
				}

				$licznik_dfn++; 
				if ($licznik_dfn>2) $ktory_dfn="a"; else $ktory_dfn="";
				if ($licznik_dfn>=4) $licznik_dfn=0;
				
				$sidebar = $sidebar.'<div class="qa-nav-main-item"><a href="http://forum.pasja-informatyki.pl/questions/'.$kategoria[$i].'" class="qa-nav-main-link'.$zazn.'"><dfn'.$ktory_dfn.' data-info="'.$tooltip[$i].'"><img src="//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/'.$ikona[$i].$kolor.'.png"';

				if ($kolor=="0")
				{
					$sidebar = $sidebar.'alt="'.$tooltip[$i].'" onmouseover="this.src = \'//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/'.$ikona[$i].'1.png\'" onmouseout="this.src = \'//forum.pasja-informatyki.pl/qa-theme/SnowFlat/icons/'.$ikona[$i].'0.png\'" /></a></dfn'.$ktory_dfn.'></div>';
				}
				else
				{
					$sidebar = $sidebar.'alt="'.$tooltip[$i].'"/></a></dfn'.$ktory_dfn.'></div>';
				}
				
				if ($i==12) $sidebar = $sidebar.'<div style="height:5px; clear:both;"></div><div style="width:100%; text-align: center; font-size:1em; padding:5px; border-bottom: 1px solid white; margin-bottom: 10px;">Luźniejsze dyskusje</div>';
			
			}		
			
			$sidebar = $sidebar.'<div style="height:3px;"></div><div style="width:100%; text-align: center; font-size:1em; padding:5px; border-bottom: 1px solid white;">Programowanie</div>';
			
			$sidebar = $sidebar.'<div style="padding:10px; text-align:justify; line-height: 115%;">'.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/c-plus-plus" class="sidebarnav">C i C++</a> '.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/html-css" class="sidebarnav">HTML i CSS</a> '.			
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/javascript-jquery-ajax" class="sidebarnav">JS, jQuery, AJAX</a> '.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/php-symfony-zend" class="sidebarnav">PHP, Symfony, Zend</a> '.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/mysql-bazy-danych" class="sidebarnav">SQL, bazy danych</a> '.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/hosting-domeny" class="sidebarnav">Hostingi, domeny</a> '.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/spoj" class="sidebarnav">SPOJ</a> '.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/systemy-cms" class="sidebarnav">Systemy CMS</a> '.
			
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/pascal-delphi" class="sidebarnav">Pascal, Delphi</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/c-sharp-dot-net" class="sidebarnav">C# i .NET</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/java" class="sidebarnav">Java</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/python-django" class="sidebarnav">Python, &nbsp;Django </a>'.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/assembler" class="sidebarnav">Assembler</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/ruby-i-ruby-on-rails" class="sidebarnav">Ruby, Ruby On Rails</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/visual-basic" class="sidebarnav">Visual Basic</a> '.		
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/android-ios-swift-symbian" class="sidebarnav">Android, Swift, Symbian</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/opengl-unity" class="sidebarnav">OpenGL, Unity</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/algorytmy" class="sidebarnav">Algorytmy</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/arduino" class="sidebarnav">Arduino</a> '.	
						
			'<a href="//forum.pasja-informatyki.pl/questions/programowanie/inne-jezyki" class="sidebarnav">Inne języki programowania</a> ';

			$sidebar = $sidebar.'</div>'.
		
			$this->output_raw($sidebar);
			$this->output('</div></div>', '');
			
		}
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
				empty($q_item['closed']) ? '' : '<img src="' . $this->rooturl . $this->icon_url . '/closed-q-list.png" class="qam-q-list-close-icon" alt="question-closed" title="' . qa_lang('main/closed') . '" />', '<a href="' . $q_item['url'] . '">' . $q_item['title'] . '</a>', '</div>'
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
						'<img src="' . $this->rooturl . $this->icon_url . '/closed-q-view.png" class="qam-q-view-close-icon" alt="question-closed" width="24" height="24" title="' . qa_lang('main/closed') . '" />' : null );

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
	 * @param type $q_item
	 */
	public function view_count($q_item)
	{ // Prevent display view counter on usual place
	}

	/**
	 * To add view counter
	 *
	 * @since Snow 1.4
	 * @param type $q_view
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
	 * @param type $q_view
	 */
	public function q_view_main($q_view)
	{
		$this->output('<div class="qa-q-view-main">');

		if (isset($q_view['main_form_tags']))
			$this->output('<form ' . $q_view['main_form_tags'] . '>'); // form for buttons on question

		$this->post_avatar_meta($q_view, 'qa-q-view');
		$this->q_view_content($q_view);
		$this->q_view_extra($q_view);
		$this->q_view_follows($q_view);
		$this->q_view_closed($q_view);
		$this->post_tags($q_view, 'qa-q-view');

		$this->q_view_buttons($q_view);
		$this->c_list(isset($q_view['c_list']) ? $q_view['c_list'] : null, 'qa-q-view');

		if (isset($q_view['main_form_tags'])) {
			if (isset($q_view['buttons_form_hidden']))
				$this->form_hidden_elements($q_view['buttons_form_hidden']);
			$this->output('</form>');
		}

		$this->c_form(isset($q_view['c_form']) ? $q_view['c_form'] : null);

		$this->output('</div> <!-- END qa-q-view-main -->');
	}

	/**
	 * To move user whometa to top in answer
	 *
	 * @since Snow 1.4
	 * @param type $a_item
	 */
	public function a_item_main($a_item)
	{
		$this->output('<div class="qa-a-item-main">');

		$this->post_avatar_meta($a_item, 'qa-a-item');

		if (isset($a_item['main_form_tags']))
			$this->output('<form ' . $a_item['main_form_tags'] . '>'); // form for buttons on answer

		if ($a_item['hidden'])
			$this->output('<div class="qa-a-item-hidden">');
		elseif ($a_item['selected'])
			$this->output('<div class="qa-a-item-selected">');

		$this->a_selection($a_item);
		if (isset($a_item['error']))
			$this->error($a_item['error']);
		$this->a_item_content($a_item);

		if ($a_item['hidden'] || $a_item['selected'])
			$this->output('</div>');

		$this->a_item_buttons($a_item);

		if (isset($a_item['c_list']))
			$this->c_list($a_item['c_list'], 'qa-a-item');

		if (isset($a_item['main_form_tags'])) {
			if (isset($a_item['buttons_form_hidden']))
				$this->form_hidden_elements($a_item['buttons_form_hidden']);
			$this->output('</form>');
		}

		$this->c_form(isset($a_item['c_form']) ? $a_item['c_form'] : null);

		$this->output('</div> <!-- END qa-a-item-main -->');
	}

	/**
	 * To move user whometa to top in comment
	 *
	 * @since Snow 1.4
	 * @param type $c_item
	 */
	public function c_item_main($c_item)
	{
		$this->post_avatar_meta($c_item, 'qa-c-item');

		if (isset($c_item['error']))
			$this->error($c_item['error']);

		if (isset($c_item['expand_tags']))
			$this->c_item_expand($c_item);
		elseif (isset($c_item['url']))
			$this->c_item_link($c_item);
		else
			$this->c_item_content($c_item);

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
				$tobar_avatar = qa_get_external_avatar_html( qa_get_logged_in_user_field('userid'), $avatarsize, true );
			}
			else {
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

			$auth_icon = strip_tags($tobar_avatar, '<img>');
		}
		// display login icon and label
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
	 * Dynamic <code>CSS</code> based on options and other interaction with Q2A.
	 *
	 * @since Snow 1.4
	 * @version 1.0
	 * @return string The CSS code
	 */
	private function head_inline_css()
	{
		$css = array('<style>');

		if (!qa_is_logged_in()) {
			$css[] = '.qa-nav-user { margin: 0 !important; }';
		}

		if (qa_request_part(1) !== qa_get_logged_in_handle()) {
			$css[] = '@media (max-width: 1179px) {';
			$css[] = ' body.qa-template-user.fixed, body[class*="qa-template-user-"].fixed { padding-top: 118px !important; }';
			$css[] = ' body.qa-template-users.fixed { padding-top: 95px !important; }';
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
	 * Question2Answer system icons info bar
	 *
	 * @since Snow 1.4
	 * @return string Info icons HTML
	 */
	private function icons_info()
	{
		$icons = array(
			'answer',
			'comment',
			'hide',
			'reshow',
			'close',
			'reopen',
			'flag',
			'unflag',
			'edit',
			'delete',
			'approve',
			'reject',
			'reply',
		);

		$icons_info = '<div class="qam-icons-info">';

		foreach ($icons as $icon) {
			$label = ucwords(qa_lang_html('question/' . $icon . '_button'));
			$icons_info .= '<div class="qam-icon-item"><span class="' . $icon . '"></span> ' . $label . '</div>';
		}
		$icons_info .= '</div> <!-- END qam-icons-info -->';

		return $icons_info;
	}
}
