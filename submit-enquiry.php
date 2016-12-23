<?php 
require_once("include/config_inc.php");
require_once('include/class.phpmailer.php');

$result =array();
$c_name = isset($_POST['name']) ? $_POST['name'] : "";
$c_email = isset($_POST['email']) ? $_POST['email'] : "";
$comment = isset($_POST['message']) ? $_POST['message'] : "";
$options = isset($_POST['options']) ? $_POST['options'] : "";
$remoteIPStr= __ipAddress();

$res= array();
if ($c_name!="" && $c_email!="" && $comment!=""){
	$insert_data= array("created_timestamp" => time(), "modified_timestamp" => time(), "status" => 0, "user_name" => $c_name, "user_email_address" => $c_email, "comment" => $comment);
	$query_insert = $db->web_enquiries->insert($insert_data);
	if($query_insert){
		$subjectStr=$c_name." has an enquiry";
			$message='<div style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;font-size:13;color:#333">We have just received the following message from the <a href="'.SITE_WS_PATH.'" target="_blank">'.SITE_WS_PATH.'</a> website:</div>';
			$message.="<br/><table border='0' cellpadding='5' cellspacing='0'>
			<tr><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px;color:#666\">Name</td><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;color:#333\">".$c_name."</td></tr>
			<tr><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px;color:#666\">Email</td><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;color:#333\">".$c_email."</td></tr>";
			if($options!=''){
				$message.="<tr><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px;color:#666\">Selected Options</td><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;color:#333\">".$options."</td></tr>";
			}
			if($comment!=''){
				$message.="<tr><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px;color:#666\">Comment</td><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;color:#333\">".$comment."</td></tr>";
			}
			$message.="<tr><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px;color:#666\">IP Address</td><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px;color:#333\">".$remoteIPStr."</td></tr>";
			$message.='</table>';
			
		require_once("include/mailer-details.php");
		
		if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
			try {
				$mail->AddReplyTo($c_email,$c_name);
				$mail->AddAddress(ADMIN_EMAIL,SITE_NAME);
				$mail->SetFrom($c_email,$c_name);			
				$mail->Subject = $subjectStr;
			
				$mail->MsgHTML($message);
				$mail->Send();
					
			}catch (phpmailerException $e) {
				save_email_queue(ADMIN_EMAIL, $c_email, $subjectStr, $message); // sendto, sendfrom, subject and content
			}
			catch (Exception $e) {
				save_email_queue(ADMIN_EMAIL, $c_email, $subjectStr, $message); // sendto, sendfrom, subject and content
			}
		}else{
			save_email_queue(ADMIN_EMAIL, $c_email, $subjectStr, $message); // sendto, sendfrom, subject and content
		}
		
		$result['success']= "Hi ".$c_name.", your enquiry has been successfully sent. We will contact you soon!";
	}else{
		$result['error']= "Sorry, your request can't be processed now, please try later!";
	}
}else{
	$result['required']= "Please enter value for all fields!";
}
 echo json_encode($result); 
?>
