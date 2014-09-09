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
			var mouseEvent = {};
			var mouseTimer;

			document.addEventListener("mousedown", function() {
				clicks.push(getEventCoordinates(event));
				console.log(JSON.stringify(clicks));
			});

			document.addEventListener("mousemove", function() {
				mouseEvent = event;
				clearTimeout( mouseTimer );

				mouseTimer = setTimeout(function() {
					movement.push(getEventCoordinates(mouseEvent));
					console.log(JSON.stringify(movement));
					mouseTimer = null;
				}, 500 );
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

