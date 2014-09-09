<?php
file_put_contents('sample_session.json', file_get_contents('php://input'));
exit("Stored coordinates...");
