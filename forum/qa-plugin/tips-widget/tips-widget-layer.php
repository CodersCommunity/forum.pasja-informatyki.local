<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	function initialize()
	{
		if (!qa_opt('tips-enable'))
		{
			qa_html_theme_base::initialize();
			return;
		}
		
		if ($widget_module = qa_load_module('widget', 'Tips Widget Widget'))
			setcookie('prev_random', $widget_module->random, time() + 86400, '/', QA_COOKIE_DOMAIN);
		
		qa_html_theme_base::initialize();
	}
}