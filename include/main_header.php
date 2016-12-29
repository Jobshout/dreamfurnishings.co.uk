<?php
header('Content-Type: text/html; charset=utf-8'); //header('Content-Type: text/html; charset=ISO-8859'); 
if(!isset($pWindowTitleTxt))$pWindowTitleTxt = "Welcome To DreamFurnishing";
if(!isset($pMetaKeywordsTxt))$pMetaKeywordsTxt = "DreamFurnishing";
if(!isset($pMetaDescriptionTxt))$pMetaDescriptionTxt = "Dream Furnishing";

$isUserSignedInBool=false;
if(isset($_COOKIE["DreamFurnishingVisitor"]) && $_COOKIE["DreamFurnishingVisitor"]!=""){

	if($session_values = $mongoCRUDClass->db_findone("session", array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"])))){
		if(isset($session_values['login_status']) && $session_values['login_status']==true){
			if($userLoggedIn= $mongoCRUDClass->db_findone("Contacts", array("uuid" => $session_values['user_uuid'], "AllowWebAccess" => true))){
				$isUserSignedInBool=true;
			}else{
				header("Location: logout.htm");
			}
		}
	}else{
		$ipAddressStr= __ipAddress();
		$session_values= array("login_status" => false, "ip_address" => $ipAddressStr);
		$mongoCRUDClass->db_insert("session", $session_values);
		setcookie("DreamFurnishingVisitor", $session_values['_id'], time() + (86400 * 30)); // 86400 = 1 day
	}
}
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title><?php if(isset($pWindowTitleTxt))echo $pWindowTitleTxt;?></title>
<meta name="keywords" content="<?php if(isset($pMetaKeywordsTxt))echo $pMetaKeywordsTxt;?>"></meta>
<meta name="description" content="<?php if(isset($pMetaDescriptionTxt))echo $pMetaDescriptionTxt;?>"></meta>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/custom.css" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
<link href="css/jquery.smartmenus.bootstrap.css" rel="stylesheet">

 <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- This css is used for old menu -->
    <link rel="stylesheet" type="text/css" href="css/menu.css" />