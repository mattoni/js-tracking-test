<!doctype html>

<html>
<head>
	<title>Record Events</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", init, false);

		function init() {
			var Stats = {
				"window"    : {
					"height"    :   window.innerHeight,
					"width"     :   window.innerWidth
				},
				"clicks"    : [],
				"movements" : [],
				"scrolls"   : [],
				"event"     : null,
				"timer"     : null
			};


			document.addEventListener("mousedown", function() {
				Stats.clicks.push(getEventCoordinates(event));
				Stats.event = null;
			});

			document.addEventListener("mousemove", function() {
				Stats.event = event;
				clearTimeout(Stats.timer );

				Stats.timer = setTimeout(function() {
					Stats.movements.push(getEventCoordinates(Stats.event));
					console.log(JSON.stringify(Stats.movements));
					Stats.timer = null;
				}, 200 );
			});

			window.addEventListener('scroll', function () {
				if (window.pageXOffset || window.pageYOffset) {
					sX = window.pageXOffset;
					sY = window.pageYOffset;
				} else {
					sX = document.documentElement.scrollLeft || document.documentElement.scrollLeft;
					sY = document.documentElement.scrollTop || document.documentElement.scrollTop;
				}

				Stats.scrolls.push({
					"x"     :   sX,
					"y"     :   sY,
					"time"  :   Math.round(+new Date()/1000)
				});

				console.log(JSON.stringify(Stats.scrolls));
			});

			window.addEventListener("beforeunload", function() {
				  makeCORSRequest(JSON.stringify(Stats.clicks));
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

