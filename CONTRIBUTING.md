# Contributing

Jest to drugi najważniejszy plik po README w projekcie. Dotyczy on osób, które chcą w sposób aktywny wpłynąć na rozwój naszego forum.

## Standardy kodu

Tworzony kod PHP **musi przestrzegać standardu [PSR-2](http://www.php-fig.org/psr/psr-2/)** tam, gdzie tylko jest to możliwe - zdajemy sobie sprawę, że w niektórych miejscach będzie to niewykonalne przez już napisany kod Question2Answer. Twój kod musi działać z **PHP w wersji 7.1** - z takiej obecnie korzystamy.

Kod w języku JavaScript **powinien być pisany z użyciem standardu ES6**. Jeśli jednak masz zamiar użyć czegoś, z czym potencjalnie mogą mieć problem nawet najpopularniejsze przeglądarki internetowe, warto wspomnieć o tym w pull requeście, abyśmy mogli razem to przedyskutować.

Należy starać się pisać możliwie najnowszy, bezbłędny i czytelny kod.

## Tworzenie `issue`

Jeśli znalazłeś błąd w projekcie (i nie wiesz jak go naprawić), masz pytanie odnośnie działania danej funkcjonalności lub chcesz powiedzieć nam, czego brakuje na forum - stwórz `Issue`. Jest to trwały sposób na przekazanie nam Twojego pomysłu lub problemu. 

### Wskazówki odnośnie `issue`

 * **Sprawdź czy już ktoś wystawił podobne `issue`** do Twojego. Nie ma sensu duplikować tych samych informacji (zgodnie z zasadą DRY), ponieważ wprowadza to niepotrzebny zamęt. Jeśli już istnieje zamknięte `issue`, a Ty dalej nie masz rozwiązania tego problemu - nic nie szkodzi. Zawsze możesz je ponownie otworzyć. 
 * **Bądź dokładny** w opisywaniu problemu lub nowej funkcjonalności. Jakiego zachowania się spodziewałeś, jakie otrzymałeś? A może wiesz jak rozwiązać ten problem? W takim razie napisz nam w jaki sposób można to zrobić.
 * **Podeślij nam linki do `demo`** - jeśli naprawiłeś buga w naszym forum, jeśli stworzyłeś jakąś funkcjonalność - podeślij właśnie w tym issue demo działania lub zrzuty ekranu.
 * **Podaj informacje o swoim systemie i środowisku**, takie jak nazwa i wersja przeglądarki, system operacyjny - cokolwiek, co pozwoli nam się skupić na danym problemie.
 * **Dołącz treść błędu** jeśli taką otrzymujesz.
 
## Pull Requests
 
 * **Forkuj lub sklonuj projekt** na swój lokalny komputer. Instalację forum przeprowadź zgodnie z plikiem README.md
 * **Utwórz `branch`**, czyli gałąź. Zasady branchowania i workflow poniżej. 
 * **Opisz dokładnie zmiany wprowadzone** przez Ciebie. Niech każdy, kto spojrzy na dany PR będzie świadomy o jego przydatności i funkcjonowaniu.
 * **Najlepiej pokaż nam testy** gdy piszesz testy, utwórz **ze swojego brancha, już utworzonego** kolejny branch z dopiskiem `-tests` na końcu. Na nim możesz trzymać swoje testy, a my możemy przejrzeć ich działanie. 
 * **Dołącz zrzuty ekranu** obrazujące to, co stworzyłeś lub naprawiłeś (przed/po).
 
 Tytuł, treść czy komentarze do pull requestów piszemy w języku polskim. 
 
## Tworzenie branchy

Proszę się pilnie zapoznać: [Git-Flow Workflow](http://danielkummer.github.io/git-flow-cheatsheet/)

## Tworzenie commit'ów

Proszę się zapoznać: [How to write a Git Commit Message](http://chris.beams.io/posts/git-commit/)

W skrócie:

* Oddziel temat od ciała wiadomości za pomocą entera
* Ogranicz tytuł commita do 50 znaków
* Linię z tytułem zaczynamy Wielką literą
* Tytuł, tak jak w podstawówce uczono, nie posiada na końcu kropki
* Tytuł powinien być zapisany w języku angielskim z wykorzystaniem bezokoliczników (używamy form: REFACTOR, UPDATE, REMOVE, RELEASE, MERGE, FIX)
* Użyj ciała commita, aby wyjaśnić WWH - What Why How


## Kilka drobnych wskazówek:
Te wskazówki przydadzą się również osobom zatwierdzającym zmiany.

### Etykiety:
* **"Assigned"** - oznacza, że Twoje issue/PR zostało zatwierdzone, a Ty wiesz, że jest sens pracować nad tym dalej.
* **"Bug"** - dostaje issue, które dotyczy jakiegoś błędu w kodzie
* **"Duplicate"** - należy dodać, oraz zamknąć issue linkując do poprzedniego.
* **"Feature"** - Jest to jakiś nowy ficzer.
* **"Need Review"** - Skończyłeś zmiany, czekaj na CodeReview.
* **"Question"** - Jeżeli Twoje issue to pytanie 
* **"Ready to Merge"** -  Gotowy, otestowany, do zmergowania.
* **"Testing"** - Aktualnie trwa testowanie zmian.
* **"Work in progress"** - aktualnie pracujesz nad czymś.
 
### Sposób dawania etykiet:
Przykładowa kolejność dla Pull Requestów.

1. Assigned (do issue zazwyczaj) / Duplicate / Question 
2. Feature / Bug
3. Work in progress
4. Testing
5. \+ Need Review | - Work in progress
6. Ready to merge

Dodatkowo nie zapominaj o tym, aby podlinkować issue do swojego PR, np.
Ta zmiana pochodzi z #19    
