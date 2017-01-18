<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("include/config_inc.php");
require_once("include/main_header.php");
$dbNewsData = $db->web_content->find(array('$or' => array(array("status" => "true"), array("status" => true)), 'code' => array('$ne' => ""), "type" => "news"))->sort(array("created_timestamp" => 1))->limit(6);
?>
</head>
<body>
<?php require_once("include/header.php"); ?>   
<section>
	<div class="headingbcg " >
		<div class="container">
			<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1>Blogs</h1>
         		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
              				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active">Blogs</li>
            			</ol>
					</div>
          		</div>
        	</div>
      	</div>
    </div>

<div class="container">
  <div class="row">
    <div class="col-sm-4 col-md-3">
    <?php if($dbNewsData->count()>0){	?>
      <div class="widget-blk">
        <h2><span>Latest News</span></h2>
        <ul class="recent-post">
        <?php foreach($dbNewsData as $rowData){	?>
			<li><a href="<?php echo gb_fn_linkCacheHandler($rowData["code"].'.html','content.htm?code='.$rowData["code"]);?>" title="Read more"><?php echo $rowData["title"];?></a></li>
		<?php } ?>
        </ul>
      </div>
      <?php } ?>	
      	<?php require_once("include/blog-archive.php");	?>
      	
    </div>
    <div class="col-sm-8 col-md-9">
      <div class="row" id="contentoftable">
       
      </div>
      <a href="javascript:void(0)" onClick="load_more_records()" class="btn submitbtn btn-block btn-lg" id="load_more_btn" style="display:none;">View more</a>
      </div>
  </div>
</div>
<?php 
	require_once("include/top_footer.php");
	require_once("include/footer.php");
?>
<script type="text/javascript">
var nPageSize=6, xhr;
var start=0, end=nPageSize, totalNum=0, y="", m= "";
<?php if(isset($_GET['y']) && $_GET['y']!=""){	?>
	y=<?php echo $_GET['y'];?> ;
<?php }	?>

<?php if(isset($_GET['m']) && $_GET['m']!=""){	?>
	m=<?php echo $_GET['m'];?> ;
<?php }	?>

function load_more_records(){
	$('#load_more_btn').hide();
	$('#img_loading_div').show();
	start=end;
	end=start+nPageSize;
	load_data();
}

function load_data(){
	var jsonRow="json-blogs.htm?start="+start+"&end="+nPageSize+"&year="+y+"&mon="+m;
	
	if(xhr) xhr.abort();
	xhr=$.getJSON(jsonRow,function(result){
		totalNum=result.iTotalRecords;
		if(result.error || totalNum==0){
			$('#contentoftable').html('<div class="alert alert-danger" role="alert">Sorry, no blogs posted yet!</div>');
		
			$('#load_more_btn').hide();
			$('#img_loading_div').hide();
		}else{
			var htmlStr="";
			$.each(result.aaData, function(i,item){
				var linkStr= item.link;
				htmlStr+='<div class="col-md-6">';
          		htmlStr+='<div class="blog-story">';
            	htmlStr+='<div class="blog-listing-img"><a href="'+linkStr+'"><img src="'+item.image+'" class="img-responsive" alt=""></a></div>';
            	htmlStr+='<div class="caption">';
              	htmlStr+='<div class="row">';
                htmlStr+='<div class="col-xs-3 col-sm-4 col-md-3">';
                htmlStr+='<div class="blog-pub-dt"><span class="date">'+item.date+'</span><span class="mnth">'+item.month+'</span></div>';
                htmlStr+='<div class="comments-count"> '+item.comments+'</div>';
                htmlStr+='</div>';
                htmlStr+='<div class="col-xs-9 col-sm-8 col-md-9">';
                htmlStr+='<h3><a href="'+linkStr+'">'+item.title+'</a></h3>';
                htmlStr+='<p>'+item.body+'</p>';
            	htmlStr+='<a href="'+linkStr+'" class="readmore">Read more &raquo; </a> </div>';
              	htmlStr+='</div>';
            	htmlStr+='</div>';
          		htmlStr+='</div>';
        		htmlStr+='</div>';		
			}); 
				
			$('#contentoftable').append(htmlStr);
			
			if(end< totalNum){
			$('#load_more_btn').show();
			}
			$('#img_loading_div').hide();
		}
	});
}

$(function () {
	load_data();
	$(window).scroll(function(){
		if ($(window).scrollTop() == $(document).height() - $(window).height()){
			if(xhr.status==200 && end < totalNum) {
				$('#load_more_btn').parent().hide();
				$('#img_loading_div').show();
				start=end;
				end=start+nPageSize;
				load_data();
			}
		}
	});
});
</script>
</body>
</html>
