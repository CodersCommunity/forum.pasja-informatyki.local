<?php

class qa_ckeditor4 {
	
	var $urltoroot;
	var $toolbar;
	var $config;
	var $toolbar_advanced;
	var $config_advanced;
	
	function load_module($directory, $urltoroot) {
		$this->urltoroot=$urltoroot;
		$this->toolbar =
			"['Bold','Italic'],\n".
			"['Link','Unlink'],\n".
			"['NumberedList','BulletedList','Blockquote'],\n".
			"['Image','HorizontalRule','Smiley'],\n".
			"['RemoveFormat','Maximize']";
		$this->config =
			"toolbarCanCollapse:false,\n".
			"removePlugins:'elementspath',\n".
			"resize_enabled:false,\n".
			"autogrow:false,\n".
			"entities:false\n";
		$this->toolbar_advanced =
			"['Bold','Italic','Underline','Strike'],\n".
			"['Font','FontSize'],\n".
			"['TextColor','BGColor'],\n".
			"['Link','Unlink'],\n".
			"['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],\n".
			"['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],\n".
			"['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar'],\n".
			"['RemoveFormat','Maximize']";
		$this->config_advanced =
			"toolbarCanCollapse:false,\n".
			"removePlugins:'elementspath',\n".
			"resize_enabled:false,\n".
			"autogrow:false,\n".
			"entities:false\n";
		$this->config_ie =
			"\nextraPlugins:'savebtn',\n".
			"saveSubmitURL:'".qa_opt('site_url')."qa-plugin/ckeditor4/qa-ckeditor4-ajax.php'\n";
	}
	
	function option_default($option) {
		if ($option=='ckeditor4_upload_max_size') {
			if(qa_qa_version_below(1.6))
				require_once QA_INCLUDE_DIR.'qa-app-blobs.php';
			else
				require_once QA_INCLUDE_DIR.'qa-app-upload.php';
			return min(qa_get_max_upload_size(), 1048576);
		}
		elseif ($option=='ckeditor4_skin')
			return 'moono';
		elseif ($option=='ckeditor4_toolbar')
			return $this->toolbar;
		elseif ($option=='ckeditor4_config')
			return $this->config;
		elseif ($option=='ckeditor4_select')
			return false;
		elseif ($option=='ckeditor4_toolbar_advanced')
			return $this->toolbar_advanced;
		elseif ($option=='ckeditor4_config_advanced')
			return $this->config_advanced;
		elseif ($option=='ckeditor4_upload_images')
			return false;
		elseif ($option=='ckeditor4_inline_editing')
			return false;
		elseif ($option=='ckeditor4_htmLawed_controler')
			return false;
		elseif ($option=='ckeditor4_htmLawed_safe')
			return true;
		elseif ($option=='ckeditor4_htmLawed_elements')
			return '*+embed+object-form';
		elseif ($option=='ckeditor4_htmLawed_schemes')
			return 'href: aim, feed, file, ftp, gopher, http, https, irc, mailto, news, nntp, sftp, ssh, telnet; *:file, http, https; style: !; classid:clsid';
		elseif ($option=='ckeditor4_htmLawed_keep_bad')
			return false;
		elseif ($option=='ckeditor4_htmLawed_anti_link_spam')
			return '/.*/,';
		elseif ($option=='ckeditor4_htmLawed_hook_tag')
			return 'qa_sanitize_html_hook_tag';
	}

	function bytes_to_mega_html($bytes) {
		return qa_html(number_format($bytes/1048576, 1));
	}

	function admin_form(&$qa_content) {
		if(qa_qa_version_below(1.6))
			require_once QA_INCLUDE_DIR.'qa-app-blobs.php';
		else
			require_once QA_INCLUDE_DIR.'qa-app-upload.php';
		
		$saved=false;
		
		if (qa_clicked('ckeditor4_save_button')) {
			qa_opt('ckeditor4_skin', qa_post_text('ckeditor4_skin_field'));
			qa_opt('ckeditor4_toolbar', qa_post_text('ckeditor4_toolbar_field'));
			qa_opt('ckeditor4_config', qa_post_text('ckeditor4_config_field'));
			qa_opt('ckeditor4_select', (int)qa_post_text('ckeditor4_select_field'));
			qa_opt('ckeditor4_toolbar_advanced', qa_post_text('ckeditor4_toolbar_advanced_field'));
			qa_opt('ckeditor4_config_advanced', qa_post_text('ckeditor4_config_advanced_field'));
			qa_opt('ckeditor4_upload_images', (int)qa_post_text('ckeditor4_upload_images_field'));
			qa_opt('ckeditor4_upload_all', (int)qa_post_text('ckeditor4_upload_all_field'));
			qa_opt('ckeditor4_upload_max_size', min(qa_get_max_upload_size(), 1048576*(float)qa_post_text('ckeditor4_upload_max_size_field')));
			qa_opt('ckeditor4_inline_editing', (int)qa_post_text('ckeditor4_inline_editing_field'));
			qa_opt('ckeditor4_htmLawed_controler', (int)qa_post_text('ckeditor4_htmLawed_controler_field'));
			qa_opt('ckeditor4_htmLawed_safe', (int)qa_post_text('ckeditor4_htmLawed_safe_field'));
			qa_opt('ckeditor4_htmLawed_elements', qa_post_text('ckeditor4_htmLawed_elements_field'));
			qa_opt('ckeditor4_htmLawed_schemes', qa_post_text('ckeditor4_htmLawed_schemes_field'));
			qa_opt('ckeditor4_htmLawed_keep_bad', (int)qa_post_text('ckeditor4_htmLawed_keep_bad_field'));
			qa_opt('ckeditor4_htmLawed_anti_link_spam', qa_post_text('ckeditor4_htmLawed_anti_link_spam_field'));
			qa_opt('ckeditor4_htmLawed_hook_tag', qa_post_text('ckeditor4_htmLawed_hook_tag_field'));
			$saved=true;
		}
		if (qa_clicked('ckeditor4_reset_button')) {
			qa_opt('ckeditor4_skin', $this->option_default('ckeditor4_skin'));
			qa_opt('ckeditor4_toolbar', $this->option_default('ckeditor4_toolbar'));
			qa_opt('ckeditor4_config', $this->option_default('ckeditor4_config'));
			qa_opt('ckeditor4_select', (int)$this->option_default('ckeditor4_select'));
			qa_opt('ckeditor4_toolbar_advanced', $this->option_default('ckeditor4_toolbar_advanced'));
			qa_opt('ckeditor4_config_advanced', $this->option_default('ckeditor4_config_advanced'));
			qa_opt('ckeditor4_upload_images', (int)$this->option_default('ckeditor4_upload_images'));
			qa_opt('ckeditor4_upload_all', (int)$this->option_default('ckeditor4_upload_all'));
			qa_opt('ckeditor4_upload_max_size', $this->option_default('ckeditor4_upload_max_size'));
			qa_opt('ckeditor4_inline_editing', (int)$this->option_default('ckeditor4_inline_editing'));
			qa_opt('ckeditor4_htmLawed_controler', (int)$this->option_default('ckeditor4_htmLawed_controler'));
			qa_opt('ckeditor4_htmLawed_safe', (int)$this->option_default('ckeditor4_htmLawed_safe'));
			qa_opt('ckeditor4_htmLawed_elements', $this->option_default('ckeditor4_htmLawed_elements'));
			qa_opt('ckeditor4_htmLawed_schemes', $this->option_default('ckeditor4_htmLawed_schemes'));
			qa_opt('ckeditor4_htmLawed_keep_bad', (int)$this->option_default('ckeditor4_htmLawed_keep_bad'));
			qa_opt('ckeditor4_htmLawed_anti_link_spam', $this->option_default('ckeditor4_htmLawed_anti_link_spam'));
			qa_opt('ckeditor4_htmLawed_hook_tag', $this->option_default('ckeditor4_htmLawed_hook_tag'));
			$saved=true;
		}
		
		qa_set_display_rules($qa_content, array(
			'ckeditor4_toolbar_advanced' => 'ckeditor4_select_field',
			'ckeditor4_config_advanced' => 'ckeditor4_select_field',
			'ckeditor4_upload_all_display' => 'ckeditor4_upload_images_field',
			'ckeditor4_upload_max_size_display' => 'ckeditor4_upload_images_field',
			'ckeditor4_htmLawed_safe_display' => 'ckeditor4_htmLawed_controler_field',
			'ckeditor4_htmLawed_elements_display' => 'ckeditor4_htmLawed_controler_field',
			'ckeditor4_htmLawed_schemes_display' => 'ckeditor4_htmLawed_controler_field',
			'ckeditor4_htmLawed_keep_bad_display' => 'ckeditor4_htmLawed_controler_field',
			'ckeditor4_htmLawed_anti_link_spam_display' => 'ckeditor4_htmLawed_controler_field',
			'ckeditor4_htmLawed_hook_tag_display' => 'ckeditor4_htmLawed_controler_field',
		));

		return array(
			'ok' => $saved ? qa_lang('ck4/saved_message') : null,
			
			'fields' => array(
				array(
					'id' => 'ckeditor4_skin',
					'type' => 'select',
					'label' => qa_lang('ck4/skin_label'),
					'value' => qa_opt('ckeditor4_skin'),
					'tags' => 'name="ckeditor4_skin_field"',
					'options' => $this->get_skins(),
				),
				array(
					'id' => 'ckeditor4_toolbar',
					'label' => qa_lang('ck4/tool_label'),
					'type' => 'textarea',
					'value' => qa_opt('ckeditor4_toolbar'),
					'tags' => 'NAME="ckeditor4_toolbar_field"',
					'rows' => 10,
				),
				array(
					'id' => 'ckeditor4_config',
					'label' => qa_lang('ck4/config_label'),
					'type' => 'textarea',
					'value' => qa_opt('ckeditor4_config'),
					'tags' => 'NAME="ckeditor4_config_field"',
					'rows' => 10,
				),
				array(
					'label' => qa_lang('ck4/selector_label'),
					'type' => 'checkbox',
					'value' => (int)qa_opt('ckeditor4_select'),
					'tags' => 'name="ckeditor4_select_field" id="ckeditor4_select_field"',
				),
				array(
					'id' => 'ckeditor4_toolbar_advanced',
					'label' => qa_lang('ck4/tool_advanced_label'),
					'type' => 'textarea',
					'value' => qa_opt('ckeditor4_toolbar_advanced'),
					'tags' => 'NAME="ckeditor4_toolbar_advanced_field"',
					'rows' => 10,
				),
				array(
					'id' => 'ckeditor4_config_advanced',
					'label' => qa_lang('ck4/config_advanced_label'),
					'type' => 'textarea',
					'value' => qa_opt('ckeditor4_config_advanced'),
					'tags' => 'NAME="ckeditor4_config_advanced_field"',
					'rows' => 10,
				),
				array(
					'label' => qa_lang('ck4/image_upload_label'),
					'type' => 'checkbox',
					'value' => (int)qa_opt('ckeditor4_upload_images'),
					'tags' => 'name="ckeditor4_upload_images_field" id="ckeditor4_upload_images_field"',
				),
				array(
					'id' => 'ckeditor4_upload_all_display',
					'label' => qa_lang('ck4/all_upload_label'),
					'type' => 'checkbox',
					'value' => (int)qa_opt('ckeditor4_upload_all'),
					'tags' => 'name="ckeditor4_upload_all_field"',
				),
				array(
					'id' => 'ckeditor4_upload_max_size_display',
					'label' => qa_lang('ck4/maxsize_upload_label'),
					'suffix' => qa_lang_sub('ck4/maxsize_upload_suffix', $this->bytes_to_mega_html(qa_get_max_upload_size())),
					'type' => 'number',
					'value' => $this->bytes_to_mega_html(qa_opt('ckeditor4_upload_max_size')),
					'tags' => 'name="ckeditor4_upload_max_size_field"',
				),
				array(
					'label' => qa_lang('ck4/inline_label'),
					'type' => 'checkbox',
					'value' => (int)qa_opt('ckeditor4_inline_editing'),
					'tags' => 'name="ckeditor4_inline_editing_field" id="ckeditor4_inline_editing_field"',
					'note' => str_replace(array("\r\n","\n","\r"), '<BR/>',qa_lang_sub('ck4/inline_note', $this->config_ie)),
				),
				array(
					'label' => qa_lang('ck4/htmLawed_label'),
					'type' => 'checkbox',
					'value' => (int)qa_opt('ckeditor4_htmLawed_controler'),
					'tags' => 'name="ckeditor4_htmLawed_controler_field" id="ckeditor4_htmLawed_controler_field"',
				),
				array(
					'id' => 'ckeditor4_htmLawed_safe_display',
					'label' => qa_lang('ck4/safe_label'),
					'type' => 'checkbox',
					'value' => (int)qa_opt('ckeditor4_htmLawed_safe'),
					'tags' => 'name="ckeditor4_htmLawed_safe_field"',
				),
				array(
					'id' => 'ckeditor4_htmLawed_elements_display',
					'label' => qa_lang('ck4/element_label'),
					'type' => 'text',
					'value' => qa_opt('ckeditor4_htmLawed_elements'),
					'tags' => 'NAME="ckeditor4_htmLawed_elements_field" ID="ckeditor4_htmLawed_elements_field"',
				),
				array(
					'id' => 'ckeditor4_htmLawed_schemes_display',
					'label' => qa_lang('ck4/scheme_label'),
					'type' => 'textarea',
					'value' => qa_opt('ckeditor4_htmLawed_schemes'),
					'tags' => 'NAME="ckeditor4_htmLawed_schemes_field" ID="ckeditor4_htmLawed_schemes_field"',
					'rows' => 3,
				),
				array(
					'id' => 'ckeditor4_htmLawed_keep_bad_display',
					'label' => qa_lang('ck4/keepbad_label'),
					'type' => 'checkbox',
					'value' => (int)qa_opt('ckeditor4_htmLawed_keep_bad'),
					'tags' => 'name="ckeditor4_htmLawed_keep_bad_field" id="ckeditor4_htmLawed_keep_bad_field"',
				),
				array(
					'id' => 'ckeditor4_htmLawed_anti_link_spam_display',
					'label' => qa_lang('ck4/antilinkspam_label'),
					'type' => 'text',
					'value' => qa_opt('ckeditor4_htmLawed_anti_link_spam'),
					'tags' => 'NAME="ckeditor4_htmLawed_anti_link_spam_field" ID="ckeditor4_htmLawed_anti_link_spam_field"',
				),
				array(
					'id' => 'ckeditor4_htmLawed_hook_tag_display',
					'label' => qa_lang('ck4/hooktag_label'),
					'type' => 'text',
					'value' => qa_opt('ckeditor4_htmLawed_hook_tag'),
					'tags' => 'NAME="ckeditor4_htmLawed_hook_tag_field" ID="ckeditor4_htmLawed_hook_tag_field"',
				),
			),
			
			'buttons' => array(
				array(
					'label' => qa_lang('ck4/save_button_label'),
					'tags' => 'name="ckeditor4_save_button"',
				),
				array(
					'label' => qa_lang('ck4/reset_button_label'),
					'tags' => 'name="ckeditor4_reset_button"',
					'tags' => 'NAME="ckeditor4_reset_button" onClick="javascript:return confirm(\''.qa_lang('ck4/reset_confirm').'\')"',
				),
			),
		);
	}

	function calc_quality($content, $format) {
		if ($format=='html')
			return 1.0;
		elseif ($format=='')
			return 0.8;
		else
			return 0;
	}
	
	function get_field(&$qa_content, $content, $format, $fieldname, $rows /* $autofocus parameter deprecated */) {
		$scriptsrc=$this->urltoroot.'ckeditor.js?'.QA_VERSION;			
		$alreadyadded=false;

		if (isset($qa_content['script_src']))
			foreach ($qa_content['script_src'] as $testscriptsrc)
				if ($testscriptsrc==$scriptsrc)
					$alreadyadded=true;
				
		if (!$alreadyadded) {
			$uploadimages=qa_opt('ckeditor4_upload_images');
			$uploadall=$uploadimages && qa_opt('ckeditor4_upload_all');
			
			$qa_content['script_src'][]=$scriptsrc;
			$qa_content['script_lines'][]=array(
				"qa_ckeditor4_config={toolbar:[".str_replace(array("\r\n","\n","\r"), '', qa_opt('ckeditor4_toolbar'))."]".
				", defaultLanguage:".qa_js(qa_opt('site_language')).
				", skin:".qa_js(qa_opt('ckeditor4_skin')).
				", ".str_replace(array("\r\n","\n","\r"), '', qa_opt('ckeditor4_config')).
				($uploadimages ? (", filebrowserImageUploadUrl:".qa_js(qa_path('wysiwyg-editor-upload', array('qa_only_image' => true)))) : "").
				($uploadall ? (", filebrowserUploadUrl:".qa_js(qa_path('wysiwyg-editor-upload'))) : "").
				"};"
			);
			$qa_content['script_lines'][]=array(
				"qa_ckeditor4_config_advanced={toolbar:[".str_replace(array("\r\n","\n","\r"), '', qa_opt('ckeditor4_toolbar_advanced'))."]".
				", defaultLanguage:".qa_js(qa_opt('site_language')).
				", skin:".qa_js(qa_opt('ckeditor4_skin')).
				", ".str_replace(array("\r\n","\n","\r"), '', qa_opt('ckeditor4_config_advanced')).
				($uploadimages ? (", filebrowserImageUploadUrl:".qa_js(qa_path('wysiwyg-editor-upload', array('qa_only_image' => true)))) : "").
				($uploadall ? (", filebrowserUploadUrl:".qa_js(qa_path('wysiwyg-editor-upload'))) : "").
				"};"
			);
			if(qa_opt('ckeditor4_select')) {
				$qa_content['script_lines'][] = array(
					'function qa_change_editor(type) {',
					'	if(qa_ckeditor4_'.$fieldname.') {',
					'		qa_ckeditor4_'.$fieldname.'.destroy();',
					'		qa_ckeditor4_'.$fieldname.' = null;',
					'	}',
					'	if(type=="standard") {',
					'		qa_ckeditor4_'.$fieldname.' = CKEDITOR.replace("'.$fieldname.'", window.qa_ckeditor4_config);',
					'	} else if(type=="advanced") {',
					'		qa_ckeditor4_'.$fieldname.' = CKEDITOR.replace("'.$fieldname.'", window.qa_ckeditor4_config_advanced);',
					'	}',
					'	qa_set_editor(type);',
					'}',
					'function qa_set_editor(type) {',
					'	var name = "qa_ckeditor4_select";',
					'	var period = 7;',
					'	var nowtime = new Date().getTime();',
					'	var clear_time = new Date(nowtime + (60 * 60 * 24 * 1000 * period));',
					'	var expire = clear_time.toGMTString();',
					'	var ckstr = "qa_ckeditor4_select="+escape(type)+";expires="+expire+";"',
					'	document.cookie = ckstr;',
					'}'
				);
			}
		}		
			
		if ($format=='html')
			$html=$content;
		else
			$html=qa_html($content, true);
		
		return array(
			'tags' => 'name="'.$fieldname.'"',
			'value' => qa_html($html),
			'rows' => $rows,
		);
	}

	function load_script($fieldname) {
		$script = "qa_ckeditor4_".$fieldname."=CKEDITOR.replace(".qa_js($fieldname).", window.qa_ckeditor4_config);";
		if(qa_opt('ckeditor4_select')) {
			if(isset($_COOKIE['qa_ckeditor4_select'])) {
				$noweditor = qa_gpc_to_string($_COOKIE['qa_ckeditor4_select']);
				switch($noweditor) {
				case 'advanced':
					$script = "qa_ckeditor4_".$fieldname."=CKEDITOR.replace(".qa_js($fieldname).", window.qa_ckeditor4_config_advanced);";
					break;
				case 'standard':
					$script = "qa_ckeditor4_".$fieldname."=CKEDITOR.replace(".qa_js($fieldname).", window.qa_ckeditor4_config);";
					break;
				default:
					$script = "qa_ckeditor4_".$fieldname."=null;";
					break;
				}
			}
		}
		return $script;
	}
	
	function focus_script($fieldname) {
		$script = "qa_ckeditor4_".$fieldname.".focus();";
		if(qa_opt('ckeditor4_select')) {
			if(isset($_COOKIE['qa_ckeditor4_select'])) {
				$noweditor = qa_gpc_to_string($_COOKIE['qa_ckeditor4_select']);
				switch($noweditor) {
				case 'basic':
					$script = "";
					break;
				}
			}
		}
		return $script;
	}
	
	function update_script($fieldname) {
		return "qa_ckeditor4_".$fieldname.".updateElement();";
	}
	
	function read_post($fieldname) {
		$html=qa_post_text($fieldname);
		
		$htmlformatting=preg_replace('/<\s*\/?\s*(br|p)\s*\/?\s*>/i', '', $html); // remove <p>, <br>, etc... since those are OK in text
		
		if (preg_match('/<.+>/', $htmlformatting)) // if still some other tags, it's worth keeping in HTML
			return array(
				'format' => 'html',
				'content' => qa_sanitize_html($html, false, true), // qa_sanitize_html() is ESSENTIAL for security
			);
		
		else { // convert to text
			$viewer=qa_load_module('viewer', '');

			return array(
				'format' => '',
				'content' => $viewer->get_text($html, 'html', array())
			);
		}
	}
	private function get_skins() {
		$arr = array();
		$skinspath = QA_PLUGIN_DIR.'ckeditor4/skins/';
		$handle = opendir($skinspath);
		while ($fname = readdir($handle)) {
			if(is_dir($skinspath . $fname)){
				if ($fname != "." && $fname != "..")
					$arr[$fname] = $fname;
			}
		}
		closedir($handle);
		ksort($arr);
		return $arr;
	}
}

/*
	Omit PHP closing tag to help avoid accidental output
*/