<!doctype html>

<html>
<head>
	<title>Get the coordinates on canvas</title>
	<meta charset="utf-8">
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", init, false);

		function init() {
			document.addEventListener("mousedown", getPosition, false);
		}

		function getPosition(event) {
			postCoords(event.pageX, event.pageY);
		}

		function postCoords(x, y) {
			xhr = new XMLHttpRequest;
			xhr.open('POST', 'logcoords.php');
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send('xcoord='+x+'&ycoord='+y);
			console.log('AJAX request sent. xcoord: '+x+' ycoord: '+y);
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

