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

$dbProductData = $db->$collectionNameStr->findOne(array("uuid" => $fileNoExtension));
//print_r($dbProductData);
//echo $dbProductData['_id'];

$gridFS = $db->getGridFS();

header('Content-type: image/jpg');
$image = $gridFS->findOne(array("_id"=>new MongoId($dbProductData['_id'])));

$memcache_obj = memcache_connect("localhost", 11211);
memcache_add($memcache_obj, $imgSrc, $image->getBytes(), false, 0);

echo $image->getBytes();

?>