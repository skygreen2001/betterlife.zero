<?php
require_once ("../../../init.php");
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeOneKey::$save_dir =$save_dir;
	AutoCodeConfig::Decode();
	AutoCodeOneKey::AutoCode();
}else{
	AutoCodeOneKey::UserInput();
}
?>
