<?php
require_once ("../../../init.php");

$tableList=Manager_Db::newInstance()->dbinfo()->tableList();
$symbol_connect="_";
foreach ($tableList as $tablename){
    $new_table_name=str_replace($old_prefix.$symbol_connect, $new_prefix.$symbol_connect, $tablename);
    echo "DROP  TABLE $tablename;<br/>";
}        
?>
