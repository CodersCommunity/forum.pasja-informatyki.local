const URL = '/report-flag';
const CONTENT_TYPE = 'application/json';

function sendReport(data) {
	return fetch(URL, {
		method: 'POST',
		headers: {
			'Content-Type': CONTENT_TYPE,
		},
		body: JSON.stringify(data),
	}).then((response) => {
		if (!response.ok) {
			return Promise.reject(response.text());
		}

		return response.json();
	});
}

export default sendReport;
