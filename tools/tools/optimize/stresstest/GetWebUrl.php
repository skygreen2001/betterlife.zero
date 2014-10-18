<?php
require_once ("../../../../init.php");

/**
 * 辅助压力测试的工具:获取网站所有的链接地址
 */
class GetWebUrl
{
	/**
	 * 是否显示测试报表
	 * @return boolean
	 */
	public static $isShowReport=true;
	/**
	* 生成保存路径
	*/
	private static $save_urls_path;

	/**
	* 获取所有可能的链接地址
	*/
	public static function getAllMaybeUrl()
	{
		self::$save_urls_path=Gc::$nav_root_path."stressdata".DS;
		UtilFileSystem::createDir(self::$save_urls_path);
		self::$save_urls_path.="urllist.txt";
		$count=0;
		$result="";
		foreach (Gc::$module_names as $moduleName) {
			if (!contain($moduleName,"admin")){
				$moduleDir=Gc::$nav_root_path.Gc::$module_root.DS.$moduleName.DS."action".DS;
				$action_names=UtilFileSystem::getFilesInDirectory($moduleDir);
				foreach ($action_names as $action_path) {
					$action_classname_name=basename($action_path,".php");
					$methods=self::getClassMethodsInfo($action_classname_name);
					$action_name=str_replace("Action_", "", $action_classname_name);

					foreach ($methods as $method) {
						$action_name=strtolower($action_name);
						$result.=Gc::$url_base."index.php?go=".$moduleName.".".$action_name."."."".$method."\r\n";

						$count+=1;
					}
				}
			}
		}
		if (self::$isShowReport) {
			echo "生成保存路径：".self::$save_urls_path."<br/>";
			echo "共计有:".$count."个访问url地址<br/><br/>";
			echo "列表如下:<br/>";
			$table_change = array("\r\n"=>"<br/>");
			$show_result= strtr($result,$table_change);
		}
		echo $show_result."<br/>";
		file_put_contents(self::$save_urls_path,$result);
	}



	/**
	* 获取对象所有属性信息
	* @object string 对象实体|对象名称
	* @return array 对象所有属性信息【分三列：方法名|属性值|属性访问权限】
	*/
	public static function getClassMethodsInfo($object)
	{
		$class=object_reflection($object);
		$dataobjectMethods=$class->getMethods(ReflectionMethod::IS_PUBLIC);
		$result=array();
		foreach($dataobjectMethods as $method)
		{
			$method_name=$method->name;
			$class_name=$method->getDeclaringClass()->name;

			if ($class_name==$object){
				$result[]=$method_name;
			}
		}
		return $result;
	}



}
GetWebUrl::getAllMaybeUrl();
?>