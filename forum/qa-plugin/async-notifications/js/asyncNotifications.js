window.addEventListener('load', function() {

	var notifications = {

		box: document.querySelector('.osn-new-events-link'),
		boxContent: document.querySelector('.osn-new-events-link .notifybub'),

		update: function() {

			var xhr = new XMLHttpRequest();

			var xhrOnLoad = function() {
				if (xhr.status === 200) {
					notifications.setNotifications(xhr.responseText);
				}
			}

			xhr.addEventListener('load', xhrOnLoad);
			xhr.open('GET', '/async-notifications');
			xhr.send();
		},

		setNotifications: function(nr) {

			// validate number of notifications
			nr = parseInt(nr);
			if (isNaN(nr)) {
				nr = 0;
			}

			// remove notifications form the title
			var newTitle = document.title.replace(/^\(\d+\) /, '');

			// set new notifications in title
			if (nr > 0) {
				newTitle = '(' + nr + ') ' + newTitle;
			} 

			// update title
			document.title = newTitle;
			

			// notification box in navbar
			if (nr === 0) {
				this.boxContent.classList.add('ntfy-event-nill');
				this.boxContent.classList.remove('ntfy-event-new');
			} else {
				this.boxContent.classList.remove('ntfy-event-nill');
				this.boxContent.classList.add('ntfy-event-new');
			}
			
			this.boxContent.textContent = nr;
		}
	};

	notifications.update();
	notifications.box.addEventListener('click', notifications.update);

	setInterval(notifications.update, 1000 * 60);
});

