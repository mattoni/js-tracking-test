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

		$.getJSON("sample_session.json", function(result) {
			$.each(result.timeline.clicks, function(index, element) {
				var path = element.path.replace(/\s+/g, '>');
				if(path in counts.clicks) {
					counts.clicks[path] ++;
				} else {
					counts.clicks[path] = 1;
				}
			});
		});

		//remove javascript in iframe
		$("#frameDemo").contents().find('script').remove();

		$("#frameDemo").load(function() {
			$.each(count.clicks, function(index, count) {
				$("#frameDemo").contents().find(index.replace(/\s+/g, ' ').append(' (' + count + ' clicks)');//.css("color","#BADA55");
			});
		});
	</script>

</body>
</html>
