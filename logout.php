<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
require_once("include/config_inc.htm");

if($db->session->findOne(array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"])))){
	echo "exists";
	$db->session->update(array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"])), array('$set' => array("login_status" => false)));
	header("Location: login.htm?" . rand());
	exit;
}else{
	echo "dnt exists";
	header("Location: login.htm?" . rand());
	exit;
}
?>
