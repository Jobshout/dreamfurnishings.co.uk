<?php

ini_set('display_errors',1);

require_once("include/functions.php");
require_once("include/mongo_connection.php");

$imgSrc= isset($_GET['src']) ? $_GET['src'] : '';
if($imgSrc!=""){

$extStr = substr($imgSrc, strripos($imgSrc,".") + 1);

$fileNoExtension = basename($imgSrc, "." . $extStr);

$collectionNameStr="fs.files";                                            
$collectionObj = $db->$collectionNameStr;

$gridFS = $db->getGridFS();

//header('Content-type: image/jpg');

if($image = $gridFS->findOne(array("uuid" => $fileNoExtension))){
	$fileMimeType=image_get_mime_type($extStr);
	header('Content-type: ' . $fileMimeType);
	header('image-src: database');
	//print_r($image);
	@$memcache_obj = memcache_connect("localhost", 11211);

	$image_data_blob = $image->getBytes();

	if($memcache_obj)
	{
	@memcache_add($memcache_obj, $imgSrc, $image_data_blob, false, 0);
	}

	echo $image_data_blob;
}
}
?>