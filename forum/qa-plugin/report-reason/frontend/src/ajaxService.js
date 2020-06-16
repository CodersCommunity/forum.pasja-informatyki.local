const AJAX_TIMEOUT_REASON = 'AJAX_TIMEOUT';
const TIMEOUT = 5000;

const URL = '/report-flag';
const CONTENT_TYPE = 'application/json';

function sendAjax(data) {
	return new Promise((resolve, reject) => {
		// TODO: handle timeout in better way - probably use AbortSignal API with some polyfill
		const timeoutId = setTimeout(() => {
			reject(AJAX_TIMEOUT_REASON);
		}, TIMEOUT);

		fetch(URL, {
			method: 'POST',
			headers: {
				'Content-Type': CONTENT_TYPE,
			},
			body: JSON.stringify(data),
		})
		.then((response) => {
			clearTimeout(timeoutId);

			if (!response.ok) {
				reject(response.text());
			}

			return response
				.json()
				.then(resolve);
		})
		.catch(reject);
	});
}

export default sendAjax;
