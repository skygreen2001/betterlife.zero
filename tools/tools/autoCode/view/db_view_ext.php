<?php
require_once ("../../../../init.php");
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
    $save_dir=$_REQUEST["save_dir"];
    AutoCodeViewExt::$save_dir =$save_dir;    
    AutoCodeViewExt::AutoCode();                                                     
}  else {
    AutoCodeViewExt::UserInput();
}


?>