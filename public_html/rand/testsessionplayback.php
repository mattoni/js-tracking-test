<!DOCTYPE html>
<html>
<head>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
	<iframe sandbox="allow-same-origin" src="http://alexmattoni.com/rand/getmousecoords.php" width="80%" height="600" id='frameDemo'></iframe>
	<script>
		var counts = {
			clicks : {}
		};
		//remove javascript in iframe
		$("#frameDemo").contents().find('script').remove();

		$.each($.getJSON( "sample_session.json").timeline.clicks, function(index, element) {
			var path = element.path.replace(/\s+/g, '');
			if(path in counts.clicks) {
				counts.clicks[path] ++;
			} else {
				counts.clicks[path] = 1;
			}
		});
		$("#frameDemo").load(function() {
			$.each(data.timeline.clicks, function(index, element) {
				$("#frameDemo").contents().find(element.path).append(' (' + counts.clicks[element.path.replace(/\s+/g, '')] + ' clicks)');//.css("color","#BADA55");
			});
		});
	</script>

</body>
</html>
