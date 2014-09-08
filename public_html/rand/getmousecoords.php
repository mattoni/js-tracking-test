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
			var mousetimer;

			document.addEventListener("mousedown", function() {
				clicks.push(getEventCoordinates(event));
				console.log(JSON.stringify(clicks));
			});

			document.addEventListener("mousemove", function() {
				var onmousestop = function(event) {
						movement.push(getEventCoordinates(event));
						console.log(mousetimer);
						mousetimer = null;
					};

				clearTimeout( mousetimer );
				mousetimer = setTimeout( onmousestop(event), 10000 );
			});

			window.addEventListener("beforeunload", function() {
				  ajaxPostData(JSON.stringify(clicks));
			});
		}

		function getEventCoordinates(event) {
			console.log(event);
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

