<?php

/* vimeo access info here  */  
$client_id = "bfa67fcff0c409e5f0dd169a32453ed7b1ba0825"; 
$vimeo_secret = "Dk4i4XuBZMNWiRFK3frYf/tpJ6LzDCkDlvmG7j5p9pggKU31sVtKEYVMc/g5VazeZ0kDAqUIo6NU2zNCJbs2HBpQMLAZSDqKewFOa7IE7iZt3ELjE/Husv43/NazOTur";
$vimeo_token = "12e50fb1d83db0f938ccd25a754c6338";

if(isset($_REQUEST['client_id'])) $client_id = $_REQUEST['client_id'];
if(isset($_REQUEST['vimeo_secret'])) $vimeo_secret = $_REQUEST['vimeo_secret'];
if(isset($_REQUEST['vimeo_token'])) $vimeo_token = $_REQUEST['vimeo_token'];


if(!isset($_REQUEST['type']) || !isset($_REQUEST['page']) || !isset($_REQUEST['per_page'])) exit("PHP Vimeo access information missing!");

$type = $_REQUEST['type'];
$page = $_REQUEST['page'];
$per_page = $_REQUEST['per_page'];
$album_id = isset($_REQUEST['album_id']) && !empty($_REQUEST['album_id']) ? $_REQUEST['album_id'] : null;
$path = isset($_REQUEST['path']) && !empty($_REQUEST['path']) ? $_REQUEST['path'] : null;
$user = isset($_REQUEST['user']) && !empty($_REQUEST['user']) ? $_REQUEST['user'] : null;
$query = isset($_REQUEST['query']) && !empty($_REQUEST['query']) ? $_REQUEST['query'] : null;
$sort = isset($_REQUEST['sort']) && !empty($_REQUEST['sort']) ? $_REQUEST['sort'] : 'default';
//$sortDirection = isset($_REQUEST['sortDirection']) && !empty($_REQUEST['sortDirection']) ? $_REQUEST['sortDirection'] : 'asc';

require("autoload.php");
use Vimeo\Vimeo;
$vimeo = new Vimeo($client_id, $vimeo_secret, $vimeo_token);

if($type == 'vimeo_channel'){
	//Get a list of videos in a Channel - https://developer.vimeo.com/api/playground/channels/{channel_id}/videos
	$result = $vimeo->request("/channels/$path/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													//'direction' => $sortDirection,								
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));
}else if($type == 'vimeo_user_album'){
	//Get the list of videos in an Album - https://developer.vimeo.com/api/playground/users/{user_id}/albums/{album_id}/videos
	$result = $vimeo->request("/users/$user/albums/$album_id/videos", array(
													'page'=> $page,
													'per_page' => $per_page,
													'fields' => 'uri,name,description,duration,width,height,privacy,pictures.sizes',
													'sort' => $sort,
													//'direction' => $sortDirection,							
													'query' => $query,
													'filter' => 'embeddable',
													'filter_embeddable' => 'true'
													));										
}

echo json_encode($result);

?>