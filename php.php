<?php

$str="/images/products/0A4A5F3215290C43BE66186BD253B92A.jpg";

$extStr = substr($str, strripos($str,".") + 1);

echo $extStr . "<br>";

$fileNoExtension = basename($str, "." . $extStr);

echo $fileNoExtension;

//phpinfo();

?>