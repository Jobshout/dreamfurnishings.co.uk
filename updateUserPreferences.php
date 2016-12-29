<?php
require_once("include/config_inc.php");
$result=array();
$uuid= isset($_POST['uuid']) ? $_POST['uuid'] : '';
$quantity= isset($_POST['quantity']) ? $_POST['quantity'] : 0;
$unitPrice= isset($_POST['unitPrice']) ? $_POST['unitPrice'] : 0;
$action= isset($_POST['action']) ? $_POST['action'] : 'cart';

if($quantity!=0 && $unitPrice!=0){
$cookie= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : '';
$ipAddressStr= __ipAddress();

$objectName="wishlist_products";
if($action=="wishlist"){
	$objectName="wishlist_products";
}else if($action=="cart"){
	$objectName="cart";
}
 
$setUserPreference=0; // 0 means nothing, 1 means insert, 2 means update
$realmongoid="";

if($cookie!=''){
    $realmongoid = new MongoId($cookie);
	
	if($dbResultsData = $mongoCRUDClass->db_findone("session", array("_id" => $realmongoid, "ip_address" => $ipAddressStr))){
        if(isset($dbResultsData[$objectName]) && count($dbResultsData[$objectName])>0){
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
        			$existRecord['Quantity']=$quantity;
            		$existRecord['UnitPrice']=$unitPrice; 
            		$mongoCRUDClass->db_update("session", array("_id" => $realmongoid), array($objectName => $existRecord), '$push');
    				$result['success']= "Updated quantiy into your ".$action."!";
        		}
            }else{
                $result['error']= "Error no such product exists in your ".$action."!";
            }
        }else{
        	$result['error']= "Error no such product exists in your ".$action."!";
        }
    }else{
	 	$result['error']= "Error while updating quantity!";
	}
}else{
	 $result['error']= "Error while updating quantity!";
}

}else{
	$result['error']= "Qunatity and unit price can't be 0!";
}
echo json_encode($result);
?>