<?php

class tips_widget_widget
{
	public $random;
	private $tips_list;
	
	public function __construct()
	{
		if (!qa_opt('tips-enable')) {
            return;
        }
		
		$this->tips_list = explode('!NEW!', qa_opt('tips-widget-content'));
		$prev_random = isset($_COOKIE['prev_random']) ? $_COOKIE['prev_random'] : -1;
		
		do {
			$this->random = mt_rand(0, count($this->tips_list) - 1);
		} while ($this->random === $prev_random);
	}
	
	public function allow_template($template)
	{
		return !qa_opt('tips-enable');
	}
	
	public function allow_region($region)
	{
		return mb_strtolower($region) === 'side';
	}
	
	public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
		if (!qa_opt('tips-enable')) {
            return;
        }
		
		$themeobject->output('<div style="margin-bottom: 10px;"><b>Porady nie od parady</b></div>');
		$themeobject->output('<div style="font-size: 13px;">', $this->tips_list[$this->random], '</div>');
		$themeobject->output('<div style="margin-top: 10px;">Ciekawy innych porad? Odwiedź <a href="'.qa_path_html('tips').'"><b>tę stronę</b></a>!</div>');
	}
}
