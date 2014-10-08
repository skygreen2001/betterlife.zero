<?php
/**
 +---------------------------------<br/>
 * 工具类:自动生成代码-实体类[基于Java的实体类]<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.autoCode
 * @author skygreen skygreen2001@gmail.com
 */
class AutoCodeDomainJava extends AutoCode
{
	/**
	 * Java 实体类所在的Package名称
	 */
	public static $package_name="com.bb.domain";
	/**
	 * 实体数据对象类完整的保存路径
	 */
	public static $domain_dir_full;
	/**
	 *实体数据对象类文件所在的路径
	 */
	public static $enum_dir="enumtype";
	/**
	 * 生成枚举类型类
	 */
	public static $enumClass;
	/**
	 * 数据对象生成定义的方式<br/>
	 * 1.所有的列定义的对象属性都是private,同时定义setter和getter方法。
	 * 2.所有的列定义的对象属性都是public。
	 */
	public static $type;
	/**
	 * 需引入的关系类或者枚举类的Package
	 */
	private static $importPackage;
	/**
	 * 是否引入了List
	 */
	private static $isHadImportList;
	/**
	 * 自动生成代码-实体类
	 */
	public static function AutoCode()
	{
		self::$app_dir=Gc::$appName;
		self::$domain_dir_full=self::$save_dir.self::$app_dir.DIRECTORY_SEPARATOR.self::$dir_src.DIRECTORY_SEPARATOR.self::$domain_dir.DIRECTORY_SEPARATOR;
		self::init();
		if (self::$isOutputCss)self::$showReport.= UtilCss::form_css()."\r\n";
		self::$enumClass="";
		self::$showReport.= '<div id="Content_11" style="display:none;">';
		$link_domain_dir_href="file:///".str_replace("\\", "/", self::$domain_dir_full);
		self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_domain_dir_href."'>".self::$domain_dir_full."</a></font><br/><br/>";

		foreach (self::$fieldInfos as $tablename=>$fieldInfo){
		   $defineJavaFileContent=self::tableToDataObjectDefine($tablename,$fieldInfo);
		   if (isset(self::$save_dir)&&!empty(self::$save_dir)&&isset($defineJavaFileContent)){
			   $classname=self::saveDataObjectDefineToDir($tablename,$defineJavaFileContent);
			   self::$showReport.= "生成导出完成:$tablename=>$classname!<br/>";
		   }else{
			   self::$showReport.= $defineJavaFileContent."<br/>";
		   }
		   self::tableToEnumClass($tablename,$fieldInfo);
		}
		self::$showReport.= "</div><br/>";
		self::$showReport.= AutoCodeFoldHelper::foldEffectCommon("Content_12");
		self::$showReport.= "<font color='#FF0000'>生成枚举类型:</font><br/>";
		self::$showReport.= '</a>';
		self::$showReport.= '<div id="Content_12" style="display:none;">';
		self::$showReport.= "<font color='#AAA'>存储路径:<a target='_blank' href='".$link_domain_dir_href.self::$enum_dir."'>".self::$domain_dir_full.self::$enum_dir."</a></font><br/><br/>";
		self::$showReport.= self::$enumClass;
		self::$showReport.= "</div>";
	}

	/**
	 * 用户输入需求
	 */
	public static function UserInput()
	{
		$inputArr=array(
			"1"=>"对象属性都是private,定义setter和getter方法。",
			"2"=>"所有的列定义的对象属性都是public"
		);
		$db_domian_php=Gc::$url_base."tools/tools/autoCode/layer/domain/db_domain.php";
		$more_content="<br/><br/><a href='$db_domian_php' target='_blank'>生成本框架使用的数据对象实体类</a>";
		parent::UserInput("一键生成Java实体类数据对象定义层",$inputArr,"1",$more_content);
	}

	/**
	 * 将表枚举列生成枚举类型类
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tableToEnumClass($tablename,$fieldInfo)
	{
		$category  = Gc::$appName;
		$author    = self::$author;
		foreach ($fieldInfo as $fieldname=>$field){
			$datatype =self::comment_type($field["Type"]);
			if ($datatype=='char'){
				$enumclassname=self::enumClassName($fieldname,$tablename);
				$enum_columnDefine=self::enumDefines($field["Comment"]);
				if (isset($enum_columnDefine)&&(count($enum_columnDefine)>0))
				{
					$comment=$field["Comment"];
					if (contains($comment,array("\r","\n"))){
						$comment=preg_split("/[\s,]+/", $comment);
						$comment=$comment[0];
					}
					$package_name=self::$package_name;
					$result= "package $package_name.enumtype;\r\n\r\n".
							 "/**\r\n".
							 " *---------------------------------------<br/>\r\n".
							 " * 枚举类型:$comment  <br/> \r\n".
							 " *---------------------------------------<br/>\r\n".
							 " * @category $category\r\n".
							 " * @package domain\r\n".
							 " * @subpackage enum \r\n".
							 " * @author $author\r\n".
							 " */\r\n".
							 "public class $enumclassname\r\n".
							 "{\r\n";
					foreach ($enum_columnDefine as $enum_column) {
						$enumname=strtoupper($enum_column['name']) ;
						$enumvalue=$enum_column['value'];
						$enumcomment=$enum_column['comment'];
						$result.="    /**\r\n".
								 "     * $comment:$enumcomment\r\n".
								 "     */\r\n".
								 "    final static char $enumname='$enumvalue';\r\n";
					}
					$result.="\r\n";
					$comment  =str_replace("\r\n", "     * ", $field["Comment"]);
					$comment  =str_replace("\r", "     * ", $comment);
					$comment  =str_replace("\n", "     * ", $comment);
					$comment  =str_replace("     * ", "<br/>\r\n     * ", $comment);
					$result.="    /** \r\n".
							 "     * 显示".$comment."<br/>\r\n".
							 "     */\r\n".
							 "    public static String {$fieldname}Show(char {$fieldname})\r\n".
							 "    {\r\n".
							 "       switch({$fieldname}){ \r\n";
					foreach ($enum_columnDefine as $enum_column) {
						$enumname=strtoupper($enum_column['name']) ;
						$enumcomment=$enum_column['comment'];
						$result.="            case {$enumname}:\r\n".
								 "                return \"{$enumcomment}\"; \r\n";
					}
					$result.="       }\r\n";
					$result.="       return \"未知\";\r\n".
							 "    }\r\n\r\n";
					$comment=explode("<br/>",$comment);
					if (count($comment)>0){
						$comment=$comment[0];
					}
					$result.="    /** \r\n".
							 "     * 根据{$comment}显示文字获取{$comment}<br/>\r\n".
							 "     * @param mixed \${$fieldname}Show {$comment}显示文字\r\n".
							 "     */\r\n".
							 "    public static char {$fieldname}ByShow(String {$fieldname}Show)\r\n".
							 "    {\r\n".
							 "       switch({$fieldname}Show){ \r\n";
					foreach ($enum_columnDefine as $enum_column) {
						$enumname=strtoupper($enum_column['name']);
						$enumcomment=$enum_column['comment'];
						$result.="            case \"{$enumcomment}\":\r\n".
								 "                return {$enumname}; \r\n";
					}
					$result.="       }\r\n";
					if (!empty($enum_columnDefine)&&(count($enum_columnDefine)>0)){
						$enumname=strtoupper($enum_columnDefine[0]['name']);
						$result.="       return {$enumname};\r\n";
					}else{
						$result.="       return null;\r\n";
					}
					$result.="    }\r\n\r\n";
					$result.="}\r\n";
					self::$enumClass.="生成导出完成:".$tablename."[".$fieldname."]=>".self::saveEnumDefineToDir($enumclassname,$result)."!<br/>";
				}
			}
		}
	}

	/**
	 * 将表中的类型定义转换成对象field的注释类型说明
	 * @param string $type 类型定义
	 */
	protected static function comment_type($type)
	{
		$typep=self::column_type($type);
		switch ($typep) {
			case "enum":
				return "char";
			case "int":
				return $typep;
			case "timestamp":
			case "datetime":
				return 'Date';
			case "bigint":
				return "int";
			case "decimal":
				return "Float";
			case "varchar":
				return "String";
			default:
				return "String";
		}
	}
	/**
	 * 将表列定义转换成数据对象Php文件定义的内容
	 * @param string $tablename 表名
	 * @param array $fieldInfo 表列信息列表
	 */
	private static function tableToDataObjectDefine($tablename,$fieldInfo)
	{
		$package_name=self::$package_name;
		$package   = self::getPackage($tablename);
		if(!empty($package))$package=".".$package;
		$result="package $package_name$package;\r\n\r\n";
		if (self::$tableInfoList!=null&&count(self::$tableInfoList)>0&&  array_key_exists("$tablename", self::$tableInfoList)){
			$table_comment=self::$tableInfoList[$tablename]["Comment"];
			$table_comment=str_replace("关系表","",$table_comment);
			if (contain($table_comment,"\r")||contain($table_comment,"\n")){
				$table_comment_arr=preg_split("/[\s,]+/", $table_comment);
				$table_comment="";
				foreach ($table_comment_arr as $tcomment){
					$table_comment.=" * $tcomment<br/>\r\n";
				}
			}else{
				$table_comment=" * ".$table_comment."<br/>\r\n";
			}
		}else{
			$table_comment="关于$tablename的描述";
		}
		$category  = Gc::$appName;
		$author    = self::$author;
		$classname = self::getClassname($tablename);
		self::$importPackage="";
		self::$isHadImportList=false;
		if(!contains($package,"domain"))$package="domain".$package;
		$result.="[importPackage]".
				 "/**\r\n".
				 " +---------------------------------------<br/>\r\n".
				 "$table_comment".
				 " +---------------------------------------\r\n".
				 " * @category $category\r\n".
				 " * @package $package\r\n".
				 " * @author $author\r\n".
				 " */\r\n";
		$result  .="public class $classname\r\n{\r\n";
		$datatype ="String";
		switch (self::$type) {
			case 2:
				$result.= '    //<editor-fold defaultstate="collapsed" desc="定义部分">'."\r\n";
				foreach ($fieldInfo as $fieldname=>$field){
					if (self::isNotColumnKeywork($fieldname)){
						$datatype =self::comment_type($field["Type"]);
						$comment  =str_replace("\r\n", "     * ", $field["Comment"]);
						$comment  =str_replace("\r", "     * ", $comment);
						$comment  =str_replace("\n", "     * ", $comment);
						$comment  =str_replace("     * ", "<br/>\r\n     * ", $comment);
						$result  .=
									"    /**\r\n".
									"     * ".$comment."\r\n".
									"     * @var $datatype\r\n".
									"     * @access public\r\n".
									"     */\r\n".
									"    public $datatype ".$fieldname.";\r\n";
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
								"    private $datatype ".$fieldname.";\r\n";
					};
				}
				$result.= "    //</editor-fold>\r\n\r\n";
				$result.= '    //<editor-fold defaultstate="collapsed" desc="setter和getter">'."\r\n";
				foreach ($fieldInfo as $fieldname=>$field){
					if (self::isNotColumnKeywork($fieldname)){
						$datatype=self::comment_type($field["Type"]);
						$result.=
							"    public void set".ucfirst($fieldname)."($datatype ".$fieldname.")\r\n".
							"    {\r\n".
							"        this.".$fieldname."=".$fieldname.";\r\n".
							"    }\r\n";
						$result.=
							"    public $datatype get".ucfirst($fieldname)."()\r\n".
							"    {\r\n".
							"        return this.".$fieldname.";\r\n".
							"    }\r\n";
					};
				}
				$result.= "    //</editor-fold>\r\n";
				break;
		}
		$result.=self::domainDataobjectSpec($fieldInfo,$tablename);
		$result.=self::domainDataobjectRelationSpec($fieldInfo,$classname);
		$result.=self::domainEnumPropertyShow($fieldInfo,$tablename);
		$result.=self::domainTreeLevelDefine($fieldInfo,$classname);
		$result.="}\r\n";
		self::$importPackage.="\r\n";
		$result=str_replace("[importPackage]", self::$importPackage, $result);
		return $result;
	}

	/**
	 * 获取关系表所在的package<br/>
	 * @param string $relation_classname 关系表名称
	 */
	private static function relation_class_package($relation_classname,$isImportList=true)
	{
		$package_name=self::$package_name;
		$tablename=self::getTablename($relation_classname);
		$package   = self::getPackage($tablename);
		if(!empty($package))$package=".".$package;
		if ((!self::$isHadImportList)&&($isImportList)){
			self::$importPackage.="import java.util.List;\r\n";
			self::$isHadImportList=true;
		}
		self::$importPackage.="import $package_name$package.$relation_classname;\r\n";
	}

	/**
	 * 生成数据对象之间关系规范定义<br/>
	 * 所有的数据对象关系:<br/>
	 * 一对一，一对多，多对多<br/>
	 * 包括*.has_one,belong_has_one,has_many,many_many,belongs_many_many. <br/>
	 * 参考说明:EnumTableRelation
	 * @param array $fieldInfo 表列信息列表
	 * @param string $classname 数据对象类名称
	 */
	private static function domainDataobjectRelationSpec($fieldInfo,$classname)
	{
		$result="";
		if (array_key_exists($classname, self::$relation_all))$relationSpec=self::$relation_all[$classname];
		if (isset($relationSpec)&&is_array($relationSpec)&&(count($relationSpec)>0))
		{
			//导出一对一关系规范定义(如果存在)
			if (array_key_exists("has_one",$relationSpec))
			{
				$has_one=$relationSpec["has_one"];
				$has_one_effect="";
				foreach ($has_one as $key=>$value) {
				    $has_one_effect.="    public $key $value;\r\n";
					self::relation_class_package($key,false);
				}
				$result.="\r\n".
						"    /**\r\n".
						"     * 一对一关系\r\n".
						"     */\r\n".
						$has_one_effect;
			}
			//导出从属一对一关系规范定义(如果存在)
			if (array_key_exists("belong_has_one",$relationSpec))
			{
				$belong_has_one=$relationSpec["belong_has_one"];
				$belong_has_one_effect="";
				foreach ($belong_has_one as $key=>$value) {
				    $belong_has_one_effect.="    public $key $value;\r\n";
					self::relation_class_package($key,false);
				}
				$result.="\r\n".
						"    /**\r\n".
						"     * 从属一对一关系\r\n".
						"     */\r\n".
						$belong_has_one_effect;
			}
			//导出一对多关系规范定义(如果存在)
			if (array_key_exists("has_many",$relationSpec))
			{
				$has_many=$relationSpec["has_many"];
				$has_many_effect="";
				foreach ($has_many as $key=>$value) {
				    $has_many_effect.="    public List<$key> $value"."s;\r\n";
					self::relation_class_package($key);
				}
				$result.="\r\n".
						"    /**\r\n".
						"     * 一对多关系\r\n".
						"     */\r\n".
						$has_many_effect;
			}
			//导出多对多关系规范定义(如果存在)
			if (array_key_exists("many_many",$relationSpec))
			{
				$many_many=$relationSpec["many_many"];
				$many_many_effect="";
				foreach ($many_many as $key=>$value) {
				    $many_many_effect.="    public List<$key> $value"."s;\r\n";
					self::relation_class_package($key);
				}
				$result.="\r\n".
						"    /**\r\n".
						"     * 多对多关系\r\n".
						"     */\r\n".
						$many_many_effect;
			}
			//导出从属于多对多关系规范定义(如果存在)
			if (array_key_exists("belongs_many_many",$relationSpec))
			{
				$belongs_many_many=$relationSpec["belongs_many_many"];
				$belongs_many_many_effect="";
				foreach ($belongs_many_many as $key=>$value) {
				    $belongs_many_many_effect.="    public List<$key> $value"."s;\r\n";
					self::relation_class_package($key);
				}
				$result.="\r\n".
						"    /**\r\n".
						"     * 从属于多对多关系\r\n".
						"     */\r\n".
						$belongs_many_many_effect;
			}
		}
		return $result;
	}

	/**
	 * 不符合规范的表定义需要用规格在数据文件里进行说明
	 * 1.移除默认字段:commitTime,updateTime
	 * @param array $fieldInfo 表列信息列表
	 * @param string $tablename 表名称
	 */
	private static function domainDataobjectSpec($fieldInfo,$tablename)
	{
		$result="";
		$table_keyfield=array("commitTime","updateTime");
		$removefields=array();
		foreach ($table_keyfield as $keyfield) {
			if (!array_key_exists($keyfield,$fieldInfo)){
				$removefields[]=$keyfield;
			}
		}
		$removeStr="";
		foreach ($removefields as $removefield) {
			$removeStr.="            '$removefield',\r\n";
		}
		if (!empty($removeStr)){
			$removeStr=substr($removeStr,0,strlen($removeStr)-3);
			$result.="    /**\r\n".
					 "     * 规格说明\r\n".
					 "     * 表中不存在的默认列定义:".implode(",",$removefields)."\r\n".
					 "     * @var mixed\r\n".
					 "     */\r\n".
					 "    public \$field_spec=array(\r\n".
					 "        EnumDataSpec::REMOVE=>array(\r\n".
					 $removeStr."\r\n".
					 "        )\r\n".
					 "    );\r\n";
		}
		return $result;
	}

	/**
	 * 在实体数据对象定义中定义枚举类型的显示
	 * @param array $fieldInfo 表列信息列表
	 * @param string $tablename 表名称
	 */
	private static function domainEnumPropertyShow($fieldInfo,$tablename)
	{
		$result="";
		foreach ($fieldInfo as $fieldname=>$field){
			if (self::isNotColumnKeywork($fieldname)){
				$datatype =self::comment_type($field["Type"]);
				if ($datatype=='char'){
					$enum_columnDefine=self::enumDefines($field["Comment"]);
					$comment  =str_replace("\r\n", "     * ", $field["Comment"]);
					$comment  =str_replace("\r", "     * ", $comment);
					$comment  =str_replace("\n", "     * ", $comment);
					$comment  =str_replace("     * ", "<br/>\r\n     * ", $comment);
					$result.= "\r\n".
							  "    /** \r\n".
							  "     * 显示".$comment."<br/>\r\n".
							  "     */\r\n";
					$enumclassname=self::enumClassName($fieldname,$tablename);
					$fieldname_up=ucfirst($fieldname);
					$result.="    public String get{$fieldname_up}Show()\r\n".
							 "    {\r\n".
							 "        return {$enumclassname}.{$fieldname}Show(this.{$fieldname});\r\n".
							 "    }\r\n";
					$package_name=self::$package_name;
					self::$importPackage.="import $package_name.enumtype.$enumclassname;\r\n";
				}
			}
		}
		return $result;
	}

	/**
	 * 只有带有目录树数据模型的数据对象定义中才拥有的方法
	 * @param array $fieldInfo 表列信息列表
	 * @param string $classname 当前类名称
	 */
	private static function domainTreeLevelDefine($fieldInfo,$classname)
	{
		$result="\r\n";
		if (array_key_exists("countChild",$fieldInfo)||array_key_exists("childCount",$fieldInfo)){
			$realId=DataObjectSpec::getRealIDColumnName($classname);
			$result.="    /**\r\n".
					 "     * 计算所有的子元素数量并存储\r\n".
					 "     */\r\n".
					 "    public static int allCountChild()\r\n".
					 "    {\r\n".
					 "        return 1000;\r\n".
					 "    }\r\n\r\n";
		}
		if (array_key_exists("level",$fieldInfo)){
			$result.="    /**\r\n".
					 "     * 最高的层次，默认为3 \r\n".
					 "     */\r\n".
					 "    public static int maxlevel()\r\n".
					 "    {\r\n".
					 "        return 3;\r\n".
					 "    }\r\n\r\n";
		}else if (array_key_exists("region_type",$fieldInfo)){
			$result.="    /**\r\n".
					 "     * 最高的层次，默认为3 \r\n".
					 "     */\r\n".
					 "    public static int maxlevel()\r\n".
					 "    {\r\n".
					 "        return 3;\r\n".
					 "    }\r\n\r\n";
		}
		return $result;
	}

	/**
	 *从表名称获取子文件夹的信息。
	 * @param string $tablename 表名称
	 * @return string 返回对象所在的Package名
	 */
	private static function getPackage($tablename)
	{
		$pacre=str_replace(Config_Db::$table_prefix, "", $tablename);
		$pacre=str_replace(Config_Db::TABLENAME_RELATION,Config_Db::TABLENAME_DIR_RELATION, $pacre);
		$package=str_replace("_", ".", $pacre);
		$packageSplit=explode(".", $package);
		unset($packageSplit[count($packageSplit)-1]);
		$package= implode(".", $packageSplit);
		return $package;
	}

	/**
	 * 保存生成的代码到指定命名规范的文件中
	 * @param string $tablename 表名称
	 * @param string $defineJavaFileContent 生成的代码
	 */
	private static function saveDataObjectDefineToDir($tablename,$defineJavaFileContent)
	{
		$package  =self::getPackage($tablename);
		$filename =self::getClassname($tablename).".java";
		$package  =str_replace(".", DIRECTORY_SEPARATOR, $package);
		return self::saveDefineToDir(self::$domain_dir_full.$package,$filename,$defineJavaFileContent);
	}

	/**
	 * 保存生成的枚举类型代码到指定命名规范的文件中
	 * @param string $enumclassname 枚举类名称
	 * @param string $defineJavaFileContent 生成的代码
	 */
	private static function saveEnumDefineToDir($enumclassname,$defineJavaFileContent)
	{
		$filename = $enumclassname.".java";
		return self::saveDefineToDir(self::$domain_dir_full.self::$enum_dir,$filename,$defineJavaFileContent);
	}
}

?>