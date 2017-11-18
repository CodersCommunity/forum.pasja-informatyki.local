<?php

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
				'tags' => 'NAME="tips-save"'
			]]
		];
		
		return $form;
	}
	
	public function prepareFields()
	{
		return [
			[
				'label' => 'Enable plugin',
				'tags'  => 'NAME="tips-enable"',
				'value' =>  qa_opt('tips-enable'),
				'type'  => 'checkbox'
			],
			
			[
				'label' => 'Enter tips. Separate them with !NEW! phrase',
				'tags'  => 'NAME="tips-widget-content"',
				'value' => qa_opt('tips-widget-content'),
				'rows'  =>  20,
				'type'  => 'textarea'
			],
			
			[
				'label' => 'Enter page content. Mark a place to insert tips list with !TIPS! phrase',
				'tags'  => 'NAME="tips-page-content"',
				'value' => qa_opt('tips-page-content'),
				'rows'  =>  20,
				'type'  => 'textarea'
			]
		];
	}
}
