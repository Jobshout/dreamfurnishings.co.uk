<?php           
$updateBillingAddressBool=isset($updateBillingAddress) ? $updateBillingAddress : true; 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
if(!empty($_POST['submit'])){
	if(!$_POST['first_name']){ $err_msg = "Please enter your first name"; }
	elseif(isset($_POST['first_name']) && $_POST['first_name']!="" && !validChr($_POST['first_name'])){ $err_msg = characterMessage('first name'); }
	elseif(!$_POST['last_name']){ $err_msg = "Please enter your last name"; }
	elseif(isset($_POST['last_name']) && $_POST['last_name']!="" && !validChr($_POST['last_name'])){ $err_msg = characterMessage('last name'); }
	elseif(!$_POST['email_address']){ $err_msg = "Please enter your email address"; }
	elseif(isset($_POST['email_address']) && $_POST['email_address']!="" && !validChr($_POST['email_address'])){ $err_msg = characterMessage('email'); }
	elseif(isset($_POST['password']) && $_POST['password']!="" && !validChr($_POST['password'])){ $err_msg = characterMessage('password'); }
	elseif(!$_POST['address_line_1']){ $err_msg = "Please enter Address (Line 1)"; }
	elseif(isset($_POST['address_line_1']) && $_POST['address_line_1']!="" && !validChr($_POST['address_line_1'])){ $err_msg = characterMessage('Address (Line 1)'); }
	elseif(isset($_POST['city']) && $_POST['city']!="" && !validChr($_POST['city'])){ $err_msg =characterMessage('city'); }
	elseif(isset($_POST['postcode']) && $_POST['postcode']!="" && !validChr($_POST['postcode'])){ $err_msg = characterMessage('postcode'); }
	elseif(isset($_POST['country']) && $_POST['country']!="" && !validChr($_POST['country'])){ $err_msg = characterMessage('country'); }
	elseif(isset($_POST['telephone']) && $_POST['telephone']!="" && !validChr($_POST['telephone'])){ $err_msg = characterMessage('telephone'); }
	elseif(isset($_POST['address_line_2']) && $_POST['address_line_2']!="" && !validChr($_POST['address_line_2'])){ $err_msg = characterMessage('Address (Line 2)'); }
	else{	
		if(!isset($err_msg)){

				$first_name=addslashes($_POST["first_name"]);
				$last_name=addslashes($_POST["last_name"]);
				$email_address=$_POST["email_address"];
				
				$addr1=addslashes($_POST["address_line_1"]);
				$addr2=addslashes($_POST["address_line_2"]);
				$city=addslashes($_POST["city"]);
				$postcode=addslashes($_POST["postcode"]);
				$state=addslashes($_POST["state"]);
				$country=addslashes($_POST["country"]);
				$telephone=$_POST["telephone"];
				$uuid=$_POST["uuid"];		
				$time = time();
				
				if($contactsRecord= $db->Contacts->findOne(array("uuid" => $uuid ))){
					$updateArr= array("First name" => $first_name, "Surname" => $last_name, "Email" => $email_address, "address_line_1" => $addr1, "address_line_2" => $addr2, "address_line_3" => $city, "county_or_state" => $state, "post_zip_code" => $postcode, "country" => $country, "Mobile" => $telephone);
					if(isset($_POST['password']) && $_POST['password']!=""){
						$password=addslashes($_POST["password"]);
						$md5_password=md5($password);
						$updateArr["zWebPassword"] = $md5_password;
					}
					$update_user_content=$db->Contacts->update(array("uuid" => $contactsRecord['uuid']), array('$set' => $updateArr));
					if($update_user_content){
						//to add in collectionToSync
						if($records= $db->collectionToSync->findOne(array("table_uuid" => $uuid, "table_name" => "Contacts"))){
							$update_sync_entry= array("modified" => time(), "sync_state" => 0 );
							$db->collectionToSync->update(array("table_uuid" => $contactsRecord['uuid'], "table_name" => "Contacts"), array('$set' => $update_sync_entry));
						}else{
							$collectionIDStr=NewGuid();
							$create_sync_entry= array("uuid" => $collectionIDStr, "modified" => time(), "table_uuid" => $contactsRecord['uuid'], "table_name" =>"Contacts", "event_type" => 1, "sync_state" => 0 );
							$db->collectionToSync->insert($create_sync_entry);
						}
						
						if($updateBillingAddressBool){
							
							$full_addr=$first_name." ".$last_name;
							if($addr1!=""){
								$full_addr.="<br>".$addr1;
							}
							if($addr2!=""){	$full_addr.=", ".$addr2;	}
							if($city!=""){	$full_addr.=", ".$city;	}
							if($state!=""){	$full_addr.=", ".$state;	}
							if($addr2!=""){	$full_addr.=" (".$postcode.")";	}
							if($country!=""){	$full_addr.=", ".$country;	}
							
							$subtotal=0; $discount=0; $tax_rate=0; $tax_code=""; $grand_total= 0; $total_tax=0; 
							$transaction_items=array();
							
							if(isset($session_values) && $session_values["user_uuid"]!="" && $session_values["login_status"]==true){
								if(isset($session_values["subtotal"])){
									$subtotal= $session_values["subtotal"];
								}
								if(isset($session_values["discount"])){
									$discount= $session_values["discount"];
								}
								if(isset($session_values["tax_rate"])){
									$tax_rate= $session_values["tax_rate"];
								}
								if(isset($session_values["tax_code"])){
									$tax_code= $session_values["tax_code"];
								}
								if(isset($session_values["total"])){
									$grand_total= $session_values["total"];
								}
								if(isset($session_values["total_tax"])){
									$total_tax= $session_values["total_tax"];
								}
								
								if(isset($session_values['cart']) && count($session_values['cart'])>0){
									$itemsSubArr=array();
									foreach($session_values['cart'] as $cartItems){
										$itemsSubArr["uuid"]=NewGuid();
										$itemsSubArr["uuid_product"]=$cartItems['uuid'];
										$itemsSubArr["created_timestamp"]=time();	
										$qunatity=1;
										if(isset($cartItems['Quantity'])){
											$qunatity=$cartItems['Quantity'];
										}
										$itemsSubArr["item_hours"]=$qunatity;	
										$itemsSubArr["item_rate"]=$cartItems['UnitPrice'];
										$total_amount=	floatval($qunatity)*floatval($cartItems['UnitPrice']);
										$itemsSubArr["item_amount"]=$total_amount;		
										$itemsSubArr["modified_timestamp"]=time();
										$comments="";
										foreach($cartItems as $key=>$value){
											if($key!="uuid" && $key!="Quantity" && $key!="UnitPrice"){
												$comments.=$key.": ".$value."<br>";
											}
										}	
										$itemsSubArr["comments"]=$comments;	
										$transaction_items[]=$itemsSubArr;				
									}
								}
							}
							
							$totalPrice= $subtotal;
							$trans_entry= array("pin_or_passcode"=> $postcode, "modified_timestamp"=>(int)time(), "discount_applied"=> (double)$discount, "bill_to"=> $full_addr, "total_due_without_tax"=> (double)$subtotal, "order_subtotal" => (double)$subtotal, "total_due_with_tax"=> (double)$grand_total, "tax_rate"=> $tax_rate, "total_tax" =>$total_tax, "tax_code" => $tax_code, "status"=> 0, "Total"=> (double)$totalPrice, "order_items" => $transaction_items);
							
							$check_for_existingTrans= $db->orders->find(array("uuid_client" => $contactsRecord['uuid'], "status" => array('$lt' => 2)));
							if($check_for_existingTrans->count() >0){
								foreach($check_for_existingTrans as $existingTrans){
									$trans_uuid=$existingTrans['uuid'];
									if(!isset($existingTrans['order_history'])){
										$trans_entry["order_history"]=array();
									}
									$db->orders->update(array("uuid" => $existingTrans['uuid']), array('$set' => $trans_entry));
									break;
								}
							}else{
								$trans_id= nextID("orders");
								$trans_entry["uuid_client"]=$contactsRecord['uuid'];
								$trans_entry["full_order_number"]=get_invoice_number($trans_id);
								$trans_entry["created_timestamp"]=(int)time();
								$trans_entry["order_id"]=(int)$trans_id;
								$trans_entry["order_date"]=date("Y-m-d");
								$trans_uuid=NewGuid();
								$trans_entry["uuid"]=$trans_uuid;
								$trans_entry["order_history"]=array();
								$db->orders->insert($trans_entry);
							}
				
							$session_update= array("checkout_state"=>1, "transaction_uuid" => $trans_uuid); // checkout_state=1 confirmation of address, checkout_state=0 items are in cart
							if($db->session->update(array("_id" => $session_values['_id']), array('$set' => $session_update))){
								header("Location: order-confirmation.htm");
								exit;
							}
						}else{
							$succ_msg = 'Your account details updated successfully!.';
						}
					}
				}else{
					$err_msg = "User with this email already doesnot exists, please contact us!";
				}			
		}
	}
}
?>
