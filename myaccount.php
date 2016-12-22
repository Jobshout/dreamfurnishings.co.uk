<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
require_once("include/config_inc.php");
require_once("include/main_header.php");

if(isset($isUserSignedInBool) && $isUserSignedInBool==true){
	if(!isset($userLoggedIn)){
		header("Location: logout.php");
		exit;
	}
}else{
	header("Location: login.php?redirect=myaccount");
	exit;
}
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
             				<li><a onclick="gb_fn_linkCacheHandlerJS('index.php','index.php')" href="javascript:void(0)" title="Home">Home</a></li>
							<li class="active">My account</li>
            			</ol>
					</div>
          		</div>
        	</div>
		</div>
	</div>
	<div class="container">
		<div class="row content">
			<div class="col-md-12  ">
				<div class="well">
        			<div class="row">
             			<div class="col-sm-12">
							<div class="my-account-pg-hding"><span class="glyphicon glyphicon-info-sign"></span> My Info</div>
							<div style="padding:0 15px;">
								<div class="row">
                   					<div class="col-xs-3 col-sm-2"><p><strong>Name:</strong></p> </div>
                    				<div class="col-xs-9 col-sm-10"><p><?php if(isset($userLoggedIn) && isset($userLoggedIn['First name'])){ echo $userLoggedIn['First name']; } ?>&nbsp;<?php if(isset($userLoggedIn) && isset($userLoggedIn['Surname'])){ echo $userLoggedIn['Surname']; } ?></p></div>
                				</div>
                 				<div class="row">
                    				<div class="col-xs-3 col-sm-2"><p><strong>E-mail:</strong></p> </div>
                    				<div class="col-xs-9 col-sm-10"> <p><?php if(isset($userLoggedIn) && isset($userLoggedIn['Email'])){ echo $userLoggedIn['Email']; } ?></p></div>
                				</div>
				                <div class="row">
                    				<div class="col-xs-3 col-sm-2"><p><strong>Telephone:</strong></p> </div>
                    				<div class="col-xs-9 col-sm-10"><p><?php if(isset($userLoggedIn) && isset($userLoggedIn['Mobile'])){ echo $userLoggedIn['Mobile']; } ?></p></div>
                				</div>
				                <div class="row">
                    				<div class="col-xs-3 col-sm-2"><p><strong>Address:</strong> </p></div>
                    				<div class="col-xs-9 col-sm-10">
										<p><?php if(isset($userLoggedIn["address_line_1"]) && $userLoggedIn["address_line_1"]!=""){ echo $userLoggedIn["address_line_1"];	} ?>
										<?php if(isset($userLoggedIn["address_line_2"]) && $userLoggedIn["address_line_2"]!=""){ echo ", ".$userLoggedIn["address_line_2"];	} ?>
										<?php if(isset($userLoggedIn["address_line_3"]) && $userLoggedIn["address_line_3"]!=""){ echo ", ".$userLoggedIn["address_line_3"];	} ?>
										<?php if(isset($userLoggedIn["county_or_state"]) && $userLoggedIn["county_or_state"]!=""){ echo ", ".$userLoggedIn["county_or_state"];	} ?>
										<?php if(isset($userLoggedIn["country"]) && $userLoggedIn["country"]!=""){ echo ", ".$userLoggedIn["country"];	} ?>
										<?php if(isset($userLoggedIn["post_zip_code"]) && $userLoggedIn["post_zip_code"]!=""){ echo ", ".$userLoggedIn["post_zip_code"];	} ?></p>
									</div>
                				</div>
			    				<div class="row">
                    				<div class="col-xs-3 col-sm-2"></div>
                    					<div class="col-xs-9 col-sm-10"><p>[ <a href="account.php">Edit</a> ]</p>
                    			</div>
                			</div>
						</div>
                	</div>
                </div>
				<!--My activity-->
				<!--div class="row" id="transaction_activity">
					<div class="col-sm-12 ">
                  		<div class="my-account-pg-hding"><span class="glyphicon glyphicon-list-alt"></span> My Activity</div>   
                 		<div style="padding:0 15px;">
				 			<div class="row">
								<div class="col-sm-12"><strong>1.</strong> <span style="font-style:italic;"><span style="font-weight:600; margin-left:5px;">Order ID:</span> <a href="activity.htm?uuid=A15DFF2013BA5A4EABC17A58F802DC37">6092</a>, <span style="font-weight:600;margin-left:5px;">Dated: </span>17 Jul 2015, <span style="font-weight:600;margin-left:5px;">Current Status:</span> <span class="status-code2">Checkout form submitted, viewing confirmation page</span> </span></div>
                			</div>
							<div class="row">
								<div class="col-sm-12"><strong>2.</strong> <span style="font-style:italic;"><span style="font-weight:600; margin-left:5px;">Order ID:</span> <a href="activity.htm?uuid=8F0315B44B984342984278CFB401CE5C">6352</a>, <span style="font-weight:600;margin-left:5px;">Dated: </span>11 Sep 2014, <span style="font-weight:600;margin-left:5px;">Current Status:</span> <span class="status-code2">Checkout form submitted, viewing confirmation page</span> </span></div>
                			</div>
							<div class="row">
								<div class="col-sm-12"><strong>3.</strong> <span style="font-style:italic;"><span style="font-weight:600; margin-left:5px;">Order ID:</span> <a href="activity.htm?uuid=8A5FD39C92DE8D4D908833E19EA21FB0">6344</a>, <span style="font-weight:600;margin-left:5px;">Dated: </span>09 Sep 2014, <span style="font-weight:600;margin-left:5px;">Current Status:</span> <span class="status-code4">Card not Approved, Declined</span> </span></div>
                			</div>
							<div class="row">
								<div class="col-sm-12"><strong>4.</strong> <span style="font-style:italic;"><span style="font-weight:600; margin-left:5px;">Order ID:</span> <a href="activity.htm?uuid=F0E22D3279871A4FA09659255DAF6201">6094</a>, <span style="font-weight:600;margin-left:5px;">Dated: </span>25 Apr 2014, <span style="font-weight:600;margin-left:5px;">Current Status:</span> <span class="status-code4">Card not Approved, Declined</span> </span></div>
                			</div>
						</div>
					</div>
 				</div>-->
    		 </div>
		</div>
	</div>
</div>    
</section>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
</body>
</html>
