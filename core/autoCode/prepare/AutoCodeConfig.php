<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成配置文件<br/>
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeConfig extends AutoCode
{         
	
	/**
	 * 自动生成配置
	 */
	public static function CreateAutoConfig()
	{
		$filename=Gc::$nav_root_path."tools".DIRECTORY_SEPARATOR."tools".DIRECTORY_SEPARATOR."autoCode".DIRECTORY_SEPARATOR."autocode_create.config.xml";
		$classes=array("class"=>array());
		self::init();
		foreach (self::$fieldInfos as $tablename=>$fieldInfo){
			if (contain($tablename,Config_Db::TABLENAME_RELATION)){
				continue;
			}
			$classname=self::getClassname($tablename);
			$showfieldname=self::getShowFieldNameByClassname($classname);
			$classes["class"][]=array(
				'@attributes' => array(
					"name"=>$classname
				),
				"conditions"=>array(
					"condition"=>array(
						array(
							"@value"=>$showfieldname
						)
					)
				),
			);
		}
		$result =UtilArray::saveXML($filename,$classes,"classes");
		return true;
	}
}
?>
