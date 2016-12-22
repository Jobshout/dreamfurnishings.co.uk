<?php 
require_once("include/config_inc.php");

$userUUIDStr="";
if(isset($_GET['token'])){
	if($chkToken= $db->authentication_token->findOne(array("_id" => new MongoId($_GET['token']), "active" => true))){
		if($findUser= $db->Contacts->findOne(array("uuid" => $chkToken['user_uuid']))){
			if($findUser["AllowWebAccess"]==true){
				/**$update_data= array("AllowWebAccess" => true);
				$update_status= $db->Contacts->update(array("uuid" => $chkToken['user_uuid']), array('$set' => $update_data));
				if($update_status){
					$db->authentication_token->remove(array("_id" => new MongoId($cc)));
					$succMsg = 'Your account has been successfully activated.';
				}**/
				$userUUIDStr=$chkToken['user_uuid'];
			}else{
				$err_msg = 'Your link has been expired!';
			}
		}else{
			$err_msg = 'You are not a registered user.';
		}
	}else{
		$err_msg = 'Your link has been expired!';
	}
}else{
	$err_msg = 'You are not authorised to view this page!';
}

if(!empty($_POST['submit'])){
	$password=addslashes($_POST['password']);
	
	if($userUUIDStr!=""){
		if($password!=""){
			$md5_password=md5($password);
			$updateArr= array("zWebPassword" => $md5_password);
			if($db->Contacts->update(array("uuid" => $userUUIDStr), array('$set' => $updateArr))){
				$db->authentication_token->remove(array("_id" => new MongoId($_GET['token'])));
				$succMsg = 'Your password has been successfully changed.';
			}else{
				$errMsg = "Sorry, your request can't be processed now, please try later!";
			}
		}else{
			$errMsg = 'Please enter your password.';
		}
	}else{
		$errMsg = 'You are not a registered user.';
	}
	
}
require_once("include/main_header.php");
?>
<body>
<?php require_once("include/header.php"); ?>  
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
				<div class="col-md-8 col-sm-8">
            		<h1 class="topHeadingsClass">Reset your password</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb">
              				<li><a onclick="gb_fn_linkCacheHandlerJS('index.php','index.php')" href="javascript:void(0)" title="Home">Home</a></li>
							<li class="active topHeadingsClass">Reset password</li>
            			</ol>
					</div>
          		</div>
        	</div>
      	</div>
    </div>
	<div class="container">
		<div class="row content">
			<div class="col-md-12">
			<?php if(isset($err_msg) && $err_msg!=""){ ?>
				<div class="alert alert-danger alert-dismissable">
  					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  					<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $err_msg; ?>
				</div>
			<?php } else { ?>
				<form class="form-signin wow fadeInUp animated" name="login" id="login" method="post" style="visibility: visible; animation-name: fadeInUp;" data-wow-animation-name="fadeInUp">
                	<h2 class="form-signin-heading topHeadingsClass">Regenerate Password</h2>
                	<?php if(isset($errMsg)){ ?>
			 			<div class="alert alert-danger alert-dismissable">
  							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  							<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $errMsg; ?>
						</div>
					<?php }
					if(isset($succMsg)){ ?>
						<div class="alert alert-success ">    
  							<i class=" glyphicon glyphicon-ok-circle Added to your Cart"></i> <?php echo $succMsg; ?>
  						</div>
					<?php } else{ ?>
                	<div class="login-wrap">
                		<input type="hidden" id="action" name="action" value="login">
                   		<input class="form-control" placeholder="Password" autofocus="" type="password" id="password" name="password">
						<input class="form-control" placeholder="Confirm password" type="password" id="confirm_password" name="confirm_password">
						
						<input class="btn btn-lg btn-danger btn-block" type="submit" name="submit" id="submit" value="Submit">
						
                	</div>
                	<?php } ?>
            	</form>
            <?php } ?>
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
    	$( "#login" ).validate( {
        	errorElement: "em",
			rules: {
				password : { required: true, minlength: 6 },
				confirm_password : { required: true, equalTo: "#password" }
			},
 			messages: {
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