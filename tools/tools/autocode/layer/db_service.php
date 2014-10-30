<?php
require_once ("../../../../init.php");

if (isset($_REQUEST["type"])&&!empty($_REQUEST["type"])){
	$type=$_REQUEST["type"];
}else{
	$type=1;
}
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeService::$save_dir =$save_dir;
	AutoCodeService::$type     =$type;
	AutoCodeService::$showReport="";
	AutoCodeService::$showReport.=AutoCodeFoldHelper::foldEffectReady();
	AutoCodeService::$showReport.="<br/>";
	AutoCodeService::$showReport.=AutoCodeFoldHelper::foldbeforeservice();
	AutoCodeService::AutoCode();
	AutoCodeService::$showReport.=AutoCodeFoldHelper::foldafterservice();
	echo AutoCodeService::$showReport;
}  else {
	AutoCodeService::UserInput($type);
}
?>
