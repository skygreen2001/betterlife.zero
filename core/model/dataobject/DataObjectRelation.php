<?php
/**
 * 数据对象间关系处理
 * @category betterlife
 * @package core.model
 * @subpackage dataobject
 * @author skygreen
 */
class DataObjectRelation extends BBObject
{
	  /**
	 * 获取多对多关系表名称定义，如无定义，则按默认规则查找指定表。<br/>
	 * @param string $dataobject 当前数据对象
	 * @param string $classname_has  多对多关系对象类名
	 * @param string $classname_belong  从属于多对多关系对象类名
	 * @return string 多对多关系表名称
	 */
	public static function getRealManyManyTable($dataobject,$classname_has,$classname_belong)
	{
		if ($dataobject instanceof DataObject){
			$field_spec_manymanytable=DataObjectSpec::getRealColumnName($dataobject,EnumDataSpec::MANY_MANY_TABLE);
			if(is_array($field_spec_manymanytable)){
				if (array_key_exists($classname_has, $field_spec_manymanytable)){
				   return $field_spec_manymanytable[$classname_has];
				}
				if (array_key_exists($classname_belong, $field_spec_manymanytable)){
				   return $field_spec_manymanytable[$classname_belong];
				}
			}

			$tablename =Config_Db::orm($classname_has);
			$tncount=explode(Config_Db::TABLENAME_CONCAT,$tablename);
			if (count($tncount)>2){
				$tablename= substr($tablename,0,strrpos($tablename, Config_Db::TABLENAME_CONCAT));
			}
			$tablename.=Config_Db::TABLENAME_RELATION.Config_Db::TABLENAME_CONCAT;
			$tablename.=strtolower($classname_has.$classname_belong);
			return $tablename;
		}else{
			LogMe::record(Wl::ERROR_INFO_EXTENDS_CLASS);
		}
	}

	/**
	 * 获取数据对象关系类外键标识列名<br/>
	 * @param string $dataobject 当前对象
	 * @param string $classname 关系对应数据对象表类名。如UserDetail有一对一关系$belong_has_one包含User,则类名$classname为User。
	 * @param string $instance_name 实例对象名称[只用于从属于一对一关系]
	 * @return string 数据对象关系类外键标识列名
	 */
	public static function getRealForeignIDColumnName($dataobject,$classname,$instance_name=null)
	{
		if ($dataobject instanceof DataObject){
			$field_spec_foreignid=DataObjectSpec::getRealColumnName($dataobject,EnumDataSpec::FOREIGN_ID);
			if(is_array($field_spec_foreignid)){
				if (array_key_exists($classname, $field_spec_foreignid)){
				   return $field_spec_foreignid[$classname];
				}

				if (array_key_exists($instance_name,$field_spec_foreignid)){
				   return $field_spec_foreignid[$instance_name];
				}
			}
			$classname{0} = strtolower($classname{0});

			$classname_dataobject=get_class($dataobject);
			$foreignid_name_strategy=UtilReflection::getClassStaticPropertyValue($classname_dataobject,EnumDataObjectDefaultKeyword::NAME_FOREIGNIDNAME_STRATEGY);
			switch ($foreignid_name_strategy) {
				case EnumForeignIDNameStrategy::TABLENAMEID:
					$columnName=$classname.ucfirst(EnumColumnNameDefault::ID);
					break;
				case EnumForeignIDNameStrategy::TABLENAME_ID:
					$columnName=$classname.DataObject::$foreignid_concat.EnumColumnNameDefault::ID;
					break;
				default:
					$columnName=$classname.ucfirst(EnumColumnNameDefault::ID);
					break;
			}
			return $columnName;
		}else{
			LogMe::record(Wl::ERROR_INFO_EXTENDS_CLASS);
		}
	}

	/**
	 * 处理表之间一对一，一对多，多对多的关系
	 * @param string $dataobject 当前对象
	 * @param string $property 关系对象类名
	 * @param bool $isRelation 是否存在关系，如果存在关系，则返回关系对象数据
	 */
	public static function getMutualRelation($dataobject,$property) {
		if ($dataobject instanceof DataObject){
			$properties=UtilReflection::getClassStaticProperties($dataobject);
			$properties=DataObjectSpec::removeNotObjectDataField($properties,$dataobject);
			if (empty($properties)) {
				return null;
			}
			foreach($properties as $propertyName=>$propertyValue) {
				/**
				 * 调用一对多
				 * 定义如下：
				 * class Department extends DataObject {
				 *     static $has_many=array(
				 *         "admins"=>"Admin",
				 *     );
				 * 示例如下：
				 *       $department=Department::get_by_id(1);
				 *       print_r($department->admins);//第1种方式
				 *       print_r($department->getAdmins());//第2种方式
				 *       print_r($department->admins());//第3种方式
				 */
				//<editor-fold defaultstate="collapsed" desc="一对多">
				if ($propertyName==EnumTableRelation::HAS_MANY) {
					$has_many=$propertyValue;
					$isExist=false;

					if (array_key_exists($property,$has_many)) {
						$isExist=true;
					}else{
						$property_lcfirst=$property;
						$property_lcfirst{0} = strtolower($property_lcfirst{0});
						if (array_key_exists($property_lcfirst,$has_many)) {
							$isExist=true;
							$property=$property_lcfirst;
						}
					}
					if ($isExist) {
						$detail_class= $has_many[$property];
						$isRelation=true;
						$classname=$dataobject->classname();
						$classname{0} = strtolower($classname{0});
						$foreignId=self::getRealForeignIDColumnName($dataobject,$classname);
						if ($dataobject->getId()) {
							return  DataObject::dao()->get($detail_class,$foreignId."=".$dataobject->getId());
						}
					}
				}
				//</editor-fold>

				/**
				 * 调用多对多【主控的一方】
				 * 定义如下：
				 * class User extends DataObject {
				 *     static $many_many=array(
				 *        "roles"=>"Role",
				 *     );
				 * 示例如下：
				 *       $user=User::get_by_id(1);
				 *       print_r($user->roles);//第1种方式
				 *       print_r($user->getRoles());//第2种方式
				 *       print_r($user->roles());//第3种方式
				 */
				//<editor-fold defaultstate="collapsed" desc="多对多">
				if ($propertyName==EnumTableRelation::MANY_MANY) {
					$many_many=$propertyValue;
					$isExist=false;
					if (array_key_exists($property,$many_many)) {
						$isExist=true;
					}else{
						$property_lcfirst=$property;
						$property_lcfirst{0} = strtolower($property_lcfirst{0});
						if (array_key_exists($property_lcfirst,$many_many)) {
							$isExist=true;
							$property=$property_lcfirst;
						}
					}
					if ($isExist) {
						$isRelation=true;
						if ($dataobject->getId()) {
							$detail_class= $many_many[$property];
							$_SQL=new Crud_Sql_Select();
							$relation_tablename= self::getRealManyManyTable($dataobject,$dataobject->classname(),$detail_class);
							$self_foreignId=self::getRealForeignIDColumnName($dataobject,$dataobject->classname());
							$relationObject_tablename=Config_Db::orm($detail_class);
							$relationObject_IdName=DataObjectSpec::getRealIDColumnNameStatic($detail_class);
							$relationObject_foreignId=self::getRealForeignIDColumnName($dataobject,$detail_class);
							$query=$_SQL->select("b.*")->from($relation_tablename." a,".$relationObject_tablename." b")->ignoreQuotes(true)->where("a.".$self_foreignId."='".$dataobject->getId()."',b.".$relationObject_IdName."=a.".$relationObject_foreignId)->ignoreQuotes(false)->result();
							return  DataObject::dao()->sqlExecute($query,$detail_class);
						}
					}
				}
				//</editor-fold>

				/**
				 * 调用多对多【被控的一方】
				 * 定义如下：
				 * class Role extends DataObject {
				 *     static $belongs_many_many=array(
				 *        "users"=>"User",
				 *     );
				 * 示例如下：
				 *       $role=Role::get_by_id(1);
				 *       print_r($role->users);//第1种方式
				 *       print_r($role->getUsers());//第2种方式
				 *       print_r($role->users());//第3种方式
				 */
				//<editor-fold defaultstate="collapsed" desc="从属于多对多">
				if ($propertyName==EnumTableRelation::BELONGS_TO) {
					$belong_to=$propertyValue;
					if (array_key_exists($property,$belong_to)) {
						$isExist=true;
					}else{
						$property_lcfirst=$property;
						$property_lcfirst{0} = strtolower($property_lcfirst{0});
						if (array_key_exists($property_lcfirst,$belong_to)) {
							$isExist=true;
							$property=$property_lcfirst;
						}
					}
					if ($isExist) {
						$isRelation=true;
						if ($dataobject->getId()) {
							$mainClass= $belong_to[$property];
							$_SQL=new Crud_Sql_Select();
							$self_foreignId=self::getRealForeignIDColumnName($dataobject,$dataobject->classname());
							$relation_tablename= self::getRealManyManyTable($dataobject,$mainClass,$dataobject->classname());
							$relationObject_tablename=Config_Db::orm($mainClass);
							$relationObject_IdName=DataObjectSpec::getRealIDColumnNameStatic($mainClass);
							$relationObject_foreignId=self::getRealForeignIDColumnName($dataobject,$mainClass);
							$query=$_SQL->select("b.*")->ignoreQuotes(true)->from($relation_tablename." a,".$relationObject_tablename." b")->where("a.".$self_foreignId."='".$dataobject->getId()."',b.".$relationObject_IdName."=a.".$relationObject_foreignId)->ignoreQuotes(false)->result();
							return  DataObject::dao()->sqlExecute($query,$mainClass);
						}
					}
				}
				//</editor-fold>

				/**
				 * 调用一对一
				 * 定义如下：
				 * class User extends DataObject {
				 *     static $has_one=array(
				 *        "userdetail"=> "UserDetail",
				 *     );
				 * 示例如下：
				 *      $user=User::get_by_id(1);
				 *      $user->userdetail;//第1种方式
				 *      //$user->getUserdetail();//第2种方式
				 *      //$user->userdetail();//第3种方式
				 */
				//<editor-fold defaultstate="collapsed" desc="一对一">
				if ($propertyName==EnumTableRelation::HAS_ONE) {
					$has_one=$propertyValue;
					$isExist=false;
					if (array_key_exists($property,$has_one)) {
						$isExist=true;
					}else{
						$property_lcfirst=$property;
						$property_lcfirst{0} = strtolower($property_lcfirst{0});
						if (array_key_exists($property_lcfirst,$has_one)) {
							$isExist=true;
							$property=$property_lcfirst;
						}
					}
					if ($isExist) {
						$detail_class= $has_one[$property];
						$isRelation=true;
						$classname=$dataobject->classname();
						$classname{0} = strtolower($classname{0});
						$foreignId=self::getRealForeignIDColumnName($dataobject,$classname);
						if ($dataobject->getId()) {
							return  DataObject::dao()->get_one($detail_class,$foreignId."=".$dataobject->getId());
						}
					}
				}
				//</editor-fold>

				/**
				 * 调用从属一对一
				 * 定义如下：
				 * class Comment extends DataObject {
				 *  static $belongs_has_one=array(
				 *    "user"=>"User"
				 *  );
				 * 示例如下：
				 *      $comment=Comment::get_by_id(3);
				 *      $comment->user;//第1种方式
				 *      //$comment->getUser();//第2种方式
				 *      //$comment->user();//第3种方式
				 */
				//<editor-fold defaultstate="collapsed" desc="从属于一对一">
				if ($propertyName==EnumTableRelation::BELONG_HAS_ONE) {
					$belong_has_one=$propertyValue;
					$isExist=false;
					if (array_key_exists($property,$belong_has_one)) {
						$isExist=true;
					}else{
						$property_lcfirst=$property;
						$property_lcfirst{0} = strtolower($property_lcfirst{0});
						if (array_key_exists($property_lcfirst,$belong_has_one)) {
							$isExist=true;
							$property=$property_lcfirst;
						}
					}
					if ($isExist) {
						$detail_class= $belong_has_one[$property];
						$isRelation=true;
						$foreignId=self::getRealForeignIDColumnName($dataobject,$detail_class,$property);
						$relationvalue=$dataobject->$foreignId;
						$relationObject_IdName=DataObjectSpec::getRealIDColumnNameStatic($detail_class);
						if (isset($relationvalue)){
							return DataObject::dao()->get_one($detail_class,$relationObject_IdName."=".$relationvalue);
						}
					}
				}
				//</editor-fold>
			}
		}else{
			LogMe::record(Wl::ERROR_INFO_EXTENDS_CLASS);
		}
        return null;
	}

	/**
	 +----------------------------------------------------<br>
	 * 数据对象存在多对多|从属于多对多关系时，因为存在一张中间表。<br>
	 * 因此它们的关系需要单独进行存储<br>
	 * 示例1【多对多-主控端】：<br>
	 *      $user=new User();<br>
	 *      $user->setId(2);<br>
	 *      $user->saveRelationForManyToMany("roles","3",array("commitTime"=>date("Y-m-d H:i:s")));<br>
	 *      说明:roles是在User数据对象中定义的变量：<br>
	 *      static $many_many=array(<br>
	 *        "roles"=>"Role",<br>
	 *      );<br>
	 * 示例2【多对多-被控端】：<br>
	 *      $role=new Role();
	 *      $role->setId(5);
	 *      $role->saveRelationForManyToMany("users","6",array("commitTime"=>date("Y-m-d H:i:s")));
	 *      说明:users是在Role数据对象中定义的变量：<br>
	 *      static $belongs_many_many=array(
	 *        "users"=>"User",
	 *      );
	 +----------------------------------------------------<br>
	 * @param string $dataobject 当前对象
	 * @param mixed $relation_object 多对多|从属于多对多关系定义对象
	 * @param mixed $relation_id_value 关系对象的主键ID值。
	 * @param array $other_column_values  其他列值键值对【冗余字段便于查询的数据列值】，如有一列：记录关系创建时间。
	 * @return mixed 保存对象后的主键
	 */
	public static function saveRelationForManyToMany($dataobject,$relation_object,$relation_id_value,$other_column_values=null){
		if ($dataobject instanceof DataObject){
			$properties=UtilReflection::getClassStaticProperties($dataobject);
			$properties=DataObjectSpec::removeNotObjectDataField($properties,$dataobject);
			if (empty($properties))return null;
			$relation_class=null;
			if(array_key_exists(EnumTableRelation::MANY_MANY, $properties)){
				$properties_m=$properties[EnumTableRelation::MANY_MANY];
				if (array_key_exists($relation_object, $properties_m)){
					$classname_has=$dataobject->classname();
					$classname_belong=$properties_m[$relation_object];
					$relation_class=$classname_belong;
				}
			}
			if(array_key_exists(EnumTableRelation::BELONGS_TO, $properties)){
				$properties_bm=$properties[EnumTableRelation::BELONGS_TO];
				if (array_key_exists($relation_object,$properties_bm)){
					$classname_has=$properties_bm[$relation_object];
					$classname_belong=$dataobject->classname();
					$relation_class=$classname_has;
				}
			}
			if (isset($classname_has)&&isset($classname_belong)){
				$relation_table=self::getRealManyManyTable($dataobject,$classname_has,$classname_belong);
				$_SQL=new Crud_Sql_Insert();
				$_SQL->isPreparedStatement=false;
				$array_properties=array();
				$self_foreignId=self::getRealForeignIDColumnName($dataobject,$dataobject->classname());
				$relationObject_foreignId=self::getRealForeignIDColumnName($dataobject,$relation_class);
				$array_properties[$self_foreignId]=$dataobject->getId();
				$array_properties[$relationObject_foreignId]=$relation_id_value;
				if ($other_column_values){
					$array_properties=array_merge($array_properties, $other_column_values);
				}
				$sQuery=$_SQL->insert($relation_table)->values($array_properties)->result();
				return DataObject::dao()->sqlExecute($sQuery);
			}else{
				LogMe::log($dataobject->classname()."在多对多关系中对".$relation_object.":".$relation_id_value."映射不正确，请确认代码中变量定义是否正确！");
			}
		}else{
			LogMe::record(Wl::ERROR_INFO_EXTENDS_CLASS);
		}
	}
}
?>
