<?php

require_once 'tips-replace-phrases.php';

class tips_widget_admin
{
	public function option_default($option)
	{
		$response = null;
		
		if ($option === 'tips-enable')
		{
			$response = false;
		}
		elseif ($option === 'tips-widget-content' || $option === 'tips-page-content')
		{
			$response = file_get_contents(__DIR__ . '/snippets/' . $option . '.html');
			if ($response === false)
			{
				$response = '';
			}
		}
		
		return $response;
	}
	
	public function admin_form(&$qa_content)
	{	
		$saved = qa_clicked('tips-save');
		
		if ($saved === true)
		{
			qa_opt('tips-enable', (bool)qa_post_text('tips-enable'));
		
			qa_opt('tips-widget-content', qa_post_text('tips-widget-content'));
			qa_opt('tips-page-content', qa_post_text('tips-page-content'));
		}
		
		$form = [
			'ok'      => $saved ? 'Save completed' : null,
			'fields'  => $this->prepareFields(),
			'buttons' => [[
				'label' => 'Save',
				'tags' => 'name="tips-save"'
			]]
		];
		
		return $form;
	}
	
	public function prepareFields()
	{
		return [
			[
				'label' => 'Enable plugin',
				'tags'  => 'name="tips-enable"',
				'value' =>  qa_opt('tips-enable'),
				'type'  => 'checkbox'
			],
			
			[
				'label' => 'You can use the phrase <b>!PATH_HTML(request)!</b> to return the path of the <i>request</i> page, relative to the current request.',
				'type'  => 'static'
			],
			
			[
				'label' => 'You can also use the phrase <b>!WIDGET_IMG_SRC(img)!</b> to return the full path of the <i>img</i> image file located in the <i>/qa-plugin/plugin_directory/icons</i>.',
				'type'  => 'static'
			],
			
			[
				'label' => 'Enter tips. Separate them with <b>!NEW!</b> phrase',
				'tags'  => 'name="tips-widget-content"',
				'value' => qa_opt('tips-widget-content'),
				'rows'  =>  20,
				'type'  => 'textarea'
			],
			
			[
				'label' => 'Enter page content. Mark a place to insert tips list with <b>!TIPS!</b> phrase',
				'tags'  => 'name="tips-page-content"',
				'value' => qa_opt('tips-page-content'),
				'rows'  =>  20,
				'type'  => 'textarea'
			]
		];
	}
}
