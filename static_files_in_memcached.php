<?php

function rscandir($base='', &$data=array()) {
//echo "base: " . $base . "<br>";

$array = array_diff(scandir($base), array('.', '..'));
//print_r($array);
//echo "<br>";

foreach($array as $value) :

  if (is_dir($base.$value)) {
    $data = rscandir($base.$value.'/', $data);

  }
  elseif (is_file($base.$value)) {
//echo "base.value: " . $base.$value . " - " . __line__ . "<br>";

   $rest = substr($value, -4);
//echo "rest: " . $rest . " - " . __line__ . "<br>";
   if ((!strcmp($rest,'.jpg')) || (!strcmp($rest,'.png')) || (!strcmp($rest,'.gif')) || (!strcmp($rest,'.js')) || (!strcmp($rest,'.css')) || (!strcmp($rest,'.tiff')) || (!strcmp($rest,'.woff')) || (!strcmp($rest,'.woff2'))  ){
echo "base.value: " . $base.$value . " - " . __line__ . "<br>";
         $data[] = $base.$value;
   }
   
      $rest = substr($value, -3);
//echo "rest: " . $rest . " - " . __line__ . "<br>";
   if ((!strcmp($rest,'.js')) || (!strcmp($rest,'.xx'))  ){
         $data[] = $base.$value;
   }

 }

endforeach;
return $data;
}

$mylist=rscandir("/Users/balinderwalia/Documents/WebApplications/dreamfurnishings.co.uk/images/");
//echo "mylist: ";
//print_r($mylist);
//echo "<br>";

$srch = array('/Users/balinderwalia/Documents/WebApplications/dreamfurnishings.co.uk/images/');
$newval = array('images/');

$memcache_obj = memcache_connect("localhost", 11211);

while (list($key, $val) = each($mylist)) {
$url=str_replace($srch,$newval,$val);
//echo $url . "<br>";
//echo "$key => $val -> ".filesize($val)."<br>";
  $value = file_get_contents($val);
//	echo "base: " . $value . "<br>";
  $url = "/" . $url;
memcache_add($memcache_obj, $url, $value, false, 0);
}

$mylist=rscandir("/Users/balinderwalia/Documents/WebApplications/dreamfurnishings.co.uk/css/");
$srch = array('/Users/balinderwalia/Documents/WebApplications/dreamfurnishings.co.uk/css/');
$newval = array('css/');

while (list($key, $val) = each($mylist)) {
  $url=str_replace($srch,$newval,$val);
//echo "$key => $val -> ".filesize($val)."<br>";
  $value = file_get_contents($val);
//	echo "base: " . $value . "<br>";
  $url = "/" . $url;
echo $url . "<br>";
memcache_add($memcache_obj, $url, $value, false, 0);
}


$mylist=rscandir("/Users/balinderwalia/Documents/WebApplications/dreamfurnishings.co.uk/js/");
$srch = array('/Users/balinderwalia/Documents/WebApplications/dreamfurnishings.co.uk/js/');
$newval = array('js/');

while (list($key, $val) = each($mylist)) {
  $url=str_replace($srch,$newval,$val);
//echo $url . "<br>";
//echo "$key => $val -> ".filesize($val)."<br>";
  $value = file_get_contents($val);
  
//echo "url: " . $url . ", value: " . $value . "<br>";
//echo "url: " . $url . "<br>";
  $url = "/" . $url;
memcache_add($memcache_obj, $url, $value, false, 0);
}

?>