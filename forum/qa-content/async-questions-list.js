window.addEventListener('DOMContentLoaded', function() {

	// Is this the main page?
	if ($('.qa-template-qa, .qa-template-activity').length === 0) {
		return // no
	}

	// connect to websocket
	var socket = new WebSocket('ws://localhost:3000')
	window._socket = socket;


	// get question list node
	var $questionList = $('.qa-q-list')

	socket.addEventListener('open', function(event) {
		console.warn('open: ', event);
	});

	// listen to message
	socket.addEventListener('message', function(event) {

		var data = window.JSON.parse(event.data)
		console.warn('data: ', data);
		$questionList.html(data.minifiedQuestionList)

	})

	socket.addEventListener('error', event => {
		console.error('Socket error: ', event);
	})

	window.addEventListener('beforeunload', () => {
		socket.close();
	});

	console.warn('...listen to socket msgs');
})
