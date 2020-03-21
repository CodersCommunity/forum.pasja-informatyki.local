# forum.pasja-informatyki.local
To repozytorium zawiera skonfigurowane **środowisko deweloperskie** naszego forum - przygotowane przez grupę **CodersCommunity**. Dzięki temu możliwe jest łatwe i szybkie odwzorowanie warunków środowiska produkcyjnego u każdego, kto zechce pomóc przy rozwoju kodu.

# Uruchomienie
Aby móc korzystać z *developerskiego środowiska* wymagane jest posiadanie następującego oprogramowania:

- Docker >= 19.0.0
- Docker Compose >= 1.25

Dockera instalujemy korzystając ze wskazówek dostępnych pod adresem: https://docs.docker.com/install/, a Docker Compose według informacji ze strony: https://docs.docker.com/compose/install/.

Po zainstalowaniu oprogramowania pozostaje nam ściągnąć projekt z GitHuba:
```
git clone https://github.com/CodersCommunity/forum.pasja-informatyki.local.git
```

Następnie uruchamiamy Dockera:
```
docker-compose up -d
```

Alternatywnym sposobem  jest skorzystanie z programu **XAMPP** lub podobnego, oferującego Apache, PHP oraz MySQL. W tym celu kieruj się niniejszą instrukcją: 
https://drive.google.com/file/d/0B_0lVIhwL9LWMjV1eGJyTk0yMTQ/view. Zalecamy jednak skorzystanie z Dockera, aby każdy z nas miał identyczne środowisko.

Gdy mamy już uruchomione kontenery, instalujemy zależności przy użyciu Composera będąc w katalogu `forum`. Możemy to zrobić np. przy użyciu poniższego polecenia:
```
docker-compose exec -u 1000 www bash -c "cd forum && composer install"
```

Teraz należy skopiować plik `forum/qa-config-example.php` do `forum/qa-config.php`. Jest to wymagane do poprawnego uruchomienia forum. W przypadku braku pliku konfiguracyjnego, po uruchomieniu poinformuje Cię o tym stosowny wyjątek.

**Forum zostało uruchomione - wejdź pod adres http://localhost!**

# Co tu znajdę?
Przygotowane środowisko zawiera:

- PHP 7.1
- Xdebug
- Composer
- MySQL (bez phpMyAdmin)
- Apache
- MailHog

Najważniejszym miejscem jest katalog `/forum`. W nim znajduje się kod naszego forum, włącznie z pluginami. W katalogu `/dump` dodany jest aktualny zrzut bazy danych przygotowany i w pełni skonfigurowany dla środowiska testowego. Istnieje również katalog `/tests`, w którym trzeba umieszczać kod testujący nasze zmiany.

## Domyślne konta użytkowników
Po zainstalowaniu forum lokalnie, masz dostęp do następujących kont:

- `superadmin`
- `admin`
- `moderator`
- `redaktor`
- `ekspert`
- `ekspertkategoria`
- `user`

Login i hasło są takie same oraz odzwierciedlają jednocześnie rangę użytkownika, która nadaje określone uprawnienia. `ekspertkategoria` - jest to ekspert przypisany tylko do jednej kategorii na forum, sam `ekspert` działa na całym forum. `user` to standardowy użytkownik bez żadnej rangi.

## Zmiana konfiguracji
W razie potrzeby zmodyfikuj konfigurację w pliku `qa-config.php`. Nie zmieniaj ani nie usuwaj pliku `qa-config-example.php`.

Jeżeli potrzebujesz zmienić konfigurację środowiska Dockera, należy nadpisać plik `docker-compose.yml` poprzez stworzenie pliku `docker-compose.override.yml` (nie zostanie on zauważony przez Gita). Wystarczy, że będzie on zawierał to, co chcesz zmienić. Więcej informacji na ten temat: https://docs.docker.com/compose/extends/. Nie modyfikuj pliku `docker-compose.yml`.

## Xdebug
Jest zainstalowany, ale domyślnie wyłączony. Możesz go łatwo włączyć i dostosować konfigurację do swoich potrzeb poprzez nadpisanie konfiguracji Dockera, jak opisano to powyżej. Domyślną konfigurację możesz podejrzeć w pliku `docker-compose.yml`.

Przykładowy plik `docker-compose.override.yml`, który uruchomi Xdebug:
```
version: '3'
services:
  www:
    build:
      args:
        - XDEBUG_REMOTE_ENABLE=on
```

## MailHog
W celu łatwego testowania maili dostępny jest MailHog. Wszystkie maile, które zostaną wysłane z lokalnego forum, **trafiają do jednej skrzynki** znajdującej się pod adresem http://localhost:8025. Jeżeli potrzebujesz wysłać maila na wprowadzony adres e-mail, musisz skonfigurować własne konto SMTP w zakładce "Emaile" panelu administracyjnego.

## Baza danych
Dane do bazy MySQL: *(znajdują się w pliku `docker-compose.yml`)*

```
mysql:
    root_password: root
    database: forum
    user: test
    password: test
```

Dump z bazą danych znajduje się w pliku `/dump/forum.sql`. W przypadku zmiany danych, nie zapomnij także o zmianie ich w pliku `qa-config.php`.

# Pomoc w rozwoju
Wszelkie poprawki dotyczące kodu strony podlegają CodeReview oraz testom.
Została włączona opcja blokady pushowania na branch `master`, dodatkowo tylko grupa **Owners** posiada prawa do mergowania, więc nie każdy kto chce będzie mógł te zmiany wprowadzać.

Więcej informacji na temat zasad współpracy można znaleźć w pliku [CONTRIBUTING](https://github.com/CodersCommunity/forum.pasja-informatyki.local/blob/master/CONTRIBUTING.md).
