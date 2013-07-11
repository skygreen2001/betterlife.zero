<?php

require_once ("../../../init.php");

$tableList=Manager_Db::newInstance()->dbinfo()->tableList();
$fieldInfos=array();
foreach ($tableList as $tablename){
	$fieldInfoList=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename);
	foreach($fieldInfoList as $fieldname=>$field){
		$fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
		$fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
		$fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];
	}
}

$tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
$filterTableColumns=array();
echo "将所有表的主键设置成Unique<br/>";
echo "<br/>";



/**
 * 从表名称获取对象的类名【头字母大写】。
 * @param string $tablename
 * @return string 返回对象的类名
 */
function getClassname($tablename)
{
	if (in_array($tablename, Config_Db::$orm)) {
		$classname=array_search($tablename, Config_Db::$orm);
	}else {
		$classnameSplit= explode("_", $tablename);
		$classnameSplit=array_reverse($classnameSplit);
		$classname=ucfirst($classnameSplit[0]);
	}
	return $classname;
}
foreach ($tableList as $tablename){  
	$classname=getClassname($tablename);
	$fieldname=DataObjectSpec::getRealIDColumnNameStatic($classname);
	// $classname{0}=strtolower($classname{0});
	// $fieldname=$classname."_id";

	if (!Manager_Db::newInstance()->dbinfo()->hasUnique($tablename,$fieldname)){ 
		echo "alter table $tablename add unique($fieldname);<br/>";
	}
}

?>