<?php
require_once ("../../../init.php");

$tableList=Manager_Db::newInstance()->dbinfo()->tableList();
$symbol_connect="_";
foreach ($tableList as $tablename){                                                                   
    echo "DROP  TABLE $tablename;<br/>";
}        
?>
