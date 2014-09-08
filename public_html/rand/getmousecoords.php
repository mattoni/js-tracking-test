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
			ajaxPostData(JSON.stringify(coords));
		}

		function ajaxPostData(data) {
			console.log(data);//alert(JSON.stringify(data));
			xhr = new XMLHttpRequest;
			xhr.open('POST', 'logcoords.php', false);
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.send('coords=' + data);
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

