<?php  
/**
 +---------------------------------<br/>
 * 所有自动生成代码工具的父类<br/>       
 +---------------------------------<br/>  
 * @category betterlife
 * @package core.autoCode   
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCode extends Object
{
	/**
	 * 开发者
	 */    
	public static $author= "skygreen skygreen2001@gmail.com";
	/**
	 * 生成Php文件保存的路径
	 */
	public static $save_dir;
    /**
     * 应用所在的路径 
     */
    public static $app_dir;
    /**
     * 生成源码[services|domain]所在目录名称
     */
    public static $dir_src="src";

	/**
	 * 从表名称获取对象的类名【头字母大写】。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	protected static function getClassname($tablename)
	{
	    $classnameSplit= explode("_", $tablename);
	    $classname=ucfirst($classnameSplit[count($classnameSplit)-1]);   
	    return $classname;
	}

	/**
	 * 从表名称获取对象的类名实例化名【头字母小写】。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	protected static function getInstancename($tablename)
	{
	    $classnameSplit= explode("_", $tablename);
	    $classname=$classnameSplit[count($classnameSplit)-1];
	    return $classname;
	}

    /**
     * 表中列的类型定义
     * @param string $type 
     */
    protected static function column_type($type)
    {
        if (UtilString::contain($type,"(")){
            list($typep,$length)=split('[()]', $type);      
        }else{
            $typep=$type;
        }
        return $typep; 
    }
    
    /**
     * 表中列的长度定义
     * @param string $type 
     */
    protected static function column_length($type)
    {
        if (UtilString::contain($type,"(")){
            list($typep,$length)=split('[()]', $type);      
        }else{
            $length=1;
        }
        return $length; 
    }
    
    
	/**
	 * 保存生成的代码到指定命名规范的文件中 
	 * @param string $dir 保存路径
     * @param string $filename 文件名称
	 * @param string $definePhpFileContent 生成的代码 
	 */
	protected static function saveDefineToDir($dir,$filename,$definePhpFileContent)
	{ 
	    UtilFileSystem::createDir($dir);
	    UtilFileSystem::save_file_content($dir.DIRECTORY_SEPARATOR.$filename,$definePhpFileContent); 
	    return basename($filename, ".php");
	}
    
    /**
     * 用户输入需求
     * @param 输入用户需求的选项
     */
    protected static function UserInput($title,$inputArr=null)
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
        echo "<br/><br/><br/><br/><br/><h1 align='center'>$title</h1>";
        echo "<div align='center' height='450'>";
        echo "<form>";  
        echo "  <div style='line-height:1.5em;'>";
        echo "      <label>输出文件路径:</label><input type=\"text\" name=\"save_dir\" />
                        <input type=\"button\" onclick=\"browseFolder('save_dir')\" value=\"浏览...\" /><br/><br/>";
        if (!empty($inputArr)){      
            echo "
                    <label>生成模式:</label><select name=\"type\">";               
            foreach ($inputArr as $key=>$value) {
                echo "        <option value='$key'>$value</option>";
            }            
            echo "      </select>";  
        }                         
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
    }
}

?>