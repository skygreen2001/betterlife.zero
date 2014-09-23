<?php
require_once ("../../../init.php");
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeModel::$save_dir =$save_dir;

	$table_names=$_GET["table_names"];
	AutoCodeConfig::Decode($table_names);
	AutoCodeModel::AutoCode($table_names);
}  else {
	AutoCodeModel::UserInput();
}
?>
