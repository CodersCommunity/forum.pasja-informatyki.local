<?php
    class qa_embed_admin {

	function option_default($option) {
		
	    switch($option) {
		case 'embed_video_width':
		case 'embed_image_width':
		case 'embed_gmap_width':
		    return 425;
		case 'embed_video_height':
		case 'embed_image_height':
		case 'embed_gmap_height':
		    return 349;
		case 'embed_thickbox_thumb':
		    return 64;
		case 'embed_mp3_player_code':
		    return '<object type="application/x-shockwave-flash" data="http://flash-mp3-player.net/medias/player_mp3_mini.swf" width="200" height="20"><param name="movie" value="http://flash-mp3-player.net/medias/player_mp3_mini.swf" /><param name="bgcolor" value="#000000" /><param name="FlashVars" value="mp3=$1" /></object>';
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
		
		if (qa_clicked('embed_save')) {
			qa_opt('embed_enable',(bool)qa_post_text('embed_enable'));
			qa_opt('embed_video_width',qa_post_text('embed_video_width'));
			qa_opt('embed_video_height',qa_post_text('embed_video_height'));
			qa_opt('embed_enable_img',(bool)qa_post_text('embed_enable_img'));
			qa_opt('embed_image_width',qa_post_text('embed_image_width'));
			qa_opt('embed_image_height',qa_post_text('embed_image_height'));
			qa_opt('embed_enable_thickbox',(bool)qa_post_text('embed_enable_thickbox'));
			qa_opt('embed_enable_mp3',(bool)qa_post_text('embed_enable_mp3'));
			qa_opt('embed_enable_gmap',(bool)qa_post_text('embed_enable_gmap'));
			qa_opt('embed_mp3_player_code', qa_post_text('embed_mp3_player_code'));
			$ok = qa_lang('admin/options_saved');
		}
  	    else if (qa_clicked('embed_reset')) {
			foreach($_POST as $i => $v) {
				$def = $this->option_default($i);
				if($def !== null) qa_opt($i,$def);
			}
			$ok = qa_lang('admin/options_reset');
	    }
	    qa_set_display_rules($qa_content, array(
		    'embed_video_height' => 'embed_enable',
		    'embed_video_width' => 'embed_enable',
	    ));
                    
        // Create the form for display

            
		$fields = array();
		
		$fields[] = array(
			'label' => 'Enable video embedding',
			'tags' => 'NAME="embed_enable"',
			'value' => qa_opt('embed_enable'),
			'type' => 'checkbox',
		);
	    $fields[] = array(
			'label' => 'Embeded video width',
			'type' => 'number',
			'value' => qa_opt('embed_video_width'),
			'tags' => 'NAME="embed_video_width"',
	    );                    
	    $fields[] = array(
			'label' => 'Embeded video height',
			'type' => 'number',
			'value' => qa_opt('embed_video_height'),
			'tags' => 'NAME="embed_video_height"',
	    );                    
            
		$fields[] = array(
			'type' => 'blank',
		);
		
		
		$fields[] = array(
			'label' => 'Enable image embedding',
			'tags' => 'NAME="embed_enable_img"',
			'value' => qa_opt('embed_enable_img'),
			'type' => 'checkbox',
		);
            
 	    $fields[] = array(
		'label' => 'Image width',
		'type' => 'number',
		'value' => qa_opt('embed_image_width'),
		'tags' => 'NAME="embed_image_width"',
	    );                    
	    $fields[] = array(
		'label' => 'Image height',
		'type' => 'number',
		'value' => qa_opt('embed_image_height'),
		'tags' => 'NAME="embed_image_height"',
	    ); 
		$fields[] = array(
			'label' => 'Enable thickbox image effect',
			'tags' => 'NAME="embed_enable_thickbox"',
			'value' => qa_opt('embed_enable_thickbox'),
			'type' => 'checkbox',
		);
	               
		$fields[] = array(
			'type' => 'blank',
		);

		$fields[] = array(
			'label' => 'Enable mp3 embedding',
			'tags' => 'NAME="embed_enable_mp3"',
			'value' => qa_opt('embed_enable_mp3'),
			'type' => 'checkbox',
		);
		
		$fields[] = array(
			'label' => 'mp3 flash player code',
			'tags' => 'NAME="embed_mp3_player_code"',
			'value' => qa_opt('embed_mp3_player_code'),
			'type' => 'textarea',
			'rows' => '5',
		);

		$fields[] = array(
			'type' => 'blank',
		);
		
		
		$fields[] = array(
			'label' => 'Enable Google maps embedding',
			'tags' => 'NAME="embed_enable_gmap"',
			'value' => qa_opt('embed_enable_gmap'),
			'type' => 'checkbox',
		);
            
 	    $fields[] = array(
		'label' => 'Map width',
		'type' => 'number',
		'value' => qa_opt('embed_gmap_width'),
		'tags' => 'NAME="embed_gmap_width"',
	    );                    
	    $fields[] = array(
		'label' => 'Map height',
		'type' => 'number',
		'value' => qa_opt('embed_gmap_height'),
		'tags' => 'NAME="embed_gmap_height"',
	    ); 

		return array(           
			'ok' => ($ok && !isset($error)) ? $ok : null,
				
			'fields' => $fields,
		 
			'buttons' => array(
				array(
					'label' => qa_lang_html('main/save_button'),
					'tags' => 'NAME="embed_save"',
				),
				array(
					'label' => qa_lang_html('admin/reset_options_button'),
					'tags' => 'NAME="embed_reset"',
				)
			),
		);
	}
}

