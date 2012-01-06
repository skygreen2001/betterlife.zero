<?php        
/**
 * 工具类:自动生成代码-控制器
 */
class AutoCodeAction extends AutoCode
{
	/**
     * 控制器生成定义的方式<br/>
     * 1.前端Action，继承基本Action。
     * 2.后端Action，继承ActionExt。
     */
	public static $type;

	/**
	 * 前端Action所在的namespace
	 */
	public static $package_front="web.front.action";
    
    /**
     * 需打印输出的文本
     * @var string
     */
    public static $echo_result="";
    
    /**
    * 需打印输出
    * 
    * @var mixed
    */
    public static $echo_upload="";
    
    /**
     * 自动生成代码-控制器
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
        self::$echo_result="";
        self::$echo_upload="";
	    foreach ($fieldInfos as $tablename=>$fieldInfo){  
	       $definePhpFileContent=self::tableToActionDefine($tablename,$tableInfoList,$fieldInfo);
           if (!empty($definePhpFileContent)){
	           if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($definePhpFileContent)){
	               $classname=self::saveActionDefineToDir(self::$save_dir,$tablename,$definePhpFileContent);
	               echo "生成导出完成:$tablename->$classname!<br/>";   
	           }else{
	               echo $definePhpFileContent."<br/>";
	           }
           }
	    }               
        $category_cap=Gc::$appName;
        $category_cap{0}=ucfirst($category_cap{0});
        if (self::$type==2){
            echo "将以下代码复制到【后台】Action_Index.php或者Action_".$category_cap."<br/>";
            self::$echo_result=str_replace(" ","&nbsp;",self::$echo_result);      
            self::$echo_result=str_replace("\r\n","<br />",self::$echo_result);    
            echo self::$echo_result;
            
            echo "将以下代码复制到【后台】Action_Upload.php<br/>";
            self::$echo_upload=str_replace(" ","&nbsp;",self::$echo_upload);      
            self::$echo_upload=str_replace("\r\n","<br />",self::$echo_upload);    
            echo self::$echo_upload;    
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
	    echo "<br/><br/><br/><br/><br/><h1 align='center'>需要定义生成控制器Action类的输出文件路径参数</h1>";
	    echo "<div align='center' height='450'>";
	    echo "<form>";  
	    echo "  <div style='line-height:1.5em;'>";
	    echo "      <label>输出文件路径:</label><input type=\"text\" name=\"save_dir\" />
	                    <input type=\"button\" onclick=\"browseFolder('save_dir')\" value=\"浏览...\" /><br/><br/>
	                <label>生成模式:</label><select name=\"type\">
	                  <option value='1'>前端Action，继承基本Action。</option>
                      <option value='2'>后端Action，继承ActionExt</option>
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
	 * @param string $tablename 表名
	 * @param array $tableInfoList 表信息列表
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tableToActionDefine($tablename,$tableInfoList,$fieldInfo)
	{
	    $result="<?php\r\n";
	    if ($tableInfoList!=null&&count($tableInfoList)>0&&  array_key_exists("$tablename", $tableInfoList)){
	        $table_comment=$tableInfoList[$tablename]["Comment"];
            $table_comment=str_replace("关系表","",$table_comment); 
	        if (contain($table_comment,"\r")||contain($table_comment,"\n")){
                $table_comment=preg_split("/[\s,]+/", $table_comment);    
                $table_comment=$table_comment[0]; 
            }
	    }else{
	        $table_comment="$tablename";
	    }   
		$category  = Gc::$appName;              
		$package   = self::$package_front;
		$classname = self::getClassname($tablename);
        $instancename=self::getInstancename($tablename);  
		$author    = self::$author;
        switch (self::$type) {
            case 2:  
                $result ="     /**\r\n";
                $result.="      * 控制器:$table_comment\r\n"; 
                $result.="      */\r\n";
                $result.="     public function $instancename()\r\n"; 
                $result.="     {\r\n";
                $result.="         \$this->init();\r\n"; 
                $result.="         \$this->ExtDirectMode();\r\n";
                $result.="         \$this->ExtUpload();\r\n"; 
                $result.="         \$this->loadExtJs('$instancename/$instancename.js');\r\n"; 
                $result.="     }\r\n\r\n";       
                self::$echo_result.=$result;  
                $result_upload ="    /**\r\n".                        
                         "     * 上传数据对象:{$table_comment}数据文件<br />\r\n".  
                         "     */\r\n".  
                         "    public function upload{$classname}()\r\n".  
                         "    {\r\n".                         
                         "        return self::ExtResponse(Manager_ExtService::{$instancename}Service()->import(\$_FILES)); \r\n".  
                         "    }\r\n\r\n";  
                self::$echo_upload.=$result_upload;                
                return "";
            default:                
	            $result.="/**\r\n".
				         " +---------------------------------------<br/>\r\n".
				         " * 控制器:$table_comment<br/>\r\n".
				         " +---------------------------------------\r\n".
				         " * @category $category\r\n".
				         " * @package $package\r\n".
				         " * @author $author\r\n".
				         " */\r\n".  
				         "class Action_$classname extends Action\r\n".  
				         "{\r\n".
				         "    /**\r\n".
				         "     * {$table_comment}列表页面\r\n".
				         "     */\r\n".
				         "    public function lists()\r\n".
				         "    {\r\n".
				         "    }\r\n".
				         "    /**\r\n".
				         "     * {$table_comment}详情页面\r\n".
				         "     */\r\n".
				         "    public function view()\r\n".
				         "    {\r\n".
				         "    }\r\n".
				         "}\r\n\r\n"; 
	            $result.="?>";  
                break;
        }             
        return $result;
	}

	/**
	 * 保存生成的代码到指定命名规范的文件中 
	 * @param string $dir
	 * @param string $definePhpFileContent 
	 */
	private static function saveActionDefineToDir($dir,$tablename,$definePhpFileContent)
	{
	    $filename="Action_".self::getClassname($tablename).".php";
	    $dir=$dir.DIRECTORY_SEPARATOR."action".DIRECTORY_SEPARATOR;
	    return self::saveDefineToDir($dir,$filename,$definePhpFileContent);
	}	
}

?>