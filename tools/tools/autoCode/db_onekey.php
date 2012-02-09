<?php
require_once ("../../../init.php");                   
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{      
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeOneKey::$save_dir =$save_dir; 
	AutoCodeViewExt::$filter_fieldnames=array(           
		'Blog'=>array('name','content')
	);    
	//读取配置文件里查询条件和关系列显示的配置
	if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."autocode.config.xml")){
		$classes=UtilXmlSimple::fileXmlToObject(dirname(__FILE__).DIRECTORY_SEPARATOR."autocode.config.xml");  
		$dataobjects = $classes->xpath("//class");  
		foreach ($dataobjects as $dataobject) {
			$attributes=$dataobject->attributes();
			$classname=$attributes->name."";          
			$conditions_obj=$dataobject->conditions->condition; 
			$conditions=array();
			foreach ($conditions_obj as $condition) {
				$conditions[]=$condition."";
			}                                                              
			AutoCodeViewExt::$filter_fieldnames[$classname]=$conditions;
			
			$relation_viewfield_obj=$dataobject->relationShows->show;
			if (!empty($relation_viewfield_obj)){
				$relation_viewfields=array();
				foreach ($relation_viewfield_obj as $relation_viewfield) {
					$attributes=$relation_viewfield->attributes(); 
					$local_key=$attributes->local_key."";
					$relation_class=$attributes->relation_class."";  
					$show=array($relation_class=>$relation_viewfield.""); 
					$relation_viewfields[$local_key]=$show;
				}                                                                                                            
				AutoCodeViewExt::$relation_viewfield[$classname]=$relation_viewfields;
			}
		} 
	}  
	AutoCodeOneKey::AutoCode();                                                     
}  else {
	AutoCodeOneKey::UserInput();
}
?>
