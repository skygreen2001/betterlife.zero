<?php
require_once ("../../../init.php");
if(isset($_REQUEST["model_save_dir"])&&!empty($_REQUEST["model_save_dir"])){
	if(isset($_REQUEST["model_save_dir"])&&!empty($_REQUEST["model_save_dir"]))
		$model_save_dir=$_REQUEST["model_save_dir"];
	$overwrite=array();

	if(isset($_REQUEST["overwritedomain"])&&!empty($_REQUEST["overwritedomain"]))$overwrite=array_merge($overwrite,$_REQUEST["overwritedomain"]);
	if(isset($_REQUEST["overwritebg"])&&!empty($_REQUEST["overwritebg"]))$overwrite=array_merge($overwrite,$_REQUEST["overwritebg"]);
	if(isset($_REQUEST["overwritefront"])&&!empty($_REQUEST["overwritefront"]))$overwrite=array_merge($overwrite,$_REQUEST["overwritefront"]);
	if(isset($_REQUEST["overwritemodel"])&&!empty($_REQUEST["overwritemodel"]))$overwrite=array_merge($overwrite,$_REQUEST["overwritemodel"]);

	if(count($overwrite)>0)AutoCodeModel::overwrite($overwrite,$model_save_dir);
	$_REQUEST["save_dir"]=$_REQUEST["model_save_dir"];
}

AutoCodeModel::UserInput();
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
    $save_dir=$_REQUEST["save_dir"];
    AutoCodeModel::$save_dir =$save_dir;

    $table_names=$_GET["table_names"];
    if(empty($table_names)){
    	die("<div align='center'><font color='red'>至少选择一张表,请确认！</font></div>");
    }else{
        AutoCodeConfig::Decode();
        AutoCodeModel::$showReport="";
        AutoCodeModel::AutoCode($table_names);
    }
	echo "<div style='width: 1000px; margin-left: 110px;'>";
	echo "<span>&nbsp;&nbsp;</span><a style='margin-left:15px;' href='javascript:' style='cursor:pointer;' onclick=\"(document.getElementById('showReport').style.display=(document.getElementById('showReport').style.display=='none')?'':'none')\">显示报告</a>";
	echo "<div id='showReport' style='display: none;'>";
	echo AutoCodeModel::$showReport;
	echo "</div>";
	echo "</div>";
	AutoCodePreviewReport::init();
	$showReport=AutoCodePreviewReport::showReport($table_names);
	echo $showReport;
}
?>
