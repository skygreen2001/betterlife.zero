<?php
require_once ("../../../init.php");

$tableList=Manager_Db::newInstance()->dbinfo()->tableList();      
foreach ($tableList as $tablename){                                                                   
    echo "DROP  TABLE $tablename;<br/>";
}        
?>
