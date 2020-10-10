<?php

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    header('Location: ../../');
    exit;
}

class qa_html_theme_layer extends qa_html_theme_base
{
    function main_part($key, $part)
    {
        if (qa_opt('ckeditor4_select')) {
            $selectable = false;
            $CK4_for_qs = qa_opt('editor_for_qs') === 'CKEditor4';
            $CK4_for_as = qa_opt('editor_for_as') === 'CKEditor4';

            if ($CK4_for_qs) {
                if ($this->template === 'ask' || $this->template === 'question') {
                    if (strpos($key, 'form') === 0 || strpos($key, 'form_q_edit') === 0) {
                        $selectable = true;
                    }
                }
            }
            if ($CK4_for_as) {
                if ($this->template === 'question') {
                    if (strpos($key, 'a_form') === 0 || strpos($key, 'form_a_edit') === 0) {
                        $selectable = true;
                    }
                }
            }

            if ($selectable) {
                if (isset($part['fields']['content'])) {
                    $basic_checked = '';
                    $standard_checked = '';
                    $advanced_checked = '';
                    if (isset($_COOKIE['qa_ckeditor4_select'])) {
                        switch (qa_gpc_to_string($_COOKIE['qa_ckeditor4_select'])) {
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
                    $html = '';
                    $html .= '<span class="qa-editor-select" style="display:block;float:right;">';
                    $html .= '<label for="qa-editor-basic" class="btn btn-default btn-xs"><input type="radio" name="qa-editor-select-item" id="qa-editor-basic" onchange="qa_change_editor(\'basic\');"' . $basic_checked . '>' . qa_lang('ck4/selector_basic_label') . '</label>';
                    $html .= '<label for="qa-editor-standard" class="btn btn-default btn-xs"><input type="radio" name="qa-editor-select-item" id="qa-editor-standard" onchange="qa_change_editor(\'standard\');"' . $standard_checked . '>' . qa_lang('ck4/selector_standard_label') . '</label>';
                    $html .= '<label for="qa-editor-advanced" class="btn btn-default btn-xs"><input type="radio" name="qa-editor-select-item" id="qa-editor-advanced" onchange="qa_change_editor(\'advanced\');"' . $advanced_checked . '>' . qa_lang('ck4/selector_advanced_label') . '</label>';
                    $html .= '</span>';

                    $content = &$part['fields']['content'];
                    $content['label'] = $html;
                    if (isset($content['label'])) {
                        $content['label'] .= $content['label'];
                    }
                }
            }
        }

        parent::main_part($key, $part);
    }

    public function head_css()
    {
        $this->content['css_src'][] = '/qa-content/css/shCore.css';
        $this->content['css_src'][] = '/qa-content/css/shThemeDefault.css';

        parent::head_css();
    }

    public function head_script()
    {
        parent::head_script();

        $scriptsOutput = '';

        $scripts = [
            'shCore', 'shLegacy', 'shBrushBash', 'shBrushCpp', 'shBrushCSharp', 'shBrushCss', 'shBrushDelphi',
            'shBrushJava', 'shBrushJScript', 'shBrushPerl', 'shBrushPhp', 'shBrushPlain', 'shBrushPowerShell',
            'shBrushPython', 'shBrushRuby', 'shBrushSql', 'shBrushVb', 'shBrushXml'
        ];
        foreach ($scripts as $script) {
            $path = "/qa-content/javascript/{$script}.js?v=" . QA_RESOURCE_VERSION;
            $scriptsOutput .= '<script src="' . $path . '" defer></script>';
        }

        $path = '/qa-plugin/ckeditor4/plugins/syntaxhighlight/init.js?v=' . QA_RESOURCE_VERSION;
        $scriptsOutput .= '<script src="' . $path . '" defer></script>';

        $this->output($scriptsOutput);
    }
}
