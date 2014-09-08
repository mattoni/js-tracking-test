<?php

print_r($_POST['coords']);
exit();
file_put_contents('coords.json', $_POST);


exit("Stored coordinates...");
