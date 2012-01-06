<?php
require_once ("../../../init.php");
//$save_dir="C:\\wamp\\www\\ele\\services_create\\";
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
    $save_dir=$_REQUEST["save_dir"];
    if (isset($_REQUEST["type"])&&!empty($_REQUEST["type"])){
        $type=$_REQUEST["type"];
    }else{
        $type=1;
    }
    AutoCodeService::$save_dir =$save_dir;
    AutoCodeService::$type     =$type;
    AutoCodeService::AutoCode();                                                     
}  else {
    AutoCodeService::UserInput();
}


?>
