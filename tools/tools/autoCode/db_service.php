<?php
require_once ("../../../init.php");
//$save_dir="C:\\wamp\\www\\ele\\services_create\\";
//Service类所在的目录
$package="services";
/**
 * 服务类Php文件保存的路径
 */
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
