<?php
require_once ("../../../init.php");

$mysql_keywords="ACCESSIBLE,ADD,ALL,ALTER,ANALYZE,AND,AS,ASC,ASENSITIVE,BEFORE,BETWEEN,BIGINT,BINARY,BLOB,BOTH,BY,CALL,CASCADE,CASE,CHANGE,CHAR,CHARACTER,CHECK,COLLATE,COLUMN,CONDITION,CONSTRAINT,CONTINUE,CONVERT,CREATE,CROSS,CURRENT_DATE,CURRENT_TIME,CURRENT_TIMESTAMP,CURRENT_USER,CURSOR,DATABASE,DATABASES,DAY_HOUR,DAY_MICROSECOND,DAY_MINUTE,DAY_SECOND,DEC,DECIMAL,DECLARE,DEFAULT,DELAYED,DELETE,DESC,DESCRIBE,DETERMINISTIC,DISTINCT,DISTINCTROW,DIV,DOUBLE,DROP,DUAL,EACH,ELSE,ELSEIF,ENCLOSED,ESCAPED,EXISTS,EXIT,EXPLAIN,FALSE,FETCH,FLOAT,FLOAT4,FLOAT8,FOR,FORCE,FOREIGN,FROM,FULLTEXT,GRANT,GROUP,HAVING,HIGH_PRIORITY,HOUR_MICROSECOND,HOUR_MINUTE,HOUR_SECOND,IF,IGNORE,IN,INDEX,INFILE,INNER,INOUT,INSENSITIVE,INSERT,INT,INT1,INT2,INT3,INT4,INT8,INTEGER,INTERVAL,INTO,IS,ITERATE,JOIN,KEY,KEYS,KILL,LEADING,LEAVE,LEFT,LIKE,LIMIT,LINEAR,LINES,LOAD,LOCALTIME,LOCALTIMESTAMP,LOCK,LONG,LONGBLOB,LONGTEXT,LOOP,LOW_PRIORITY,MASTER_SSL_VERIFY_SERVER_CERT,MATCH,MEDIUMBLOB,MEDIUMINT,MEDIUMTEXT,MIDDLEINT,MINUTE_MICROSECOND,MINUTE_SECOND,MOD,MODIFIES,NATURAL,NOT,NO_WRITE_TO_BINLOG,NULL,NUMERIC,ON,OPTIMIZE,OPTION,OPTIONALLY,OR,ORDER,OUT,OUTER,OUTFILE,PRECISION,PRIMARY,PROCEDURE,PURGE,RANGE,READ,READS,READ_WRITE,REAL,REFERENCES,REGEXP,RELEASE,RENAME,REPEAT,REPLACE,REQUIRE,RESTRICT,RETURN,REVOKE,RIGHT,RLIKE,SCHEMA,SCHEMAS,SECOND_MICROSECOND,SELECT,SENSITIVE,SEPARATOR,SET,SHOW,SMALLINT,SPATIAL,SPECIFIC,SQL,SQLEXCEPTION,SQLSTATE,SQLWARNING,SQL_BIG_RESULT,SQL_CALC_FOUND_ROWS,SQL_SMALL_RESULT,SSL,STARTING,STRAIGHT_JOIN,TABLE,TERMINATED,THEN,TINYBLOB,TINYINT,TINYTEXT,TO,TRAILING,TRIGGER,TRUE,UNDO,UNION,UNIQUE,UNLOCK,UNSIGNED,UPDATE,USAGE,USE,USING,UTC_DATE,UTC_TIME,UTC_TIMESTAMP,VALUES,VARBINARY,VARCHAR,VARCHARACTER,VARYING,WHEN,WHERE,WHILE,WITH,WRITE,XOR,YEAR_MONTH,ZEROFILL";

echo "<a href='?isComment=1'>开启注释</a>|<a href='?isComment=0'>关闭注释</a><br/>";
$isComment=false;
if (isset($_REQUEST["isComment"])&&!empty($_REQUEST["isComment"])){
	if($_REQUEST["isComment"]=="1")$isComment=true;
}

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
if ($isComment) {
	echo "1.列名头字母大写<br/>";
	echo "2.表主键字段统一成ID<br/><br/>";
	echo "3.去掉表前缀:".Config_Db::$table_prefix."<br/>";
	echo "4.表名头字母大写<br/><br/>";
	echo "--在MySQL的配置文件中my.ini [mysqld] 中增加一行<br/>";
	echo "--lower_case_table_names=0<br/>";
	echo "--参数解释：<br/>";
	echo "--0：区分大小写<br/>";
	echo "--1：不区分大小写<br/>";
	echo "<br/>";
}


if ($isComment) {
	echo str_repeat("*",40)."1.列名头字母大写".str_repeat("*",40)."<br/><br/>";
}
foreach ($fieldInfos as $tablename=>$fieldInfo){  
	if ($isComment) {
		echo "表名:$tablename<br/>";
	}
	
	foreach ($fieldInfo as $fieldname=>$field){
		$newwords=ucfirst($fieldname);
		$type=$fieldInfos[$tablename][$fieldname]["Type"];
		if(stripos($newwords,"time")!==false){
			$type="datetime";
		}else if(stripos($newwords,"_")!==false){
			$index=stripos($newwords,"_");
			$fname=substr($newwords,$index+1);
			if($fname=="id"){
				$fname="ID";
			}else{
				$fname=ucfirst($fname);
			}
			$newwords=substr($newwords,0,$index)."_".$fname;
		}
		echo "alter table $tablename change column $fieldname $newwords ".$type." COMMENT '".$fieldInfos[$tablename][$fieldname]["Comment"]."';<br/>";
	}
}

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
if ($isComment) {
	echo "<br/>".str_repeat("*",40)."2.表主键字段统一成ID".str_repeat("*",40)."<br/>";
}
foreach ($fieldInfos as $tablename=>$fieldInfo){  
	if ($isComment) {
		echo "表名:$tablename<br/>";
	}
	$classname=getClassname($tablename);
	$classname{0}=strtolower($classname{0});
	$old_fieldname=$classname."_id";
	echo "alter table $tablename change column $old_fieldname ID ".$fieldInfos[$tablename][$old_fieldname]["Type"]." COMMENT '".$fieldInfos[$tablename][$old_fieldname]["Comment"]."';<br/>";
	if (!Manager_Db::newInstance()->dbinfo()->hasUnique($tablename,array("ID",$old_fieldname))){ 
		echo "alter table $tablename add unique(ID);<br/>";
	}
}

if ($isComment) {
	echo "<br/>".str_repeat("*",40)."3.去掉表前缀:".Config_Db::$table_prefix.str_repeat("*",40)."<br/>";
	echo str_repeat("*",40)."4.表名头字母大写".str_repeat("*",40)."<br/><br/>";
}
foreach ($tableList as $tablename){
	$new_table_name=str_replace(Config_Db::$table_prefix, "", $tablename);
	$new_table_name=ucfirst($new_table_name);
	$new_table_names=strtoupper($new_table_name);
	if (contain($mysql_keywords,$new_table_names.",")){
		$new_table_name=$new_table_name."s";
	}
	echo "ALTER  TABLE $tablename RENAME TO $new_table_name;<br/>";
}

?>