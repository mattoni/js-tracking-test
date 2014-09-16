<?php

exec('/usr/local/bin/phantomjs /home/amattoni/public_html/rand/phantomjs/capture.js', $output, $response);

echo "<br /> <br /><img src='/rand/afo.png'>";

exit();
