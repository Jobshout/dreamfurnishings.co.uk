<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("include/config_inc.php");
require_once("include/main_header.php");

$dbBlogsData = $db->web_content->find(array('$or' => array(array("status" => "true"), array("status" => true)),'code' => array('$ne' => ""), "type" => "blog"))->sort(array("created_timestamp" => 1))->limit(6);
?>
</head>
<body>
<?php require_once("include/header.php"); ?>   
<section>
	<div class="headingbcg " >
		<div class="container">
			<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1>News</h1>
         		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
              				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active">News</li>
            			</ol>
					</div>
          		</div>
        	</div>
      	</div>
    </div>
	<div CLASS="container">
		<div CLASS="row ">
			<?php if($dbBlogsData->count()>0){ ?>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading"> <span class="glyphicon glyphicon-list-alt" STYLE="font-size:19px;"></span><b STYLE="font-size:20px; color:#273066;"> Latest Blog</b></div>
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
								<ul class="blogSection">
									<?php foreach($dbBlogsData as $blogData){
										$bodyStr= $blogData["body"];
										$firstSPosNum=stripos($bodyStr,"<p>");
										if($firstSPosNum !== false) {
											$firstEPosNum=stripos($bodyStr,"</p>");
											if($firstEPosNum !== false) {
												$bodyStr=substr($bodyStr,$firstSPosNum,$firstEPosNum);
											}
										}
										$bodyStr=strip_tags($bodyStr);
										if(strlen($bodyStr)>125){
											$bodyStr=substr($bodyStr,0,125)."...";
										}
										?>
									<li class="news-item">
										<h4><?php echo $blogData["title"]; ?></h4>
										<p><?php echo $bodyStr; ?></p>
										<div class="text-right"><a href="<?php echo gb_fn_linkCacheHandler($blogData["code"].'.html','content.htm?code='.$blogData["code"]); ?>" title="Read more">Read more...</a></div>
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div CLASS="col-md-8 " id="content_area">
			<?php }else{ ?>
			<div CLASS="col-md-12" id="content_area">
			<?php } ?>	
	      
			</div>
		</div>
	</div>      
</section>
<?php 
	require_once("include/top_footer.php");
	require_once("include/footer.php");
?>
<script src="js/jquery.bootstrap.newsbox.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $(".blogSection").bootstrapNews({
            newsPerPage: 3,
            autoplay: true,
			pauseOnHover:true,
            direction: 'up',
            newsTickerInterval: 2000,
            onToDo: function () {
                //console.log(this);
            }
        });
		$.movePage(1);
	});

	$.movePage = function movePage(pageNum){
	
		$('#content_area').html("");
		$('#ImageLoadingDiv').show();
		$.ajax({
		  type : "GET",
		  url: "json-news.htm?pageNum="+pageNum,
		  cache: false,
		  success: function(html){
			if(html!='no'){
				$('#ImageLoadingDiv').hide();
				$('#content_area').html(html);
				
			}
		  }
		});
	}
</script>
</body>
</html>
