<?php

$url = 'https://afo.com';
$resolutions = array(
	'1600x900',
	'1920x1080',
	'1372x1843'
);

exec('cd /home/amattoni/public_html/rand/images/');

exec('pageres ' . $url .' ' . implode(' ', $resolutions), $output, $response);

echo 'Completed with message: ' . $response;

echo "Images located <a href='/rand/images/'>here</a>";

exit();