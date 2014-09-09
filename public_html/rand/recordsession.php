<?php
exit(print_r($_POST));
file_put_contents('sample_session.json', $_POST['data']);
exit("Stored coordinates...");
