<?php
require_once ("../../../../../init.php");
if (isset($_REQUEST["type"])&&!empty($_REQUEST["type"])){
    $type=$_REQUEST["type"];
}else{
    $type=2;
}
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
    $save_dir=$_REQUEST["save_dir"];
    AutoCodeDomain::$save_dir =$save_dir;
    AutoCodeDomain::$type     =$type;
    //读取配置文件里查询条件和关系列显示的配置
    $filename_config_xml=Gc::$nav_root_path."tools".DS."tools".DS."autocode".DS."autocode.config.xml";
    if (file_exists($filename_config_xml)){
        $classes=UtilXmlSimple::fileXmlToObject($filename_config_xml);
        $dataobjects = $classes->xpath("//class");
        foreach ($dataobjects as $dataobject) {
            $attributes=$dataobject->attributes();
            $classname=$attributes->name."";
            //**********************start:导出数据对象之间关系规范定义*************************
            AutoCodeConfig::relation_specification_create($classname,$dataobject);
            //**********************end  :导出数据对象之间关系规范定义*************************
        }
    }
    AutoCodeDomain::$showReport="";
    AutoCodeDomain::$showReport.=AutoCodeFoldHelper::foldEffectReady();
    AutoCodeDomain::$showReport.=AutoCodeFoldHelper::foldbeforedomain();
    AutoCodeDomain::AutoCode();
    AutoCodeDomain::$showReport.=AutoCodeFoldHelper::foldafterdomain();
    echo AutoCodeDomain::$showReport;
}  else {
    AutoCodeDomain::UserInput($type);
}

?>
