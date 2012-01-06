<?php

/**
* 所有自动生成代码工具的父类
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
	 * @param string $dir
	 * @param string $definePhpFileContent 
	 */
	protected static function saveDefineToDir($dir,$filename,$definePhpFileContent)
	{ 
	    UtilFileSystem::createDir($dir);
	    UtilFileSystem::save_file_content($dir.DIRECTORY_SEPARATOR.$filename,$definePhpFileContent); 
	    return basename($filename, ".php");
	}

}

?>