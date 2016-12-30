<?php
require_once("include/config_inc.php");

$uuid= isset($_POST['uuid']) ? $_POST['uuid'] : 0;
$cookie= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : '';
$action= isset($_POST['action']) ? $_POST['action'] : '';
$objectName="wishlist_products";
if($action=="wishlist"){
	$objectName="wishlist_products";
}else if($action=="cart"){
	$objectName="cart";
}
 
if($cookie!=''){
    $ipAddressStr= __ipAddress();
    $realmongoid = new MongoId($cookie);
    $result=array();
    if($dbResultsData = $mongoCRUDClass->db_findone("session", array("_id" => $realmongoid, "ip_address" => $ipAddressStr))){
    	$existBool=false; $existRecord=array();
         foreach($dbResultsData[$objectName] as $subObjects)  {   
         	if($subObjects["uuid"]==$uuid){
              	$existBool=true;
              	$existRecord=$subObjects;
                break;
            }
        }
		
		//check product exists or not
        if($existBool) {
        	$set_v= array($objectName => $existRecord);
        	
        	if($mongoCRUDClass->db_update("session", array("_id" => $realmongoid), $set_v, '$pull')){
            	$result["success"]="Deleted this product successfully from your ".$action."!";
        	}else{
           		$result["error"]="Please try after sometime!";
       	 	}
        }else{
        	$result["error"]="No such product found in ".$action."!";
        }
    }else{
        $result["error"]="No such product found in ".$action."!";
    }
}else{
    $result["error"]="You have no products in your ".$action."!";
}
echo json_encode($result);
?>