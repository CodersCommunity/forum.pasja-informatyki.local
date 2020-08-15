function sendReport(data) {
	return fetch('/report-flag', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
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
