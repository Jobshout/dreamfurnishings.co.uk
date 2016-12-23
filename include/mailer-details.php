<?php

$mail = new PHPMailer(true); 		// the true param means it will throw exceptions on errors, which we need to catch
$mail->Charset = 'utf-8';
$mail->IsSMTP();                    // Set mailer to use SMTP

$mail->Host = 'smtp.sendgrid.net';  // Specify main and backup server
$mail->SMTPAuth = true;             // Enable SMTP authentication
$mail->Username = 'tenthmatrix';    // SMTP username
$mail->Password = 'z3F-602-6rt-FGH';     // SMTP password

?>
