<?php 
require_once("include/config_inc.php");
require_once('include/class.phpmailer.php');

$result =array();
$c_blog = isset($_POST['c_blog']) ? $_POST['c_blog'] : "";
$comment = isset($_POST['comment']) ? $_POST['comment'] : "";
$c_email = isset($_POST['c_email']) ? $_POST['c_email'] : "";
$c_name = isset($_POST['c_name']) ? $_POST['c_name'] : "";

$remoteIPStr= __ipAddress();
$res= array();
if ($c_blog!="" && $comment!="" && $c_email!="" && $c_name!=""){
	if($documentdetail = $mongoCRUDClass->db_findone("web_content", array("code" => $c_blog))){
		$blogTitle =$documentdetail["title"];
		$blogUUID =$documentdetail["uuid"];
		
		//if(isset($documentdetail['blog_comments']) && count($documentdetail['blog_comments'])>0){
		
		//}else{
			$comment_entry=array("web_content_uuid"=> $blogUUID, "uuid"=> NewGuid(), "name"=> $c_name.'('.$c_email.')', "object_content"=> $comment, "created_timestamp"=> time(), "modified_timestamp"=> time(),  "code"=> $c_name, "status"=> "false", "order_num"=> 0);
			$set_v= array("objects" => $comment_entry);
			$update_Blog=$mongoCRUDClass->db_update("web_content", array("code" => $c_blog, "uuid" => $blogUUID), $set_v);
		//}
		if($update_Blog){
			//event type 1 for add and 2 for delete
			if($checkSyncTable = $mongoCRUDClass->db_findone("collectionToSync", array("table_name" => "web_content", "table_uuid" => $blogUUID))){
				$updateArr= array("modified" => time(),"sync_state" => 0,"event_type" => 1);
				$mongoCRUDClass->db_update("collectionToSync", array("uuid" => $checkSyncTable['uuid']), $updateArr);
			}else{
				$insertArr= array("uuid" => NewGuid(), "modified" => time(), "table_name" => 'web_content' ,"event_type" => 1 , "table_uuid" => $blogUUID, "sync_state" => 0, "sub_table_name" => "objects");
				$query_insert = $mongoCRUDClass->db_insert("collectionToSync", $insertArr);
			}
					
			//email content
			$message='<div style="font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;font-size:13;color:#333">'.$c_name.' has posted a comment for the <b>'.$blogTitle.'</b> blog:</div>';
			$message.="<br/><table border='0' cellpadding='5' cellspacing='0' >
			<tr style='background-color:#ddd/'>
			<td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px; border:1px solid #ddd; color:#666\">Email</td>
			<td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px; border:1px solid #ddd;color:#333\">".$c_email."</td></tr>";
			if($comment!=''){
				$message.="<tr><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px; border:1px solid #ddd;color:#666\">Comment</td><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:14px; border:1px solid #ddd; color:#333\">".$comment."</td></tr>";
			}
			$message.="<tr><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; border:1px solid #ddd;font-size:12px;color:#666\">IP Address</td><td style=\"font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; border:1px solid #ddd; font-size:14px;color:#333\">".$remoteIPStr."</td></tr>";
				
			$message.='</table>';
			$message.="<br><span style='font-style:italic;font-color:#CC0000'>Note: To display posted comment on website, please change the status to Active from backend!</span>";
			
			$subjectStr=$c_name." has posted a comment";
			require_once("include/mailer-details.php");
			
			if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
				try {
					$mail->AddReplyTo($c_email,$c_name);
					$mail->AddAddress(ADMIN_EMAIL,SITE_NAME);
					$mail->SetFrom($c_email,$c_name);			
					if(ADMIN_CC_EMAIL!=''){
						$mail->AddCC(ADMIN_CC_EMAIL);
					}
					if(ADMIN_BB_EMAIL!=''){
						$mail->AddBCC(ADMIN_BB_EMAIL);
					}
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
			$result['success']= "Hi ".$c_name.", thanks for your comment. It has been posted successfully and will be visible soon!";
		}else{
			$result['error']= "Error in posting comment for this blog, please try after sometime!";
		}
	}else{
		$result['error']= "Error in posting comment for this blog, please try after sometime!";
	}
}else{
	$result['required']= "Please enter value for all fields!";
}
 echo json_encode($result); 
?>
