<?php  
//<editor-fold defaultstate="collapsed" desc="枚举类型">
/**
 +---------------------------------------<br/>
 * 枚举类型：DataObject默认关键字<br/>
 * 数据对象可重载对默认关键字的定义
 +---------------------------------------<br/>
 */
class EnumDataObjectDefaultKeyword extends Enum
{
	/**
	 * 自定义列规格说明的名称。<br/>
	 * 在具体的数据对象里需要用该名称声明列规格说明。它需要定义为public static的Access属性。<br/>
	 * 它和$field_spec_default内定义的列规格最终会整合在一起从而最终决定数据对象的列规格说明
	 */
	const NAME_FIELD_SPEC="field_spec";
	/**
	 * 列规格说明的名称。<br/>
	 * 它只能在DataObject内定义，作为所有数据对象全局通用的列规格说明<br/>
	 * 如果某个数据对象的列规格说明细节与其不一致，可以通过field_spec自定义规格说明<br/>
	 * 它和$field_spec内定义的列规格最终会整合在一起从而最终决定数据对象的列规格说明<br/>
	 */
	//const NAME_FIELD_SPEC_DEFAULT="field_spec_default";
	/**
	 * ID名称定义的策略的名称
	 */
	const NAME_IDNAME_STRATEGY="idname_strategy";
	/**
	 * ID名称中的连接符的名称。<br/>
	 * ID名称定义的策略为TABLENAME_ID有效。
	 */
	const NAME_IDNAME_CONCAT="idname_concat";
	/**
	 * Foreign ID名称定义的策略的名称
	 */
	const NAME_FOREIGNIDNAME_STRATEGY="foreignid_name_strategy";
	/**
	 * Foreign ID名称中的连接符的名称。<br/>
	 * Foreign ID名称定义的策略为TABLENAME_ID有效。
	 */
	const NAME_FOREIGNID_CONCAT="foreignid_concat";
}

/**
 +---------------------------------------<br/>
 * 枚举类型：数据库关联模式<br/>
 * 数据对象关系定义<br/>
 * 数据对象间关系对应表关系定义，有以下关系：<br/>
 * 一对一，一对多，多对多<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class EnumTableRelation extends Enum
{
	/**
	* 一对一关联
	*/
	const HAS_ONE    = 'has_one';
	/**
	* 从属一对一关联，即主表中一字段关联关系表中的主键
	*/
	const BELONG_HAS_ONE = 'belong_has_one';
	/**
	* 一对多关联
	*/
	const HAS_MANY   = 'has_many';
	/**
	* 多对多关联
	*/
	const MANY_MANY  = 'many_many';
	/**
	* 从属多对多关联
	*/
	const BELONGS_TO = 'belongs_many_many';  
}

/**
 +---------------------------------------<br/>
 * 枚举类型：数据对象默认列定义<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class EnumColumnNameDefault extends Enum
{
	/**
	 * 数据对象的唯一标识
	 */
	const ID="id";
	/**
	 * 数据创建的时间，当没有updateTime时，其亦代表数据最后更新的时间
	 */
	const COMMITTIME="commitTime";
	/**
	 * 数据最后更新的时间
	 */
	const UPDATETIME="updateTime";
}

/**
 +---------------------------------------<br/>
 * 枚举类型：ID名称定义的策略
 +---------------------------------------<br/>
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class EnumIDNameStrategy extends Enum
{
  /**
	* 无策略<br/>
	* 说明：需要在数据对象类里定义$field_spec；说明ID别名。
	*/
	const NONE=-1;
	/**
	* ID名称为：id
	*/
	const ID=0;
	/**
	* ID名称为:对象名+'id'<br/>
	* 如果对象名为User,则ID名称为:userid【头字母大小写均可】
	*/
	const TABLENAMEID=1;
	/**
	* ID名称为:对象名+连接符+'id' <br/>
	* 如果对象名为User,连接符为'_';则ID名称为:user_id【头字母大小写均可】
	*/
	const TABLENAME_ID=2;    
}


/**
 +---------------------------------------<br/>
 * 枚举类型：默认外键ID名称定义的策略
 +---------------------------------------<br/>
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class EnumForeignIDNameStrategy extends Enum
{
	/**
	* ID名称为:对象名+'id'<br/>
	* 如果对象名为User,则ID名称为:userid【头字母大小写均可】
	*/
	const TABLENAMEID=1;
	/**
	* ID名称为:对象名+连接符+'id' <br/>
	* 如果对象名为User,连接符为'_';则ID名称为:user_id【头字母大小写均可】
	*/
	const TABLENAME_ID=2;    
}

/**
 +---------------------------------------<br/>
 * 枚举类型：数据对象列规格默认列定义<br/>
 +---------------------------------------<br/>
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class EnumDataSpec extends Enum
{   
	/**
	 * 数据对象定义中需要移除的列
	 */
	const REMOVE="remove";
	/**
	 * 多对多关系表名称定义，如无定义，则按默认规则查找指定表。
	 */
	const MANY_MANY_TABLE="many_many_table";
	/**
	 * 数据对象外键名称定义，如无定义，则按默认规则查找指定外键。
	 */
	const FOREIGN_ID="foreign_id";  
}
//</editor-fold>

/**
 * 数据对象的列规格说明。<br/>
 * 在数据对象的列规格里，<br/>
 * 1.$key->$value说明是：DataObject默认列名->列别名。<br/>
 *   它主要用于与第三方WEB应用整合时，可能数据对象表唯一标识定义为$table_id,如用户表的唯一标识是：user_id;<br/>
 *   在框架中设计当列名有别名时，以列别名去表中查找相应列。<br/>
 * 2.remove:在数据对象中移除不需要持久化的列。<br/>
 *   如数据对象中不需要列commitTime或者updateTime数据列时，只需要在其中声明，其中声明的列即不在框架的持久层中进行存储。<br/>
 * 3.many_many_table:多对多关系表名称定义，如无定义，则按默认规则查找指定表。<br/>
 *   多对多表名默认规则：<br/>
 *        多对多【主控端-即定义为$many_many】:数据库表名前缀+“_”+[文件夹目录+“_”]...+TABLENAME_RELATION+"_"+主表名+关系表名。<br/>
 *        如User和Role是多对多关系，数据库表名前缀为bb,文件夹目录是user,TABLENAME_RELATION是re；那么在User里定义$many_many包含:Role;则对应的表名是:bb_user_re_userrole.<br/>
 *        多对多【从属端-即定义为$belongs_many_many】:数据库表名前缀+“_”+[文件夹目录+“_”]...+TABLENAME_RELATION+"_"+关系表名+主表名。<br/>
 *        如User和Role是多对多关系，数据库表名前缀为bb,文件夹目录是user,TABLENAME_RELATION是re；那么在Role里定义$belongs_many_many包含:User;则对应的表名是:bb_user_re_userrole.<br/>
 * 4.foreign_id:在对象之间或者说表之间存在一对一，一对多，多对多的关系时，可通过它指定外键的名称，如果没有指定，则按默认定义。<br/>
 *   外键的名称默认定义：<br/>
 *   一对一:【关系表类名+Id】；注意关系表类名头字母小写，Id头字母大写；<br/>
 *                      如UserDetail和User是一对一关系，则在UserDetail中对应User的外键就是：userId。<br/>
 *                      在User中定义$has_one是UserDetail，在UserDetail定义$belong_has_one是User<br/>
 *   一对多:【关系表类名+Id】；注意关系表类名头字母小写，Id头字母大写；<br/>
 *                      如Department和User是一对多关系，则在User中对应Department的外键就是：departmentId<br/>
 *                      在User中定义$belong_has_one是Department，在Department中定义$has_many是User。<br/>
 *   多对多【主控端】:多对多关系会产生一张中间表,它定义在EnumDataSpec::MANY_MANY_TABLE里，<br/>
 *                   注意表类名头字母小写，Id头字母大写。 <br/>
 *                   主表类外键名称：【主表类名+Id】，关系表类外键名称：【关系表类名+Id】<br/>
 *   多对多【从属端】:多对多关系会产生一张中间表,它定义在EnumDataSpec::MANY_MANY_TABLE里，<br/>
 *                   注意表类名头字母小写，Id头字母大写。<br/>
 *                   主表类外键名称：【主表类名+Id】，关系表类外键名称：【关系表类名+Id】<br/>
 * 说明：$field_spec_default为默认的数据对象的列规格说明，它全局的定义了当前应用的列规格说明；<br/>
 *      数据对象定义需定义字段：public $field_spec，它定义了当前数据对象的列规格说明。 
 * @static
 */
class DataObjectSpec
{
	/**
	* 默认的数据对象的列规格说明<br/>
	* 它全局的定义了当前应用的列规格说明；<br/>
	* @var array
	*/
	static $field_spec_default= array(
		//EnumColumnNameDefault::ID=>'id',
		//EnumColumnNameDefault::COMMITTIME=>'commitTime',
		EnumDataSpec::REMOVE=>array(
			'updateTime'
		),
		EnumDataSpec::MANY_MANY_TABLE=>array(
			//多对多关系类名=>多对多关系表名
		),
		EnumDataSpec::FOREIGN_ID=>array(
			//类名=>外键名
		)
	);

	/**
	 * 初始化方法
	 */
	public static function init(){
		
	}
	
	//<editor-fold defaultstate="collapsed" desc="数据对象的列规格的处理维护方法">  
	/**
	 * 当前数据对象的列规格说明
	 * 由两部分组成：
	 * 1.默认的全局列规格：$field_spec_default,在DataObject内定义。
	 * 2.当前数据对象的列数据规格:$field_spec
	 *   它可以重载或重写全局列规格的属性说明。
	 * @param DataObject $dataobject 数据对象实体
	 * @return array 当前数据对象的列规格说明 
	 */
	public static function real_field_spec_static($dataobject)
	{
		$field_spec=null;
		if (isset(self::$field_spec_default)){       
		   $field_spec=self::$field_spec_default;   
		}        
		$propertyname=EnumDataObjectDefaultKeyword::NAME_FIELD_SPEC;   
		if (property_exists($dataobject, EnumDataObjectDefaultKeyword::NAME_FIELD_SPEC)){ 
			if (is_string($dataobject)){
				$dataobject=new $dataobject();
			}
			if (!empty($dataobject->$propertyname)){       
				$object_field_spec=$dataobject->$propertyname;
			}
		}else{
			if (!is_string($dataobject)){    
				if (method_exists($dataobject,'classname')){                       
					$classname =$dataobject->classname();
					if (class_exists($classname)){
						$dataobject=new $dataobject(); 
						if (property_exists($dataobject, EnumDataObjectDefaultKeyword::NAME_FIELD_SPEC)){
						   $object_field_spec=$dataobject->$propertyname; 
						}
					}
				}
			}
		}

		if (isset($object_field_spec)){
			if (isset($field_spec)){
				if (isset($field_spec[EnumDataSpec::REMOVE])&&isset($object_field_spec[EnumDataSpec::REMOVE])){
					$remove_spec=array_merge($field_spec[EnumDataSpec::REMOVE],$object_field_spec[EnumDataSpec::REMOVE]);
				}             
				$field_spec=array_merge($field_spec,$object_field_spec);  
				if (isset($remove_spec)){
					$field_spec[EnumDataSpec::REMOVE]=$remove_spec;
				}            
			}else{
				$field_spec=$object_field_spec;
			}
		}
		return $field_spec;
	}
	
	/**
	 * @param mixed $dataobject 数据对象实体|对象名称
	 * @return 数据对象当前唯一标识列名
	 */
	public static function getRealIDColumnNameStatic($dataobject)
	{
		$field_spec=self::real_field_spec_static($dataobject);
		$columnName=EnumColumnNameDefault::ID;
		if (isset ($field_spec)){
		   if (array_key_exists(EnumColumnNameDefault::ID, $field_spec)){
			   $columnName= $field_spec[$columnName];
		   }   
		}        
		if ($columnName===EnumColumnNameDefault::ID){
		   $idname_strategy=UtilReflection::getClassStaticPropertyValue($dataobject, EnumDataObjectDefaultKeyword::NAME_IDNAME_STRATEGY);
		   switch ($idname_strategy) {
			   case EnumIDNameStrategy::ID:               
				   break;
			   case EnumIDNameStrategy::TABLENAMEID:
				   if (is_object($dataobject)){
					   $classname=$dataobject->classname();
				   }else{
					   $classname=$dataobject;
				   }
				   $classname{0} = strtolower($classname{0});
				   $columnName=$classname.EnumColumnNameDefault::ID;
				   break;
			   case EnumIDNameStrategy::TABLENAME_ID:                   
				   if (is_object($dataobject)){
					   $classname=$dataobject->classname();
				   }else{
					   $classname=$dataobject;
				   }
				   $classname{0} = strtolower($classname{0});
				   $idname_concat=UtilReflection::getClassStaticPropertyValue($dataobject, EnumDataObjectDefaultKeyword::NAME_IDNAME_CONCAT);            
				   $columnName=$classname.$idname_concat.EnumColumnNameDefault::ID;
				   break;
			   default:
				   break;
		   }
		}
		return $columnName;
	}      

	/**
	 +---------------------------------------<br/>
	 * 针对DataObject对象定义的非数据对象属性需要被过滤掉。<br/>
	 * 根据数据对象列规格说明移除指定的列。 <br/>
	 +---------------------------------------<br/>
	 * @param array $arrDataObject 数据对象数组
	 * @param mixed $obj 指明所属的对象
	 * @return array DataObject对象定义的非数据对象属性被过滤掉后的数据对象数组
	 */
	public static function removeNotObjectDataField($arrDataObject,$obj){
		unset($arrDataObject[EnumDataObjectDefaultKeyword::NAME_FIELD_SPEC]);
		//unset($arrDataObject[EnumDataObjectDefaultKeyword::NAME_FIELD_SPEC_DEFAULT]);
		unset($arrDataObject[EnumDataObjectDefaultKeyword::NAME_IDNAME_STRATEGY]);
		unset($arrDataObject[EnumDataObjectDefaultKeyword::NAME_IDNAME_CONCAT]);
		unset($arrDataObject[EnumDataObjectDefaultKeyword::NAME_FOREIGNIDNAME_STRATEGY]);
		unset($arrDataObject[EnumDataObjectDefaultKeyword::NAME_FOREIGNID_CONCAT]);
		unset($arrDataObject[self::NAME_REAL_FIELDSPEC]);
		unset($arrDataObject["currentDao"]);
		$field_spec=self::real_field_spec($obj);
		if (isset($field_spec)){
			if (array_key_exists(EnumDataSpec::REMOVE, $field_spec)){
			   $field_spec_remove=$field_spec[EnumDataSpec::REMOVE];             
			   foreach ($field_spec_remove as $value) {                    
					unset($arrDataObject[$value]);
					if (in_array($value, $field_spec)){
						unset($arrDataObject[array_search($value, $field_spec)]); 
					}
			   }
			}  
		}   
		return $arrDataObject;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="默认列更底层的处理方法">
	/**
	 * 存放当前数据对象的列规格说明的名称。
	 */
	const NAME_REAL_FIELDSPEC="real_fieldspec"; 
	/**
	 * 当前数据对象的列规格说明
	 * 由两部分组成：
	 * 1.默认的全局列规格：$field_spec_default,在DataObject内定义。
	 * 2.当前数据对象的列数据规格:$field_spec
	 *   它可以重载或重写全局列规格的属性说明。
	 * @param string $dataobject 当前对象
	 * @return array 当前数据对象的列规格说明 
	 */
	public static function real_field_spec($dataobject){  
		if (!isset($dataobject->real_fieldspec)){
		   $dataobject->real_fieldspec=self::real_field_spec_static($dataobject);
			//var_dump($dataobject->real_fieldspec);   
		}
		return $dataobject->real_fieldspec;
	}
	
	/**
	 * 根据默认列标识字段获取数据对象默认列的实际列名。
	 * @param string $dataobject 当前对象
	 * @param string $columnFlag 默认列标识字段,定义在枚举类型：EnumColumnNameDefault
	 * @param string 实际列名
	 */
	public static function getRealColumnName($dataobject,$columnFlag){
		$field_spec=self::real_field_spec($dataobject);
		if (isset ($field_spec)){
		   if (array_key_exists($columnFlag, $field_spec)){
			   return $field_spec[$columnFlag];
		   }   
		}
		return $columnFlag;
	}
	
	/**
	 * 为数据对象实际的默认列名设置列值
	 * @param string $dataobject 当前对象
	 * @param string 列名
	 * @param mixed 列值 
	 */
	public static function setRealProperty($dataobject,$columnFlag,$value){
		if ($dataobject instanceof DataObject){        
			$columnName=self::getRealColumnName($dataobject,$columnFlag);  
			$dataobject->$columnName=$value;
		}else{
			LogMe::record(Wl::ERROR_INFO_EXTENDS_CLASS);   
		}
	}    
	
	/**
	 * 查看指定列是否被移除。
	 * @param string $dataobject 当前对象
	 * @param string $columnName 列名称
	 */
	public static function isColumnRemove($dataobject,$columnName){
		if ($dataobject instanceof DataObject){ 
			$field_spec_remove=self::getRealColumnName($dataobject,EnumDataSpec::REMOVE);         
			if (is_array($field_spec_remove)){
				$columnNameLcfirst=$columnName;
				$columnNameLcfirst{0} = strtolower($columnNameLcfirst{0}); 
			   if (in_array($columnNameLcfirst, $field_spec_remove)||
				   in_array(ucfirst($columnName), $field_spec_remove)){
				 return true;
			   }
		   }
		   return false;        
	   }else{
			LogMe::record(Wl::ERROR_INFO_EXTENDS_CLASS);              
	   }
	}    
	
	/**
	 * 获取数据对象当前唯一标识列名
	 * @param string $dataobject 当前对象
	 * @return 数据对象当前唯一标识列名
	 */
	public static function getRealIDColumnName($dataobject){
		if(is_string($dataobject)){
			if (class_exists($dataobject)){
				$dataobject=new $dataobject();
			}
		}    
		if ($dataobject instanceof DataObject){   
			$columnName=self::getRealColumnName($dataobject,EnumColumnNameDefault::ID); 
			if ($columnName===EnumColumnNameDefault::ID){
				$idname_strategy=UtilReflection::getClassStaticPropertyValue($dataobject, EnumDataObjectDefaultKeyword::NAME_IDNAME_STRATEGY);
				switch ($idname_strategy) {
					case EnumIDNameStrategy::ID:               
						break;
					case EnumIDNameStrategy::TABLENAMEID:
						$classname=$dataobject->classname();
						$classname{0} = strtolower($classname{0});
						$columnName=$classname.EnumColumnNameDefault::ID;
						break;
					case EnumIDNameStrategy::TABLENAME_ID:
						$classname=$dataobject->classname();
						$classname{0} = strtolower($classname{0});
						$idname_concat=UtilReflection::getClassStaticPropertyValue($dataobject, EnumDataObjectDefaultKeyword::NAME_IDNAME_CONCAT);            
						$columnName=$classname.$idname_concat.EnumColumnNameDefault::ID;
						break;
					default:
						break;
				}
			}
			return $columnName;
		}else{
			LogMe::record(Wl::ERROR_INFO_EXTENDS_CLASS);             
		}        
	}        
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="数据对象默认属性是否需要的处理方法">
	/**
	 * 检验是否需要唯一标识。
	 * @param string $dataobject 当前对象
	 * return bool 是否需要记录唯一标识。
	 */
	public static function isNeedID($dataobject){
		$idName=self::getRealIDColumnName($dataobject);
		if (self::isColumnRemove($dataobject,$idName)){
			return false;
		}
		return true;
	} 
	
	/**
	* 检验是否需要记录CommitTime
	* 它有可能有别名，因此也需要判断其别名是否存在。
	* @param string $dataobject 当前对象
	* return bool 是否需要记录CommitTime
	*/
	public static function isNeedCommitTime($dataobject){ 
		$commitTimeName=self::getRealColumnName($dataobject,EnumColumnNameDefault::COMMITTIME);
		if (self::isColumnRemove($dataobject,$commitTimeName)){
			return false;
		}
		return true;
	}
	
	/**
	* 检验是否需要记录UpdateTime
	* 它有可能有别名，因此也需要判断其别名是否存在。
	* @param string $dataobject 当前对象
	*/
	public static function isNeedUpdateTime($dataobject){  
	   $updateTimeName=self::getRealColumnName($dataobject,EnumColumnNameDefault::UPDATETIME);
	   if (self::isColumnRemove($dataobject,$updateTimeName)){
			return false;
	   }
	   return true;
	}    
	//</editor-fold>
	
  }
?>
