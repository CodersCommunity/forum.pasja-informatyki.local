window.addEventListener('DOMContentLoaded', function() {

	// Is this the main page?
	if ($('.qa-template-qa').length === 0) {
		return // no
	}

	// connect to websocket
	var socket = new WebSocket('ws://localhost:3001')

	// get question list node
	var $questionList = $('.qa-q-list')

	// listen to message
	socket.addEventListener('message', function(event) {

		var data = window.JSON.parse(event.data)
		$questionList.html(data.html)

	})
})
