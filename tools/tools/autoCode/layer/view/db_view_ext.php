<?php
require_once ("../../../../../init.php");
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeViewExt::$save_dir =$save_dir;

	//读取配置文件里查询条件和关系列显示的配置
	if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."/../autocode.config.xml")){
		$classes=UtilXmlSimple::fileXmlToObject(dirname(__FILE__).DIRECTORY_SEPARATOR."/../autocode.config.xml");
		$dataobjects = $classes->xpath("//class");
		foreach ($dataobjects as $dataobject) {
			$attributes=$dataobject->attributes();
			$classname=$attributes->name."";
			$conditions_obj=$dataobject->conditions->condition;
			$conditions=array("relation_show"=>array());
			foreach ($conditions_obj as $condition) {
				$attributes_condition=$condition->attributes();
				$condition_name=$condition."";
				if ($attributes_condition){
					$con_relation_class=$attributes_condition->relation_class."";
					$show_name=$attributes_condition->show_name."";
					if (!array_key_exists($condition_name,$conditions["relation_show"])){
						$conditions["relation_show"][$condition_name]["relation_class"]=$con_relation_class;
						$conditions["relation_show"][$condition_name]["show_name"]=$show_name;
					}
				}
				if (!in_array($condition_name,$conditions)){
					$conditions[]=$condition_name;
				}
			}
			AutoCodeViewExt::$filter_fieldnames[$classname]=$conditions;

			$relation_viewfield_obj=$dataobject->relationShows->show;
			if (!empty($relation_viewfield_obj)){
				$relation_viewfields=array();
				foreach ($relation_viewfield_obj as $relation_viewfield) {
					$attributes=$relation_viewfield->attributes();
					$local_key=$attributes->local_key."";
					if (!array_key_exists($local_key,$relation_viewfields)){
						$relation_class=$attributes->relation_class."";
						$show=array($relation_class=>$relation_viewfield."");
						$relation_viewfields[$local_key]=$show;
					}
				}
				AutoCodeViewExt::$relation_viewfield[$classname]=$relation_viewfields;
			}
		}
	}
	AutoCodeViewExt::AutoCode();
}  else {
	AutoCodeViewExt::UserInput();
}


?>