<?php

/**
 * 工具类:自动生成代码-实体类
 */
class AutoCodeDomain extends AutoCode
{
	/**
     * 数据对象生成定义的方式<br/>
     * 1.所有的列定义的对象属性都是private,同时定义setter和getter方法。
     * 2.所有的列定义的对象属性都是public。
     */
	public static $type;
    /**
     * 自动生成代码-实体类
     */
	public static function AutoCode()
	{
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
	    echo UtilCss::form_css()."\r\n";
	    foreach ($fieldInfos as $tablename=>$fieldInfo){
	       //print_r($fieldInfo);
	       //echo("<br/>");
	       $definePhpFileContent=self::tableToDataObjectDefine($tablename,$tableInfoList,$fieldInfo);
	       if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($definePhpFileContent)){
	           $classname=self::saveDataObjectDefineToDir(self::$save_dir,$tablename,$definePhpFileContent);
	           echo "生成导出完成:$tablename->$classname!<br/>";   
	       }else{
	           echo $definePhpFileContent."<br/>";
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
	    echo "<br/><br/><br/><br/><br/><h1 align='center'>需要定义生成实体类的输出文件路径参数</h1>";
	    echo "<div align='center' height='450'>";
	    echo "<form>";  
	    echo "  <div style='line-height:1.5em;'>";
	    echo "      <label>输出文件路径:</label><input type=\"text\" name=\"save_dir\" />
	                    <input type=\"button\" onclick=\"browseFolder('save_dir')\" value=\"浏览...\" /><br/><br/>
	                <label>生成模式:</label><select name=\"type\">
	                  <option value='1'>对象属性都是private,定义setter和getter方法。</option><option value='2'>所有的列定义的对象属性都是public</option>
	                </select>";  
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
	 * 将表列定义转换成数据对象Php文件定义的内容
	 * @param string $tablename
	 * @param array $fieldInfo 
	 */
	private static function tableToDataObjectDefine($tablename,$tableInfoList,$fieldInfo)
	{
	    $result="<?php\r\n";
	    if ($tableInfoList!=null&&count($tableInfoList)>0&&  array_key_exists("$tablename", $tableInfoList)){
	        $table_comment=$tableInfoList[$tablename]["Comment"];
	    }else{
	        $table_comment="关于$tablename的描述";
	    }    
	    $category=  Gc::$appName;
	    $author= "skygreen skygreen2001@gmail.com";
	    
	    $package=self::getPackage($tablename);
	    $classname=self::getClassname($tablename);
	    	    
	    $result.="/**\r\n".
				 " +---------------------------------------<br/>\r\n".
				 " * $table_comment<br/>\r\n".
				 " +---------------------------------------\r\n".
				 " * @category $category\r\n".
				 " * @package $package\r\n".
				 " * @author $author\r\n".
				 " */\r\n";
	    $result.="class $classname extends DataObject\r\n{\r\n";
	    $datatype="string";
	    switch (self::$type) {
	        case 2:
	            $result.= '    //<editor-fold defaultstate="collapsed" desc="定义部分">'."\r\n";
	            foreach ($fieldInfo as $fieldname=>$field){
	              if (self::isNotColumnKeywork($fieldname)){
	                   $datatype=self::comment_type($field["Type"]);
	                   $comment=str_replace("\r\n", "     * ", $field["Comment"]);
	                   $comment=str_replace("\r", "     * ", $comment);
	                   $comment=str_replace("\n", "     * ", $comment);
	                   $comment=str_replace("     * ", "\r\n     * ", $comment);
	                   $result.= 
								"    /**\r\n".
								"     * ".$comment."\r\n".
								"     * @var $datatype\r\n".
								"     * @access public\r\n". 
								"     */\r\n".
								"    public \$".$fieldname.";\r\n";
	              }
	            };
	            $result.= "    //</editor-fold>\r\n";
	            break;
	        default:            
	            $result.= '    //<editor-fold defaultstate="collapsed" desc="定义部分">'."\r\n";
	            foreach ($fieldInfo as $fieldname=>$field){
	              if (self::isNotColumnKeywork($fieldname)){
	                   $datatype=self::comment_type($field["Type"]);
	                   $comment=str_replace("\r\n", "     * ", $field["Comment"]);
	                   $comment=str_replace("\r", "     * ", $comment);
	                   $comment=str_replace("\n", "     * ", $comment);
	                   $comment=str_replace("     * ", "\r\n     * ", $comment);
	                   $result.= 
								"    /**\r\n".
								"     * ".$comment."\r\n".
								"     * @var $datatype\r\n".
								"     * @access private\r\n". 
								"     */\r\n".
								"    private \$".$fieldname.";\r\n";
	                };
	            }
	            $result.= "    //</editor-fold>\r\n\r\n";
	            $result.= '    //<editor-fold defaultstate="collapsed" desc="setter和getter">'."\r\n";
	            foreach ($fieldInfo as $fieldname=>$field){
	              if (self::isNotColumnKeywork($fieldname)){
	                   $result.= 
							"    public function set".ucfirst($fieldname)."(\$".$fieldname.")\r\n".
							"    {\r\n".
							"        \$this->".$fieldname."=\$".$fieldname.";\r\n".
							"    }\r\n";
                   $result.=
							"    public function get".ucfirst($fieldname)."()\r\n".
							"    {\r\n".
							"        return \$this->".$fieldname.";\r\n".
							"    }\r\n";
	                };
	            }      
	            $result.= "    //</editor-fold>\r\n";            
	            break;
	    }
	    $result.="}\r\n";    
	    $result.="?>";
	    return $result;
	}

	/**
	 *从表名称获取子文件夹的信息。
	 * @param string $tablename 
	 * @return string 返回对象所在的Package名 
	 */
	private static function getPackage($tablename){
	    $package="domain.";
	    $pacre=str_replace(Config_Db::$table_prefix, "", $tablename);
	    $pacre=str_replace(Config_Db::TABLENAME_RELATION,Config_Db::TABLENAME_DIR_RELATION, $pacre);      
	    $package.=str_replace("_", ".", $pacre);
	    $packageSplit=explode(".", $package);
	    unset($packageSplit[count($packageSplit)-1]);
	    $package= implode(".", $packageSplit);      
	    return $package;
	}

	/**
	 * 从表名称获取对象的类名。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	private static function getClassname($tablename)
	{
	    $classnameSplit= explode("_", $tablename);
	    $classname=ucfirst($classnameSplit[count($classnameSplit)-1]);   
	    return $classname;
	}

	/**
	 * 是否默认的列关键字：id,committime
	 * @param type $fieldname 
	 */
	private static function isNotColumnKeywork($fieldname)
	{
	    if ($fieldname=="id"||$fieldname=="commitTime"||$fieldname=="updateTime"){
	        return false;
	    }else{    
	        return true;
	    }
	}

	/**
	 * 将表中的类型定义转换成对象field的注释类型说明
	 * @param string $type 
	 */
	private static function comment_type($type)
	{
	    if (UtilString::contain($type,"(")){
	        list($typep,$length)=split('[()]', $type);      
	    }else{
	        $typep=$type;
	    }
	    switch ($typep) {
	        case "int":
	        case "enum":
	        case "timestamp":
	            return $typep; 
	        case "bigint":            
	            return "int";
	        case "varchar":
	            return "string";
	        default:
	            return "string";
	    }      
	}

	/**
	 * 保存生成的代码到指定命名规范的文件中 
	 * @param string $dir
	 * @param string $definePhpFileContent 
	 */
	private static function saveDataObjectDefineToDir($dir,$tablename,$definePhpFileContent)
	{
	    $package=self::getPackage($tablename);
	    $filename=self::getClassname($tablename).".php";
	    $package=str_replace(".", DIRECTORY_SEPARATOR, $package);
	    if(endWith($dir, "domain".DIRECTORY_SEPARATOR)||endWith($dir, "domain\\")){
	        $package=str_replace("domain", "", $package);
	    }
	    $dir=$dir.DIRECTORY_SEPARATOR.$package;
	    return self::saveDefineToDir($dir,$filename,$definePhpFileContent);
	}	

}

?>