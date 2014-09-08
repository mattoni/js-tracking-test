<!doctype html>

<html>
<head>
	<title>Get the coordinates on canvas</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", init, false);

		function init() {
			var clicks = [];
			var movement = [];

			document.addEventListener("mousedown", function() {
				clicks.concat(getEventCoordinates());
				console.log(clicks);
			});

			window.addEventListener("beforeunload", function() {
				  ajaxPostData(JSON.stringify(clicks));
				}
			);
		}

		function getEventCoordinates() {
			return [{
				"x"     : event.pageX,
				"y"     : event.pageY,
				"time"  : Math.round(+new Date()/1000)
			}];
		}

		function recordMouseMovement(event, pushTo) {

		}

		function ajaxPostData(data) {
			console.log(data);
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

