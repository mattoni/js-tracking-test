<!doctype html>

<html>
<head>
	<title>Record Events</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", init, false);

		function init() {
			var clicks = [];
			var movement = [];

			document.addEventListener("mousedown", function() {
				clicks.push(getEventCoordinates(this));
			});

			document.addEventListener("mousemove", function() {
				var event = this;
				var timer,
					onmousestop = function(event) {
						movement.push(getEventCoordinates(event));
						console.log(JSON.stringify(movement));
						timer = null;
					};

				clearTimeout( timer );  // remove active end timer
				timer = setTimeout( onmousestop, 250 );  // delay the stopping action another 25 millis
			});

			window.addEventListener("beforeunload", function() {
				  ajaxPostData(JSON.stringify(clicks));
			});
		}

		function getEventCoordinates(event) {
			return {
				"x"     : event.pageX,
				"y"     : event.pageY,
				"time"  : Math.round(+new Date()/1000)
			};
		}

		function recordMouseMovement() {

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

	</script>
</head>
<body>
	<span>Click Anywhere!</span>
</body>

</html>

