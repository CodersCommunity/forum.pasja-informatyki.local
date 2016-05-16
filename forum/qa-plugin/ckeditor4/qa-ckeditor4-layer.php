<?php
if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}
class qa_html_theme_layer extends qa_html_theme_base {
	function head_css() {
		qa_html_theme_base::head_css();
		if(qa_opt('ckeditor4_inline_editing')) {
			if($this->template == 'question' && isset($this->content['q_view']['form']['buttons']['edit'])) {
				$this->output('<LINK REL="stylesheet" TYPE="text/css" HREF="'.qa_path_to_root().'qa-plugin/ckeditor4/qa-ckeditor4-inline.css"/>');
			}
		}
	}
	function head_script() {
		qa_html_theme_base::head_script();
		if(qa_opt('ckeditor4_inline_editing')) {
			if($this->template == 'question' && isset($this->content['q_view']['form']['buttons']['edit'])) {
				$this->output('<script type="text/javascript">');
				$this->output('qa_ckeditor4_config.toolbar.unshift(Array("savebtn"));');
				$this->output('</script>');
			}
		}
	}
	function main_part($key, $part) {
		if(qa_opt('ckeditor4_select')) {
			$selectable = false;
			if(qa_opt('editor_for_qs') == 'CKEditor4')
				$CK4_for_qs = true;
			else
				$CK4_for_qs = false;
			if(qa_opt('editor_for_as') == 'CKEditor4')
				$CK4_for_as = true;
			else
				$CK4_for_as = false;
			if($CK4_for_qs) {
				if($this->template == 'ask' || $this->template == 'question') {
					if(strpos($key, 'form') === 0 || strpos($key, 'form_q_edit') === 0) {
						$selectable = true;
					}
				}
			}
			if($CK4_for_as) {
				if($this->template == 'question') {
					if(strpos($key, 'a_form') === 0 || strpos($key, 'form_a_edit') === 0) {
						$selectable = true;
					}
				}
			}
			if($selectable) {
				if(isset($part['fields']['content'])) {
					$basic_checked = '';
					$standard_checked = '';
					$advanced_checked = '';
					if(isset($_COOKIE['qa_ckeditor4_select'])) {
						switch(qa_gpc_to_string($_COOKIE['qa_ckeditor4_select'])) {
						case 'basic':
							$basic_checked = ' checked';
							break;
						case 'advanced':
							$advanced_checked = ' checked';
							break;
						default:
							$standard_checked = ' checked';
							break;
						}
					} else {
						$standard_checked = ' checked';
					}
					$html  = '';
					$html .= '<span class="qa-editor-select" style="display:block;float:right;">';
					$html .= '<label for="qa-editor-basic" class="btn btn-default btn-xs"><input type="radio" name="qa-editor-select-item" id="qa-editor-basic" onchange="qa_change_editor(\'basic\');"'.$basic_checked.'>'.qa_lang('ck4/selector_basic_label').'</label>';
					$html .= '<label for="qa-editor-standard" class="btn btn-default btn-xs"><input type="radio" name="qa-editor-select-item" id="qa-editor-standard" onchange="qa_change_editor(\'standard\');"'.$standard_checked.'>'.qa_lang('ck4/selector_standard_label').'</label>';
					$html .= '<label for="qa-editor-advanced" class="btn btn-default btn-xs"><input type="radio" name="qa-editor-select-item" id="qa-editor-advanced" onchange="qa_change_editor(\'advanced\');"'.$advanced_checked.'>'.qa_lang('ck4/selector_advanced_label').'</label>';
					$html .= '</span>';
					
					$content = &$part['fields']['content'];
					if(isset($content['label']))
						$content['label'] = $html.$content['label'];
					else
						$content['label'] = $html;
				}
			}
		}
		qa_html_theme_base::main_part($key, $part);
	}
	function q_view_content($q_view) {
		if(qa_opt('ckeditor4_inline_editing')) {
			if(@$q_view['raw']['editable'] && is_array(@$q_view['form']['buttons']['edit'])) {
				$this->output('<div class="qa-q-view-content">');
				$id = strtolower($q_view['raw']['type'].$q_view['raw']['postid'].'_content');
				$this->output('<a name="'.$q_view['raw']['postid'].'"></a>');
				$this->output('<div class="entry-content cke_editable cke_editable_inline" contenteditable="true" id="'.$id.'">'.$q_view['raw']['content'].'</div>');
				$this->output('<script>');
				$this->output('var editor = CKEDITOR.inline( document.getElementById("'.$id.'"),  qa_ckeditor4_config);');
				$this->output('</script>');
				
				$this->output('</div>');
			} else
				qa_html_theme_base::q_view_content($q_view);
		} else
			qa_html_theme_base::q_view_content($q_view);
	}
	function a_item_content($a_item) {
		if(qa_opt('ckeditor4_inline_editing')) {
			if(@$a_item['raw']['editable'] && is_array(@$a_item['form']['buttons']['edit'])) {
				$this->output('<div class="qa-a-item-content">');
				$id = strtolower($a_item['raw']['type'].$a_item['raw']['postid'].'_content');
				$this->output('<a name="'.$a_item['raw']['postid'].'"></a>');
				$this->output('<div class="entry-content cke_editable cke_editable_inline" contenteditable="true" id="'.$id.'">'.$a_item['raw']['content'].'</div>');
				$this->output('<script>');
				$this->output('var editor = CKEDITOR.inline( document.getElementById("'.$id.'"), qa_ckeditor4_config);');
				$this->output('</script>');
				
				$this->output('</div>');
			} else
				qa_html_theme_base::a_item_content($a_item);
		} else
			qa_html_theme_base::a_item_content($a_item);
	}
}

/*
	Omit PHP closing tag to help avoid accidental output
*/