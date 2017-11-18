<?php

class tips_widget_admin
{
	function option_default($option)
	{
		switch ($option)
		{
			case 'tips-enable':
				return 0;
			case 'tips-widget-content':
				return <<< TAG
Publikując kody źródłowe korzystaj ze specjalnego bloczku koloryzującego składnię (przycisk z napisem <b>code</b> w edytorze).
!NEW!
Komentarze do pytań nie służą do odpowiadania, od tego jest wydzielona sekcja odpowiedzi. Funkcją komentarzy jest natomiast możliwość uzyskania dodatkowych informacji na temat samego posta.
!NEW!
Zadając pytanie postaraj się o poprawną pisownię i czytelne formatowanie tekstu.
!NEW!
Zadając pytanie postaraj się o szczegółowe opisanie problemu oraz udostępnienie wszystkich istotnych informacji (kody źródłowe, zrzuty ekranu itp.).
!NEW!
Zadając pytanie postaraj się o odpowiedni <b>tytuł</b>, <b>kategorię</b> oraz <b>tagi</b>.
!NEW!
Nie wiesz jak poprawnie zredagować pytanie lub pragniesz poznać którąś z funkcji forum? Odwiedź podstronę <a href="./faq">Pomoc (FAQ)</a> dostępną w menu pod ikoną apteczki.
!NEW!
Forum posiada swój własny <a href="./chat-irc">chat IRC</a>, dzięki któremu będziesz mógł po prostu pogadać z innymi Pasjonatami lub zapytać o jakiś problem. Podstrona z chatem znajduje się w menu pod ikoną człowieka w dymku.
!NEW!
Odznacz odpowiedź zieloną fajką, jeśli uważasz, że jest ona najlepsza ze wszystkich i umożliwiła ci rozwiązanie problemu.
!NEW!
Pytania na temat serwisu SPOJ należy zadawać z odpowiednią kategorią dotyczącą tej strony.
TAG;
			case 'tips-page-content':
				return <<< TAG
<p>Cieszymy się, że postanowiłeś przeczytać porady na temat używania forum.
Zostały one stworzone z myślą o poprawie jakości publikowanych tutaj treści.
Jeśli masz propozycje co do kolejnych porad, <a href="./feedback">napisz do nas</a>.</p>
<p>Oto porady, które powinny cię zainteresować:</p>!TIPS!
TAG;
		}
	}
	
	function admin_form(&$qa_content)
	{
		// Process form input
		
		$saved = qa_clicked('tips-save');
		
		if ($saved)
		{
			qa_opt('tips-enable', (bool)qa_post_text('tips-enable'));
		
			qa_opt('tips-widget-content', qa_post_text('tips-widget-content'));
			qa_opt('tips-page-content', qa_post_text('tips-page-content'));
		}
		
		// Create form for admin
		
		$fields = [];
		
		$fields[] = [
			'label' => 'Enable plugin',
			'tags'  => 'NAME="tips-enable"',
			'value' =>  qa_opt('tips-enable'),
			'type'  => 'checkbox'
		];
		
		$fields[] = [
			'label' => 'Enter tips. Separate them with !NEW! phrase',
			'tags'  => 'NAME="tips-widget-content"',
			'value' => qa_opt('tips-widget-content'),
			'rows'  =>  20,
			'type'  => 'textarea'
		];
		
		$fields[] = [
			'label' => 'Enter page content. Mark a place to insert tips list with !TIPS! phrase',
			'tags'  => 'NAME="tips-page-content"',
			'value' => qa_opt('tips-page-content'),
			'rows'  =>  20,
			'type'  => 'textarea'
		];
		
		$form = [
			'ok'      => $saved ? 'Save completed' : null,
			'fields'  => $fields,
			'buttons' => [[
				'label' => 'Save',
				'tags' => 'NAME="tips-save"'
			]]
		];
		
		return $form;
	}
}
