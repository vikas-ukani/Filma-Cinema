<?php 
   header("Content-Type: application/octet-stream");
   header("Content-Disposition: attachment; filename=". $_GET['name']);
   
   $path = $_GET['path'];
  
   $pathFragments = explode('/', $path);
   $first ='';
   $end = end($pathFragments);
   for($i=0; $i<count($pathFragments) - 1; $i++){
	$first .= $pathFragments[$i].'/';
   }
   $first = rawurlencode($first);
   $first = str_replace("%2F","/",$first);
   $first = str_replace("%3A",":",$first);
  
   $path = $first . rawurlencode($end);

  if(isset($path))readfile($path);
?>
