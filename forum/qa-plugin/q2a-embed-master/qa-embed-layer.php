<?php

	class qa_html_theme_layer extends qa_html_theme_base {

	// theme replacement functions

		function head_custom()
		{
			qa_html_theme_base::head_custom();
			if(qa_opt('embed_enable_thickbox')) { 
				$this->output('<script type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'thickbox.js"></script>');
				$this->output('<link rel="stylesheet" href="'.QA_HTML_THEME_LAYER_URLTOROOT.'thickbox.css" type="text/css" media="screen" />');
			}
		}
		function q_view_content($q_view)
		{
			if (isset($q_view['content'])){
				$q_view['content'] = $this->embed_replace($q_view['content']);
			}
			qa_html_theme_base::q_view_content($q_view);
		}
		function a_item_content($a_item)
		{
			if (isset($a_item['content'])) {
				$a_item['content'] = $this->embed_replace($a_item['content']);
			}
			qa_html_theme_base::a_item_content($a_item);
		}
		function c_item_content($c_item)
		{
			if (isset($c_item['content'])) {
				$c_item['content'] = $this->embed_replace($c_item['content']);
			}
			qa_html_theme_base::c_item_content($c_item);
		}

		function embed_replace($text) {
			
			$w  = qa_opt('embed_video_width');
			
			$h = qa_opt('embed_video_height');
			
			$w2 = qa_opt('embed_image_width');
			
			$h2 = qa_opt('embed_image_height');
			
			$types = array(
				'youtube'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*youtube\.com\/watch\?\S*v=([A-Za-z0-9_-]+)[^< ]*',
						'<div class="video-container"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" allowfullscreen></iframe></div>'
					),
					array(
						'https{0,1}:\/\/w{0,3}\.*youtu\.be\/([A-Za-z0-9_-]+)[^< ]*',
						'<div class="embed-responsive embed-responsive-16by9"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" allowfullscreen></iframe></div>'
					)
				),
				'vimeo'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*vimeo\.com\/([0-9]+)[^< ]*',
						'<div class="video-container"><iframe src="http://player.vimeo.com/video/$1?title=0&amp;byline=0&amp;portrait=0&amp;wmode=transparent" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe></div>')
				),
				'metacafe'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*metacafe\.com\/watch\/([0-9]+)\/([a-z0-9_]+)[^< ]*',
						'<div class="video-container"><embed flashVars="playerVars=showStats=no|autoPlay=no" src="http://www.metacafe.com/fplayer/$1/$2.swf" width="'.$w.'" height="'.$h.'" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_$1" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed></div>'
					)
				),
				'dailymotion'=>array(
					array(
						'https{0,1}:\/\/w{0,3}\.*dailymotion\.com\/video\/([A-Za-z0-9]+)[^< ]*',
						'<div class="video-container"><iframe frameborder="0" width="'.$w.'" height="'.$h.'" src="http://www.dailymotion.com/embed/video/$1?wmode=transparent"></iframe></div>'
					)
				),
				'image'=>array(
					array(
						'(https*:\/\/[-\%_\/.a-zA-Z0-9+]+\.(png|jpg|jpeg|gif|bmp))[^< ]*',
						'<img src="$1" style="max-width:'.$w2.'px;max-height:'.$h2.'px" />','img'
					)
				),
				'mp3'=>array(
					array(
						'(https*:\/\/[-\%_\/.a-zA-Z0-9]+\.mp3)[^< ]*',qa_opt('embed_mp3_player_code'),'mp3'
					)
				),
				'gmap'=>array(
					array(
						'(https*:\/\/maps.google.com\/?[^< ]+)',
						'<iframe width="'.qa_opt('embed_gmap_width').'" height="'.qa_opt('embed_gmap_height').'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="$1&amp;ie=UTF8&amp;output=embed"></iframe><br /><small><a href="$1&amp;ie=UTF8&amp;output=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>','gmap'
					)
				),
			);

			foreach($types as $t => $ra) {
				foreach($ra as $r) {
					if( (!isset($r[2]) && !qa_opt('embed_enable')) || (isset($r[2]) && !qa_opt('embed_enable_'.$r[2])) ) continue;
					
					if(isset($r[2]) && @$r[2] == 'img' && qa_opt('embed_enable_thickbox') && preg_match('/MSIE [5-7]/',$_SERVER['HTTP_USER_AGENT']) == 0) {
						preg_match_all('/'.$r[0].'/',$text,$imga);
						if(!empty($imga)) {
							foreach($imga[1] as $img) {
								$replace = '<a href="'.$img.'" class="thickbox"><img  src="'.$img.'" style="max-width:'.$w2.'px;max-height:'.$h2.'px" /></a>';
								$text = preg_replace('|<a[^>]+>'.$img.'</a>|i',$replace,$text);
								$text = preg_replace('|(?<![\'"=])'.$img.'|i',$replace,$text);
							}
						}
						continue;
					}
					$text = preg_replace('/<a[^>]+>'.$r[0].'<\/a>/i',$r[1],$text);
					$text = preg_replace('/(?<![\'"=])'.$r[0].'/i',$r[1],$text);
				}
			}
			return $text;
		}
	}

