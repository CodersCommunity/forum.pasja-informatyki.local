<?php
    class qa_smilies_admin {

	function option_default($option) {
		
	    switch($option) {
			case 'embed_smileys_css':
				return '
				.smiley-button {
					cursor:pointer !important;
				}
				.smiley-box {
					background: none repeat scroll 0 0 rgba(255, 255, 255, 0.8) !important;
					border: 1px solid black !important;
					padding: 10px !important;
					display: none;
					width: 378px;
					margin: 7px 0 0 20px;
					z-index: 1000 !important;
					position: absolute !important;
				}
				.wmd-button-bar{
					min-height:16px;
					width:auto !important;
					margin-right:36px !important;
					position:relative !important;
				}
				.wmd-button-bar .smiley-button {
					position:absolute !important;
					right:-25px !important;
					top:3px !important;
				}
				.wmd-button-bar	.smiley-box {
					margin: 24px 0 0 169px !important;
				}
				.smiley-child {
					margin:4px !important;
					cursor:pointer !important;
				}
				';
			default:
				return null;				
		}
		
	}
        
        function allow_template($template)
        {
            return ($template!='admin');
        }       
            
        function admin_form(&$qa_content)
        {                       
                            
        // Process form input
            
            $ok = null;
            
            if (qa_clicked('smilies_save')) {
                qa_opt('embed_smileys',(bool)qa_post_text('embed_smileys'));
                qa_opt('embed_smileys_animated',(bool)qa_post_text('embed_smileys_animated'));
                qa_opt('embed_smileys_editor_button',(bool)qa_post_text('embed_smileys_editor_button'));
                qa_opt('embed_smileys_markdown_button',(bool)qa_post_text('embed_smileys_markdown_button'));
                qa_opt('embed_smileys_css',qa_post_text('embed_smileys_css'));
                $ok = 'Settings Saved.';
            }
  
	    qa_set_display_rules($qa_content, array(
		    'embed_smileys_animated' => 'embed_smileys',
	    ));
                    
        // Create the form for display

            
            $fields = array();
            
            $fields[] = array(
                'label' => 'Enable smiley embedding',
                'tags' => 'NAME="embed_smileys"',
                'value' => qa_opt('embed_smileys'),
                'type' => 'checkbox',
            );
            $fields[] = array(
                'label' => 'Use animated smilies where available',
                'tags' => 'NAME="embed_smileys_animated"',
                'value' => qa_opt('embed_smileys_animated'),
                'type' => 'checkbox',
                'note' => 'For a complete list of smilies, visit <a href="http://www.skype-emoticons.com/">this page</a>.',
            );
            
            $fields[] = array(
                'label' => 'Add smiley button to ordinary editor',
                'tags' => 'NAME="embed_smileys_editor_button"',
                'value' => qa_opt('embed_smileys_editor_button'),
                'type' => 'checkbox',
            );
            
            
            $fields[] = array(
                'label' => 'Add smiley button to markdown editor',
                'tags' => 'NAME="embed_smileys_markdown_button"',
                'value' => qa_opt('embed_smileys_markdown_button'),
                'type' => 'checkbox',
                'note' => 'Requires markdown editor plugin, available <a href="http://codelair.co.uk/2011/markdown-editor-plugin-q2a/">here</a>.',
            );
            
            
            $fields[] = array(
                'type' => 'blank',
            );
            
            
            $fields[] = array(
                'label' => 'Smiley CSS',
                'tags' => 'NAME="embed_smileys_css"',
                'value' => qa_opt('embed_smileys_css'),
                'type' => 'textarea',
                'rows' => '20'
            );
            

            return array(           
                'ok' => ($ok && !isset($error)) ? $ok : null,
                    
                'fields' => $fields,
             
                'buttons' => array(
                    array(
                        'label' => 'Save',
                        'tags' => 'NAME="smilies_save"',
                    )
                ),
            );
        }
    }

