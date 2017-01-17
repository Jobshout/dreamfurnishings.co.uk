<?php 
require_once("include/config_inc.php");
require_once('include/class.phpmailer.php');
$result=array();

if(isset($_REQUEST['id']) && $_REQUEST['id']!=""){ 
	if($userRecords= $db->Contacts->findOne(array("uuid" => $_REQUEST['id']))){
		//to add authentication_token
		$create_token_entry= array("user_uuid" => $userRecords["uuid"], "created" => time(), "active" => true );
		$mongoCRUDClass->db_insert("authentication_token", $create_token_entry);
		
		//Create HTML For Email 
		$returnSuccMsgFlag=true;
						
		$subject=SITE_NAME." account activation mail";
		
		$user_footer='</table>';
		$user_header  = "<table border='0' style='text-align:left; width:95%; padding:5px;'>";
		$user_header .= "<tr><td colspan='4' style='text-align:left;'>Hi ".$userRecords['First name']." ".$userRecords['Surname'].",\n\n</td></tr>";
		$user_header .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		$user_header .= "<tr><td colspan='4'>This e-mail is to confirm your request to activate your account with <a href='".SITE_WS_PATH."' target='_blank'>".SITE_WS_PATH."</a> Dream Furnishings.</td></tr>";
		$user_header .= "<tr><td colspan='4'>&nbsp;</td></tr>";
		$user_header .= "<tr><td colspan='4'>To validate the e-mail address you entered on <a href='".SITE_WS_PATH."' target='_blank'>".SITE_WS_PATH."</a>, click on the link below or copy the line and paste it into a web browser (if the ENTIRE line does not look like a link you must copy and paste or you will get an error):</td></tr>";
		$user_header .= "<tr><td colspan='4'><a href='".SITE_WS_PATH."login.htm?cc=".$create_token_entry['_id']."&".rand()."'>".SITE_WS_PATH."login.htm?cc=".$create_token_entry['_id']."&".rand()."</a></td></tr>";					  
		$user_header .= "<tr><td colspan='4'>&nbsp;</td></tr>";
						
		$user_html = $user_header.$user_footer;
						
		require_once("include/mailer-details.php");

		//user email
		if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
			//$mail->SMTPDebug  = 2;  
			try {
				$mail->AddReplyTo(ADMIN_EMAIL,SITE_NAME);
				$mail->AddAddress($userRecords['Email'],$userRecords['First name']);
				$mail->SetFrom(ADMIN_EMAIL,SITE_NAME);		
				if(ADMIN_CC_EMAIL!=''){
					$mail->AddCC(ADMIN_CC_EMAIL);
				}
				if(ADMIN_BB_EMAIL!=''){
					$mail->AddBCC(ADMIN_BB_EMAIL);
				}
							
				$mail->Subject = $subject;
	
				$mail->MsgHTML($user_html);
				$mail->Send();
				$mail->ClearAddresses();
			}catch (phpmailerException $e) {
				$returnSuccMsgFlag=false;
				save_email_queue($userRecords['Email'], ADMIN_EMAIL, $subject, $user_html);	// sendto, sendfrom, subject and content
			}
			catch (Exception $e) {
				$returnSuccMsgFlag=false;
				save_email_queue($userRecords['Email'], ADMIN_EMAIL, $subject, $user_html);	// sendto, sendfrom, subject and content
			}
		}else{
			$returnSuccMsgFlag=false;
			save_email_queue($userRecords['Email'], ADMIN_EMAIL, $subject, $user_html);	// sendto, sendfrom, subject and content
		}
					
		if($returnSuccMsgFlag){
			$result['success'] = 'Activation email has been sent successfully to your email address!';
		}else{
			$result['error'] = "Sorry, your request can't be processed now, please try later!";
		}
						
	}else{
		$result['error'] = "Sorry, you are not a registered user!";
	}
}else{
	$result['error'] = "Please enter your email address!";
}
echo json_encode($result);
?>
