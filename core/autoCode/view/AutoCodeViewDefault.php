<?php

/**
 * 工具类:自动生成代码-前端默认的表示层
 */
class AutoCodeViewDefault extends AutoCode
{
	/**
	 * 表示层所在的目录
	 */
	public static $view_core;

	/**
	 * 设置必需的路径
	 */
    public static function pathset()
    {
    	$view_package=Config_F::VIEW_VIEW.DIRECTORY_SEPARATOR.Gc::$self_theme_dir.DIRECTORY_SEPARATOR;
    	self::$view_core=$view_package."core".DIRECTORY_SEPARATOR;
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
	        	$tplName=self::saveTplDefineToDir(self::$save_dir,$tablename,$defineTplFileContent,$filename);
	        	echo "生成导出完成:$tablename->$tplName!<br/>";  
	    		$filename="view".Config_F::SUFFIX_FILE_TPL; 
	        	$tplName=self::saveTplDefineToDir(self::$save_dir,$tablename,$defineTplFileContent,$filename);
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
	    /**
	     * javascript文件夹选择框的两种解决方案,这里选择了第一种
	     * @link http://www.blogjava.net/supercrsky/archive/2008/06/17/208641.html
	     */
	    echo  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	           <html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">';
	    echo "<head>\r\n";     
	    echo UtilCss::form_css()."\r\n";
	    $url_base=UtilNet::urlbase();
	    echo "<script type='text/javascript' src='".$url_base."common/js/util/file.js'></script>";
	    echo "</head>";     
	    echo "<body>";   
	    echo "<br/><br/><br/><br/><br/><h1 align='center'>默认生成前台所需的表示层页面[用于前台]的输出文件路径参数</h1>";
	    echo "<div align='center' height='450'>";
	    echo "<form>";  
	    echo "  <div style='line-height:1.5em;'>";
	    echo "      <label>输出文件路径:</label><input type=\"text\" name=\"save_dir\" />
	                    <input type=\"button\" onclick=\"browseFolder('save_dir')\" value=\"浏览...\" /><br/><br/>";  
	    echo "  </div>";
	    echo "  <input type=\"submit\" value='生成' /><br/>";
	    echo "  <p id='indexPage'>说明： <br/>
	                * 可手动输入文件路径，也可选择浏览指定文件夹。<br/>
	                * 如果您希望选择指定文件夹，特别注意的是,由于安全方面的问题,你还需要如下设置才能使本JS代码正确运行,否则会出现\"没有权限\"的问题。<br/>
	                1.设置可信任站点（例如本地的可以为：http://localhost）<br/>
	                2.其次：可信任站点安全级别自定义设置中：设置下面的选项<br/>
	                \"对没有标记为安全的ActiveX控件进行初始化和脚本运行\"----\"启用\"</p>"; 
	    echo "</form>";
	    echo "</div>";
	    echo "</body>";      
	    echo "</html>";
	    return;
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
	 * @param string $dir
	 * @param string $defineTplFileContent 
	 */
	private static function saveTplDefineToDir($dir,$tablename,$defineTplFileContent,$filename)
	{ 
	    $package =self::getInstancename($tablename);  
	    $dir=$dir.DIRECTORY_SEPARATOR.self::$view_core.DIRECTORY_SEPARATOR.$package.DIRECTORY_SEPARATOR;
	    return self::saveDefineToDir($dir,$filename,$defineTplFileContent);
	}
}

?>