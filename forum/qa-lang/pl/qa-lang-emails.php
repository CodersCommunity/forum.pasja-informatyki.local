<?php
/*
	Question2Answer by Gideon Greenspan and contributors
	http://www.question2answer.org/

	File: qa-include/qa-lang-emails.php
	Description: Language phrases for email notifications


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	return array(
		'a_commented_body' => "Użytkownik ^c_handle skomentował Twoją odpowiedź w serwisie ^site_title:\n\n^open^c_content^close\n\nTwoja odpowiedź:\n\n^open^c_context^close\n\nMożesz odpowiedzieć dodając swój komentarz:\n\n^url\n\nSerwis ^site_title",
		'a_commented_subject' => 'Skomentowano Twoją odpowiedź w serwisie ^site_title',

		'a_followed_body' => "Twoja odpowiedź w serwisie ^site_title ma nowe powiązane pytanie od użytkownika ^q_handle:\n\n^open^q_title^close\n\nTwoja oryginalna odpowiedź:\n\n^open^a_content^close\n\nKliknij poniżej, aby odpowiedzieć na nowe pytanie:\n\n^url\n\nSerwis ^site_title",
		'a_followed_subject' => 'Twoja odpowiedź w serwisie ^site_title ma nowe powiązane pytanie',

		'a_selected_body' => "Gratulujemy! Twoja odpowiedź w serwisie ^site_title została wybrana jako najlepsza przez użytkownika ^s_handle:\n\n^open^a_content^close\n\nPytanie brzmiało:\n\n^open^q_title^close\n\nKliknij poniżej, aby zobaczyć swoją odpowiedź:\n\n^url\n\nSerwis ^site_title",
		'a_selected_subject' => 'Twoja odpowiedź w serwisie ^site_title wybrana!',

		'c_commented_body' => "Użytkownik ^c_handle dodał komentarz w serwisie ^site_title:\n\n^open^c_content^close\n\nObecna dyskusja:\n\n^open^c_context^close\n\nMożesz odpowiedzieć dodając swój komentarz:\n\n^url\n\nSerwis ^site_title",
		'c_commented_subject' => 'Twój komentarz w serwisie ^site_title został dodany',

		'confirm_body' => "Kliknij w poniższy link, aby potwierdzić swój adres email w serwisie ^site_title.\n\n^url\n\nSerwis ^site_title",
		'confirm_subject' => 'Potwierdzenie adresu email w serwisie ^site_title',

		'feedback_body' => "Treść:\n^message\n\nImię i nazwisko:\n^name\n\nEmail:\n^email\n\nPoprzednia strona:\n^previous\n\nUżytkownik:\n^url\n\nAdres IP:\n^ip\n\nPrzeglądarka:\n^browser",
		'feedback_subject' => 'Formularz kontaktowy serwisu ^',

		'flagged_body' => "Treść dodana przez użytkownika ^p_handle została zgłoszona ^flags razy:\n\n^open^p_context^close\n\nKliknij poniżej, aby zobaczyć treść:\n\n^url\n\n\nKliknij poniżej, aby zobaczyć wszystki oznaczone wpisy:\n\n^a_url\n\n\nDziękujemy,\n\n^site_title", 

		'flagged_subject' => 'Zgłoszona treść w serwisie ^site_title',

		'moderate_body' => "Treść dodana przez użytkownika ^p_handle wymaga Twojego zatwierdzenia:\n\n^open^p_context^close\n\nKliknij poniżej, aby ją zatwierdzić lub odrzucić:\n\n^url\n\n\nKliknij poniżej, aby zobaczysz wszystkie wpisy w kolejce:\n\n^a_url\n\n\nDziękujemy,\n\n^site_title", 
		'moderate_subject' => 'Moderacja serwisu ^site_title',

		'new_password_body' => "Twoje nowe hasło w serwisie ^site_title:\n^password\n\nZalecamy zmianę tego hasła zaraz po zalogowaniu w serwisie.\n\nSerwis ^site_title\n^url",
		'new_password_subject' => 'Nowe hasło w serwisie ^site_title',

		'private_message_body' => "Prywatna wiadomość od użytkownika ^f_handle w serwisie ^site_title:\n\n^open^message^close\n\n^moreSerwis ^site_title\n\n\nAby zablokować prywatne wiadomości, przejdź do swojej strony użytkownika:\n^a_url",
		'private_message_info' => "Więcej informacji o użytkowniku ^f_handle:\n\n^url\n\n",
		'private_message_reply' => "Kliknij poniżej, aby wysłać prywatną wiadomość do użytkownika ^f_handle:\n\n^url\n\n",
		'private_message_subject' => 'Wiadomość od użytkownika ^f_handle w serwisie ^site_title',

		'q_answered_body' => "Użytkownik ^a_handle odpowiedział na Twoje pytanie w serwisie ^site_title:\n\n^open^a_content^close\n\nTwoje pytanie:\n\n^open^q_title^close\n\nJeśli satysfakcjonuje Cię ta odpowiedź, możesz wybrać ją jako najlepszą:\n\n^url\n\nMożesz pomóc innym użytkownikom odpowiadając na ich pytania.\n\nSerwis ^site_title",
		'q_answered_subject' => 'Odpowiedź na Twoje pytanie w serwisie ^site_title',

		'q_commented_body' => "Użytkownik ^c_handle skomentował Twoje pytanie w serwisie ^site_title:\n\n^open^c_content^close\n\nTwoje pytanie:\n\n^open^c_context^close\n\nMożesz odpowiedzieć dodając swój komentarz:\n\n^url\n\nSerwis ^site_title",
		'q_commented_subject' => 'Nowy komentarz do Twojego pytania w serwisie ^site_title',

		'q_posted_body' => "Użytkownik ^q_handle zadał nowe pytanie:\n\n^open^q_title\n\n^q_content^close\n\nKliknij poniżej, aby zobaczyć to pytanie:\n\n^url\n\nSerwis ^site_title",
		'q_posted_subject' => '^site_title - nowe pytanie',

		'reset_body' => "Kliknij poniższy link, aby wyzerować swoje hasło w serwisie ^site_title.\n\n^url\n\nMożesz też wpisać poniższy kod na stronie potwierdzenia.\n\nKod: ^code\n\nJeśli prośba przypomnienia hasła nie pochodzi od Ciebie, prosimy zignorować tę wiadomość.\n\nSerwis ^site_title",
		'reset_subject' => '^site_title - wyzerowanie zapomnianego hasła',

		'to_handle_prefix' => "^,\n\n",

		'welcome_body' => "Dziękujemy Ci ^handle za rejestrację w serwisie ^site_title.\n\n^custom^confirmTwoje dane logowania:\n\nEmail: ^email\nHasło: ^password\n\nZapamiętaj lub zapisz te informacje. Będą Ci potrzebne do logowania.\n\nDziękujemy,\n\n^site_title\n^url",
		'welcome_confirm' => "Kliknij w poniższy link, aby potwierdzić swój adres email.\n\n^url\n\n",
		'welcome_subject' => 'Witaj w serwisie ^site_title!',
		'remoderate_body' => "Edytowany post wymaga ^p_handle zatwierdzenia:\n\n^open^p_context^close\n\nKliknij poniżej aby zatwierdzić lub schować edytowany post:\n\n^url\n\n\nKliknij poniżej aby przejżeć wszystkie oczekujące wiadomości:\n\n^a_url\n\n\nDziękuje,\n\n^site_title",
		'remoderate_subject' => "^site_title moderation",
		'u_registered_body' => "Nowy użytkownik zarejestrował się jako ^u_handle.\n\nKliknij poniżej aby zobaczyć profil użytkownika :\n\n^url\n\nDziękuje,\n\n^site_title",
		'u_to_approve_body' => "Nowy użytkownik zarejestrował się jako ^u_handle.\n\nKliknij poniżej aby zatwierdzić użytkownika:\n\n^url\n\nKliknij poniżej aby wyświetlić wszystkich użytkowników oczekujących zatwierdzenia:\n\n^a_url\n\nDziękuje,\n\n^site_title",
		'u_registered_subject' => "^site_title rejestracja nowego użytkownika",
		'u_approved_body' => "Możesz zobaczyć swój nowy profil użytkownika tu:\n\n^url\n\nDziękuje,\n\n^site_title",
		'u_approved_subject' => "Twój użytkownika na ^site_title został zatwierdzony",
		'wall_post_subject' => "Wpisa na Twojej ścianie ^site_title",
		'wall_post_body' => "^f_handle dodał wpis na Twojej ścianie ^site_title:\n\n^open^post^close\n\nMożesz odpowiedzieć na post tutaj:\n\n^url\n\nDziękuje,\n\n^site_title",
	);
	

/*
	Omit PHP closing tag to help avoid accidental output
*/