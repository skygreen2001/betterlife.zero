<?php
require_once ("../../../../init.php");
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
    $save_dir=$_REQUEST["save_dir"];   
    AutoCodeViewDefault::$save_dir =$save_dir;
    AutoCodeViewDefault::AutoCode();                                                     
}  else {
    AutoCodeViewDefault::UserInput();
}

?>