<?php 
require_once ("../../../../init.php");
$pageSize=15;
$blog_name   = !empty($_REQUEST['query'])&&($_REQUEST['query']!="?")&&($_REQUEST['query']!="？") ? trim($_REQUEST['query']) : "";
$condition=array();
if (!empty($blog_name)){
    $condition["blog_name"]=" like '%$blog_name%'";
}
$start=0;
if (isset($_REQUEST['start'])){
    $start=$_REQUEST['start']+1;
}
$limit=$pageSize;
if (isset($_REQUEST['limit'])){
    $limit=$_REQUEST['limit'];
    $limit= $start+$limit-1;
}
$arr['totalCount']= Blog::count($condition);
$arr['blogs']    = Blog::queryPage($start,$limit,$condition);
foreach ($arr['blogs'] as $blog) {
    $blog->Blog_ID=$blog->ID;
}
echo json_encode($arr);
?>
