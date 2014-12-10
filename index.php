<?php
header('Content-type: application/json');
//error_reporting(0);
require 'youtube.php';
$youtube = new Youtube();
$links = array("error"=>1,"message"=>"no Videos ID");
//if(isset($_GET["id"]))
	$links = $youtube->getDownloadLinks('d5CC95lE8uE');
//https://www.youtube.com/watch?v=d5CC95lE8uE
echo json_encode($links);