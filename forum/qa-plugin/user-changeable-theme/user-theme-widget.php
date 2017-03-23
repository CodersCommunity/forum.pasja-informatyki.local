<?php

class user_theme_widget
{
    public function allow_template($template)
    {
        if (qa_opt('user_theme_enable')) {
            return true;
        }
        return false;
    }

    public function allow_region($region)
    {
        return ($region === 'side');
    }

    public function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        $themeobject->output('
			<form class="widget-select-theme" action="/'.qa_request().'" method="post">
				<p class="widget-select-theme__text">Motyw:</p>
	            <button class="widget-select-theme__button widget-select-theme__button--light" name="select_theme" value="0">Jasny</button>
	            <button class="widget-select-theme__button widget-select-theme__button--dark" name="select_theme" value="1">Ciemny</button>
	        </form>
		');
    }
}
