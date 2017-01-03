<?php 
require_once("include/config_inc.php");
require_once("include/main_header.php");
require_once('include/class.phpmailer.php');

if(!empty($_POST['submit'])){
	if(!$_POST['first_name']){ $err_msg = "Please enter your first name"; }
	elseif(isset($_POST['first_name']) && $_POST['first_name']!="" && !validChr($_POST['first_name'])){ $err_msg = characterMessage('first name'); }
	elseif(!$_POST['last_name']){ $err_msg = "Please enter your last name"; }
	elseif(isset($_POST['last_name']) && $_POST['last_name']!="" && !validChr($_POST['last_name'])){ $err_msg = characterMessage('last name'); }
	elseif(!$_POST['email_address']){ $err_msg = "Please enter your email address"; }
	elseif(isset($_POST['email_address']) && $_POST['email_address']!="" && !validChr($_POST['email_address'])){ $err_msg = characterMessage('email'); }
	elseif(!$_POST['password']){ $err_msg = "Please enter password"; }
	elseif(isset($_POST['password']) && $_POST['password']!="" && !validChr($_POST['password'])){ $err_msg = characterMessage('password'); }
	elseif(!$_POST['confirm_password']){ $err_msg = "Please enter confirm password"; }
	elseif($_POST['password'] != $_POST['confirm_password']){ $err_msg = "Passwords needs to be same"; }
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
				$password=addslashes($_POST["password"]);
				$md5_password=md5($password);
				$addr1=addslashes($_POST["address_line_1"]);
				$addr2=addslashes($_POST["address_line_2"]);
				$city=addslashes($_POST["city"]);
				$postcode=addslashes($_POST["postcode"]);
				$state=addslashes($_POST["state"]);
				$country=addslashes($_POST["country"]);
				$telephone=$_POST["telephone"];
				$GUID= NewGuid();
				
				$time = time();
				
				$records= $db->Contacts->find(array("Email" => $email_address ));
				if($records->count() == 0){
					$encoded_uuid=md5($GUID);
					
					$insert_data= array("uuid" => $GUID, "DateAdded" => $time, "First name" => $first_name, "Surname" => $last_name, "Email" => $email_address, "zWebPassword" => $md5_password, "address_line_1" => $addr1, "address_line_2" => $addr2, "address_line_3" => $city, "county_or_state" => $state, "post_zip_code" => $postcode, "country" => $country, "Mobile" => $telephone, "AllowWebAccess" => false);
					$query_insert = $mongoCRUDClass->db_insert("Contacts", $insert_data);
					if($query_insert){
					
					//to add authentication_token
					$create_token_entry= array("user_uuid" => $GUID, "created" => time(), "active" => true );
					$mongoCRUDClass->db_insert("authentication_token", $create_token_entry);
					
					//to add in collectionToSync
					$collectionIDStr=NewGuid();
					$create_sync_entry= array("uuid" => $collectionIDStr, "modified" => time(), "table_uuid" => $GUID, "table_name" =>"Contacts", "event_type" => 1, "sync_state" => 0 );
					$mongoCRUDClass->db_insert("collectionToSync", $create_sync_entry);
					
					//Create HTML For Email 
						$returnSuccMsgFlag=true;
						
						$subject="Registration Confirmation Mail";
						$user_footer='</table>';
						$HTMLEmailBodyTxt='';
						
						$admin_header  = "<table border='0' style='text-align:left; width:470px; padding:5px;'>";
						$admin_header .= "<tr><td colspan='4' style='text-align:left;'>".$first_name." ".$last_name." has been registered with Dream Furnishings. Account details are given below:</td></tr>";
						
						$user_header  = "<table border='0' style='text-align:left; width:95%; padding:5px;'>";
						$user_header .= "<tr><td colspan='4' style='text-align:left;'>Hi ".$first_name." ".$last_name.",\n\n</td></tr>";
						$user_header .= "<tr><td colspan='4'>&nbsp;</td></tr>";
						$user_header .= "<tr><td colspan='4'>This e-mail is to confirm your registration with <a href='".SITE_WS_PATH."' target='_blank'>".SITE_WS_PATH."</a> Dream Furnishings.</td></tr>";
						$user_header .= "<tr><td colspan='4'>&nbsp;</td></tr>";
						$user_header .= "<tr><td colspan='4'>To validate the e-mail address you entered on <a href='".SITE_WS_PATH."' target='_blank'>".SITE_WS_PATH."</a>, click on the link below or copy the line and paste it into a web browser (if the ENTIRE line does not look like a link you must copy and paste or you will get an error):</td></tr>";
						$user_header .= "<tr><td colspan='4'><a href='".SITE_WS_PATH."login.htm?cc=".$create_token_entry['_id']."&".rand()."'>".SITE_WS_PATH."login.htm?cc=".$create_token_entry['_id']."&".rand()."</a></td></tr>";					  
						$user_header .= "<tr><td colspan='4'>&nbsp;</td></tr>";
						
						$HTMLEmailBodyTxt .= "<tr><td colspan='4'>&nbsp;</td></tr>";
						$HTMLEmailBodyTxt .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>Name:</td>
											  <td style='text-align:left;'>".$first_name." ".$last_name."</td></tr>";
						$HTMLEmailBodyTxt .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>Email Address:</td>
											  <td style='text-align:left;'>".$email_address."</td></tr>";
						$HTMLEmailBodyTxt .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>Password:</td>
											  <td style='text-align:left;'>".$password."</td></tr>";
						if($addr1 !='' || $addr2 !=''){
						$HTMLEmailBodyTxt .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>Address:</td>
											  <td style='text-align:left;'>".$addr1." ".$addr2."</td></tr>";
						}
						if($city !=''){					  
						$HTMLEmailBodyTxt .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>City:</td>
											  <td style='text-align:left;'>".$city."</td></tr>";
						}
						
						if($country !=''){
						$HTMLEmailBodyTxt .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>Country:</td>
											  <td style='text-align:left;'>".$country."</td></tr>";
						}
						if($postcode !=''){
						$postcode .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>Zip/Postal Code:</td>
											  <td style='text-align:left;'>".$postcode."</td></tr>";
						}
						if($telephone !=''){
						$HTMLEmailBodyTxt .= "<tr><td style='text-align:left;font-weight:bold;width:135px;'>Telephone No.:</td>
											  <td style='text-align:left;'>".$telephone."</td></tr>";
						}
						$HTMLEmailBodyTxt .= "<tr><td colspan='4'>&nbsp;</td></tr>";
						$HTMLEmailBodyTxt .= "<tr><td colspan='4'><div><strong>IP:</strong> <em>".__ipAddress()."</em></div></td></tr>";
						
						$admin_html = $admin_header.$HTMLEmailBodyTxt.'</table>';
						$user_html = $user_header.$user_footer;
						require_once("include/mailer-details.php");
						
					//admin email
					if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
						try {
							$mail->AddReplyTo($email_address,$first_name);
							$mail->AddAddress(ADMIN_EMAIL,SITE_NAME);
							$mail->SetFrom($email_address,$first_name);		
			
							$mail->Subject = $subject;
			
							$mail->MsgHTML($admin_html);
							$mail->Send();
							$mail->ClearAddresses();
							
						}catch (phpmailerException $e) {
							save_email_queue(ADMIN_EMAIL, $email_address, $subject, $admin_html); // sendto, sendfrom, subject and content
						}
						catch (Exception $e) {
							save_email_queue(ADMIN_EMAIL, $email_address, $subject, $admin_html); // sendto, sendfrom, subject and content
						}
					}else{
						save_email_queue(ADMIN_EMAIL, $email_address, $subject, $admin_html); // sendto, sendfrom, subject and content
					}
					
					//user email
					if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
						try {
							$mail->AddReplyTo(ADMIN_EMAIL,SITE_NAME);
							$mail->AddAddress($email_address,$first_name);
							$mail->SetFrom(ADMIN_EMAIL,SITE_NAME);		
							if(ADMIN_CC_EMAIL!=''){
								$mail->AddCC(ADMIN_CC_EMAIL);
							}
							if(ADMIN_BB_EMAIL!=''){
								$mail->AddBCC(ADMIN_BB_EMAIL);
							}
							
							$mail->Subject = $subject;
			
							$mail->MsgHTML($user_html);
							$mail->Send();
							$mail->ClearAddresses();
						}catch (phpmailerException $e) {
							$returnSuccMsgFlag=false;
							save_email_queue($email_address, ADMIN_EMAIL, $subject, $user_html);	// sendto, sendfrom, subject and content
						}
						catch (Exception $e) {
							$returnSuccMsgFlag=false;
							save_email_queue($email_address, ADMIN_EMAIL, $subject, $user_html);	// sendto, sendfrom, subject and content
						}
					}else{
						$returnSuccMsgFlag=false;
						save_email_queue($email_address, ADMIN_EMAIL, $subject, $user_html);	// sendto, sendfrom, subject and content
					}
					
						if($returnSuccMsgFlag){
							$succ_msg = 'Thank you for registration with '.SITE_NAME.'. Please check your email.';
						}else{
							$err_msg = "Sorry, your request can't be processed now, please try later!";
						}
						
					}
				}else{
					$err_msg = "User with this email already exists, please confirm your registration from email sent to you or contact us!";
				}
				
				unset($_POST['first_name']);
				unset($_POST['last_name']);
				unset($_POST['email_address']);
				unset($_POST['address_line_1']);
				unset($_POST['city']);
				unset($_POST['postcode']);
				unset($_POST['country']);
				unset($_POST['telephone']);
				unset($_POST['password']);
				unset($_POST['confirm_password']);
			
		}
	}
}
?>
<body>
<?php require_once("include/header.php"); ?>    
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
				<div class="col-md-8 col-sm-8">
            		<h1>Register now</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb">
              				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active">Register</li>
            			</ol>
					</div>
          		</div>
        	</div>
      	</div>
    </div>
	<div class="container">
		<div class="row content">
			<div class="col-md-12">
				<form class="form-signin wow fadeInUp animated" role="form" name="register" id="register" method="post" style="visibility: visible; animation-name: fadeInUp;" data-wow-animation-name="fadeInUp">
                	<h2 class="form-signin-heading">Register now</h2>
					<?php if(isset($err_msg)){ ?>
			 			<div class="alert alert-danger alert-dismissable">
  							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  							<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $err_msg; ?>
						</div>
					<?php }else if(isset($succ_msg)){ ?>
						<div class="alert alert-success ">    
  							<i class=" glyphicon glyphicon-ok-circle Added to your Cart"></i> <?php echo $succ_msg; ?>
  						</div>
					<?php } ?>
                	<div class="login-wrap">
                		<div class="row">
                            <div class="col-md-6">
                              	<div class="form-group">
                                	<input class="form-control" placeholder="First Name*" autofocus="" type="text" id="first_name" name="first_name">
                              	</div>                             
                            </div>
                            <div class="col-md-6">
                              	<div class="form-group">
                                	<input class="form-control" placeholder="Last Name*" type="text" id="last_name" name="last_name">
                              	</div>
                            </div>
						</div>
                   		<div class="row">
							<div CLASS="col-md-12"><div class="form-group">
                    			<input class="form-control" placeholder="Address 1*" type="text" id="address_line_1" name="address_line_1">
                    		</div></div>
                    	</div>
                    	<div class="row">
							<div CLASS="col-md-12"><div class="form-group">
                    			<input class="form-control" placeholder="Address 2" type="text" id="address_line_2" name="address_line_2">
                    		</div></div>
                    	</div>
                    	<div class="row">
                            <div class="col-md-6">
                              	<div class="form-group">
                              		<input class="form-control" placeholder="City*" type="text" id="city" name="city">
                              	</div>                             
                            </div>
                            <div class="col-md-6">
                              	<div class="form-group">
                    				<input class="form-control" placeholder="State/County*" type="text" id="state" name="state">
                    			</div>
                    		</div>
                    	</div>
                    	<div class="row">
							<div CLASS="col-md-12"><div class="form-group">
                    	<select class="form-control" id="country" name="country">
                    		<option value="">--Select Country--</option>
							<?php $country_table = $db->countries->find();
								if(count($country_table)>0){
									foreach($country_table as $country){
										echo '<option value="'.$country['name'].'">'.$country['name'].'</option>';
									}
								}
							?>
						</select>
						</div></div></div>
						<div class="row">
                            <div class="col-md-6">
                              	<div class="form-group">
                              		<input class="form-control" placeholder="Post Code*" type="text" id="postcode" name="postcode">
                              	</div>
                            </div>
                            <div class="col-md-6">
                              	<div class="form-group">
                    				<input class="form-control" placeholder="Telephone*" type="text" id="telephone" name="telephone">
                    			</div>
                    		</div>
                    	</div>
                    	<div class="row">
							<div CLASS="col-md-12">
                    			<input class="form-control" placeholder="Email*" type="text" id="email_address" name="email_address">
                    		</div>
                    	</div>
                    	<div class="row">
                            <div class="col-md-6">
                              	<div class="form-group">
                    				<input class="form-control" placeholder="Password*" type="password" id="password" name="password">
                    			</div>
                    		</div>
                            <div class="col-md-6">
                              	<div class="form-group">
					 				<input class="form-control" placeholder="Confirm password*" type="password"  id="confirm_password" name="confirm_password">
					 			</div>
					 		</div>
					 	</div>
                    	<!--<label class="checkbox" style="margin-left: 23px;">
                        	<input value="agree this condition" type="checkbox"> I agree to the Terms of Service and Privacy Policy
                    	</label>-->
                    	<input class="btn btn-lg btn-danger btn-block" type="submit" name="submit" value="Submit">
						<div STYLE="padding-top:10px;">Already Registered ?
                        <a title="Login" href="login.htm" class="btn btn-default btn-sm">Login</a>
                    	</div>
                	</div>
           		</form>
			</div>
		</div>
	</div>      
</section>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
<script src="js/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(function () {
       	$( "#register" ).validate( {
        	errorElement: "em",
			rules: {
				first_name: "required",
				last_name: "required",
				email_address : { required :true, email: true},
				address_line_1 : "required",
				city : "required",
				state : "required",
				postcode : "required",
				country : "required",
				telephone : "required",
				password : { required: true, minlength: 6 },
				confirm_password : { required: true, equalTo: "#password" }

			},
 			messages: {
    			first_name: "Please specify your first name",
    			last_name: "Please specify your last name",
    			email_address: {
      				required: "We need your email address for registeration",
      				email: "Please enter a valid email address"
    			},
    			address_line_1: "Please enter your address",
    			city: "Please specify your city",
    			state: "Please specify your state/county",
    			postcode: "Please specify your postcode",
    			country: "Please specify your country",
    			telephone: "Please enter your telephone",
    			password: {
      				required: "Please enter password",
      				minlength: "Minimum length of password need to be 6"
    			},
    			confirm_password: {
      				required: "Please enter confirm password",
      				equalTo: "Passwords don't match"
    			}
  			}
		});
    });
</script>
</body>
</html>
