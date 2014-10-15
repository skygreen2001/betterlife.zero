<?php
/**
 * 显示所有的核心源码文件信息
 * @todo 按行数排序
 * @author zhouyuepu
 */
require_once ("../../init.php");
class ViewFiles {
	public static function getFiles($dir_name) {
		return UtilFileSystem::getAllFilesInDirectory($dir_name);
	}
}

/******************************显示本工程文件列表清单********************************************************/
$files=UtilFileSystem::getAllFilesInDirectory(Initializer::$NAV_CORE_PATH);
$files_config=UtilFileSystem::getAllFilesInDirectory(Initializer::$NAV_CONFIG_PATH);
$files=array_merge($files_config,$files);
foreach (Initializer::$moduleFiles as $moduleFile) {
	$files=array_merge($files, $moduleFile);
}
$files_tools=UtilFileSystem::getAllFilesInDirectory(Gc::$nav_root_path."tools");
$files=array_merge($files,$files_tools);
UtilCss::report_info();

// $sort="";
// if(isset($_POST)&&isset($_POST["code"])){

// }

/**
 * 共分为三列
 * 1：路径
 * 2：文件名称【查看】
 * 3：文件信息【查看】
 */
echo '<pre>';
echo '<table class='.UtilCss::CSS_REPORT_TABLE.'>';
echo '<tr><td colspan="5" align="center"><h1>所有源码清单</h1></td></tr>';
echo '<tr>';
echo '<th>文件路径</th>';
echo '<th>文件名</th>';

echo '<th>行数</th>';
echo '<th>大小</th>';
echo '<th>操作</th>';
echo '</tr>';

$file_arr=array();

$file_key_arr=array();
foreach ($files as $file) {
	$file_key_arr[]=count(file($file));
	$file_arr[$file]["dirname"]=dirname($file);
	$file_arr[$file]["basename"]=basename($file);
	$file_arr[$file]["count"]=count(file($file));
	$file_arr[$file]["filesize"]=filesize($file);
	$file_arr[$file]["file"]=$file;
}

//array_multisort($file_key_arr,SORT_DESC,$file_arr,SORT_DESC);

foreach ($file_arr as $file) {
	echo '<tr>';
	echo '<td>'.$file["dirname"].'</td>';
	echo '<td>'.$file["basename"].' </td>';
	echo '<td>'.$file["count"].'</td>';
	echo '<td>'.$file["filesize"].'</td>';
	echo '<td>[<a href="viewfilebyline.php?f='.$file["file"].'" target="_blank">查看</a>]';
	echo '[<a href="editfile.php?f='.$file["file"].'" target="_blank">编辑</a>]</td>';
	echo '</tr>';
}
echo '</table>';
echo "<br/><br/><br/><br/><br/><br/><br/>";
?>
