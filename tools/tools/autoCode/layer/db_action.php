<?php
require_once ("../../../../init.php");
//$_REQUEST["save_dir"]="C:\\wamp\\www\\ele\\domain_create\\";
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	$save_dir=$_REQUEST["save_dir"];
	if (isset($_REQUEST["type"])&&!empty($_REQUEST["type"])){
		$type=$_REQUEST["type"];
	}else{
		if ($_REQUEST["type"]==0){
			$type=0;
		}else{
			$type=2;
		}
	}
	AutoCodeAction::$save_dir =$save_dir;
	AutoCodeAction::$type     =$type;
	AutoCodeFoldHelper::foldEffectReady();
	echo "<br/>";
	AutoCodeFoldHelper::foldbeforeaction();
	AutoCodeAction::AutoCode();
	echo "<br/>";
	AutoCodeFoldHelper::foldafteraction();
}  else {
	AutoCodeAction::UserInput();
}
?>