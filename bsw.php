<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
require_once("include/config_inc.php");
require_once("include/main_header.php");

/*$collection="authentication_token";
$guid="CCE21AF1-3A18-4AC0-A829-8349763B8D75";
$db->$collection->insert(array("name" => "security-token", "active" => true, "guid" => $guid));  
*/
$collections = $db->listCollections();

foreach ($collections as $collection) {
  echo "$collection: ";
  echo $collection->count(), "<br>\n";
}

?>
