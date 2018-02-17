<?php

require_once 'tips-replace-phrases.php';

class tips_widget_page
{
	public function match_request($request)
	{
		return $request === 'tips';
	}
	
	public function suggest_requests()
	{
		return [[
			'title'   => 'Porady nie od parady',
			'request' => 'tips',
			'nav'     => null
		]];
	}
	
	public function process_request($request)
	{
		if (!qa_opt('tips-enable'))
		{
			return include QA_INCLUDE_DIR . 'qa-page-not-found.php';
		}
		
		$qa_content = qa_content_prepare();
		
		$qa_content['title'] = 'Porady nie od parady';
		
		$qa_content['custom'] = str_replace('!TIPS!', $this->prepareTipsList(), replaceWidgetImgSrc(replacePathHtml(qa_opt('tips-page-content'))));
		
		return $qa_content;
	}
	
	public function prepareTipsList()
	{
		$tips_content = replaceWidgetImgSrc(replacePathHtml(qa_opt('tips-widget-content')));
		
		$tips_list_content = "\t<ul class=\"tips-page-list\">\n";
		
		$tips_list = explode('!NEW!', $tips_content);
		foreach ($tips_list as $element)
		{
			$tips_list_content .= "\t\t<li>" . $element . "</li>\n";
		}
		
		$tips_list_content .= "\t</ul>";
		
		return $tips_list_content;
	}
}
