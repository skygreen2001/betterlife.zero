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
	public static $isNoOutputCss=true;
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
	 *实体数据对象类文件所在的路径
	 */
	public static $domain_dir="domain";
	/**
	 * 表列表
	 * @var mixed
	 */
	public static $tableList;
	/**
	 * 所有表信息
	 * @var mixed
	 */
	public static $tableInfoList;
	/**
	 * 所有表列信息
	 * @var mixed
	 */
	public static $fieldInfos=array();
	/**
	 * 数据对象关系显示字段
	 * @var mixed
	 */
	public static $relation_viewfield;
	/**
	 * 所有的数据对象关系:<br/>
	 * 一对一，一对多，多对多<br/>
	 * 包括*.has_one,belong_has_one,has_many,many_many,belongs_many_many. <br/>
	 * 参考说明:EnumTableRelation
	 * @var mixed
	 */
	public static $relation_all;
	/**
	 * 数据对象引用另一个数据对象同样值的冗余字段
	 * @var mixed
	 */
	public static $redundancy_table_fields;
	/**
	 * 获取类和注释的说明<br/>
	 */
	public static $class_comments;

	/**
	 * 初始化
	 */
	public static function init()
	{
		UtilFileSystem::createDir(self::$save_dir);
		if (!is_dir(self::$save_dir)){
			die("因为安全原因，需要手动在操作系统中创建目录:".self::$save_dir."<br/>".
				"Linux command need:<br/>".str_repeat("&nbsp;",40).
				"sudo mkdir -p ".self::$save_dir."<br/>".str_repeat("&nbsp;",40).
				"sudo chmod 0777 ".self::$save_dir);
		}
		if (empty(self::$tableList)){
			self::$tableInfoList=Manager_Db::newInstance()->dbinfo()->tableInfoList();
			self::$tableList=array_keys(self::$tableInfoList);//Manager_Db::newInstance()->dbinfo()->tableList();
		}
		if (empty(self::$fieldInfos)){
			$ignoreTables=array();
			foreach (self::$tableList as $tablename){
				$classname=self::getClassname($tablename);
				if (!contain($tablename,Config_Db::$table_prefix)){
					$ignoreTables[]=$tablename;
					continue;
				}
				if (($classname=="Copy")||($classname=="Copy1")||($classname=="Copy2")||($classname=="Copy3")||($classname=="Copy4")){
					$ignoreTables[]=$tablename;
					continue;
				}
				$fieldInfoList=Manager_Db::newInstance()->dbinfo()->fieldInfoList($tablename);
				foreach($fieldInfoList as $fieldname=>$field){
					self::$fieldInfos[$tablename][$fieldname]["Field"]=$field["Field"];
					self::$fieldInfos[$tablename][$fieldname]["Type"]=$field["Type"];
					self::$fieldInfos[$tablename][$fieldname]["Comment"]=$field["Comment"];
					self::$fieldInfos[$tablename][$fieldname]["Key"]=$field["Key"];
					if ($field["Null"]=='NO'){
						self::$fieldInfos[$tablename][$fieldname]["IsPermitNull"]=false;
					}else{
						self::$fieldInfos[$tablename][$fieldname]["IsPermitNull"]=true;
					}
				}
				self::$class_comments[$classname]=self::$tableInfoList[$tablename]["Comment"];
			}
			self::$tableList=array_diff(self::$tableList, $ignoreTables);
			foreach ($ignoreTables as $tablename) {
				unset(self::$tableInfoList[$tablename]);
			}
		}
	}

	/**
	 * 从表名称获取对象的类名【头字母大写】。
	 * @param string $tablename 表名称
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
	 * 根据类名获取表名
	 * @param mixed $class
	 */
	protected static function getTablename($class)
	{
		$tableList=Manager_Db::newInstance()->dbinfo()->tableList();
		foreach ($tableList as $tablename){
			$classname=self::getClassname($tablename);
			if ($class==$classname){
				return $tablename;
			}
		}
		return null;
	}

	/**
	 * 从表名称获取对象的类名实例化名【头字母小写】。
	 * @param string $tablename 表名称
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
		//echo "<script type='text/javascript' src='".$url_base."common/js/util/file.js'></script>";
		echo "</head>";
		echo "<body>";
		echo "<br/><br/><br/><br/><br/><h1 align='center'>$title</h1>";
		echo "<div align='center' height='450'>";
		echo "<form>";
		echo "  <div style='line-height:1.5em;'>";
		$default_dir=Gc::$nav_root_path."model".DIRECTORY_SEPARATOR;
		echo "      <label>输出文件路径:</label><input style=\"width:400px;text-align:left;padding-left:10px;\" type=\"text\" name=\"save_dir\" value=\"$default_dir\" id=\"save_dir\" />";
/*		$browser=getbrowser();
		if (contain($browser,"Internet Explorer")){
			echo "			<input type=\"button\" onclick=\"browseFolder('save_dir')\" value=\"浏览...\" /><br/><br/>";
		}*/
		if (!empty($inputArr)){
			echo "<br/><br/>
					<label>&nbsp;&nbsp;&nbsp;生成模式:</label><select name=\"type\">";
			foreach ($inputArr as $key=>$value) {
				echo "        <option value='$key'>$value</option>";
			}
			echo "      </select>";
		}
		echo "  </div>";
		echo "  <input type=\"submit\" value='生成' /><br/>";

/*		if (contain($browser,"Internet Explorer")){
			echo "  <p id='indexPage'>说明： <br/>
					* 可手动输入文件路径，也可选择浏览指定文件夹。<br/>
					* 如果您希望选择指定文件夹，特别注意的是,由于安全方面的问题,你还需要如下设置才能使本JS代码正确运行,否则会出现\"没有权限\"的问题。<br/>
					1.设置可信任站点（例如本地的可以为：http://localhost）<br/>
					2.其次：可信任站点安全级别自定义设置中：设置下面的选项<br/>
					\"对没有标记为安全的ActiveX控件进行初始化和脚本运行\"----\"启用\"</p>";
		}*/
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
		$comment_arr=preg_split("/[\r\n;]+/", $fieldComment);
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
					$cn_en_arr=array();
					$enum_comment=$part_arr[1];
					if (is_numeric($part_arr[0])){
						if (contain($enum_comment,"-")){
							$cn_en_arr[0]=substr($enum_comment,0,strrpos($enum_comment,"-"));
							$cn_en_arr[1]=substr($enum_comment,strrpos($enum_comment,"-")+1);
							$enum_columnDefine[]=array(
								'name'=>strtolower($cn_en_arr[1]),
								'value'=>$part_arr[0],
								'comment'=>$cn_en_arr[0]
							);
						}
					}else{
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
		$column_name=strtoupper($column_name);
		if (contain($column_name,"ID")){
			return false;
		}
		if (((self::column_length($column_type)>=500)&&(!contains($column_name,array("PROFILE","IMAGES","LINK","ICO","PASSWORD","EMAIL","PHONE","ADDRESS"))))
			 ||(contains($column_name,array("INTRO","MEMO","CONTENT")))||(self::column_type($column_type)=='text')||(self::column_type($column_type)=='longtext')){
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
		$column_name=strtoupper($column_name);
		if (contain($column_name,"ID")){
			return false;
		}
		if (contains($column_name,array("PROFILE","IMAGE","IMG","ICO","LOGO","PIC"))){
			return true;
		}
		return false;
	}

	/**
	 * 列是否是email
	 * @param string $column_name 列名称
	 * @param mixed $column_comment 列注释
	 */
	protected static function columnIsEmail($column_name,$column_comment)
	{
		$column_name=strtoupper($column_name);
		if ((contain($column_name,"EMAIL")||contains($column_comment,array("邮件","邮箱")))&&(!contain($column_name,"IS"))){
			return true;
		}
		return false;
	}

	/**
	 * 列是否是密码
	 * @param string $tablename 表名称
	 * @param string $column_name 列名称
	 */
	protected static function columnIsPassword($table_name,$column_name)
	{

		$table_name=strtoupper($table_name);
		if (contains($table_name,array("MEMBER","ADMIN","USER"))){
			$column_name=strtoupper($column_name);
			if (contain($column_name,"PASSWORD")){
				return true;
			}
		}
		return false;
	}

	/**
	 * 是否默认的列关键字：id,committime,updateTime
	 * @param string $fieldname 列名称
	 */
	protected static function isNotColumnKeywork($fieldname)
	{
		$fieldname=strtoupper($fieldname);
		if ($fieldname=="ID"||$fieldname=="COMMITTIME"||$fieldname=="UPDATETIME"){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 获取数据对象的ID列名称
	 * @param mixed $dataobject 数据对象实体|对象名称
	 */
	protected static function keyIDColumn($dataobject)
	{
		return DataObjectSpec::getRealIDColumnNameStatic($dataobject);
	}

	/**
	 * 获取表注释第一行关键词说明
	 * @param string $tablename 表名称
	 */
	protected static function tableCommentKey($tablename)
	{
		if (self::$tableInfoList!=null&&count(self::$tableInfoList)>0&&array_key_exists("$tablename", self::$tableInfoList))
		{
			$table_comment=self::$tableInfoList[$tablename]["Comment"];
			$table_comment=str_replace("关系表","",$table_comment);
			if (contain($table_comment,"\r")||contain($table_comment,"\n")){
				$table_comment=preg_split("/[\s,]+/", $table_comment);
				$table_comment=$table_comment[0];
			}
		}else{
			$table_comment=self::getClassname($tablename);
		}
		return $table_comment;
	}

	/**
	 * 获取列注释第一行关键词说明
	 * @param mixed $field_comment 列注释
	 * @param mixed $default 默认返回值
	 * @return mixed
	 */
	protected static function columnCommentKey($field_comment,$default="")
	{
		if (empty($field_comment)){
			return $default;
		}
		if (contain($field_comment,"\r")||contain($field_comment,"\n"))
		{
			$field_comment=preg_split("/[\s,]+/", $field_comment);
			$field_comment=$field_comment[0];
		}
		if ($field_comment)$field_comment=str_replace(array('标识','编号','主键'),"",$field_comment);
		return $field_comment;
	}

	/**
	 * 根据类名获取表代表列显示名称
	 * @param string $classname 数据对象类名
	 * @param bool $isReturnNull 是否没有就返回Null
	 */
	protected static function getShowFieldNameByClassname($classname,$isReturnNull=false)
	{
		$fieldInfo=self::$fieldInfos[self::getTablename($classname)];
		$fieldNames=array_keys($fieldInfo);
		foreach ($fieldNames as $fieldname)
		{
			$fieldname_filter=strtolower($fieldname);
			if (!contain($fieldname,"id")){
				if (contains($fieldname,array("name","title")))return $fieldname;
				$classname_filter=strtolower($classname);
				if (contain($fieldname,$classname_filter))return $fieldname;
			}
		}
		if ($isReturnNull){
			return "";
		}else{
			return "name";
		}
	}

	/**
	 * 根据类名判断是不是多对多关系，存在中间表表名
	 * @param string $classname 数据对象类名
	 */
	protected static function isMany2ManyByClassname($classname)
	{
		$tablename=self::getTablename($classname);
		if (contain($tablename,Config_Db::TABLENAME_RELATION."_")){
			return true;
		}
		return false;
	}

	/**
	 * 根据类名判断是不是多对多关系，如果存在其他显示字段则需要在显示Tab中显示has_many
	 * @param string $classname 数据对象类名
	 */
	protected static function isMany2ManyShowHasMany($classname)
	{
		if (self::isMany2ManyByClassname($classname))
		{
			$fieldInfo=self::$fieldInfos[self::getTablename($classname)];
			unset($fieldInfo['updateTime'],$fieldInfo['commitTime']);
			$realId=DataObjectSpec::getRealIDColumnName($classname);
			unset($fieldInfo[$realId]);
			if (count($fieldInfo)==2)return false;
		}
		return true;
	}
}

?>
