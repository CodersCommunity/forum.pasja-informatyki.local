document.addEventListener('DOMContentLoaded', function runAsyncQuestionsList() {
	const { pathname } = window.location;
	const isMainOrActivityPage = /^$|\/$|(\/?)activity/.test(pathname);

	if (!isMainOrActivityPage) {
		return;
	}

	const QA_Q_LIST_SELECTOR = '.qa-q-list';
	const questionList = document.querySelector(QA_Q_LIST_SELECTOR);
	const newContentNotifier = createNewContentNotifier();
	const webSocket = setupWebSocketConnection();

	function setupWebSocketConnection() {
		const webSocket = new WebSocket('ws://localhost:3000');

		// TODO: just for tests
		window._webSocket = webSocket;

		webSocket.addEventListener('open', onOpen);
		webSocket.addEventListener('message', onMessage);
		webSocket.addEventListener('error', onError);

		window.addEventListener('beforeunload', () => {
			console.warn('close webSocket...');

			webSocket.close();
			newContentNotifier.disconnectIntersectionObserver();
		});

		return webSocket;
	}

	function onOpen(event) {
		console.warn('open: ', event);

		webSocket.send(JSON.stringify({ pathname }));
	}

	function onMessage(event) {
		const data = window.JSON.parse(event.data)
		console.warn('data: ', data);

		notifyUserAboutNewContent();
	}

	function onError(event) {
		console.error('Socket error: ', event);

		// TODO: send error via Ajax to Node, which will send an email to PM
	}

	function notifyUserAboutNewContent() {
		newContentNotifier.show();
	}

	function createNewContentNotifier() {
		const notifierTargetPlace = document.querySelector('.qa-part-q-list');
		const NOTIFIER_CLASSES = Object.freeze({
			BASE: 'qa-custom-new-content-notifier',
			VISIBLE: 'qa-custom-new-content-notifier--visible'
		});
		const { notifier, notifierCounter } = createNotifierDOM();
		const disconnectIntersectionObserver = observeNotifierIntersections(notifier);

		let notifierCounterValue = 0;

		////
		show();

		return { show, hide, disconnectIntersectionObserver };

		function createNotifierDOM() {
			const notifier = document.createElement('button');
			notifier.title = 'Aktualizuj listę postów';
			notifier.classList.add(NOTIFIER_CLASSES.BASE);
			notifier.innerHTML = `
				<span>Nowych postów: </span>
				<output></output>
			`;
			notifier.addEventListener('click', updatePageContent);

			notifierTargetPlace.insertAdjacentElement('beforebegin', notifier);

			const [ , notifierCounter ] = notifier.children;

			return { notifier, notifierCounter };
		}

		function show() {
			notifier.classList.add(NOTIFIER_CLASSES.VISIBLE);
			incrementCounter();
		}

		function hide() {
			notifier.classList.remove(NOTIFIER_CLASSES.VISIBLE);
			resetCounter();
		}

		function incrementCounter() {
			notifierCounter.textContent = ++notifierCounterValue;
		}

		function resetCounter() {
			notifierCounterValue = 0;
			notifierCounter.textContent = notifierCounterValue;
		}
	}

	function updatePageContent({ currentTarget }) {
		fetch(window.location.href)
			.then((response) => response.text())
			.then((html) => {
				const responseQuestionList =
						new DOMParser()
							.parseFromString(html, 'text/html')
							.querySelector(QA_Q_LIST_SELECTOR);

				showUpdatedPageContent(responseQuestionList.innerHTML, currentTarget);
			});
	}

	function showUpdatedPageContent(updatedContent, notifier) {
		// TODO: consider wrapping it with requestAnimationFrame function
		questionList.innerHTML = updatedContent;
		newContentNotifier.hide();

		if (notifier.classList.contains('fixed-to-top')) {
			window.scroll({
				top: 0,
				behavior: 'smooth'
			});
		}
	}

	function observeNotifierIntersections(target) {
		const intersectionObserver = new IntersectionObserver(([entry]) => {
			target.classList.toggle('fixed-to-top', entry.intersectionRatio <= 0);
		});
		intersectionObserver.observe(target.previousElementSibling);

		return intersectionObserver.disconnect.bind(intersectionObserver);
	}
});
