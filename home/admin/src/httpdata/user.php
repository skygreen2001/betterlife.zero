<?php 
require_once ("../../../../init.php"); 
$pageSize=15;
$name   = !empty($_REQUEST['query'])&&($_REQUEST['query']!="?")&&($_REQUEST['query']!="？") ? trim($_REQUEST['query']) : "";
$condition=array();
if (!empty($name)){
	$condition["name"]=" like '%$name%'"; 
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
$arr['totalCount']= User::count($condition);
$arr['users']    = User::queryPage($start,$limit,$condition);
if ((!empty($arr['users']))&&(count($arr['users'])>0)){
	foreach ($arr['users'] as $key=>$data) {
		if (is_object($data)){
			$arr['users'][$key]=UtilObject::object_to_array($data);
		}
	}
}   
echo json_encode($arr);
?>