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

    if($dbResultsData = $db->session->findOne(array("_id" => $realmongoid, "ip_address" => $ipAddressStr))){
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
        		if($db->session->update(array("_id" => $realmongoid), array('$pull' => $set_v))){
        			$productsArr=array("uuid" => $uuid, "Quantity" => $quantity);
        			if(isset($availableOptionsObj) && count($availableOptionsObj)>0){
						foreach($availableOptionsObj as $obj){
							foreach($obj as $key=>$value){
								$productsArr[$key]=$value;
							}
						}
					}
    				$productsArr['UnitPrice']=$unit_price;
    				$db->session->update(array("_id" => $realmongoid), array('$push' => array($objectName => $productsArr)));	
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
   		$session_values= array("ip_address" => $ipAddressStr , $objectName => $productsArr);
    }else{
    	$session_values= array("ip_address" => $ipAddressStr);
    }
    if($db->session->insert($session_values)){
        $result["success"]="Added this product successfully to your ".$action."!";
        setcookie("DreamFurnishingVisitor", $session_values['_id'], time()+60*60*24*365);
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
    if($db->session->update(array("_id" => $realmongoid), array('$push' => array($objectName => $productsArr)))){
       $result["success"]="Added this product successfully to your ".$action."!";
    }else{
        $result["error"]="Please try after sometime!";
    }
}
echo json_encode($result);
?>