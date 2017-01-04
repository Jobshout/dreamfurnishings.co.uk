<?php
$mailerDetailsAvailableInDbFlag=false;
$smtpservice="gmail";

$defaultSettingsFile="../public_ftp/includes/mailer-details.php";

if (file_exists($defaultSettingsFile)) {
	require_once($defaultSettingsFile);
	$mailerDetailsAvailableInDbFlag=true;
}else{
	$tokensQry= $db->Tokens->find(array("code" => array('$in' => array('dreamfurnishing-mailer-host','dreamfurnishing-mailer-username','dreamfurnishing-mailer-password'))));
	if($tokensQry->count()>0){
		foreach($tokensQry as $token){	
			if(isset($token["contentTxt"]) && $token["contentTxt"]!=""){
			
				if(isset($token["code"]) && $token["code"]=="dreamfurnishing-mailer-host"){
					$mailerHostStr=$token["contentTxt"];
				}elseif(isset($token["code"]) && $token["code"]=="dreamfurnishing-mailer-username"){
					$mailerUsernameStr=$token["contentTxt"];
				}elseif(isset($token["code"]) && $token["code"]=="dreamfurnishing-mailer-password"){
					$mailerPasswordStr=$token["contentTxt"];
				}
			}
		}
	}

	if(isset($mailerHostStr) && $mailerHostStr!="" && isset($mailerPasswordStr) && $mailerPasswordStr!="" && isset($mailerUsernameStr) && $mailerUsernameStr!=""){
		$mailerDetailsAvailableInDbFlag=true; //set this flag true, when all found in database

		$mail = new PHPMailer(true); 		// the true param means it will throw exceptions on errors, which we need to catch
		$mail->Charset = 'utf-8';
		$mail->IsSMTP();                    // Set mailer to use SMTP

		$mail->Host = $mailerHostStr;  // Specify main and backup server
		$mail->SMTPAuth = true;             // Enable SMTP authentication
		$mail->Username = $mailerUsernameStr;    // SMTP username
		$mail->Password = $mailerPasswordStr;     // SMTP password
	}
}

if(isset($smtpservice) && $smtpservice=="gmail"){
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
}

//mailer emails
$adminEmailAddr="jobshout421@gmail.com";
$bbEmailAddr=$adminEmailAddr;
$ccEmailAddr="";

$fetchAdminEmails= $db->Tokens->find(array("code" => array('$in' => array('dreamfurnishing-admin-email','dreamfurnishing-cc-email','dreamfurnishing-bb-email'))));
	if($fetchAdminEmails->count()>0){
		foreach($fetchAdminEmails as $token){	
			if(isset($token["contentTxt"]) && $token["contentTxt"]!=""){
			
				if(isset($token["code"]) && $token["code"]=="dreamfurnishing-admin-email"){
					$adminEmailAddr=$token["contentTxt"];
				}elseif(isset($token["code"]) && $token["code"]=="dreamfurnishing-bb-email"){
					$bbEmailAddr=$token["contentTxt"];
				}elseif(isset($token["code"]) && $token["code"]=="dreamfurnishing-cc-email"){
					$ccEmailAddr=$token["contentTxt"];
				}
			}
		}
	}
define("ADMIN_EMAIL", $adminEmailAddr);
define("ADMIN_CC_EMAIL", $ccEmailAddr);
define("ADMIN_BB_EMAIL", $bbEmailAddr);
?>
