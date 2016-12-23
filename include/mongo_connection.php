<?php
switch ($_SERVER['SERVER_NAME']) {
    case "dev.dreamfurnishings.co.uk":
	$conn = new MongoClient( 'mongodb://localhost:27017/' );
    break;
        
    case "staging.dreamfurnishings.com":
	$conn = new MongoClient( 'mongodb://localhost:27017/' );
    break;
        
    default:
	$conn = new MongoClient( 'mongodb://localhost:27017/' );
    break;
}

$db= $conn->DreamFurnishings;
?>