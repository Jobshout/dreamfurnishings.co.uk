<?php
// using SendGrid's PHP Library
// https://github.com/sendgrid/sendgrid-php

// If you are using Composer (recommended)
require '../vendor/autoload.php';

// If you are not using Composer
// require("path/to/sendgrid-php/sendgrid-php.php");

$from = new SendGrid\Email("Example User", "nehak189@gmail.com");
$subject = "Sending with SendGrid is Fun";
$to = new SendGrid\Email("Example User", "neha.kapoor@tenthmatrix.co.uk");
$content = new SendGrid\Content("text/plain", "and easy to do anywhere, even with PHP");
$mail = new SendGrid\Mail($from, $subject, $to, $content);

$apiKey = getenv('SENDGRID_API_KEY');
$apiKey ="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";
$sg = new \SendGrid($apiKey);

$response = $sg->client->mail()->send()->post($mail);
echo "Status code : ".$response->statusCode()."<br>";
print_r($response);
echo $response->body();


?>