<?php

// If running this outside of this context, use the following include and
// comment out the two includes below
// require __DIR__ . '/vendor/autoload.php';
include(dirname(__DIR__) . '/lib/Client.php');


// This gets the parent directory, for your current directory use getcwd()
$path_to_config = dirname(__DIR__);
$apiKey = getenv('SENDGRID_API_KEY');
$headers = ['Authorization: Bearer ' . $apiKey];
$client = new SendGrid\Client('https://api.sendgrid.com', $headers, '/v3');

// GET Collection
$query_params = ['limit' => 100, 'offset' => 0];
$request_headers = ['X-Mock: 200'];



echo time();

?>