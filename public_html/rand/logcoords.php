<?php
$coords = file_get_contents('coords.json');

date_default_timezone_set('UTC');
$timestamp = new Datetime();

if($coords) {
	$coords =  json_decode($coords, true);

	$coords[] = array(
		'x' => $_POST['xcoord'],
		'y' => $_POST['ycoord'],
		'time'  =>  $timestamp->getTimestamp()
	);
} else {
	$coords = array(
		array(
			'x' => $_POST['xcoord'],
			'y' => $_POST['ycoord'],
			'time'  =>  $timestamp->getTimestamp()
		)
	);
}

file_put_contents('coords.json', json_encode($coords));

exit("Stored coordinates X: {$_POST['xcoord']}, Y: {$_POST['ycoord']}");
