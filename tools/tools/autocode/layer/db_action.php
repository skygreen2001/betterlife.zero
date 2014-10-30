<?php
require_once ("../../../../init.php");
if (isset($_REQUEST["type"])&&!empty($_REQUEST["type"])){
	$type=$_REQUEST["type"];
}else{
	if ($_REQUEST["type"]==0){
		$type=0;
	}else{
		$type=2;
	}
}
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeAction::$save_dir =$save_dir;
	AutoCodeAction::$type     =$type;
	AutoCodeAction::$showReport="";
	AutoCodeAction::$showReport.=AutoCodeFoldHelper::foldEffectReady();
	AutoCodeAction::$showReport.="<br/>";
	AutoCodeAction::$showReport.=AutoCodeFoldHelper::foldbeforeaction();
	AutoCodeAction::AutoCode();
	AutoCodeAction::$showReport.="<br/>";
	AutoCodeAction::$showReport.=AutoCodeFoldHelper::foldafteraction();
	echo AutoCodeAction::$showReport;
}  else {
	AutoCodeAction::UserInput($type);
}
?>