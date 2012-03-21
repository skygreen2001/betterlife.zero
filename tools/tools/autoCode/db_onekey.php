<?php
require_once ("../../../init.php");                   
if (isset($_REQUEST["save_dir"])&&!empty($_REQUEST["save_dir"]))
{      
	$save_dir=$_REQUEST["save_dir"];
	AutoCodeOneKey::$save_dir =$save_dir; 
	   
	//读取配置文件里查询条件和关系列显示的配置
	if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."autocode.config.xml")){
		$classes=UtilXmlSimple::fileXmlToObject(dirname(__FILE__).DIRECTORY_SEPARATOR."autocode.config.xml");  
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
			//**********************start:导出数据对象之间关系规范定义*************************       
			relation_specification_create($classname,$dataobject);
			//**********************end  :导出数据对象之间关系规范定义*************************
		} 
	}  
	AutoCodeOneKey::AutoCode();                                                     
}  else {
	AutoCodeOneKey::UserInput();
}     

/**
 * 导出数据对象之间关系规范定义
 * @param string classname 导出的类名
 * @param array dataobject 来自xml配置文件单个类的所有说明
 */
function relation_specification_create($classname,$dataobject)
{           
	//导出一对一关系规范定义(如果存在)
	$has_one_spec=$dataobject->has_one->relationclass;
	if (!empty($has_one_spec)){       
		foreach ($has_one_spec as $has_one) {
			$attributes=$has_one->attributes();  
			AutoCodeDomain::$relation_all[$classname]['has_one'][$attributes->name.""]=$has_one."";  
		}                     
	}                  
	//导出从属一对一关系规范定义(如果存在)
	$belong_has_one_spec=$dataobject->belong_has_one->relationclass;
	if (!empty($belong_has_one_spec)){        
		foreach ($belong_has_one_spec as $belong_has_one) {
			$attributes=$belong_has_one->attributes();  
			AutoCodeDomain::$relation_all[$classname]['belong_has_one'][$attributes->name.""]=$belong_has_one."";  
		}                              
	}                          
	//导出一对多关系规范定义(如果存在)
	$has_many_spec=$dataobject->has_many->relationclass;
	if (!empty($has_many_spec)){         
		foreach ($has_many_spec as $has_many) {
			$attributes=$has_many->attributes();  
			AutoCodeDomain::$relation_all[$classname]['has_many'][$attributes->name.""]=$has_many."";  
		}                             
	}                                
	//导出多对多关系规范定义(如果存在)
	$many_many_spec=$dataobject->many_many->relationclass;
	if (!empty($many_many_spec)){            
		foreach ($many_many_spec as $many_many) {
			$attributes=$many_many->attributes();  
			AutoCodeDomain::$relation_all[$classname]['many_many'][$attributes->name.""]=$many_many."";  
		}                          
	}                                       
	//导出从属于多对多关系规范定义(如果存在)
	$belongs_many_many_spec=$dataobject->belongs_many_many->relationclass;
	if (!empty($belongs_many_many_spec)){        
		foreach ($belongs_many_many_spec as $belongs_many_many) {
			$attributes=$belongs_many_many->attributes();  
			AutoCodeDomain::$relation_all[$classname]['belongs_many_many'][$attributes->name.""]=$belongs_many_many."";  
		}                                    
	}  
}
?>
