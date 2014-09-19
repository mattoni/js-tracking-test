<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>eq demo</title>
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>

</head>
<body>

	<ul class="nav">
		<li>List 1, item 1</li>
		<li>List 1, item 2</li>
		<li>List 1, item 3</li>
	</ul>
	<ul class="nav">
		<li>List 2, item 1</li>
		<li>List 2, item 2</li>
		<li>List 2, item 3</li>
	</ul>

	<script>
		// Applies red text color to descendants of <ul class="nav">
		// for each <li> that is the second child of its parent
		$( "ul:eq(1) li:eq(2)" ).css( "color", "red" );
	</script>

</body>
</html>
