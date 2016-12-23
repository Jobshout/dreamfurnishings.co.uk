<?php
ini_set("display_errors",1);
require_once("include/config_inc.php");

/**db.Products.createIndex(
     { "$**": "text" },
     { name: "TextIndex" }
	)

if($db->products_search->ensureIndex(array( "$**" => "text") , array( "name" => "TextIndex" ) )){
	echo "Text index created for products_search table <br>";
}
if($db->products_search->ensureIndex( array( "product_category.uuid" => 1, "publish_on_web" => 1))){
	echo "Compound Index of 'products_search' created successfully!<br>";
}
if($db->products_search->ensureIndex( array("product_code" =>1) )){
	echo "Index on '[products_search]product_code' created successfully!<br>";
}
if($db->products_search->ensureIndex( array("uuid" =>1) )){
	echo "Index on '[products_search]uuid' created successfully!<br>";
}
if($db->products_search->ensureIndex( array("created_timestamp" =>1) )){
	echo "Index on '[products_search]created_timestamp' created successfully!<br>";
}
if($db->products_search->ensureIndex( array("modified_timestamp" =>1) )){
	echo "Index on '[products_search]modified_timestamp' created successfully!<br>";
}
if($db->products_search->ensureIndex( array("product_category" =>1) )){
	echo "Index on '[products_search]product_category' created successfully!<br>";
}**/

//Products table
if($db->Products->ensureIndex(array( "$**" => "text") , array( "name" => "TextIndex" ) )){
	echo "Text index created for Products table <br>";
}
if($db->Products->ensureIndex( array( "product_images.uuid" => 1))){
	echo "Index of 'Products' created successfully!<br>";
}
if($db->Products->ensureIndex( array( "product_category.uuid" => 1, "publish_on_web" => 1))){
	echo "Compound Index of 'Products : product_images.uuid' created successfully!<br>";
}
if($db->Products->ensureIndex( array("product_code" =>1) )){
	echo "Index on '[Products]product_code' created successfully!<br>";
}
if($db->Products->ensureIndex( array("uuid" =>1) )){
	echo "Index on '[Products]uuid' created successfully!<br>";
}
if($db->Products->ensureIndex( array("created_timestamp" =>1) )){
	echo "Index on '[Products]created_timestamp' created successfully!<br>";
}
if($db->Products->ensureIndex( array("modified_timestamp" =>1) )){
	echo "Index on '[Products]modified_timestamp' created successfully!<br>";
}
if($db->Products->ensureIndex( array("product_category" =>1) )){
	echo "Index on '[Products]product_category' created successfully!<br>";
}
if($db->web_content->ensureIndex( array( "code" => 1, "status" => 1, "type" => 1) )){
	echo "Compound index of 'web_content' created successfully!<br>";
}
if($db->web_content->ensureIndex( array( "uuid" => 1) )){
	echo "Index of '[web_content]uuid' created successfully!<br>";
}
if($db->session->ensureIndex( array("ip_address" => 1,"_id" => 1) )){
	echo "Compound index on 'session' created successfully!<br>";
}

if($db->session->ensureIndex( array("ip_address" => 1) )){
	echo "Index on '[session]ip_address' created successfully!<br>";
}

if($db->collectionToSync->ensureIndex( array( "table_name" => 1, "table_uuid" => 1) )){
	echo "Compound index on 'collectionToSync' created successfully!<br>";
}
if($db->collectionToSync->ensureIndex( array( "uuid" => 1, "table_name" => 1) )){
	echo "Compound index on 'collectionToSync' created successfully!<br>";
}
if($db->collectionToSync->ensureIndex( array( "uuid" => 1) )){
	echo "Index of '[collectionToSync]uuid' created successfully!<br>";
}
if($db->categories->ensureIndex( array( "is_active" => 1, "uuid_top_level_category" => 1) )){
	echo "Compound Index of 'categories' created successfully!<br>";
}

if($db->categories->ensureIndex( array("project_uuid" =>1) )){
	echo "Indexes of 'categories' created successfully!<br>";
}
if($db->Tokens->ensureIndex( array("code" =>1) )){
	echo "Indexes of 'Tokens' created successfully!<br>";
}
if($db->Contacts->ensureIndex( array("uuid" =>1) )){
	echo "Indexes of 'Contacts' created successfully!<br>";
}
if($db->authentication_token->ensureIndex( array("active" =>1) )){
	echo "Indexes of 'authentication_token' created successfully!<br>";
}
if($db->countries->ensureIndex( array("name" =>1) )){
	echo "Indexes of 'authentication_token' created successfully!<br>";
}
if($db->orders->ensureIndex( array("uuid" =>1) )){
	echo "Indexes of 'orders' created successfully!<br>";
}
if($db->orders->ensureIndex( array( "uuid_client" => 1, "status" => 1))){
	echo "Compound Index of 'orders' created successfully!<br>";
}

if($db->orders->ensureIndex( array( "uuid" => 1, "order_items" => 1))){
	echo "Compound Index of 'orders' created successfully!<br>";
}
?>