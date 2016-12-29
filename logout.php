<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
require_once("include/config_inc.php");
if($findUser=$mongoCRUDClass->db_findone("session", array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"])))){
	$mongoCRUDClass->db_update("session", array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"])), array("login_status" => false));
	header("Location: login.htm?" . rand());
	exit;
}else{
	header("Location: login.htm?" . rand());
	exit;
}
?>
