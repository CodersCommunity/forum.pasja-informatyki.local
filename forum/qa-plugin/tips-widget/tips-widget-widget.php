<?php

require_once 'tips-replace-phrases.php';

class tips_widget_widget
{
	public $random;
	private $tips_list;
	
	public function init()
	{
		if (!qa_opt('tips-enable'))
		{
			return;
		}
		
		$this->prepareTipsList();
		
		$tips_list_count = count($this->tips_list);
		
		if (isset($_COOKIE['prev_random']) &&
		    is_numeric($_COOKIE['prev_random']) &&
		    $_COOKIE['prev_random'] >= 0 &&
		    $_COOKIE['prev_random'] < $tips_list_count)
		{
			$prev_random = (int)$_COOKIE['prev_random'];
		}
		else
		{
			$prev_random = -1;
		}
		
		do
		{
			if ($tips_list_count < 2)
			{
				$this->random = 0;
				break;
			}
			
			$this->random = mt_rand(0, $tips_list_count - 1);
			
		} while ($this->random === $prev_random);
	}
	
	public function allow_template($template)
	{
		return qa_opt('tips-enable');
	}
	
	public function allow_region($region)
	{
		return $region === 'side';
	}
	
	public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		if (!qa_opt('tips-enable'))
		{
			return;
		}
		
		$themeobject->output('<div class="tips-widget-title"><b>Porady nie od parady</b></div>');
		$themeobject->output('<div class="tips-widget-content">', $this->tips_list[$this->random], '</div>');
		$themeobject->output('<div class="tips-widget-footer">Ciekawy innych porad? Odwiedź <a href="' . qa_path_html('tips') . '"><b>tę stronę</b></a>!</div>');
	}
	
	public function prepareTipsList()
	{
		$tips_content = replaceWidgetImgSrc(replacePathHtml(qa_opt('tips-widget-content')));

		$this->tips_list = explode('!NEW!', $tips_content);
	}
}
