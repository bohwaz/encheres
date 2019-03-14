(function () {
	var loaded = [];

	var require = function (name, callback) {
		if (loaded.indexOf(name) != -1) {
			return callback();
		}

		loaded.push(name);

		var s = document.createElement('script');
		s.type = 'text/javascript';
		s.src = '/static/' + name + '.js';
		s.onload = callback;
		document.head.appendChild(s);
		return;
	};

	var updateSession = function () {
		var img = new Image(1, 1);
		img.src = '/admin/login.php?refresh=' + (+new Date);
	};

	document.addEventListener('DOMContentLoaded', function() {
		var inputs = document.querySelectorAll('input[type="datetime"]');

		if (inputs.length)
		{
			require('flatpickr', function () {
				for (var i = 0; i < inputs.length; i++) {
					flatpickr(inputs[i], {enableTime: true, time_24hr: true, inline: true});
				}
			});
		}

		if (document.body.getAttribute('data-logged')) {
			window.setInterval(updateSession, 1000*60*10);
		}
	});
}());