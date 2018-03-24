window.addEventListener('DOMContentLoaded', function() {

    // Is this the main page?
    if ($('.qa-template-qa').length === 0) {
        return // no
    }

    // connect to websocket
    const socket = new WebSocket('ws://localhost:3001')

    // get question list node
    const $questionList = $('.qa-q-list')

    // listen to message
    socket.addEventListener('message', function(event) {

        const data = window.JSON.parse(event.data)
        $questionList.html(data.html)

    })
})
