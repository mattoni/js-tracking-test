<?php

$dir = '/home/amattoni/public_html/rand/images/';

rmdir($dir);
mkdir($dir);
chdir($dir);
exec('pageres [ https://afo.com 1280x1024 ] ', $output, $response);

print_r($output);

echo "Images located <a href='/rand/images/'>here</a>";

exit();
