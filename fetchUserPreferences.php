<?php
require_once("include/config_inc.php");
$result=array();
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
   
   	if($dbResultsData = $mongoCRUDClass->db_findone("session", array("_id" => $realmongoid, "ip_address" => $ipAddressStr))){
        if(isset($dbResultsData[$objectName]) && count($dbResultsData[$objectName])>0){
        	//$countNum=count($dbResultsData[$objectName]);
        	$countNum=0;
        	foreach($dbResultsData[$objectName] as $subObj){
        		if(isset($subObj["Quantity"])){
        			$countNum=$countNum+$subObj["Quantity"];
        		}
        	}
            $result["success"]= $countNum;
        }else{
            $result["error"]="No such product found in ".$action."!";
        }	
    }else{
        $result["error"]="No such product found in ".$action."!";
    }
}else{
    $result["error"]="No products found in ".$action."!"; 
}
echo json_encode($result);
?>