<?php

$dir = '/home/amattoni/public_html/rand/images/';

rmdir($dir);
mkdir($dir);
chdir($dir);
exec('pageres https://afo.com w3counter ', $output, $response);

print_r($output);

echo "Images located <a href='/rand/images/'>here</a>";

exit();
