<?php

print_r($_POST);
exit();
file_put_contents('coords.json', $_POST);


exit("Stored coordinates...");
