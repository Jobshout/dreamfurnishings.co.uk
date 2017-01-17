<?php           
if(!empty($_POST['sign_in'])){
	$email=$_POST['email_address'];
	$action=$_POST['action'];
	$password=addslashes($_POST['password']);
	$md5_password=md5($password);
	
if($email!="" && validChr($email)){
	if($user = $mongoCRUDClass->db_findone("Contacts", array("Email" => $email))){
		//if($user['AllowWebAccess']==true || $user['AllowWebAccess']=="true"){
			if($action=="requestnewpassword"){
				//to add authentication_token
				$create_token_entry= array("user_uuid" => $user["uuid"], "created" => time(), "active" => true );
			
				if($mongoCRUDClass->db_insert("authentication_token", $create_token_entry)){
			
					$succ_msg = 'A link to reset your password has been sent to you. Please check your email.';
			
					$user_html  = "<table border='0' style='text-align:left; width:95%; padding:5px;'>";
					$user_html .= "<tr><td colspan='4' style='text-align:left;'>Hi ".$user["First name"]." ".$user["Surname"].",\n\n</td></tr>";
					$user_html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
					$user_html .= "<tr><td colspan='4'>To regenerate your password, please click on the link below or copy the line and paste it into a web browser (if the ENTIRE line does not look like a link you must copy and paste or you will get an error):</td></tr>";
					$user_html .= "<tr><td colspan='4'><a href='".SITE_WS_PATH."regenerate_password.htm?token=".$create_token_entry['_id']."&".rand()."'>".SITE_WS_PATH."regenerate_password.htm?token=".$create_token_entry['_id']."&".rand()."</a></td></tr>";					  
					$user_html .= "<tr><td colspan='4'>&nbsp;</td></tr>";
					$user_html .= "<tr><td colspan='4'>If case you don't requested for this action, please contact us!</td></tr>";
					$user_html .= "</table>";
			
					$subjectStr=SITE_NAME." password reset";	
	
					require_once("include/mailer-details.php");
			
					if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){	
						try {
							$mail->AddReplyTo(ADMIN_EMAIL,SITE_NAME);
							$mail->AddAddress($user["Email"],$user["First name"]);
							$mail->SetFrom(ADMIN_EMAIL,SITE_NAME);	
							if(ADMIN_CC_EMAIL!=''){
								$mail->AddCC(ADMIN_CC_EMAIL);
							}
							if(ADMIN_BB_EMAIL!=''){
								$mail->AddBCC(ADMIN_BB_EMAIL);
							}
							if(ADMIN_CC_WEBMASTER!=''){
								$mail->AddCC(ADMIN_CC_WEBMASTER);
							}
							$mail->Subject = $subjectStr;
			
							$mail->MsgHTML($user_html);
							$mail->Send();
	
						}catch (phpmailerException $e) {
							$err_msg = "Sorry, your request can't be processed now, please try later!";
							save_email_queue($user["Email"], ADMIN_EMAIL, $subjectStr, $user_html); // sendto, sendfrom, subject and content
						}
						catch (Exception $e) {
							$err_msg = "Sorry, your request can't be processed now, please try later!";
							save_email_queue($user["Email"], ADMIN_EMAIL, $subjectStr, $user_html); // sendto, sendfrom, subject and content
						}
					}else{
						$err_msg = "Sorry, your request can't be processed now, please try later!";
						save_email_queue($user["Email"], ADMIN_EMAIL, $subjectStr, $user_html); // sendto, sendfrom, subject and content
					}
				}else{
					$err_msg = "Sorry, your request can't be processed now, please try later!";
				}
			}
			else{
				if($user['AllowWebAccess']==true || $user['AllowWebAccess']=="true"){
					if($md5_password==$user['zWebPassword']){
						$userCurrentIP= __ipAddress();
				
						$session_exits = $mongoCRUDClass->db_findone("session", array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"])));
						if($session_exits){
							$session_update= array("last_loggedIn"=>time(), "user_uuid" => $user['uuid'], "login_status" => true, "ip_address" => $userCurrentIP);
							$session_details= $mongoCRUDClass->db_update("session", array("_id" => $session_exits['_id']), $session_update);
					
							if(isset($_POST['referer']) && $_POST['referer']!=''){
								header("location:".$_POST['referer']."&".rand());
								exit;
							}elseif(isset($_REQUEST['redirect']) && $_REQUEST['redirect']!=''){
								header("location:".$_REQUEST['redirect'].".htm?".rand());
								exit;
							}else{
								header("location:index.htm?".rand());
								exit;
							}
						}
					}else{
						$err_msg="Invalid password!";
					}
				}else{
					$err_msg= $email.' your account is inactive. If you want to activate your account, please click this button : <a title="Activate my account" href="javascript:void(0)" onClick="activateUserAccount(\''.$user['uuid'].'\')" class="btn btn-primary btn-xs">Activate my account</a> and this will send you a confirmation email.';
				}
			}
		//}else{
		//	$err_msg= $email.' your account is inactive. If you want to activate your account, please click this button : <a title="Activate my account" href="javascript:void(0)" onClick="activateUserAccount(\''.$user['uuid'].'\')" class="btn btn-primary btn-xs">Activate my account</a> and this will send you a confirmation email.';
		//}
	}else{
		$err_msg= $email."  not a registered user!";	
	}

}else{  $err_msg = characterMessage("email"); }
}
?>
