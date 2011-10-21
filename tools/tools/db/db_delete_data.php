<?php
require_once ("../../../init.php");

$tableList=Manager_Db::newInstance()->dbinfo()->tableList();
$symbol_connect="_";
foreach ($tableList as $tablename){
    echo "delete from  $tablename;<br/>";
}        
?>
