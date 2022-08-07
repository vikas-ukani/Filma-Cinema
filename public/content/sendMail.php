<?php
	$to = $_GET['mail'];
	$subject = 'Download Link from Easy Video Player';
	$name = $_GET['name'];
	$path = $_GET['path'];
	$path = ltrim($path, "/");
	
	$header = "From: do-not-reply@yourdomain.com\r\n"; 
	$message = 'Video file "'. $name .'" can be downloaded at the following link: '. $path;
	
	if(!mail($to, $subject, $message, $header)){
		echo('error');
	}else{
		echo('sent');
	}
?>