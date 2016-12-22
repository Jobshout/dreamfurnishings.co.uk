<?php
switch ($_SERVER['SERVER_NAME']) {
    case "dev.dreamfurnishings.co.uk":
	$conn = new MongoClient( 'mongodb://127.0.0.1:27017/' );
    break;
        
    case "staging.dreamfurnishings.com":
	$conn = new MongoClient( 'mongodb://127.0.0.1:27017/' );
    break;
        
    default:
	$conn = new MongoClient( 'mongodb://127.0.0.1:27017/' );
    break;
}

$db= $conn->DreamFurnishings;
?>