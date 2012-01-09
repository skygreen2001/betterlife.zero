<?php
require_once ("../../../init.php");                   
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{      
    $save_dir=$_REQUEST["save_dir"];
    AutoCodeOneKey::$save_dir =$save_dir; 
    AutoCodeViewExt::$filter_fieldnames=array(           
        'Blog'=>array('name','content')
    );    
    AutoCodeOneKey::AutoCode();                                                     
}  else {
    AutoCodeOneKey::UserInput();
}
?>
