<?php
require_once ("../../../../../init.php");

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
    AutoCodeViewDefault::$save_dir =$save_dir;
	AutoCodeViewDefault::$type     =$type;
    AutoCodeViewDefault::$showReport="";
	AutoCodeViewDefault::$showReport.=AutoCodeFoldHelper::foldEffectReady();
	AutoCodeViewDefault::$showReport.="<br/>";
	AutoCodeViewDefault::$showReport.=AutoCodeFoldHelper::foldbeforeaction();
    AutoCodeViewDefault::AutoCode();
	AutoCodeViewDefault::$showReport.="<br/>";
	AutoCodeViewDefault::$showReport.=AutoCodeFoldHelper::foldafteraction();
    echo AutoCodeViewDefault::$showReport;
}  else {
    AutoCodeViewDefault::UserInput($type);
}

?>