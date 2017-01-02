<?php

ini_set('display_errors',1);

require_once("include/functions.php");
require_once("include/mongo_connection.php");

$imgSrc= isset($_GET['src']) ? $_GET['src'] : '';
/*
echo $imgSrc;
exit;

$imgSrc="/images/products/E21CD835753B1A49B951920814D985B3.jpg";
*/
$extStr = substr($imgSrc, strripos($imgSrc,".") + 1);

//echo $extStr . "<br>";

$fileNoExtension = basename($imgSrc, "." . $extStr);

//echo $fileNoExtension;

$collectionNameStr="fs.files";                                            
$collectionObj = $db->$collectionNameStr;

//$dbProductData = $db->$collectionNameStr->findOne(array("uuid" => $fileNoExtension));
//echo $dbProductData['_id'];

$gridFS = $db->getGridFS();

header('Content-type: image/jpg');
$image = $gridFS->findOne(array("uuid" => $fileNoExtension));
//print_r($image);
@$memcache_obj = memcache_connect("localhost", 11211);

$image_data_blob = $image->getBytes();

if($memcache_obj)
{
@memcache_add($memcache_obj, $imgSrc, $image_data_blob, false, 0);
}

echo $image_data_blob;

?>