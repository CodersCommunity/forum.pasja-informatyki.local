<?php

class qa_html_theme_layer extends qa_html_theme_base
{
	const DAY = 86400;
	
	public function initialize()
	{
		if (!qa_opt('tips-enable'))
		{
			qa_html_theme_base::initialize();
			return;
		}
		
		$widget_module = qa_load_module('widget', 'Tips Widget Widget');
		if (!is_null($widget_module))
		{
			$widget_module->init();
			setcookie('prev_random', $widget_module->random, time() + self::DAY, '/', QA_COOKIE_DOMAIN);
		}
		
		qa_html_theme_base::initialize();
	}
}