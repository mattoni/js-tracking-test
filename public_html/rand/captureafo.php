<?php

$url = 'https://afo.com';
$resolutions = array(
	'1600x900',
	'1920x1080',
	'1372x1843'
);

mkdir('/home/amattoni/public_html/rand/images/');
chdir('/home/amattoni/public_html/rand/images');
exec('pageres ' . $url .' 1920x1080', $output, $response);

print_r($output);

echo "Images located <a href='/rand/images/'>here</a>";

exit();
