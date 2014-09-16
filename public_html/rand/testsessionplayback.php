<!DOCTYPE html>
<html>
<head>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
	<iframe src="http://alexmattoni.com/rand/getmousecoords.php" width="80%" height="600" id='frameDemo'></iframe>
	<script>
		var data = jQuery.parseJSON('{"window":{"height":{"inner":592,"outer":1040},"width":{"inner":1920,"outer":1920}},"screen":{"height":{"available":1040,"total":1080},"width":{"available":1920,"total":1920},"depth":{"color":24,"pixel":24}},"client":{"agent":"Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/37.0.2062.120 Safari\/537.36","language":"en-US","cookies":{"enabled":true},"url":"http:\/\/alexmattoni.com\/rand\/getmousecoords.php","ip":"173.236.68.4"},"timeline":{"clicks":[{"x":153,"y":75,"path":"body","time":1410898151},{"x":167,"y":213,"path":"body","time":1410898151},{"x":63,"y":56,"path":"body div.myclass div.hi","time":1410898152},{"x":41,"y":17,"path":"body span","time":1410898153},{"x":23,"y":32,"path":"body div.myclass div.nested","time":1410898154}],"movements":[{"x":63,"y":56,"path":"body div.myclass div.hi","time":1410898152},{"x":40,"y":17,"path":"body span","time":1410898153},{"x":23,"y":32,"path":"body div.myclass div.nested","time":1410898154},{"x":108,"y":15,"path":"body span","time":1410898155}],"scrolls":[],"resizes":[]}}')
		var counts = {};

		$.each(data.timeline.clicks, function(index, element) {
			var path = element.path.replace(/\s+/g, '');
			if(counts.clicks[path]) {
				counts.clicks[path] ++;
			} else {
				counts.clicks[path] = 1;
			}
		});
		$("#frameDemo").load(function() {
			$.each(data.timeline.clicks, function(index, element) {
				$("#frameDemo").contents().find(element.path).append('(' + counts.clicks[element.path.replace(/\s+/g, '')] + ' clicks)');//.css("color","#BADA55");
			});
		});
	</script>

</body>
</html>
