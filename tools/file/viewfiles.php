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

$dirs_core=UtilFileSystem::getAllFilesInDirectory(Initializer::$NAV_CORE_PATH);
foreach (Initializer::$moduleFiles as $moduleFile) {
    $files=array_merge($dirs_core, $moduleFile);
}


UtilCss::report_info();

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
foreach ($files as $file) {
    echo '<tr>';
    echo '<td>'.dirname($file).'</td>';
    echo '<td>'.basename($file).'&nbsp;</td>';
    echo '<td>'.count(file($file)).'</td>';
    echo '<td>'.filesize($file).'</td>';
    echo '<td>[<a href="viewfilebyline.php?f='.$file.'">查看</a>]';    
    echo '[<a href="editfile.php?f='.$file.'">编辑</a>]</td>';    
    echo '</tr>';
}
echo '</table>';
echo "<br/><br/><br/><br/><br/><br/><br/>"

?>
