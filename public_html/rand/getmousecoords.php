<!doctype html>

<html>
<head>
	<title>Get the coordinates on canvas</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		var coords = [];

		window.addEventListener("unload", ajaxPostData(JSON.stringify(coords)));
		document.addEventListener("DOMContentLoaded", init, false);

		function init() {
			//window.onunload = ajaxPostData(JSON.stringify(coords));
			document.addEventListener("mousedown", getPosition, false);
		}

		function getPosition(event) {
			coords.push({
				"x"     : event.pageX,
				"y"     : event.pageY,
				"time"  : Math.round(+new Date()/1000)
			});
			console.log(JSON.stringify(coords));
		}
		window.addEventListener("unload", ajaxPostData(JSON.stringify(coords)));

		function ajaxPostData(data) {
			console.log(data);//alert(JSON.stringify(data));
			xhr = new XMLHttpRequest;
			xhr.open('POST', 'logcoords.php', false);
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.send(data);
			console.log('AJAX request sent: ' + data);
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

