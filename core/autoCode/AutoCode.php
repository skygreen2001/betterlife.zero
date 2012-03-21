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
	 * 数据对象关系显示字段 
	 * @var mixed
	 */
	public static $relation_viewfield;      
	/**
	 * 获取类和注释的说明 
	 */
	public static $class_comments; 
	/**
	 * 从表名称获取对象的类名【头字母大写】。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	protected static function getClassname($tablename)
	{      
		if (in_array($tablename, Config_Db::$orm)) {
			$classname=array_search($tablename, Config_Db::$orm);  
		}else {                
			$classnameSplit= explode("_", $tablename);
			$classnameSplit=array_reverse($classnameSplit);
			$classname=ucfirst($classnameSplit[0]);   
		}
		return $classname;
	}

	/**
	 * 从表名称获取对象的类名实例化名【头字母小写】。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	protected static function getInstancename($tablename)
	{   
		if (in_array($tablename, Config_Db::$orm)) {
			$classname=array_search($tablename, Config_Db::$orm);
		}else { 
			$classnameSplit= explode("_", $tablename);
			$classnameSplit=array_reverse($classnameSplit);
			$classname=$classnameSplit[0];
		}
		return $classname;
	}

	/**
	 * 从表名称获取对象类名的帮助说明。
	 * @param string $tablename
	 * @return string 返回对象的类名 
	 */
	protected static function getClassComments($classname)
	{
		return self::$class_comments[$classname];     
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
	 * 将表中的类型定义转换成对象field的注释类型说明
	 * @param string $type 类型定义
	 */
	protected static function comment_type($type)
	{  
		$typep=self::column_type($type);
		switch ($typep) {
			case "int":
			case "enum":   
				return $typep; 
			case "timestamp":
			case "datetime":
				return 'date'; 
			case "bigint":            
				return "int";
			case "decimal":
				return "float";
			case "varchar":
				return "string";
			default:
				return "string";
		}      
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
		$default_dir=Gc::$nav_root_path.DIRECTORY_SEPARATOR."model".DIRECTORY_SEPARATOR;        
		echo "      <label>输出文件路径:</label><input type=\"text\" name=\"save_dir\" value=\"$default_dir\" id=\"save_dir\" />
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
		 
	/**
	 * 根据表列枚举类型生成枚举类名称 
	 * @param string $columnname 枚举列名称
	 * @param string $tablename 表名称    
	 */
	protected static function enumClassName($columnname,$tablename=null)
	{
		$enumclassname="Enum"; 
		if ((strtolower($columnname)=='type')||(strtolower($columnname)=='statue')||(strtolower($columnname)=='status')){ 
			$enumclassname.=self::getClassname($tablename).ucfirst($columnname);
		}else{  
			if (contain($columnname,"_")){
				$c_part=explode("_",$columnname); 
				foreach ($c_part as $column) {
					$enumclassname.=ucfirst($column);
				}
			}else{
				$enumclassname.=ucfirst($columnname);    
			}  
		}   
		return $enumclassname;
	}

	/**
	 * 表枚举类型列注释转换成可以处理的数组数据
	 * 注释风格如下：
	 *    用户性别
	 *    0：女-female
	 *    1：男-mail
	 *    -1：待确认-unknown
	 *    默认男  
	 * @param mixed $fieldComment 表枚举类型列注释
	 */
	protected static function enumDefines($fieldComment)
	{
		$comment_arr=preg_split("/[\s,]+/", $fieldComment);
		unset($comment_arr[0]);
		$enum_columnDefine=array();
		if ((!empty($comment_arr))&&(count($comment_arr)>0))
		{                               
			foreach ($comment_arr as $comment) {
				if (!UtilString::is_utf8($comment)){
					$comment=UtilString::gbk2utf8($comment);    
				}
				if (contain($comment,"：")){
					$comment = str_replace("：",':',$comment);  
				}
				$part_arr=preg_split("/[.:]+/", $comment);
				if ((!empty($part_arr))&&(count($part_arr)==2)){         
					if (is_numeric($part_arr[0])){
					   $cn_en_arr=explode("-",$part_arr[1]);
					   if ((!empty($cn_en_arr))&&(count($cn_en_arr)==2)){ 
						   $enum_columnDefine[]=array(
								'name'=>strtolower($cn_en_arr[1]),
								'value'=>$part_arr[0],
								'comment'=>$cn_en_arr[0]
						   ); 
					   } 
					}else{
						if (contain($part_arr[1],"-")){
							$cn_en_arr=explode("-",$part_arr[1]);
							$part_arr[1]=$cn_en_arr[0];
						}
						$enum_columnDefine[]=array(
							'name'=>strtolower($part_arr[0]),
							'value'=>strtolower($part_arr[0]),
							'comment'=>$part_arr[1]
						); 
					}
				}
			}  
		} 
		return $enum_columnDefine;
	}  
			 
	/**
	 * 列是否大量文本输入应该TextArea输入  
	 * @param string $column_name 列名称
	 * @param string $column_type 列类型
	 */
	protected static function columnIsTextArea($column_name,$column_type)
	{        
		if (((self::column_length($column_type)>=500)&&(!contain($column_name,"images"))&&(!contain($column_name,"link"))&&(!contain($column_name,"ico")))
			 ||(contain($column_name,"intro"))||(self::column_type($column_type)=='text')||(self::column_type($column_type)=='longtext')){  //&&(!contain($column_name,"addr"))
		   return true;
		}else{
		   return false;
		} 
	} 
	
	/**
	 * 列是否是图片路径                 
	 * @param string $column_name 列名称
	 * @param mixed $column_comment 列注释
	 */
	protected static function columnIsImage($column_name,$column_comment)
	{
		if ((contain($column_name,"image"))||(contain($column_name,"img"))||(contain($column_name,"ico"))||(contain($column_name,"logo"))||(contain($column_name,"pic"))){
			return true;    
		}  
		return false;
	}

	/**
	 * 是否默认的列关键字：id,committime,updateTime   
	 * @param string $fieldname 列名称
	 */
	protected static function isNotColumnKeywork($fieldname)
	{                                         
		if ($fieldname=="id"||$fieldname=="commitTime"||$fieldname=="updateTime"){
			return false; 
		}else{    
			return true;
		}  
	}     
}    

?>
