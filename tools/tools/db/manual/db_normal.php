<?php
require_once ("../../../../init.php");

$tableList=Manager_Db::newInstance()->dbinfo()->tableList(); 
$fieldInfos=array();
foreach ($tableList as $tablename){
   $fieldInfoList=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename); 
   foreach($fieldInfoList as $fieldname=>$field){
       $fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
       $fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
       $fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];
       $fieldInfos[$tablename][$fieldname]["Key"]="";
       $fieldInfos[$tablename][$fieldname]["Null"]="";
       if ($field["Key"]=="PRI"){
           $fieldInfos[$tablename][$fieldname]["Key"]="√";
       }
       if ($field["Null"]=="NO"){
           $fieldInfos[$tablename][$fieldname]["Null"]="√";
       }
   }
}
$tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF8"/>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo Gc::$site_name?>数据库说明手册</title>
<style type="text/css">
a:link,a:hover{font-size:15px;margin:0;padding:0;color:#000;}
a:visited {font-size:15px;    margin:0;padding:0;color:#f00;}
*{margin:0 0 0 12px;padding:0px;}
td,#setting th{padding:0 0 0 15px;}
#setting th{background-color:#aaa;width:200px;height:40px;border:1px solid #ccc;}
#setting th.beizhu {width:541px;height:30px;}
#setting td{width:200px;height:30px;border:1px solid #ccc;}
#setting td.beizhu {width:541px;height:30px;}
#setting {position:relativel;text-align:left;border:1px solid #777;border-collapse:collapse;}
.head1,.head2,.head3,.head4,.head5,.head6 {background-color:#aaa;height:30px;font-size:18px}
.head1 {width:200px;height:20px;}
.head2 {width:100px;height:20px;}
.head3 {width:40px;height:20px;}
.head4 {width:40px;height:20px;}
.head5 {width:279px;height:20px;}
.head6 {width:279px;height:20px;}
.sheet {border:1px solid #777;border-collapse:collapse;text-align:left;}
.sheet td{border:1px solid #ccc;height:30px;}
.sheet th{border:1px solid #ccc;height:40px;text-align:center;padding:0 15px;}
</style>
</head>

<body>
<br /><h1 align="center"><?php echo Gc::$site_name?>数据库说明手册</h1><br />
<h2>表清单</h2>
<br />
<table id="setting">
    <tr>
        <th>表名称</th>
        <th>用途</th>
        <th class="beizhu">备注</th>
    </tr>
<?php
    foreach ($tableInfoList as $tablename=>$tableinfo) 
    {
        $table_comment=$tableinfo["Comment"];
        $tablename_cn=str_replace("关系表","",$table_comment); 
        if (contain($tablename_cn,"\r")||contain($tablename_cn,"\n")){
            $tablename_cn=preg_split("/[\s,]+/", $tablename_cn);    
            $tablename_cn=$tablename_cn[0]; 
        }    
        $tableInfoList[$tablename]["Comment_Table"]=$tablename_cn;
        $table_comment=str_replace("\r\n","<br/>",$table_comment); 
        $table_comment=str_replace("\r","<br/>",$table_comment); 
        $table_comment=str_replace("\n","<br/>",$table_comment);             
        echo "    <tr>".
             "        <td><a href=\"#$tablename\">$tablename</a></td>".
             "        <td>$tablename_cn</td>".
             "        <td class=\"beizhu\">$table_comment</td>".
             "    </tr>";
    }
?>    
</table><br /><br />
<h2>数据库手册</h2>
<?php
    foreach ($tableInfoList as $tablename=>$tableinfo) 
    {    
        echo "<br />".
             "<h4 id='$tablename'>数据表:$tablename&nbsp;&nbsp;&nbsp;&nbsp;用途:{$tableinfo['Comment_Table']}</h4><br />".
             "<table class='sheet'>".
             "    <tr class='head'>".
             "        <th class='head1'>字段名称</th>".
             "        <th class='head2'>数据类型</th>".
             "        <th class='head3'>主键</th>".
             "        <th class='head4'>必填</th>".
             "        <th class='head6'>说明</th>".
             "    </tr>";     

        $fieldInfoList=$fieldInfos[$tablename]; 
        foreach($fieldInfoList as $fieldname=>$field){
            $column_comment=$field['Comment'];
            $column_comment=str_replace("\r\n","<br/>",$column_comment); 
            $column_comment=str_replace("\r","<br/>",$column_comment); 
            $column_comment=str_replace("\n","<br/>",$column_comment);  
            echo "      <tr>".
                 "          <td>$fieldname</td>".
                 "          <td>{$field['Type']}</td>".
                 "          <td>{$field['Key']}</td>".
                 "          <td>{$field['Null']}</td>".
                 "          <td>$column_comment</td>".
                 "     </tr>";
        }
        echo "</table>";
    }             
?> 
<br /><br />
<!--参考:http://wwwy1.blog.hexun.com/51907244_d.html-->
<a onclick="window.scrollTo(0,0);" 
   onmouseout="this.style.backgroundPosition='-64px 0';" 
   onmouseover="this.style.backgroundPosition='-96px 0';" 
   style="background: url('resource/returntop.png') no-repeat scroll -64px 0px transparent;display:block;position:fixed;bottom:5px; right:5px;_position:absolute;_top: expression(documentElement.scrollTop + documentElement.clientHeight-this.offsetHeight);overflow:visible;cursor:pointer;float: right; outline: 0px none; text-indent: -9999em; width: 32px; height: 32px;" title="返回顶部">返回顶部</a>
</body>
</html>
