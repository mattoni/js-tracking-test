<?php

exec('phantomjs /home/amattoni/public_html/rand/phantomjs/capture.js', $output, $response);

print_r($output);
print_r($response);

echo "<br /> <br /><img src='/rand/afo.png'>";

exit();
