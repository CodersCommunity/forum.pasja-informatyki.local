<?php

return [
    'REASON_LIST' => [
        'SPAM lub reklama',
        'Wypowiedź jest obraźliwa',
        'Nieprawidłowy temat/kategoria/otagowanie',
        'Niepełna lub niezrozumiała treść',
        'Kod nie jest umieszczony w odpowiednim bloczku',
        'Prośba o gotowca',
        'Odpowiedź nie jest na temat lub go odkopuje',
        'Duplikat pytania',
        'Inny (własny)',
    ],
    'CONFIG' => [
        'WRAP_CUSTOM_FLAG_REASON_CONTENT_FROM_LENGTH' => 50,
    ],
    'POPUP_LABELS' => [
        'HEADER' => 'Zaznacz powód zgłoszenia lub podaj własny:',
        'REPORT_REASON_ACCESSIBILITY' => 'Powód zgłoszenia i jego autor będą widoczne tylko i wyłącznie dla <a href="/zasluzeni-pasjonaci-hall-of-fame">Administracji</a>.',
        'CHAR_COUNTER_INFO' => 'pozostało znaków: ',
        'SEND' => 'Wyślij',
        'CANCEL' => 'Anuluj',
        'REPORT_SENT' => 'Zgłoszenie zostało wysłane.',
        'CLOSE' => 'Zamknij',
        'RELOAD' => 'Odśwież',
    ],
    'ERROR_CODES' => [
        'GENERIC_ERROR' => 'Wystąpił nieoczekiwany błąd...<br>Prosimy, powiadom o tym <a href="/zasluzeni-pasjonaci-hall-of-fame">Administrację</a>.',
        'NO_REASON_CHECKED' => 'Nie zaznaczono powodu zgłoszenia.',
        'CUSTOM_REASON_EMPTY' => 'Nie podano własnego powodu zgłoszenia.',
        'UNRECOGNIZED_POST_TYPE' => 'Nierozpoznany typ postu: ',
        'USER_LOGGED_OUT' => 'Użytkownik nie jest zalogowany.',
        'REQUEST_IS_NOT_VALID_JSON' => 'Zgłoszenie nie zawiera danych w formacie JSON.',
        'EMPTY_REQUEST' => 'Zgłoszenie jest puste.',
        'INVALID_SECURITY_CODE' => 'Zgłoszenie zawiera nieprawidłowy kod zabezpieczający.',
        'UNMATCHED_REQUEST_PROP' => 'Zgłoszenie zawiera nieprawidłowe dane.',
        'INCORRECT_REQUEST_PROP_TYPE' => 'Zgłoszenie zawiera dane o nieprawidłowym typie.',
        'INCORRECT_REPORT_TYPE' => 'Zgłoszenie jest nieprawidłowego typu.',
        'REQUEST_NOT_CONTAIN_ALL_PROPS' => 'Zgłoszenie nie zawiera pełnych danych.',
        'INVALID_REASON_ID' => 'Zgłoszenie zawiera nieprawidłowy identyfikator powodu.',
        'INAPPROPRIATE_CUSTOM_REASON_USAGE' => 'Zgłoszenie z własnym powodem zawiera nieprawidłowy identyfikator.',
        'REPORTED_QUESTION_NOT_FOUND' => 'Nie znaleziono pytania, do którego odnosi się zgłoszenie.',
        'REPORTED_ANSWER_NOT_FOUND' => 'Nie znaleziono odpowiedzi, do której odnosi się zgłoszenie.',
        'REPORTED_COMMENT_NOT_FOUND' => 'Nie znaleziono komentarza, do którego odnosi się zgłoszenie.',
        'PAGE_NEEDS_RELOAD' => 'Zgłoszenie posta nie powiodło się.<br>Strona wymaga odświeżenia.',
        'POST_HIDDEN' => 'Nie można zgłosić tej treści.',
        'ALREADY_FLAGGED' => 'Ten post został już przez Ciebie zgłoszony.'
    ]
];
