<?php
require_once("include/config_inc.php");

$result=array();
$uuid= isset($_POST['uuid']) ? $_POST['uuid'] : '';
$action= isset($_POST['action']) ? $_POST['action'] : '';
$unit_price= isset($_POST['unit_price']) ? $_POST['unit_price'] : 0;
$quantity= isset($_POST['quantity']) ? $_POST['quantity'] : 1;
$availableOptions= isset($_POST['availableOptions']) ? $_POST['availableOptions'] : '';

if(isset($availableOptions) && $availableOptions!=""){
$availableOptionsObj=json_decode($availableOptions);
}

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
        			$productsArr=array("uuid" => $uuid, "Quantity" => $quantity);
        			if(isset($availableOptionsObj) && count($availableOptionsObj)>0){
						foreach($availableOptionsObj as $obj){
							foreach($obj as $key=>$value){
								$productsArr[$key]=$value;
							}
						}
					}
    				$productsArr['UnitPrice']=$unit_price;
    				$mongoCRUDClass->db_update("session", array("_id" => $realmongoid), array($objectName => $productsArr), '$push');
    				$result['success']= "Updated this product in your ".$action."!";
        		}
                
            }else{
                $setUserPreference=2;
            }
        }else{
        	$setUserPreference=2;
        }
    }else{
        $setUserPreference=1;
    }
}else{
	$setUserPreference=1;
}

if($setUserPreference==1){
	if($dbResultsData = $mongoCRUDClass->db_findone("session", array("ip_address" => $ipAddressStr))){
		$sessionIDStr=$dbResultsData['_id'];
		setcookie("DreamFurnishingVisitor", $dbResultsData['_id'], time()+60*60*24*365);
	}else{
		$session_values= array("ip_address" => $ipAddressStr);
		$mongoCRUDClass->db_insert("session", $session_values);
		$sessionIDStr=$session_values['_id'];
		setcookie("DreamFurnishingVisitor", $session_values['_id'], time()+60*60*24*365);
	}
	
	if($uuid!=""){
   		$productsArr=array("uuid" => $uuid, "Quantity" => $quantity);
        if(isset($availableOptionsObj) && count($availableOptionsObj)>0){
			foreach($availableOptionsObj as $obj){
				foreach($obj as $key=>$value){
					$productsArr[$key]=$value;
				}
			}
		}
        $productsArr['UnitPrice']=$unit_price;
        
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
				$mongoCRUDClass->db_update("session", array("_id" => $sessionIDStr), $set_v, '$pull');
			}
		}
		if($sessionIDStr!=""){
   			if($mongoCRUDClass->db_update("session", array("_id" => $sessionIDStr), array($objectName => $productsArr), '$push')){
  	   			$result["success"]="Added this product successfully to your ".$action."!";
    		}else{
        		$result["error"]="Please try after sometime!";
    		}
    	}
    }else{
    	$result["error"]="Please try after sometime!";
    }
}else if($setUserPreference==2){
	$productsArr=array("uuid" => $uuid, "Quantity" => $quantity);
    if(isset($availableOptionsObj) && count($availableOptionsObj)>0){
		foreach($availableOptionsObj as $obj){
			foreach($obj as $key=>$value){
				$productsArr[$key]=$value;
			}
		}
	}
    $productsArr['UnitPrice']=$unit_price;
    
    if($mongoCRUDClass->db_update("session", array("_id" => $realmongoid), array($objectName => $productsArr), '$push')){
  	   $result["success"]="Added this product successfully to your ".$action."!";
    }else{
        $result["error"]="Please try after sometime!";
    }
}
echo json_encode($result);
?>