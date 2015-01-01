<?php
require_once ("../../../init.php");

if (isset($_REQUEST["old_prefix"])&&!empty($_REQUEST["old_prefix"])){
	$old_prefix=$_REQUEST["old_prefix"];
	if (contain($old_prefix,"_"))$old_prefix=str_replace("_", "", $old_prefix);
}
if (isset($_REQUEST["new_prefix"])&&!empty($_REQUEST["new_prefix"])){
	$new_prefix=$_REQUEST["new_prefix"];
	if (contain($new_prefix,"_"))$new_prefix=str_replace("_", "", $new_prefix);
}
if (!isset($old_prefix)||!isset($old_prefix)){
	echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		   <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
	echo "<head>";
	echo UtilCss::form_css();
	echo "</head>";
	echo "<body>";
	echo "<br/><br/><br/><br/><br/><h1 align='center'>修改数据库表前缀名：【原前缀名】和【新前缀名】</h1>";
	echo "<div align='center' height='600'>";
	echo "<form>";
	echo "  <div>";
	echo "      <label>原前缀名</label><input name=\"old_prefix\" /><br/>";
	echo "      <label>新前缀名</label><input name=\"new_prefix\" />";
	echo "  </div>";
	echo "  <input type=\"submit\" value='提交' />";
	echo "</form>";
	echo "</div>";
	echo "</body>";
	echo "</html>";
}else{
	$tableList=Manager_Db::newInstance()->dbinfo()->tableList();
	$symbol_connect="_";
	if (empty($new_prefix)) $symbol_connect_new=""; else $symbol_connect_new="_";
	foreach ($tableList as $tablename){
		$new_table_name=str_replace($old_prefix.$symbol_connect, $new_prefix.$symbol_connect_new, $tablename);
		echo "ALTER  TABLE $tablename RENAME TO $new_table_name;<br/>";
	}
}
?>
