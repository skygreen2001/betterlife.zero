<?php
require_once ("../../../init.php");
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeModel::$save_dir =$save_dir;

	$table_names=$_GET["table_names"];
	if(!empty($table_names)){
		AutoCodeConfig::Decode();//$table_names
		AutoCodeModel::AutoCode($table_names);
	}
}

if(isset($_REQUEST["overwrite"])&&!empty($_REQUEST["overwrite"])){
	if($_REQUEST["overwrite"][0]=="on")unset($_REQUEST["overwrite"][0]);
	if(isset($_REQUEST["model_save_dir"])&&!empty($_REQUEST["model_save_dir"]))
		$model_save_dir=$_REQUEST["model_save_dir"];
	AutoCodeModel::overwrite($_REQUEST["overwrite"],$model_save_dir);
}

AutoCodeModel::UserInput();
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	echo "<div style='width: 1000px; margin-left: 110px;'>";
	echo "<span>&nbsp;&nbsp;</span><a style='margin-left:15px;' href='javascript:' style='cursor:pointer;' onclick=\"(document.getElementById('showReport').style.display=(document.getElementById('showReport').style.display=='none')?'':'none')\">显示报告</a>";
	echo "<div id='showReport' style='display: none;'>";
	echo AutoCodeModel::$showReport;
	echo "</div>";
	echo "</div>";
	AutoCodePreviewReport::init();
	$showReport=AutoCodePreviewReport::showReport();
	echo $showReport;
}
?>
