<?php
require_once("include/config_inc.php");
require_once("include/main_header.php");
?>
</head>
<body>
<?php require_once("include/header.php"); ?>  
<div class="container">
<div class="row content">

<div CLASS="col-md-5">
		<IMG SRC="images/404.png" CLASS="img-responsive" ALT=""/> </div>
<div CLASS="col-md-7">

<div class="errorpage">
   			<h2>Sorry! The page you were <span>looking <br>

for was not found.</span></h2> 
<p>It appears that you've lost your way either through an outdated link or a typo on the page you were trying to reach.
</p>


<h3>While we work on resolving the problem, here are couple of things you can do:</h3>

<ul>
<li>If you typed in the address, check your spelling. Could just be a typo.</li>
<li>Go to <a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm'); ?>" title="Home">main page.</a></li>
<li>Get to know us a little better - <a href="<?php echo gb_fn_linkCacheHandler('about-us.html','content.htm?code=about-us'); ?>" title="About uus">About us</a>, or visit our full website Site Map here.</li>
<li><a href="#">Visit us on Facebook</a>, <a href="#">follow our tweets.</a></li>






</ul>

</div>


</div>

<style>

.errorpage{
	padding-top:10px;
}

.errorpage h2{
	font-size:28px;
	line-height:42px;
	letter-spacing:1.1px;
	color:#2c3880!important;
}
.errorpage h2 span{
	color:#f37e0c;
	
}

.errorpage p{
	font-size:16px;
}

.errorpage h3{
	background: #f7f7f7;
padding: 8px 10px;
letter-spacing: 0.1px;
font-size: 17px;
color: #4F4D4D;
border-radius: 5px;
line-height: 25px;
	
	
}

.errorpage ul li{
	padding-left: 5px;
font-size: 15px;
list-style: inside;
line-height: 30px;
}

</style>




</div>

</div>
<?php 
	require_once("include/top_footer.php");
	require_once("include/footer.php");
?>
</body>
</html>
