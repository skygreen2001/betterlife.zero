<?php
require_once ("../../../init.php");
//$_REQUEST["save_dir"]="C:\\wamp\\www\\ele\\domain_create\\";
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
    $save_dir=$_REQUEST["save_dir"];
    if (isset($_REQUEST["type"])&&!empty($_REQUEST["type"])){
        $type=$_REQUEST["type"];
    }else{
        $type=2;
    }
    AutoCodeDomain::$save_dir =$save_dir;
    AutoCodeDomain::$type     =$type;
    AutoCodeDomain::AutoCode();
}  else {
    AutoCodeDomain::UserInput();
}
?>
