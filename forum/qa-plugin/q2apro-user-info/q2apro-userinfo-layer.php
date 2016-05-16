<?php

/*
	Plugin Name: User Info
	Plugin URI: http://www.q2apro.com/plugins/user-info
	Plugin Description: Mouse over a username to display user profile information: Avatar image, account age, total points, monthly points, answers, best answers, ratio, questions posted, badges.
	Plugin Version: 1.0
	Plugin Date: 2014-02-20
	Plugin Author: q2apro.com
	Plugin Author URI: http://www.q2apro.com
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: http://www.q2apro.com/pluginupdate?id=3
	
	Licence: Copyright © q2apro.com - All rights reserved

*/

	class qa_html_theme_layer extends qa_html_theme_base {
		
		var $plugin_url_userinfo;

		// needed to get the plugin url
		function qa_html_theme_layer($template, $content, $rooturl, $request)
		{
			if(qa_opt('q2apro_userinfo_enabled')) {
				global $qa_layers;
				$this->plugin_url_userinfo = $qa_layers['User Info Layer']['urltoroot'];
			}
			qa_html_theme_base::qa_html_theme_base($template, $content, $rooturl, $request);
		}
		
		function head_script(){
			qa_html_theme_base::head_script();
			if(qa_opt('q2apro_userinfo_enabled')) {
				// load only if cool tooltips plugin is not present
				if(qa_opt('q2apro_cooltooltips_enabled')=='') {
					$this->output('<script type="text/javascript" src="'. qa_opt('site_url') . $this->plugin_url_userinfo .'tipsy.script.js"></script>');
				}
				
				$this->output("<script type='text/javascript'>
	$(document).ready(function(){
		$('.qa-user-link').not($('.qa-logged-in-data').children()).mouseover(function() { // .nickname, .bestusers .qa-user-link
			var recentItem = $(this);
			var username = recentItem.text();
			if(typeof recentItem.attr('data-test') == 'undefined') {
				$.ajax({
					 type: 'POST',
					 url: '".qa_path('userinfo-ajax')."',
					 data: {ajax:username},
					 success: function(data) {
						recentItem.attr('title', data);
						recentItem.tipsy( {gravity:'s', fade:true, html:true, offset:0 } );
						// check if mouse has already left username field
						if (recentItem.is(':hover')) {
							recentItem.tipsy('show');
						}
						else {
							recentItem.tipsy('hide');
						}
						// mark element as loaded
						recentItem.attr('data-test', 'loaded');
					 }
				});
			}
		}); 
		// user info (i)
		$('.qa-logged-in-points').mouseover(function() {
			var recentItem = $(this);
			var username = recentItem.attr('title');
			if(typeof recentItem.attr('data-test') == 'undefined') {
				$.ajax({
					 type: 'POST',
					 url: '".qa_path('userinfo-ajax')."',
					 data: {ajax:username},
					 success: function(data) {
						recentItem.attr('title', data);
						recentItem.tipsy( {gravity: 'n', offset:5, html:true, fade:true });
						// check if mouse has already left username field
						if(recentItem.is(':hover')) {
							recentItem.tipsy('show');
						}
						else {
							recentItem.tipsy('hide');
						}
						// mark element as loaded
						recentItem.attr('data-test', 'loaded');
					 }
				});
			}
		});
	}); // end ready
				</script>"); // end js output
			}
		} // end head_script
		
	} // end qa_html_theme_layer
	

/*
	Omit PHP closing tag to help avoid accidental output
*/