<?php
require_once("include/config_inc.php");
$data='';
$limit=5;

$dbResultsData = $db->web_content->find(array('$or' => array(array("status" => "true"), array("status" => true)),'code' => array('$ne' => ""), "type" => "news"));
$total_pages=$dbResultsData->count();
require_once("include/pagination.php");
echo $pagination;
$dbResultsData = $db->web_content->find(array('$or' => array(array("status" => "true"), array("status" => true)), 'code' => array('$ne' => ""), "type" => "news"))->sort(array("sort_order" => 1))->limit($endLim)->skip($startLim);
$total_pages=$dbResultsData->count();
if($dbResultsData->count()>0){
	foreach($dbResultsData as $document){
        $postedByStr='';
        $publishedDate ='';
        $categoriesStr ='';
       	$Published_timestamp=$document["posted_timestamp"];
       	$monthStr = date('M',$Published_timestamp);
		$dateStr = date('d',$Published_timestamp);
		
		$bodyStr= $document["body"];
		$bodyStr=getBriefText($bodyStr,250);
		
	    $data.='<div class="news-teaser"><div class="post-date"><div class="month">'.$monthStr.'</div><div class="day">'.$dateStr.'</div></div>';
	    
	    $data.='<h3><a title="'.$document["title"].'" rel="bookmark" href="javascript:void(0)" onClick="gb_fn_linkCacheHandlerJS(\''.$document["code"].'.html\',\'content.php?code='.$document["code"].'\')">'.$document["title"].'</a></h3>';
	   	$data.='<p>'.$bodyStr.'</p>';
		$data.='<div class="text-right"><a href="javascript:void(0)" onClick="gb_fn_linkCacheHandlerJS(\''.$document["code"].'.html\',\'content.php?code='.$document["code"].'\')" class="more-link">Continue reading  <span class="right-arrow"></span></a></div>';
		$data.='</div>';
	}
}else{
	$data.="<div><h1 style='border:none;'>No news available!</h1></div>";
}			
echo $data;


?>