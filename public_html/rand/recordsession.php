<?php
exit(print_r(file_get_contents('php://input')));
file_put_contents('sample_session.json', $_POST['data']);
exit("Stored coordinates...");
