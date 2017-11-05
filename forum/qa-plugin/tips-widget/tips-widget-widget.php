<?php

class tips_widget_widget
{
	function allow_template($template)
	{
		if (!qa_opt('tips-enable'))
			return false;
		
		return true;
	}
	
	function allow_region($region)
	{
		if ($region == 'side')
			return true;
		
		return false;
	}
	
	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		if (!qa_opt('tips-enable'))
			return;
		
		if (is_null(qa_opt('prev_random')))
			qa_opt('prev_random', -1);
		
		$tips_list = explode('!NEW!', qa_opt('tips-widget-content'));
		
		do
		{
			$random = rand(0, count($tips_list) - 1);
			
		} while ($random == qa_opt('prev_random'));
		
		qa_opt('prev_random', $random);
		
		$themeobject->output('<div style="margin-bottom: 10px;"><b>Porady nie od parady</b></div>');
		$themeobject->output('<div style="font-size: 13px;">', $tips_list[$random], '</div>');
		$themeobject->output('<div style="margin-top: 10px;">Ciekawy innych porad? Odwiedź <a href="'.qa_path_html('tips').'"><b>tę stronę</b></a>!</div>');
	}
}
