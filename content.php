<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("include/config_inc.php");
$codeStr = isset($_GET['code']) ? $_GET['code'] : '';
$codeStr = str_replace(".html", "", $codeStr);

if($codeStr<>''){
	if($documentdetail = $db->web_content->findOne(array("code" => $codeStr, '$or' => array(array("status" => "true"), array("status" => true))))){
		$pWindowTitleTxt = $documentdetail['windowtitle'];
		
		$pMetaKeywordsTxt = $documentdetail['meta_tag_keywords'];
		$pMetaDescriptionTxt = $documentdetail['meta_tag_description'];
		$docTypeStr=$documentdetail['type'];
		$docSortOrderNum=0;
		if(isset($documentdetail['sort_order']) && $documentdetail['sort_order']!=""){
			$docSortOrderNum=$documentdetail['sort_order'];
		}
		
		if(isset($documentdetail['title']) && $documentdetail['title']!=""){
			$documentTitleStr=$documentdetail['title'];
		}elseif(isset($documentdetail['subtitle']) && $documentdetail['subtitle']!=""){
			$documentTitleStr=$documentdetail['subtitle'];
		}
		if($pWindowTitleTxt==""){
			$pWindowTitleTxt=$documentTitleStr;
		}
		//echo json_encode(array("type" => $docTypeStr, "status" => true, "code" => array('$ne' => $documentdetail['code']) ));
		$related_documents = $db->web_content->find(array("type" => $docTypeStr, "status" => true, "code" => array('$ne' => $documentdetail['code']) ));

	}else{
		header("location:404.htm");
		exit;
	}
}else{
	header("location:404.htm");
	exit;
}
require_once("include/main_header.php");
?>
</head>
<body>
<?php require_once("include/header.php"); ?>  
<section>
	<div class="headingbcg " >
    	<div class="container">
        	<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1><?php if(isset($documentTitleStr) && $documentTitleStr!=""){	echo $documentTitleStr; }	?></h1>
          		</div>
				<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
              				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm'); ?>" title="Home">Home</a></li>
							<?php switch ($docTypeStr) {
    								case "news":
    							?>
    								<li><a href="<?php echo gb_fn_linkCacheHandler('news.htm','news.htm'); ?>" title="News">News</a></li>
    							<?php	break;
    								case "blog":
    							?>
    								<li><a href="<?php echo gb_fn_linkCacheHandler('blogs.htm','blogs.htm'); ?>" title="Blogs">Blogs</a></li>
    							<?php break;
    								default:
    							}
							?>
							<li class="active"><?php if(isset($documentTitleStr) && $documentTitleStr!=""){	echo $documentTitleStr; }	?></li>
						</ol>
					</div>
          		</div>
        	</div>
      	</div>
	</div>
	
	<div class="container">
		<?php switch ($docTypeStr) {
    		case "news":
    	?>
			<div CLASS="row">
				<?php if(isset($related_documents) && $related_documents->count()>0){ ?>
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading"> <span class="glyphicon glyphicon-list-alt" STYLE="font-size:19px;"></span><b STYLE="font-size:20px; color:#273066;"> Latest News</b></div>
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-12">
									<ul class="demo1">
										<?php $countNewsNum=0;
										foreach($related_documents as $related_document){ 
										$countNewsNum++;
										if($countNewsNum==7){ break; }
										?>
										<li class="news-item">
											<h4><?php echo $related_document["title"];?></h4>
											<?php 
											$bodyStr= $related_document["body"];
											$firstSPosNum=stripos($bodyStr,"<p>");
											$firstEPosNum=stripos($bodyStr,"</p>");
											$bodyStr=substr($bodyStr,$firstSPosNum,$firstEPosNum);
											$bodyStr=strip_tags($bodyStr);
											if(strlen($bodyStr)>50){
												$bodyStr=substr($bodyStr,0,50)."...";
											}
											echo $bodyStr;
											?>
											<?php //echo substr($related_document["body"],50)."..."; ?>
											<div class="text-right"><a href="<?php echo gb_fn_linkCacheHandler($related_document["code"].'.html','content.htm?code='.$related_document["code"]);?>" >Read more...</a></div>
										</li>
										<?php	} ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div CLASS="col-md-8 ">
				<?php }else{ ?>
				<div CLASS="col-md-12">
				<?php } ?>
					<?php if((isset($documentdetail['owner']) && $documentdetail['owner']!="") || (isset($documentdetail['posted_timestamp']) && $documentdetail['posted_timestamp']!="")){	?>
					<div class="entry-meta"> Posted 
						<?php if(isset($documentdetail['posted_timestamp']) && $documentdetail['posted_timestamp']!=""){	
							$postedTimestampStr= date("F d,Y",$documentdetail['posted_timestamp']);
						?>
							&nbsp;on&nbsp;<a href="<?php echo gb_fn_linkCacheHandler('news.htm?on='.$documentdetail['posted_timestamp'],'news.htm?on='.$documentdetail['posted_timestamp']);?>"><?php echo $postedTimestampStr;?></a>
						<?php } ?>
						<?php if(isset($documentdetail['owner']) && $documentdetail['owner']!=""){	?>
							&nbsp;by&nbsp;<a href="<?php echo gb_fn_linkCacheHandler('news.htm?by='.$documentdetail['owner'],'news.htm?by='.$documentdetail['owner']); ?>"><?php echo $documentdetail['owner'];?></a>
						<?php } ?>
					</div>
					<?php } ?>
					<div class="entry-content">
						<?php if(isset($documentdetail['body']) && $documentdetail['body']!=""){
							echo $documentdetail['body'];
						} ?>
					</div>
					<?php $nextNum=0; $previousNum=0; $nextNews=""; $previousNews="";	
					foreach($related_documents as $selected_doc){
						if(isset($selected_doc["sort_order"]) && $selected_doc["sort_order"]){
						$tempOrderNum=$selected_doc["sort_order"];
						if($tempOrderNum!=$docSortOrderNum && $tempOrderNum>$docSortOrderNum){
							if($nextNum==0 || $nextNum>$tempOrderNum){
								$nextNum=$tempOrderNum;
								$nextNews=$selected_doc['code'];
							}
						}
						
						if($tempOrderNum!=$docSortOrderNum && $tempOrderNum<$docSortOrderNum){
							if($previousNum==0 || $previousNum<$tempOrderNum){
								$previousNum=$tempOrderNum;
								$previousNews=$selected_doc['code'];
							}
						}
						}
					}
					if($nextNews!="" || $previousNews!=""){
					?>
					<div id="nav-below" class="navigation">
						<?php if($previousNews!=""){ ?><div class="nav-prev"><a href="echo gb_fn_linkCacheHandler($previousNews.'.html','content.htm?code='.$previousNews);?>">Previous</a></div><?php } ?>
						<?php if($nextNews!=""){ ?><div class="nav-next"><a href="<?php echo gb_fn_linkCacheHandler($nextNews.'.html','content.htm?code='.$nextNews);	?>">Next</a></div><?php } ?>
					</div>
      				<?php } ?>
					<!--<div id="nav-below" class="navigation"><div class="nav-prev"><a href="#">Previous Post</a></div><div class="nav-next"><a href="#">Next Post </a></div></div>-->
        			</div>	
				</div>
			</div>
		<?php 
			break;
				default:
		?>
		<?php	case "blog":	?>
			<div class="row">
				<?php if(isset($related_documents) && $related_documents->count()>0){ ?>
				<div class="col-sm-4 col-md-3">
					<div class="widget-blk">
        				<h2><span>Recent Posts</span></h2>
        				<ul class="recent-post">
        					<?php $count=0;
        					foreach($related_documents as $related_document){ 
        					$count++;
        						if($count==7){ break; }
        					?>
							<li><a title="<?php echo $related_document["title"];?>" href="<?php echo gb_fn_linkCacheHandler($related_document["code"].'.html','content.htm?code='.$related_document["code"]);?>"><?php echo $related_document["title"];?></a></li>
							<?php 	} 	?>
        				</ul>
      				</div>
      				<?php require_once("include/blog-archive.php");	?>
    			</div>
    			<div class="col-sm-8 col-md-9">
    			<?php }else{ ?>
    			<div class="col-sm-12 col-md-12">
    			<?php } ?>
    				<div class="post-detail clearfix">
    					<small>
    					<?php if(isset($documentdetail['posted_timestamp']) && $documentdetail['posted_timestamp']!=""){	
							$postedTimestampStr= date("F d,Y",$documentdetail['posted_timestamp']);
							$curryear= date('Y',$documentdetail["posted_timestamp"]);
							$currmonth= date('m',$documentdetail["posted_timestamp"]);
						?>
							<b>Posted: </b><a class="meta-link" href="<?php echo gb_fn_linkCacheHandler('blogs.htm?y='.$curryear.'&m='.$currmonth,'blogs.htm?y='.$curryear.'&m='.$currmonth); ?>"><?php echo $postedTimestampStr;?></a>
						<?php } ?>
						<?php if(isset($documentdetail['owner']) && $documentdetail['owner']!=""){	?>
							&nbsp;| &nbsp;<b>Author: </b><a class="meta-link" href="<?php  echo gb_fn_linkCacheHandler('news.htm?by='.$documentdetail['owner'],'news.htm?by='.$documentdetail['owner']);?>"><?php echo $documentdetail['owner'];?></a>
						<?php } ?>   
						<?php if(isset($documentdetail['objects']) && count($documentdetail['objects'])>0){
								$countCommentsNum=0;
								foreach($documentdetail['objects'] as $blog_comment){
									if($blog_comment["status"] && isset($blog_comment["object_content"]) && $blog_comment["object_content"]!=""){
										$countCommentsNum++;
									}
								}
						?>     
						&nbsp;| &nbsp;<a href="#blog_comment" class="meta-link">
						<?php if($countCommentsNum==1){
							echo $countCommentsNum." Comment";
						}else{
							echo $countCommentsNum." Comments";
						}	?>
						</a></small>
						<?php } ?>
						<div class="big-imgblk">
          				 	<img class="img-responsive" src="images/blog-default-image.jpg">
            		 	</div>
          			 	<?php if(isset($documentdetail['body']) && $documentdetail['body']!=""){
							echo $documentdetail['body'];
						} ?>
  						<hr>
						<!-- Blog Comments -->
						<!-- Comments Form -->
                		<div class="well">
							<form role="form" name="blog_comment" id="blog_comment" method="post">
                    			<div class="form-group displayBlogMsg">
                    				<h4>Name</h4>
                            		<input type="text" class="form-control" id="c_name" name="c_name">
                            		<input type="hidden" class="form-control" id="c_blog" name="c_blog" value="<?php echo $documentdetail['code'];?>">
                        		</div>
                        		<div class="form-group">
                    				<h4>E-mail</h4>
                            		<input type="text" class="form-control" id="c_email" name="c_email">
                        		</div>
                        		<div class="form-group">
                        			<h4>Leave a Comment</h4>
                            		<textarea class="form-control" rows="3" id="comment" name="comment"></textarea>
                        		</div>
                        		<button type="submit" class="btn send-btn submitbtn" id="submitBtn">Submit</button>
                        		<span style="font-size:12px;font-style:italic;float:right;color:#e68e03;">Note: E-mail will not be displayed publicly!</span>
                    		</form>
               			</div>
						<hr>
                		<!-- Posted Comments -->
						<!-- Comment -->
						<?php if(isset($documentdetail['objects']) && count($documentdetail['objects'])>0){
							foreach($documentdetail['objects'] as $blog_comment){
								if($blog_comment["status"] && isset($blog_comment["object_content"]) && $blog_comment["object_content"]!=""){	?>
                		<div class="media">
                    		<div class="media-body">
                        		<h4 class="media-heading"><?php echo $blog_comment["code"];	?>
                            		<small><?php echo date("F d,Y h:i A",$blog_comment["created_timestamp"]); ?></small>
                        		</h4><?php echo $blog_comment["object_content"];	?>
                        	</div>
                		</div>
                		<?php 	} 
                			}
                		}	?>
                 	</div>
                 	<?php $nextNum=0; $previousNum=0; $nextBlog=""; $previousBlog="";	
					foreach($related_documents as $selected_doc){
						if(isset($selected_doc["sort_order"]) && $selected_doc["sort_order"]){
						$tempOrderNum=$selected_doc["sort_order"];
						if($tempOrderNum!=$docSortOrderNum && $tempOrderNum>$docSortOrderNum){
							if($nextNum==0 || $nextNum>$tempOrderNum){
								$nextNum=$tempOrderNum;
								$nextBlog=$selected_doc['code'];
							}
						}
						
						if($tempOrderNum!=$docSortOrderNum && $tempOrderNum<$docSortOrderNum){
							if($previousNum==0 || $previousNum<$tempOrderNum){
								$previousNum=$tempOrderNum;
								$previousBlog=$selected_doc['code'];
							}
						}
						}
					}
					if($nextBlog!="" || $previousBlog!=""){
					?>
					<div class="pager"><div class="text-right">
						<?php if($previousBlog!=""){ ?><span><a href="<?php echo gb_fn_linkCacheHandler($previousBlog.'.html','content.htm?code='.$previousBlog); ?>">&lt; Previous</a></span><?php } ?>
						<?php if($nextBlog!=""){ ?><span><a href="<?php echo gb_fn_linkCacheHandler($nextBlog.'.html','content.htm?code='.$nextBlog);?>">Next &gt; </a></span><?php } ?>
					</div></div>
      				<?php } ?>
      			</div>
  			</div>
		<?php 
			break;
			default:
		?>
		<div class="row content">
			<div class="col-md-12">
				<?php if(isset($documentdetail['body']) && $documentdetail['body']!=""){
					echo $documentdetail['body'];
				} ?>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
                   
<?php 
	require_once("include/top_footer.php");
	require_once("include/footer.php");
?>
<script src="js/jquery.bootstrap.newsbox.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(function () {
        $(".demo1").bootstrapNews({
            newsPerPage: 3,
            autoplay: true,
			pauseOnHover:true,
            direction: 'up',
            newsTickerInterval: 2000,
            onToDo: function () {
                //console.log(this);
            }
        });
        
        $( "#blog_comment" ).validate( {
        	errorElement: "em",
			rules: {
				comment: "required",
				c_email : { required :true, email: true},
				c_name : "required"
			},
			submitHandler: function(form) {
				$(".alert").remove();
				$("#submitBtn").attr("disabled",true);
				$("#submitBtn").html("Submitting form...");
    			$.ajax({
					url: 'submit-comment.htm',
					type: 'POST',
					data: {"c_blog" : $("#c_blog").val(), "comment" : $("#comment").val(), "c_email" : $("#c_email").val(), "c_name" : $("#c_name").val() },
					dataType: 'json',
					cache: false,
					success: function(response){
						$("#submitBtn").html("Submit");
						$("#submitBtn").attr("disabled",false);
						if(response.success){
							$("#blog_comment").trigger('reset');
							$(".displayBlogMsg").before('<div class="alert alert-success" role="alert">'+response.success+'</div>');
						}else if(response.error){
							$(".displayBlogMsg").before('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
						}else if(response.required){
							$(".displayBlogMsg").before('<div class="alert alert-info" role="alert">'+response.required+'</div>');
						}
						
					}
				});
  			}
		});
    });
</script>
</body>
</html>
