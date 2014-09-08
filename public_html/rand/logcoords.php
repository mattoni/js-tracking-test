<?php


file_put_contents('coords.json', $_POST);

exit("Stored coordinates X: {$_POST['xcoord']}, Y: {$_POST['ycoord']}");
