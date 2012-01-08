<?php  
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-前端默认的表示层
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode.view   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeViewDefault extends AutoCode
{
	/**
	 * 表示层所在的目录
	 */
	public static $view_core;
    /**
     * 表示层完整的保存路径
     */
    public static $view_dir_full;
	/**
	 * 设置必需的路径
	 */
    public static function pathset()
    {    
        self::$app_dir=Gc::$appName;               
        self::$view_dir_full=self::$save_dir.DIRECTORY_SEPARATOR.self::$app_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_VIEW.DIRECTORY_SEPARATOR.Gc::$self_theme_dir.DIRECTORY_SEPARATOR.Config_F::VIEW_CORE.DIRECTORY_SEPARATOR;
    }   
                  
    /**
     * 自动生成代码-前端默认的表示层
     */
	public static function AutoCode()
	{
		self::pathset();
	    $tableList=Manager_Db::newInstance()->dbinfo()->tableList();
	    $fieldInfos=array();
	    foreach ($tableList as $tablename){
	       $fieldInfoList=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename); 
	       foreach($fieldInfoList as $fieldname=>$field){
	           $fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
	           $fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
	           $fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];
               if ($field["Null"]=='NO'){
                  $fieldInfos[$tablename][$fieldname]["IsPermitNull"]=false; 
               }else{
                  $fieldInfos[$tablename][$fieldname]["IsPermitNull"]=true;   
               }
	       }
	    }
	    $tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList(); 
	    echo UtilCss::form_css()."\r\n";
	    foreach ($fieldInfos as $tablename=>$fieldInfo){
	        $defineTplFileContent=self::tableToViewTplDefine($tablename,$tableInfoList,$fieldInfo);
	        if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineTplFileContent)){
	    		$filename="lists".Config_F::SUFFIX_FILE_TPL; 
	        	$tplName=self::saveTplDefineToDir($tablename,$defineTplFileContent,$filename);
	        	echo "生成导出完成:$tablename->$tplName!<br/>";  
	    		$filename="view".Config_F::SUFFIX_FILE_TPL; 
	        	$tplName=self::saveTplDefineToDir($tablename,$defineTplFileContent,$filename);
                echo "生成导出完成:$tablename->$tplName!<br/>";  
	        }else{
	           echo $defineTplFileContent."<br/>";
	        }
	    } 
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput()
	{
        return parent::UserInput("默认生成前台所需的表示层页面[用于前台]的输出文件路径参数");  
	}

	/**
	 * 将表列定义转换成使用ExtJs生成的表示层tpl文件定义的内容
     * @param string $tablename 表名
     * @param array $tableInfoList 表信息列表
     * @param array $fieldInfo 表列信息列表
     */
	private static function tableToViewTplDefine($tablename,$tableInfoList,$fieldInfo)
	{	    
		if ($tableInfoList!=null&&count($tableInfoList)>0&&  array_key_exists("$tablename", $tableInfoList)){
	        $table_comment=$tableInfoList[$tablename]["Comment"];
	    }else{
	        $table_comment="$tablename";
	    }   
	    $result="{extends file=\"\$templateDir/layout/normal/layout.tpl\"}\r\n".
	    		"{block name=body}\r\n".
	    		"$table_comment\r\n".
	    		"{/block}";     
	    return $result;
	}
	   
	/**
	 * 保存生成的tpl代码到指定命名规范的文件中       
     * @param string $tablename 表名称    
     * @param string $filename 文件名称
	 * @param string $defineTplFileContent 生成的代码 
	 */
	private static function saveTplDefineToDir($tablename,$defineTplFileContent,$filename)
	{ 
	    $package =self::getInstancename($tablename);  
	    $dir=self::$view_dir_full.$package.DIRECTORY_SEPARATOR;
	    return self::saveDefineToDir($dir,$filename,$defineTplFileContent);
	}
}

?>