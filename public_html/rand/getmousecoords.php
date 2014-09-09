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
				console.log(JSON.stringify(mouseStats.clicks));
			});

			document.addEventListener("mousemove", function() {
				mouseStats.event = event;
				clearTimeout( mouseTimer );

				mouseTimer = setTimeout(function() {
					mouseStats.movement.push(getEventCoordinates(mouseStats.event));
					console.log(JSON.stringify(mouseStats.movement));
					mouseStats.timer = null;
				}, 200 );
			});

			window.addEventListener("beforeunload", function() {
				  ajaxPostData(JSON.stringify(mouseStats.clicks));
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

