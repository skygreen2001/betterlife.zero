<?php
require_once ("../../../init.php");
if (isset($_REQUEST["oldwords"])&&!empty($_REQUEST["oldwords"]))
{        
    $oldwords=$_REQUEST["oldwords"];
    if (isset($_REQUEST["newwords"])&&!empty($_REQUEST["newwords"])){
        $newwords=$_REQUEST["newwords"];
    }else{
        $newwords=Gc::$appName;
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
    foreach ($fieldInfos as $tablename=>$fieldInfo){  
        foreach ($fieldInfo as $fieldname=>$field)
        {  
            $data=Manager_Db::newInstance()->dao()->sqlExecute("select $fieldname from $tablename where $fieldname like '%$oldwords%'");
            if ($data){
                $filterTableColumns[$tablename][]=$fieldname;
            }
        }
    }
    if ($filterTableColumns){
        echo "存在[{$oldwords}]的表列清单如下<br/>";
        foreach ($filterTableColumns as $key=>$columns) {
            echo "表名:$key<br/>";
            foreach ($columns as $column) {
                echo "===$column===<br/>";
            }
        }
        echo "<br/>";

        echo "查询[{$oldwords}]的SQL语句清单如下<br/>";
        foreach ($filterTableColumns as $key=>$columns) {
            foreach ($columns as $column) {
                echo "select * from $key where $column like '%$oldwords%';<br/>";
            }
        }
        echo "<br/>";

        echo "将[{$oldwords}]替换成[{$newwords}]的SQL语句清单如下<br/>";
        foreach ($filterTableColumns as $key=>$columns) {
            foreach ($columns as $column) {
                echo "update $key set $column  = replace($column,'$oldwords','$newwords');<br/>";
            }
        }
    }else{
        echo "在数据库里没有关键字:[{$oldwords}]";
    }
}
else
{
    echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
           <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
    echo "<head>\r\n";     
    echo UtilCss::form_css()."\r\n";
    $url_base=UtilNet::urlbase();
    echo "<script type='text/javascript' src='".$url_base."common/js/util/file.js'></script>";
    echo "</head>";     
    echo "<body>";   
    echo "<br/><br/><br/><br/><br/><h1 align='center'>$title</h1>";
    echo "<div align='center' height='450'>";
    echo "<form>";  
    echo "  <div style='line-height:1.5em;'>";  
    echo "      <label>原关键字:</label><input type=\"text\" name=\"oldwords\" value=\"\" id=\"oldwords\" /><br/><br/>";    
    echo "      <label>新关键字:</label><input type=\"text\" name=\"newwords\" value=\"\" id=\"newwords\" /><br/><br/>";
    echo "  </div>";
    echo "  <input type=\"submit\" value='生成' /><br/>";
    echo "</form>";
    echo "</div>";
    echo "</body>";      
    echo "</html>";  
}
?>

