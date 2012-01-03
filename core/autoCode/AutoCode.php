<?php

/**
* 所有自动生成代码工具的父类
*/
class AutoCode extends Object
{
	/**
	 * 生成Php文件保存的路径
	 */
	public static $save_dir;
   
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