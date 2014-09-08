<!doctype html>

<html>
<head>
	<title>Get the coordinates on canvas</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		var coords = [];

		document.addEventListener("DOMContentLoaded", init, false);

		function init() {
			document.addEventListener("mousedown", getPosition, false);
			window.addEventListener("beforeunload", function() {
				  ajaxPostData(JSON.stringify(coords));
				}
			);
		}

		function getPosition(event) {
			coords.push({
				"x"     : event.pageX,
				"y"     : event.pageY,
				"time"  : Math.round(+new Date()/1000)
			});
		}

		function ajaxPostData(data) {
			console.log(data);
			xhr = new XMLHttpRequest;
			xhr.open('POST', 'logcoords.php');
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.send('data='+data);
		}

	</script>
</head>
<body>
	<span>Click Anywhere!</span>
</body>

</html>

