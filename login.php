<?php 
require_once("include/config_inc.php");
require_once('include/class.phpmailer.php');

if(isset($_GET['cc'])){
	$cc= $_GET['cc'];
	
	if($chkToken=$mongoCRUDClass->db_findone("authentication_token", array("_id" => new MongoId($cc), "active" => true))){
		if($findUser=$mongoCRUDClass->db_findone("Contacts", array("uuid" => $chkToken['user_uuid']))){
			if($findUser["AllowWebAccess"]==false){
				$update_data= array("AllowWebAccess" => true);
				$update_status=$mongoCRUDClass->db_update("Contacts", array("uuid" => $chkToken['user_uuid']), $update_data);
				if($update_status){
					$mongoCRUDClass->db_delete("authentication_token", array("_id" => new MongoId($cc)));
					$succ_msg = 'Your account has been successfully activated.';
				}
			}else{
				$err_msg = 'Your link has been expired!';
			}
		}else{
			$err_msg = 'You are not a registered user.';
		}
	}else{
		//$err_msg = 'Your link has been expired!';
	}
}

require_once("login_submit_request.php");
require_once("include/main_header.php");

?>
<body>
<?php require_once("include/header.php"); ?>  
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
				<div class="col-md-8 col-sm-8">
            		<h1 class="topHeadingsClass">Login</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb">
              				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active topHeadingsClass">Login</li>
            			</ol>
					</div>
          		</div>
        	</div>
      	</div>
    </div>
	<div class="container">
		<div class="row content">
			<div class="col-md-12">
				<form class="form-signin wow fadeInUp animated" name="login" id="login" method="post" style="visibility: visible; animation-name: fadeInUp;" data-wow-animation-name="fadeInUp">
                	<h2 class="form-signin-heading topHeadingsClass displayMessageClass">Sign in now</h2>
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
                		<input type="hidden" id="action" name="action" value="login">
                   		<input class="form-control" placeholder="Email" autofocus="" type="text" id="email_address" name="email_address">
						<input class="form-control" placeholder="Password" type="password" id="password" name="password">
						
						<input class="btn btn-lg btn-danger btn-block" type="submit" name="sign_in" id="sign_in" value="Sign In">
						<div STYLE="padding-top:10px;"> Don't have an account yet?
                    		<a title="Register" href="register.htm"   class="btn btn-primary btn-xs">
                        		Create an account
                    		</a>
							
                		</div>
						
						
						<div class="pull-right" style="position: relative;bottom: 22px;">
                        	<a href="javascript:void(0)" onClick="swtichForms('fpwd'); return false;" id="fPwdBtn" class="btn btn-primary btn-xs"  > Forgot Password?</a>
                        	<a href="javascript:void(0)" onClick="swtichForms('login'); return false;" style="display:none; margin-bottom:5px;" id="loginBtn" class="btn btn-primary btn-xs"  > Sign In?</a>
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
function swtichForms(v, freshAlertBool=true){
	if(freshAlertBool){
		$(".alert").remove();
	}
	if(v=="fpwd"){
		$("#action").val("requestnewpassword");
		
		$(".form-signin-heading").html("Can't Sign In");
		$(".topHeadingsClass").html("Forgot Password");
		
		$("#sign_in").val("Request New Password");
		
		$("#password").hide();
		$("#fPwdBtn").hide();
		$("#loginBtn").show();
	}else{
		$("#action").val("login");
		$(".form-signin-heading").html("Sign in now");
		$(".topHeadingsClass").html("Login");
		
		$("#sign_in").val("Sign In");
		
		$("#password").show();
		$("#fPwdBtn").show();
		$("#loginBtn").hide();
	}
}

    $(function () {  	
    	<?php if(isset($_REQUEST['action']) && $_REQUEST['action']=='requestnewpassword') { ?>
    		swtichForms('fpwd', false);
    	<?php } ?>
    	
       	$( "#login" ).validate( {
        	errorElement: "em",
			rules: {
				email_address : { required :true, email: true},
				password : { required: true }
			},
 			messages: {
    			email_address: {
      				required: "Please enter your email address",
      				email: "Please enter a valid email address"
    			},
    			password: {
      				required: "Please enter password"
    			}
  			}
		});
    });
</script>
</body>
</html>