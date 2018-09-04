<?php

	class qa_html_theme_layer extends qa_html_theme_base {

		var $smilies = array();
		var $idx = 0;

	// init
	
		function doctype() {
			if (qa_opt('embed_smileys')) { 
				$this->smilies = array(
					'(finger)' => array('name'=>'Finger','static'=>'images/emoticon-00173-middlefinger.png','animated'=>'images/emoticon-00173-middlefinger.gif'),
					'(bandit)' => array('name'=>'Bandit','static'=>'images/emoticon-00174-bandit.png','animated'=>'images/emoticon-00174-bandit.gif'),
					'(drunk)' => array('name'=>'Drunk','static'=>'images/emoticon-00175-drunk.png','animated'=>'images/emoticon-00175-drunk.gif'),
					'(smoking)' => array('name'=>'Smoking','static'=>'images/emoticon-00176-smoke.png','animated'=>'images/emoticon-00176-smoke.gif'),
					'(toivo)' => array('name'=>'Toivo','static'=>'images/emoticon-00177-toivo.png','animated'=>'images/emoticon-00177-toivo.gif'),
					'(rock)' => array('name'=>'Rock','static'=>'images/emoticon-00178-rock.png','animated'=>'images/emoticon-00178-rock.gif'),
					'(headbang)' => array('name'=>'Headbang','static'=>'images/emoticon-00179-headbang.png','animated'=>'images/emoticon-00179-headbang.gif'),
					'(bug)' => array('name'=>'Bug','static'=>'images/emoticon-00180-bug.png','animated'=>'images/emoticon-00180-bug.gif'),
					'(fubar)' => array('name'=>'Fubar','static'=>'images/emoticon-00181-fubar.png','animated'=>'images/emoticon-00181-fubar.gif'),
					'(poolparty)' => array('name'=>'Poolparty','static'=>'images/emoticon-00182-poolparty.png','animated'=>'images/emoticon-00182-poolparty.gif'),
					'(swear)' => array('name'=>'Swearing','static'=>'images/emoticon-00183-swear.png','animated'=>'images/emoticon-00183-swear.gif'),
					'(tmi)' => array('name'=>'TMI','static'=>'images/emoticon-00184-tmi.png','animated'=>'images/emoticon-00184-tmi.gif'),
					'(heidy)' => array('name'=>'Heidy','static'=>'images/emoticon-00185-heidy.png','animated'=>'images/emoticon-00185-heidy.gif'),
					'(mooning)' => array('name'=>'Mooning','static'=>'images/emoticon-00172-mooning.png','animated'=>'images/emoticon-00172-mooning.gif'),
					'(highfive)' => array('name'=>'High Five','static'=>'images/highfive.png','animated'=>'images/highfive.png'),
					'(facepalm)' => array('name'=>'Face Palm','static'=>'images/facepalm.png','animated'=>'images/facepalm.png'),
					'(fingers)' => array('name'=>'Fingers Crossed','static'=>'images/fingerscrossed.png','animated'=>'images/fingerscrossed.png'),
					'(lalala)' => array('name'=>'Lalala','static'=>'images/lalala.png','animated'=>'images/lalala.png'),
					'(waiting)' => array('name'=>'Waiting','static'=>'images/waiting.png','animated'=>'images/waiting.png'),
					'(tumbleweed)' => array('name'=>'Tumbleweed','static'=>'images/tumbleweed.png','animated'=>'images/tumbleweed.png'),
					'(wfh)' => array('name'=>'Working From Home','static'=>'images/wfh.png','animated'=>'images/wfh.png'),
					':)' => array('name'=>'Smile','static'=>'images/emoticon-00100-smile.png','animated'=>'images/emoticon-00100-smile.gif'),
					':(' => array('name'=>'Sad Smile','static'=>'images/emoticon-00101-sadsmile.png','animated'=>'images/emoticon-00101-sadsmile.gif'),
					':D' => array('name'=>'Big Smile','static'=>'images/emoticon-00102-bigsmile.png','animated'=>'images/emoticon-00102-bigsmile.gif'),
					'8-)' => array('name'=>'Cool','static'=>'images/emoticon-00103-cool.png','animated'=>'images/emoticon-00103-cool.gif'),
					':o' => array('name'=>'Wink','static'=>'images/emoticon-00105-wink.png','animated'=>'images/emoticon-00105-wink.gif'),
					';(' => array('name'=>'Crying','static'=>'images/emoticon-00106-crying.png','animated'=>'images/emoticon-00106-crying.gif'),
					'(sweat)' => array('name'=>'Sweating','static'=>'images/emoticon-00107-sweating.png','animated'=>'images/emoticon-00107-sweating.gif'),
					':|' => array('name'=>'Speechless','static'=>'images/emoticon-00108-speechless.png','animated'=>'images/emoticon-00108-speechless.gif'),
					':*' => array('name'=>'Kiss','static'=>'images/emoticon-00109-kiss.png','animated'=>'images/emoticon-00109-kiss.gif'),
					':P' => array('name'=>'Tongue Out','static'=>'images/emoticon-00110-tongueout.png','animated'=>'images/emoticon-00110-tongueout.gif'),
					'(blush)' => array('name'=>'Blush','static'=>'images/emoticon-00111-blush.png','animated'=>'images/emoticon-00111-blush.gif'),
					':^)' => array('name'=>'Wondering','static'=>'images/emoticon-00112-wondering.png','animated'=>'images/emoticon-00112-wondering.gif'),
					'|-)' => array('name'=>'Sleepy','static'=>'images/emoticon-00113-sleepy.png','animated'=>'images/emoticon-00113-sleepy.gif'),
					'|(' => array('name'=>'Dull','static'=>'images/emoticon-00114-dull.png','animated'=>'images/emoticon-00114-dull.gif'),
					'(inlove)' => array('name'=>'In love','static'=>'images/emoticon-00115-inlove.png','animated'=>'images/emoticon-00115-inlove.gif'),
					']:)' => array('name'=>'Evil grin','static'=>'images/emoticon-00116-evilgrin.png','animated'=>'images/emoticon-00116-evilgrin.gif'),
					'(talk)' => array('name'=>'Talking','static'=>'images/emoticon-00117-talking.png','animated'=>'images/emoticon-00117-talking.gif'),
					'(yawn)' => array('name'=>'Yawn','static'=>'images/emoticon-00118-yawn.png','animated'=>'images/emoticon-00118-yawn.gif'),
					'(puke)' => array('name'=>'Puke','static'=>'images/emoticon-00119-puke.png','animated'=>'images/emoticon-00119-puke.gif'),
					'(doh)' => array('name'=>'Doh!','static'=>'images/emoticon-00120-doh.png','animated'=>'images/emoticon-00120-doh.gif'),
					':@' => array('name'=>'Angry','static'=>'images/emoticon-00121-angry.png','animated'=>'images/emoticon-00121-angry.gif'),
					'(wasntme)' => array('name'=>'It wasn\'t me','static'=>'images/emoticon-00122-itwasntme.png','animated'=>'images/emoticon-00122-itwasntme.gif'),
					'(party)' => array('name'=>'Party!!!','static'=>'images/emoticon-00123-party.png','animated'=>'images/emoticon-00123-party.gif'),
					':S' => array('name'=>'Worried','static'=>'images/emoticon-00124-worried.png','animated'=>'images/emoticon-00124-worried.gif'),
					'(mm)' => array('name'=>'Mmm...','static'=>'images/emoticon-00125-mmm.png','animated'=>'images/emoticon-00125-mmm.gif'),
					'8-|' => array('name'=>'Nerd','static'=>'images/emoticon-00126-nerd.png','animated'=>'images/emoticon-00126-nerd.gif'),
					':x' => array('name'=>'Lips Sealed','static'=>'images/emoticon-00127-lipssealed.png','animated'=>'images/emoticon-00127-lipssealed.gif'),
					'(hi)' => array('name'=>'Hi','static'=>'images/emoticon-00128-hi.png','animated'=>'images/emoticon-00128-hi.gif'),
					'(call)' => array('name'=>'Call','static'=>'images/emoticon-00129-call.png','animated'=>'images/emoticon-00129-call.gif'),
					'(devil)' => array('name'=>'Devil','static'=>'images/emoticon-00130-devil.png','animated'=>'images/emoticon-00130-devil.gif'),
					'(angel)' => array('name'=>'Angel','static'=>'images/emoticon-00131-angel.png','animated'=>'images/emoticon-00131-angel.gif'),
					'(envy)' => array('name'=>'Envy','static'=>'images/emoticon-00132-envy.png','animated'=>'images/emoticon-00132-envy.gif'),
					'(wait)' => array('name'=>'Wait','static'=>'images/emoticon-00133-wait.png','animated'=>'images/emoticon-00133-wait.gif'),
					'(bear)' => array('name'=>'Bear','static'=>'images/emoticon-00134-bear.png','animated'=>'images/emoticon-00134-bear.gif'),
					'(makeup)' => array('name'=>'Make-up','static'=>'images/emoticon-00135-makeup.png','animated'=>'images/emoticon-00135-makeup.gif'),
					'(giggle)' => array('name'=>'Covered Laugh','static'=>'images/emoticon-00136-giggle.png','animated'=>'images/emoticon-00136-giggle.gif'),
					'(clap)' => array('name'=>'Clapping Hands','static'=>'images/emoticon-00137-clapping.png','animated'=>'images/emoticon-00137-clapping.gif'),
					'(think)' => array('name'=>'Thinking','static'=>'images/emoticon-00138-thinking.png','animated'=>'images/emoticon-00138-thinking.gif'),
					'(bow)' => array('name'=>'Bow','static'=>'images/emoticon-00139-bow.png','animated'=>'images/emoticon-00139-bow.gif'),
					'(rofl)' => array('name'=>'Rolling on the floor laughing','static'=>'images/emoticon-00140-rofl.png','animated'=>'images/emoticon-00140-rofl.gif'),
					'(whew)' => array('name'=>'Whew','static'=>'images/emoticon-00141-whew.png','animated'=>'images/emoticon-00141-whew.gif'),
					'(happy)' => array('name'=>'Happy','static'=>'images/emoticon-00142-happy.png','animated'=>'images/emoticon-00142-happy.gif'),
					'(smirk)' => array('name'=>'Smirking','static'=>'images/emoticon-00143-smirk.png','animated'=>'images/emoticon-00143-smirk.gif'),
					'(nod)' => array('name'=>'Nodding','static'=>'images/emoticon-00144-nod.png','animated'=>'images/emoticon-00144-nod.gif'),
					'(shake)' => array('name'=>'Shaking','static'=>'images/emoticon-00145-shake.png','animated'=>'images/emoticon-00145-shake.gif'),
					'(punch)' => array('name'=>'Punch','static'=>'images/emoticon-00146-punch.png','animated'=>'images/emoticon-00146-punch.gif'),
					'(emo)' => array('name'=>'Emo','static'=>'images/emoticon-00147-emo.png','animated'=>'images/emoticon-00147-emo.gif'),
					'(y)' => array('name'=>'Yes','static'=>'images/emoticon-00148-yes.png','animated'=>'images/emoticon-00148-yes.gif'),
					'(n)' => array('name'=>'No','static'=>'images/emoticon-00149-no.png','animated'=>'images/emoticon-00149-no.gif'),
					'(handshake)' => array('name'=>'Shaking Hands','static'=>'images/emoticon-00150-handshake.png','animated'=>'images/emoticon-00150-handshake.gif'),
					'(skype)' => array('name'=>'Skype','static'=>'images/emoticon-00151-skype.png','animated'=>'images/emoticon-00151-skype.gif'),
					'(h)' => array('name'=>'Heart','static'=>'images/emoticon-00152-heart.png','animated'=>'images/emoticon-00152-heart.gif'),
					'(u)' => array('name'=>'Broken heart','static'=>'images/emoticon-00153-brokenheart.png','animated'=>'images/emoticon-00153-brokenheart.gif'),
					'(e)' => array('name'=>'Mail','static'=>'images/emoticon-00154-mail.png','animated'=>'images/emoticon-00154-mail.gif'),
					'(f)' => array('name'=>'Flower','static'=>'images/emoticon-00155-flower.png','animated'=>'images/emoticon-00155-flower.gif'),
					'(rain)' => array('name'=>'Rain','static'=>'images/emoticon-00156-rain.png','animated'=>'images/emoticon-00156-rain.gif'),
					'(sun)' => array('name'=>'Sun','static'=>'images/emoticon-00157-sun.png','animated'=>'images/emoticon-00157-sun.gif'),
					'(o)' => array('name'=>'Time','static'=>'images/emoticon-00158-time.png','animated'=>'images/emoticon-00158-time.gif'),
					'(music)' => array('name'=>'Music','static'=>'images/emoticon-00159-music.png','animated'=>'images/emoticon-00159-music.gif'),
					'(~)' => array('name'=>'Movie','static'=>'images/emoticon-00160-movie.png','animated'=>'images/emoticon-00160-movie.gif'),
					'(mp)' => array('name'=>'Phone','static'=>'images/emoticon-00161-phone.png','animated'=>'images/emoticon-00161-phone.gif'),
					'(coffee)' => array('name'=>'Coffee','static'=>'images/emoticon-00162-coffee.png','animated'=>'images/emoticon-00162-coffee.gif'),
					'(pizza)' => array('name'=>'Pizza','static'=>'images/emoticon-00163-pizza.png','animated'=>'images/emoticon-00163-pizza.gif'),
					'(cash)' => array('name'=>'Cash','static'=>'images/emoticon-00164-cash.png','animated'=>'images/emoticon-00164-cash.gif'),
					'(muscle)' => array('name'=>'Muscle','static'=>'images/emoticon-00165-muscle.png','animated'=>'images/emoticon-00165-muscle.gif'),
					'(^)' => array('name'=>'Cake','static'=>'images/emoticon-00166-cake.png','animated'=>'images/emoticon-00166-cake.gif'),
					'(beer)' => array('name'=>'Beer','static'=>'images/emoticon-00167-beer.png','animated'=>'images/emoticon-00167-beer.gif'),
					'(d)' => array('name'=>'Drink','static'=>'images/emoticon-00168-drink.png','animated'=>'images/emoticon-00168-drink.gif'),
					'(dance)' => array('name'=>'Dance','static'=>'images/emoticon-00169-dance.png','animated'=>'images/emoticon-00169-dance.gif'),
					'(ninja)' => array('name'=>'Ninja','static'=>'images/emoticon-00170-ninja.png','animated'=>'images/emoticon-00170-ninja.gif'),
					'(*)' => array('name'=>'Star','static'=>'images/emoticon-00171-star.png','animated'=>'images/emoticon-00171-star.gif')
				);			
			}
			qa_html_theme_base::doctype();
		}

	// theme replacement functions

		function head_custom() {
			if(qa_opt('embed_smileys') && qa_opt('embed_smileys_markdown_button')) {
				$this->output('<style>',qa_opt('embed_smileys_css'),'</style>');
				$this->output('<script>',
				"
				function toggleSmileyBox(idx) {
					jQuery('#smiley-box'+idx).toggle();
				}
				function insertSmiley(code,img,idx) {
					var el = jQuery('textarea[index=\"'+idx+'\"]');
					el.val(el.val()+code);
					toggleSmileyBox(idx);
				}
				"
				,'</script>');
			}
			qa_html_theme_base::head_custom();
		}
		function form($form) {
			if(qa_opt('embed_smileys') && isset($form['hidden']) && isset($form['id'])) {
				$id = substr($form['id'],0,1);
				if($id == 'c')
					$id = $form['id'];
				$editor = @$form['hidden'][$id.'_editor'];
				if($editor === null)
					return qa_html_theme_base::form($form);
				if($editor == "" && qa_opt('embed_smileys_editor_button')) {
					@$form['fields']['content']['tags'] .= ' index="'.$this->idx.'"';
					$smileybox = $this->makeSmileyBox();
					$form['fields'] = array_merge(
						array(
							'smileys' => array(
								"type" => "custom",
								"html" => '<div class="smiley-button" id="smiley-button'.$this->idx.'" title="Add emoticon" onclick="toggleSmileyBox('.$this->idx.')"><img src="'.QA_HTML_THEME_LAYER_URLTOROOT.'images/emoticon-00100-smile.gif"/></div>'.$smileybox,
							)
						),
						$form['fields']
					);
					$this->idx++;
				}
				else if($editor == "Markdown Editor" && qa_opt('embed_smileys_markdown_button')) {
					@$form['fields']['content']['tags'] .= ' index="'.$this->idx.'"';
					$smileybox = $this->makeSmileyBox();
					$form['fields']['content']['html'] = str_replace('class="wmd-button-bar"></div>','class="wmd-button-bar"><div class="smiley-button" id="smiley-button'.$this->idx.'" title="Add emoticon" onclick="toggleSmileyBox('.$this->idx.')"><img src="'.QA_HTML_THEME_LAYER_URLTOROOT.'images/emoticon-00100-smile.gif"/></div>'.$smileybox.'</div>',$form['fields']['content']['html']);
					$form['fields']['content']['html'] = str_replace('<textarea','<textarea index="'.$this->idx.'"',$form['fields']['content']['html']);
					$this->idx++;
				}
			}
			qa_html_theme_base::form($form);
		}
		

		function q_view_content($q_view)
		{
			if (qa_opt('embed_smileys') && isset($q_view['content'])){
				$q_view['content'] = $this->smiley_replace($q_view['content']);
			}
			qa_html_theme_base::q_view_content($q_view);
		}
		function a_item_content($a_item)
		{
			if (qa_opt('embed_smileys') && isset($a_item['content'])) {
				$a_item['content'] = $this->smiley_replace($a_item['content']);
			}
			qa_html_theme_base::a_item_content($a_item);
		}
		function c_item_content($c_item)
		{
			if (qa_opt('embed_smileys') && isset($c_item['content'])) {
				$c_item['content'] = $this->smiley_replace($c_item['content']);
			}
			qa_html_theme_base::c_item_content($c_item);
		}

	// worker functions

		function smiley_replace($text) {
			
			// remove tags
			
			preg_match_all('/<[^>]+>/', $text, $tags);
			$idx = 0;
			while(preg_match('/<[^>]*[^>0-9][^>]*>/',$text) > 0)
				$text = preg_replace('/<[^>]*[^>0-9][^>]*>/', '<'.($idx++).'>', $text,1);

			// replace smilies

			foreach($this->smilies as $t => $r) {
				
				$url = (qa_opt('embed_smileys_animated')?$r['animated']:$r['static']);
				$text = str_replace($t,'<img src="'.QA_HTML_THEME_LAYER_URLTOROOT.$url.'"/>',$text);
				
			}
			
			// restore tags
			
			foreach($tags[0] as $idx => $tag) {
				$text = str_replace('<'.$idx.'>',$tag,$text);
			}
				
			return $text;
		}
		function makeSmileyBox() {
			$smileybox = '<div class="smiley-box" id="smiley-box'.$this->idx.'">';
			foreach($this->smilies as $c => $d) {
				$url = (qa_opt('embed_smileys_animated')?$d['animated']:$d['static']);
				$smileybox.='<img title="'.$c.'" class="smiley-child" onclick="insertSmiley(\''.$c.'\',\''.QA_HTML_THEME_LAYER_URLTOROOT.$url.'\','.$this->idx.');" src="'.QA_HTML_THEME_LAYER_URLTOROOT.$url.'"/>';
			}
			$smileybox.='</div>';
			return $smileybox;
		}
	}

