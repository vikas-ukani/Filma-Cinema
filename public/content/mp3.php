<?php

$url = "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$url = substr($url, 0, strrpos($url, "/") + 1); 

$dir = (isset($_GET['dir']) ? $_GET['dir'] : null);
$imlBody .= "{'li':["; // Start XMLBody output
$dirHandle = opendir($dir); 
$ar = array();
$i = 0;
while ($file = readdir($dirHandle)) { 
      // if file is not a folder and if file name contains the string .jpg  
      if(!is_dir($file) && strpos($file, '.mp3')){
	    $i++; // increment $i by one each pass in the loop
		$ar[$i] = $file;
     } 
} 
sort($ar);
for($i=0;$i<1;$i++){
	$imlBody .= "{'@attributes':{";
	$file = $ar[$i];
	$trackTitle;
	if($i < 9){
		$trackTitle = "track 0" . ($i + 1);
	}else{
		$trackTitle = "track " . ($i + 1);
	}
	$imlBody .="'data-path':'" . $url.$file . "',";
	$imlBody .="'data-title':'" . $trackTitle . "'";
	$imlBody .= "}}";
	
 };
closedir($dirHandle); // close the open directory
$imlBody .= "]}";
echo $imlBody; 
?>