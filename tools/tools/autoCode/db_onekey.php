<?php
require_once ("../../../init.php");
//$save_dir="C:\\wamp\\www\\ele\\services_create\\";
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{      
    AutoCodeOneKey::$save_dir =$save_dir; 
    AutoCodeOneKey::AutoCode();                                                     
}  else {
    AutoCodeOneKey::UserInput();
}
?>
