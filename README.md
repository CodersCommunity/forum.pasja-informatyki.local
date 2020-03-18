# Opis
To repozytorium zawiera skonfigurowaną **wirtualną maszynę** przygotowaną specjalnie do tego, aby nie dokonywać zmian bezpośrednio na serwerze **produkcyjnym**.
Grupa **CodersCommunity** postanowiła stworzyć środowisko developerskie naszego forum.

# Informacje techniczne
## Wirtualna maszyna
Aby móc korzystać z *developerskiego środowiska* wymagane jest posiadanie następującego oprogramowania:

 - Vagrant >= 1.8.1
 - VirtualBox 5.0.18 (nowsze wersje w tym momencie powodują problemy uniemożliwiające postawienie projektu)
 - NFS server

## Instalacja
### Docker i docker compose

Musimy pobrać i zainstalować aktualną wersję dockera zgodnie z instrukcją: https://docs.docker.com/install/

Musimy również mieć pobraną i zainstalowaną aktualną wersję docker compose: https://docs.docker.com/compose/install/

Po zainstalowaniu powyższego oprogramowania pozostaje nam ściągnąć projekt z GitHuba:
```git clone https://github.com/CodersCommunity/forum.pasja-informatyki.local.git```

I ostatnim krokiem jest wykonanie w katalogu projektu:
```
docker-compose up -d
```
#### Ustawienie /etc/hosts
Ponieważ mamy do czynienia z **wirtualną maszyną**, będziemy musieli zapewnić z nią wygodną komunikację. Domyślnym adresem, pod którym jest dostępny *mirror* forum jest ```192.168.122.122```.
W celu ułatwienia pracy, zamieniamy ten adres IP na zrozumiały dla człowieka w pliku ```/etc/hosts```, dopisując to na samym końcu tego pliku:
```
127.0.0.1 forum.pasja-informatyki.local
```

W systemie Windows ten plik należy uruchomić w dowolnym edytorze tekstu z uprawnieniami administracyjnymi. Powinien znajdować się w ```C:\Windows\System32\drivers\etc\```.
W systemach Unix'owych po prostu w ```/etc/hosts```.

Strona forum powinna być pod adresem: http://forum.pasja-informatyki.local:8181

### Vagrant
Serwer NFS instalujemy na systemach Unix'owych w następujący sposób:
```sudo apt-get install nfs-common nfs-kernel-server```

Vagranta instalujemy korzystając z odnośnika: https://www.vagrantup.com/downloads.html

VirtualBox możemy zainstalować w Ubuntu (i pochodnych) za pomocą
```sudo apt-get install VirtualBox```. Następnie musimy sprawdzić, czy wersja się zgadza.

Możemy też skorzystać z https://www.virtualbox.org/wiki/Linux_Downloads

Po zainstalowaniu powyższego oprogramowania pozostaje nam ściągnąć projekt z GitHuba:
```git clone https://github.com/CodersCommunity/forum.pasja-informatyki.local.git```

I ostatnim krokiem jest wykonanie w katalogu projektu:
```vagrant up```

Inną możliwością jest skorzystanie z programu **XAMPP** lub podobnego, oferującego serwer ```Apache``` i postępowanie zgodnie z niniejszą instrukcją: https://drive.google.com/file/d/0B_0lVIhwL9LWMjV1eGJyTk0yMTQ/view?pref=2&pli=1

#### Ustawienie /etc/hosts
Ponieważ mamy do czynienia z **wirtualną maszyną**, będziemy musieli zapewnić z nią wygodną komunikację. Domyślnym adresem, pod którym jest dostępny *mirror* forum jest ```192.168.122.122```.
W celu ułatwienia pracy, zamieniamy ten adres IP na zrozumiały dla człowieka w pliku ```/etc/hosts```, dopisując to na samym końcu tego pliku:
```
192.168.112.112 forum.pasja-informatyki.local
```

W systemie Windows ten plik należy uruchomić w dowolnym edytorze tekstu z uprawnieniami administracyjnymi. Powinien znajdować się w ```C:\Windows\System32\drivers\etc\```.
W systemach Unix'owych po prostu w ```/etc/hosts```.

Strona forum powinna być pod adresem: http://forum.pasja-informatyki.local

### Konfiguracja
Podczas tworzenia środowiska przez Vagranta przygotowanie właściwej konfiguracji następuje automatycznie. Jeżeli uruchamiasz środowisko samemu, skopiuj plik `qa-config-example.php` do `qa-config.php`.

W razie potrzeby zmodyfikuj konfigurację w pliku `qa-config.php`. Nie zmieniaj ani nie usuwaj pliku `qa-config-example.php`.

# Co tu znajdę?
## Serwer
Na serwerze zainstalowano:

 - PHP 7.1
 - xdebug
 - Composer
 - mysql (bez phpmydamin) **[Zamiast phpmyadmin świetnie spisuje się MySQL Workbench]**
 - Apache

## Pliki skryptu z forum
Najważniejszym punktem jest katalog **/forum**. W nim znajduje się *mirror* naszego forum, włącznie z pluginami.
Istnieje również katalog **/tests**, w którym trzeba umieszczać kod testujący nasze zmiany.

Repozytorium zawiera:

 - Przygotowany "config" zawierający dane do bazy
 - aktualny dump testowej bazy, zawierający tylko niezbędne dane takie jak:
   - Użytkownika testowego z loginem: `test` i hasłem `test`
   - Niezbędną konfigurację pluginow
 - Kod strony, obecne Q2A wraz ze zmianami wprowadzonymi do szablonu strony.
  - Kod strony **nie zawiera**: cache, avatarów, danych do bazy.
  - specjalnie przygotowany plik `.gitignore`

## Baza danych
Dane do bazy mysql:
*(znajdują się w pliku ansible/vars/all.yml)*

```
mysql:
    root_password: root
    database: forum
    user: test
    password: test
```

Dump z bazą danych znajduje się w pliku **/dump/forum.sql**

## Domyślne konta użytkowników

Po zainstalowaniu form lokalnie, masz dostęp do następujących kont (nazwa użytkownika mówi nam również o funkcji danego użytkownika):

 * superadmin
 * admin
 * moderator
 * redaktor
 * ekspert
 * ekspertkategoria
 * user

Login i hasło są takie same.
Ranga dla każdego konta chyba też jest jasna.
Jedyne, co można wyjaśnić to 'ekspertkategoria' - jest to ekspert przypisany tylko do jednej kategorii na forum, sam 'ekspert' działa na całym forum.
'user' to standardowy użytkownik bez żadnej rangi.

# Pomoc w rozwoju
Wszelkie poprawki dotyczące kodu strony podlegają CodeReview oraz testom.
Została włączona opcja blokady pushowania na branch `master`, dodatkowo tylko grupa **Owners** posiada prawa do mergowania, więc nie każdy kto chce będzie mógł te zmiany wprowadzać.

Więcej informacji na temat zasad współpracy można znaleźć w pliku CONTRIBUTING.md.
