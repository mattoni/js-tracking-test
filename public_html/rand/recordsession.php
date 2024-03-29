<?php

try {
	cors();
	file_put_contents('sample_session.json', modifyStats(file_get_contents('php://input')));
} catch(\Exception $e) {
	exit($e->getMessage());
};

function modifyStats($stats) {
	$stats = json_decode($stats);
	$stats->client->ip  =   $_SERVER['REMOTE_ADDR'];

	return json_encode($stats);
}

function cors() {

	// Allow from any origin
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}

	// Access-Control headers are received during OPTIONS requests
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

		exit(0);
	}
}

exit('Stats Logged');
