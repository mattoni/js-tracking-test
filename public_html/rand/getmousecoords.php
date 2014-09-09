<!doctype html>

<html>
<head>
	<title>Record Events</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", init, false);

		function init() {
			var mouseStats = {
				"clicks"    : [],
				"movement"  : [],
				"event"     : null,
				"timer"     : null
			};

			document.addEventListener("mousedown", function() {
				mouseStats.clicks.push(getEventCoordinates(event));
				mouseStats.event = null;
				makeCORSRequest(JSON.stringify(mouseStats));
			});

			document.addEventListener("mousemove", function() {
				mouseStats.event = event;
				clearTimeout(mouseStats.timer );

				mouseStats.timer = setTimeout(function() {
					mouseStats.movement.push(getEventCoordinates(mouseStats.event));
					console.log(JSON.stringify(mouseStats.movement));
					mouseStats.timer = null;
				}, 200 );
			});

			window.addEventListener("beforeunload", function() {
				  makeCORSRequest(JSON.stringify(mouseStats.clicks));
			});
		}

		function getEventCoordinates(event) {
			return {
				"x"     : event.pageX,
				"y"     : event.pageY,
				"time"  : Math.round(+new Date()/1000)
			};
		}

		function ajaxPostData(data) {
			xhr = new XMLHttpRequest;
			xhr.open('POST', 'logcoords.php', false);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send('data='+data);
			xhr.onreadystatechange=function() {
				console.log(xhr.responseText);
			};
		}

		// Create the XHR object.
		function createCORSRequest(method, url) {
			var xhr = new XMLHttpRequest();
			if ("withCredentials" in xhr) {
				// XHR for Chrome/Firefox/Opera/Safari.
				xhr.open(method, url, true);
			} else if (typeof XDomainRequest != "undefined") {
				// XDomainRequest for IE.
				xhr = new XDomainRequest();
				xhr.open(method, url);
			} else {
				// CORS not supported.
				xhr = null;
			}
			return xhr;
		}

		function makeCORSRequest(data) {
			console.log(data);
			var url = 'http://alexmattoni.com/rand/recordsession.php';

			var xhr = createCORSRequest('POST', url);
			if (!xhr) {
				alert('CORS not supported');
				return;
			}

			// Response handlers.
			xhr.onload = function() {
				var text = xhr.responseText;
				console.log('Response from CORS request to ' + url + ': ' + text);
			};

			xhr.onerror = function() {
				alert('Woops, there was an error making the request.');
			};

			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);
		}


	</script>
</head>
<body>
	<span>Click Anywhere!</span>
</body>

</html>

