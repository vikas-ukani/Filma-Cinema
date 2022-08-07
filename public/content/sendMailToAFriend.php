<?php
	$to = $_GET['friendMail'];
	$from = $_GET['yourMail'];
	$link = urldecode($_GET['link']);
	
	$header = "From: " . $from . "\r\n"; 
	$subject = "Shared video from Ultimate Video Player";
	$message = 'Your friend '. $from .' shared a video with you: '. $link;
	
	if(!mail($to, $subject, $message, $header)){
		echo('error');
	}else{
		echo('sent');
	}
?>