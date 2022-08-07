<?php

ini_set('display_errors', false);
set_exception_handler('ReturnError');
 
$r = '';
$url = (isset($_GET['url']) ? $_GET['url'] : null);

if ($url) {

	// fetch XML
	$c = curl_init();
	curl_setopt_array($c, array(
		CURLOPT_URL => $url,
		CURLOPT_HEADER => false,
		CURLOPT_TIMEOUT => 15,
		CURLOPT_RETURNTRANSFER => true
	));
	$r = curl_exec($c);
	curl_close($c);

}

if ($r) {
	// XML to JSON
	echo json_encode(new SimpleXMLElement($r));
}
else {
	// nothing returned?
	ReturnError();
}

// return JSON error flag
function ReturnError() {
	echo '{"error":true}';
}