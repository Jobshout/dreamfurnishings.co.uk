<?php 
require_once("include/config_inc.php");
require_once('include/class.phpmailer.php');
require_once("include/mailer-details.php");
$sentEmailsCount=0;
$pendingEmailsCount=0;
$totalPendingEmailsCount=0;

$dbResultsData = $db->email_queue->find(array('status' => array('$gte' => 0)))->sort(array("modified_timestamp" => 1));
if($dbResultsData->count()>0){
	$totalPendingEmailsCount=$dbResultsData->count();
	
	foreach($dbResultsData as $dbRowData){
		$emailFailureBool=false;
		if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
			try {
				$mail->AddReplyTo($dbRowData['sender_email_address']);
				$mail->AddAddress($dbRowData['recipient_email_address']);
				$mail->SetFrom($dbRowData['sender_email_address']);		
			
				$mail->Subject = $dbRowData['subject'];
				$mail->MsgHTML($dbRowData['email_content']);
				$mail->Send();
				$mail->ClearAddresses();
			}catch (phpmailerException $e) {
				$emailFailureBool=true;
			}
			catch (Exception $e) {
				$emailFailureBool=true;
			}
		}else{
			$emailFailureBool=true;
		}
		if($emailFailureBool){
			$changeStatusNum=intval($dbRowData['status'])+1;
			$pendingEmailsCount++;
		}else{
			$changeStatusNum=-1;
			$sentEmailsCount++;
		}
		$mongoCRUDClass->db_update("email_queue", array("_id" => $dbRowData['_id']), array("status" => $changeStatusNum));
	}
}
$resultJson=array("iTotalRecords" => $totalPendingEmailsCount, "totalSentEmails" => $sentEmailsCount, "totalPendingEmails" => $pendingEmailsCount);
echo json_encode($resultJson);
?>