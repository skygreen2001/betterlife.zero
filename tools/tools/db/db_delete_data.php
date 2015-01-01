<?php
require_once ("../../../init.php");

$tableList=Manager_Db::newInstance()->dbinfo()->tableList();
foreach ($tableList as $tablename)
{
	echo "TRUNCATE TABLE $tablename;<br/>";
    // echo "delete from  $tablename;<br/>";
}
?>
