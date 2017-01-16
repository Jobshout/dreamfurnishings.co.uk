<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
require_once("include/config_inc.php");
require_once("include/main_header.php");

if(isset($isUserSignedInBool) && $isUserSignedInBool==true){
	if(!isset($userLoggedIn)){
		header("Location: logout.htm");
		exit;
	}
}else{
	header("Location: login.htm?redirect=myaccount");
	exit;
}

$updateBillingAddress=false;
require_once("save_contact.php");
?>
</head>
<body>
<?php require_once("include/header.php"); ?>	  
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1>Account</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
             				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active">My account</li>
            			</ol>
					</div>
          		</div>
        	</div>
		</div>
	</div>

	<div class="container">
		<div class="row content">
			<div class="col-md-12">
				<div class="panel-body">
					<form role="form" name="myaccount" id="myaccount" method="post" >
					<div class="row">
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
  						<div class="col-sm-6">
							<fieldset>
      							<legend>Your Personal Details</legend>
      							<div class="form-group">
       								<label class="control-label" for="first_name">First Name <sup CLASS="required">*</sup> </label>
        							<input name="first_name" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['First name'])){ echo $userLoggedIn['First name']; } ?>" placeholder="First Name" id="first_name" class="form-control inputControl" type="text">
        							<input name="uuid" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['uuid'])){ echo $userLoggedIn['uuid']; } ?>" id="uuid" class="form-control inputControl" type="hidden">
      							</div>
      							<div class="form-group ">
        							<label class="control-label" for="last_name">Last Name <sup CLASS="required">*</sup> </label>
        							<input name="last_name" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['Surname'])){ echo $userLoggedIn['Surname']; } ?>" placeholder="Last Name" id="last_name" class="form-control inputControl" type="text">
      							</div>
      							<div class="form-group  new_user" >
        							<label class="control-label" for="email_address">Confirm E-Mail <sup CLASS="required">*</sup> </label>
        							<input name="email_address" readonly value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['Email'])){ echo $userLoggedIn['Email']; } ?>" placeholder="E-Mail" id="email_address" class="form-control inputControl"  type="text">
      							</div>
	  							<div class="form-group  new_user">
      								<label class="control-label" for="password">Confirm Password <sup CLASS="required">*</sup> </label>
      								<input name="password" value="" placeholder="Password" id="password" class="form-control" type="password">
      							</div>
	  							<div class="form-group ">
       								<label class="control-label" for="telephone">Mobile<sup CLASS="required">*</sup> </label>
        							<input name="telephone" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['Mobile'])){ echo $userLoggedIn['Mobile']; } ?>" placeholder="Contact no." id="telephone" class="form-control inputControl" type="text">
      							</div>
							</fieldset>
  						</div>
  						<div class="col-sm-6">
    						<fieldset>
      							<legend>Your Address</legend> 
      <div class="form-group ">
        <label class="control-label" for="address_line_1">Address 1 <sup CLASS="required">*</sup> </label>
        <input name="address_line_1" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['address_line_1'])){ echo $userLoggedIn['address_line_1']; } ?>" placeholder="Address 1" id="address_line_1" class="form-control inputControl" type="text">
      </div>
      <div class="form-group">
        <label class="control-label" for="address_line_2">Address 2</label>
        <input name="address_line_2" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['address_line_2'])){ echo $userLoggedIn['address_line_2']; } ?>" placeholder="Address 2" id="address_line_2" class="form-control inputControl" type="text">
      </div>
      <div class="form-group ">
        <label class="control-label" for="city">City <sup CLASS="required">*</sup> </label>
        <input name="city" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['address_line_3'])){ echo $userLoggedIn['address_line_3']; } ?>" placeholder="City" id="city" class="form-control inputControl" type="text">
      </div>
      <div class="form-group ">
        <label class="control-label" for="state">State/County <sup CLASS="required">*</sup> </label>
        <input name="state" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['county_or_state'])){ echo $userLoggedIn['county_or_state']; } ?>" placeholder="State/County" id="state" class="form-control inputControl" type="text">
      </div>
      <div class="form-group ">
        <label class="control-label" for="postcode">Post Code <sup CLASS="required">*</sup> </label>
        <input name="postcode" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['post_zip_code'])){ echo $userLoggedIn['post_zip_code']; } ?>" placeholder="Post Code" id="postcode" class="form-control inputControl" type="text">
      </div>
      <div class="form-group ">
        <label class="control-label" for="country">Country<sup CLASS="required">*</sup> </label>
        <select class="form-control" id="country" name="country">
                    							<option value="">--Select Country--</option>
												<?php $country_table = $db->countries->find(array("status"=>1));
													if($country_table->count()>0){
														foreach($country_table as $country){
															$selectedStr="";
															if(isset($userLoggedIn['country']) && $userLoggedIn['country']==$country['name']){ 
																$selectedStr="selected";
															}
															echo '<option value="'.$country['name'].'" '.$selectedStr.'>'.$country['name'].'</option>';
														}
													}
												?>
										</select>
      </div>
      
    </fieldset>
      </div>
					</div>
					<div class="buttons">
  						<div class="pull-right">
  							<input class="btn btn-danger" type="submit" name="submit" value="<?php if(isset($session_values['checkout_state']) && $session_values['checkout_state']==0) { echo 'Continue'; } else{ echo 'Save'; } ?>">
  						</div>
					</div>
					</form>
				</div>
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
       	$( "#myaccount" ).validate( {
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
				password : { required: true, minlength: 6 }

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
    			}
  			}
		});
    });
</script>
</body>
</html>
