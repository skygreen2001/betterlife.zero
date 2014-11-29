<?php

//<editor-fold defaultstate="collapsed" desc="枚举类型">
DataObjectSpec::init();
//</editor-fold>

/**
 +-----------------------------------------<br/>
 * 所有数据实体类如POJO的父类<br/>
 * 该实体类设计为ActiveRecord模式。<br/>
 * 可直接在对象上操作CRUD增删改查操作<br/>
 * 查主要为：根据主键和名称查找对象。<br/>
 *			总记录数和分页查找等常规方法。<br/>
 * 框架定义数据对象的默认列[关键字可通过数据对象列规格$field_spec修改]：<br/>
 *			id,commitTime，updateTime<br/>
 * id:数据对象的唯一标识<br/>
 * committime:数据创建的时间，当没有updateTime时，其亦代表数据最后更新的时间<br/>
 * updateTime:数据最后更新的时间。<br/>
 +-----------------------------------------<br/>
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
abstract class DataObject extends Object implements ArrayAccess
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * @var enum $id_name_strategy ID名称定义的策略
	 */
	public static $idname_strategy=EnumIDNameStrategy::TABLENAME_ID;
	/**
	 * ID名称中的连接符。<br/>
	 * ID名称定义的策略为TABLENAME_ID有效。
	 * @static
	 */
	public static $idname_concat='_';
	/**
	 * @var enum $foreignid_name_strategy Foreign ID名称定义的策略
	 */
	public static $foreignid_name_strategy=EnumForeignIDNameStrategy::TABLENAME_ID;
	/**
	 * Foreign ID名称中的连接符。<br/>
	 * Foreign ID名称定义的策略为TABLENAME_ID有效。
	 * @static
	 */
	public static $foreignid_concat='_';
	/**
	 * 数据对象定义需定义字段：public $field_spec<br/>
	 * 它定义了当前数据对象的列规格说明。<br/>
	 * 数据对象的列规格说明可参考DataObjectSpec::$field_spec_default的定义
	 */
	public $field_spec;
	/**
	 * @var mixed 数据对象的唯一标识
	 */
	protected $id;
	/**
	 * @var int 记录创建的时间timestamp
	 */
	public $commitTime;
	/**
	 * @var int 记录最后更新的时间，当表中无该字段时，一般用commitTime记录最后更新的时间。
	 */
	public $updateTime;
	/**
	 * @var IDao 当前使用的数据库调用对象
	 */
	private static $currentDao;
	/**
	 * 获取当前使用的数据库调用对象
	 * @return IDao
	 */
	public static function dao()
	{
		if (!isset(self::$currentDao)) {
			self::$currentDao=Manager_Db::newInstance()->dao();
		}
		return self::$currentDao;
	}
	/**
	 * 静态方法:获取数据对象的类名
	 */
	public static function classname_static()
	{
		$result=get_called_class();
		return $result;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="魔术方法">
	/**
	 * 从数组创建对象。
	 * @param mixed $array
	 * @return DataObject
	 */
	public function __construct($array=null)
	{
		if (!empty($array)){
			UtilObject::array_to_object($array,$this);
		}
	}

	/**
	 * 说明：若每个具体的实现类希望不想实现set,get方法；<br/>
	 *		则将该方法复制到每个具体继承他的对象类内。<br/>
	 * 可设定对象未定义的成员变量[但不建议这样做]<br/>
	 * 可无需定义get方法和set方法<br/>
	 * 类定义变量访问权限设定需要是pulbic<br/>
	 * @param string $method 方法名
	 * @param array $arguments 传递的变量数组
	 */
	public function __call($method, $arguments)
	{
		return DataObjectFunc::call($this,$method,$arguments);
	}

	/**
	 * 可设定对象未定义的成员变量[但不建议这样做]<br/>
	 * 类定义变量访问权限设定需要是pulbic
	 * @param mixed $property 属性名
	 * @return mixed 属性值
	 */
	public function __get($property)
	{
		return DataObjectFunc::get($this,$property);
	}

	/**
	 * 可设定对象未定义的成员变量[但不建议这样做]<br/>
	 * 类定义变量访问权限设定需要是pulbic
	 * @param mixed $property 属性名
	 * @param mixed $value 属性值
	 */
	public function __set($property, $value)
	{
		return DataObjectFunc::set($this,$property,$value);
	}

	/**
	 * 打印当前对象的数据结构
	 * @return string 描述当前对象。
	 */
	public function __toString() {
		return DataObjectFunc::toString($this);
	}
	//</editor-fold>

	/**
	 * 处理表之间一对一，一对多，多对多的关系
	 */
	public function getMutualRelation($property)
	{
		return DataObjectRelation::getMutualRelation($this,$property);
	}

	//<editor-fold defaultstate="collapsed" desc="默认列Setter和Getter">
	/**
	 * @var array 存放当前数据对象的列规格说明
	 */
	public $real_fieldspec;

	/**
	 * 设置唯一标识
	 * @param mixed $id
	 */
	public function setId($id)
	{
		if (DataObjectSpec::isNeedID($this)){
			$columnName=DataObjectSpec::getRealIDColumnName($this);
			$this->$columnName=$id;
		}
		unset($this->real_fieldspec);
	}

	/**
	 * 获取唯一标识
	 * @return mixed
	 */
	public function getId()
	{
		if (DataObjectSpec::isNeedID($this)){
			$columnName=DataObjectSpec::getRealIDColumnName($this);
			unset($this->real_fieldspec);
			return $this->$columnName;
		}else{
			unset($this->real_fieldspec);
			return null;
		}
	}

	/**
	 * 设置数据创建的时间
	 * @param mixed $commitTime
	 */
	public function setCommitTime($commitTime)
	{
		if (DataObjectSpec::isNeedCommitTime($this))
		{
			$columnName=DataObjectSpec::getRealColumnName($this,EnumColumnNameDefault::COMMITTIME);
			$this->$columnName= $commitTime;
		}
		unset($this->real_fieldspec);
	}

	/**
	 * 获取数据创建的时间
	 * @return mixed
	 */
	public function getCommitTime()
	{
		if (DataObjectSpec::isNeedCommitTime($this)){
			$columnName=DataObjectSpec::getRealColumnName($this,EnumColumnNameDefault::COMMITTIME);
			unset($this->real_fieldspec);
			return $this->$columnName;
		} else {
			unset($this->real_fieldspec);
			return null;
		}
		//return $this->commitTime;
	}

	/**
	 * 设置数据最后更新的时间
	 * @param mixed $updateTime
	 */
	public function setUpdateTime($updateTime)
	{
		if (DataObjectSpec::isNeedUpdateTime($this))
		{
			$columnName=DataObjectSpec::getRealColumnName($this,EnumColumnNameDefault::UPDATETIME);
			$this->$columnName= $updateTime;
		}else{
			$this->setCommitTime($updateTime);
		}
		unset($this->real_fieldspec);
	}

	/**
	 * 获取数据最后更新的时间
	 * @return mixed
	 */
	public function getUpdateTime()
	{
		if (DataObjectSpec::isNeedUpdateTime($this)){
			$columnName=DataObjectSpec::getRealColumnName($this,EnumColumnNameDefault::UPDATETIME);
			unset($this->real_fieldspec);
			return $this->$columnName;
		} else {
			unset($this->real_fieldspec);
			return $this->getCommitTime($updateTime);
		}
		//return $this->updateTime;
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="定义数组进入对象方式">
	public function offsetExists($key)
	{
		$method="get".ucfirst($key);
		return method_exists($this,$method);
	}
	public function offsetGet($key)
	{
		$method="get".ucfirst($key);
		return $this->$method();
	}
	public function offsetSet($key, $value)
	{
		$method="set".ucfirst($key);
		$this->$method($value);
		//$this->$key = $value;
	}
	public function offsetUnset($key)
	{
		unset($this->$key);
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="数据持久化：数据库的CRUD操作">
	/**
	 * 获取当前数据对象的表名
	 */
	public static function tablename(){
		return Config_Db::orm(get_called_class());
	}

	/**
	 * 根据数据对象的属性名获取属性名的显示。
	 * @param mixed $data 数据对象数组。如:array(user,user)
	 * @param mixed $property_name  属性名【可以一次指定多个属性名】
	 */
	public static function propertyShow($data,$property_name)
	{
		DataObjectFunc::propertyShow($data,get_called_class(),$property_name);
	}

	/**
	 * 保存前操作
	 */
	protected function onBeforeWrite()
	{
	}

	/**
	 * 保存当前对象
	 * @return boolen 是否新建成功；true为操作正常
	 */
	protected function write()
	{
		$this->save();
	}

	/**
	 * 保存当前对象
	 * @return int 保存对象记录的ID标识号
	 */
	public function save()
	{
		$this->onBeforeWrite();
		$id= $this->getId();
		if (empty($id)){
			$idColumn=DataObjectSpec::getRealIDColumnName($this);
			unset($this->{$idColumn});
		}
		return self::dao()->save($this);
	}

	/**
	 +----------------------------------------------------<br>
	 * 数据对象存在多对多|从属于多对多关系时，因为存在一张中间表。<br>
	 * 因此它们的关系需要单独进行存储<br>
	 * 示例1【多对多-主控端】：<br>
	 *		$user=new User();<br>
	 *		$user->setId(2);<br>
	 *		$user->saveRelationForManyToMany("roles","3",array("commitTime"=>date("Y-m-d H:i:s")));<br>
	 *		说明:roles是在User数据对象中定义的变量：<br>
	 *		static $many_many=array(<br>
	 *			"roles"=>"Role",<br>
	 *		);<br>
	 * 示例2【多对多-被控端】：<br>
	 *		$role=new Role();
	 *		$role->setId(5);
	 *		$role->saveRelationForManyToMany("users","6",array("commitTime"=>date("Y-m-d H:i:s")));
	 *		说明:users是在Role数据对象中定义的变量：<br>
	 *		static $belongs_many_many=array(
	 *			"users"=>"User",
	 *		);
	 +----------------------------------------------------<br>
	 * @param mixed $relation_object 多对多|从属于多对多关系定义对象
	 * @param mixed $relation_id_value 关系对象的主键ID值。
	 * @param array $other_column_values  其他列值键值对【冗余字段便于查询的数据列值】，如有一列：记录关系创建时间。
	 * @return mixed 保存对象后的主键
	 */
	public function saveRelationForManyToMany($relation_object,$relation_id_value,$other_column_values=null)
	{
		return DataObjectRelation::saveRelationForManyToMany($this,$relation_object,$relation_id_value,$other_column_values);
	}

	/**
	 * 由标识删除指定ID数据对象
	 * @param mixed $id 数据对象编号
	 */
	public static function deleteByID($id)
	{
		return DataObjectFunc::deleteByID(get_called_class(),$id);
	}

	/**
	 * 根据主键删除多条记录
	 * @param array|string $ids 数据对象编号
	 *  形式如下:
	 *  1.array:array(1,2,3,4,5)
	 *  2.字符串:1,2,3,4
	 */
	public static function deleteByIds($ids)
	{
		return DataObjectFunc::deleteByIds(get_called_class(),$ids);
	}

	/**
	 * 根据条件删除多条记录
	 * @param mixed $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
	 */
	public static function deleteBy($filter)
	{
		return DataObjectFunc::deleteBy(get_called_class(),$filter);
	}

	/**
	 * 删除当前对象
	 * @return boolen 是否删除成功；true为操作正常
	 */
	public function delete()
	{
		return self::dao()->delete($this);
	}

	/**
	 * 保存或更新当前对象
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function saveOrUpdate()
	{
		return self::dao()->saveOrUpdate($this);
	}

	/**
	 * 更新当前对象
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function update()
	{
		$result=self::dao()->update($this);
		unset($this["real_fieldspec"]);
		return  $result;
	}

	/**
	 * 更新对象指定的属性
	 * @param array|string $sql_ids 需更新数据的ID编号或者ID编号的Sql语句<br/>
	 * 示例如下：<br/>
	 *		$sql_ids:<br/>
	 *			1.1,2,3<br/>
	 *			2.array(1,2,3)<br/>
	 * @param string $array_properties 指定的属性<br/>
	 * 示例如下：<br/>
	 *		$array_properties<br/>
	 *			1.pass=1,name='sky'<br/>
	 *			2.array("pass"=>"1","name"=>"sky")<br/>
	 * @return boolen 是否更新成功；true为操作正常<br/>
	 */
	public static function updateProperties($sql_ids,$array_properties)
	{
		return DataObjectFunc::updateProperties(get_called_class(),$sql_ids,$array_properties);
	}

	/**
	 * 根据条件更新数据对象指定的属性
	 * @param mixed $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
	 * @param string $array_properties 指定的属性<br/>
	 * 示例如下：<br/>
	 *		$array_properties<br/>
	 *			1.pass=1,name='sky'<br/>
	 *			2.array("pass"=>"1","name"=>"sky")<br/>
	 * @return boolen 是否更新成功；true为操作正常<br/>
	 */
	public static function updateBy($filter,$array_properties)
	{
		return DataObjectFunc::updateBy(get_called_class(),$filter,$array_properties);
	}


	/**
	 * 对属性进行递增
	 * @param object|string|array $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string property_name 属性名称
	 * @param int incre_value 递增数
	 */
	public static function increment($filter=null,$property_name,$incre_value=1)
	{
		return DataObjectFunc::increment(get_called_class(),$filter,$property_name,$incre_value);
	}

	/**
	 * 对属性进行递减
	 * @param object|string|array $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string property_name 属性名称
	 * @param int decre_value 递减数
	 */
	public static function decrement($filter=null,$property_name,$decre_value=1)
	{
		return DataObjectFunc::decrement(get_called_class(),$filter,$property_name,$decre_value);
	}

	/**
	 * 由标识判断指定ID数据对象是否存在
	 * @param mixed $id 数据对象编号
	 * @return bool 是否存在
	 */
	public static function existByID($id)
	{
		return DataObjectFunc::existByID(get_called_class(),$id);
	}

	/**
	 * 判断符合条件的数据对象是否存在
	 * @param mixed $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
	 * @return bool 是否存在
	 */
	public static function existBy($filter)
	{
		return DataObjectFunc::existBy(get_called_class(),$filter);
	}

	/**
	 * 查询当前对象需显示属性的列表
	 * @param string $columns指定的显示属性，同SQL语句中的Select部分。
	 * 示例如下：<br/>
	 *		id,name,commitTime
	 * @param object|string|array $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
	 * @param string $sort 排序条件<br/>
	 * 示例如下：<br/>
	 *		1.id asc;<br/>
	 *		2.name desc;<br/>
	 * @param string $limit 分页数目:同Mysql limit语法
	 * 示例如下：<br/>
	 *	0,10<br/>
	 * @return 查询列数组，当只有一个值的时候如select count(表名_id)，自动从数组中转换出来值字符串
	 */
	public static function select($columns,$filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
	{
		return DataObjectFunc::showColumns(get_called_class(),$columns,$filter, $sort, $limit);
	}

	/**
	 * 查询当前对象单个需显示的属性
	 * @param string 指定的显示属性，同SQL语句中的Select部分。
	 * 示例如下：<br/>
	 *		id,name,commitTime
	 * @param object|string|array $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
	 * @param string $sort 排序条件<br/>
	 * 示例如下：<br/>
	 *		1.id asc;<br/>
	 *		2.name desc;<br/>
	 * @param string $limit 分页数目:同Mysql limit语法
	 * 示例如下：<br/>
	 *		0,10<br/>
	 * @return 查询列数组，自动从数组中转换出来值字符串,最后只返回一个值
	 */
	public static function select_one($columns,$filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
	{
		$result=DataObjectFunc::showColumns(get_called_class(),$columns,$filter, $sort, $limit);
		if (!empty($result)&&(is_array($result))&&(count($result)>0)){
			$result=$result[0];
		}
		return $result;
	}

	/**
	 * 查询数据对象列表
	 * @param object|string|array $filter 查询条件，在where后的条件<br/>
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如："(id=1 and name='sky') or (name like 'sky')"<br/>
	 * @param string $sort 排序条件<br/>
	 * 示例如下：<br/>
	 *		1.id asc;<br/>
	 *		2.name desc;<br/>
	 * @param string $limit 分页数目:同Mysql limit语法
	 * 示例如下：<br/>
	 *	0,10<br/>
	 * @return 对象列表数组
	 */
	public static function get($filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
	{
		return self::dao()->get(get_called_class(), $filter, $sort, $limit);
	}

	/**
	 * 查询得到单个对象实体
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件
	 * 示例如下：
	 *		1.id asc;
	 *		2.name desc;
	 * @return 单个对象实体
	 */
	public static function get_one($filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		return self::dao()->get_one(get_called_class(),$filter,$sort);
	}

	/**
	 * 根据表ID主键获取指定的对象[ID对应的表列]
	 * @param string $id
	 * @return 对象
	 */
	public static function get_by_id($id)
	{
		return self::dao()->get_by_id(get_called_class(), $id);
	}

	/**
	 * 对象总计数
	 * @param object|string|array $filter<br/>
	 *		$filter 格式示例如下：<br/>
	 *			0.允许对象如new User(id="1",name="green");<br/>
	 *			1."id=1","name='sky'"<br/>
	 *			2.array("id=1","name='sky'")<br/>
	 *			3.array("id"=>"1","name"=>"sky")
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @return 对象总计数
	 */
	public static function count($filter=null)
	{
		return self::dao()->count(get_called_class(), $filter);
	}

	/**
	 * 对象总计数[多表关联查询]
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param string|array $from 来自多张表或者多个类[必须是数据对象类名]，在from后的多张表名，表名之间以逗号[,]隔开
	 * 示例如下：<br/>
	 *		0."table1,table2"<br/>
	 *		1.array("table1","table2")<br/>
	 *		2."class1,class2"<br/>
	 *		3.array("class1","class2")<br/>
	 * @param object|string|array $filter
	 *		$filter 格式示例如下：<br/>
	 *			0.允许对象如new User(id="1",name="green");<br/>
	 *			1."id=1","name='sky'"<br/>
	 *			2.array("id=1","name='sky'")<br/>
	 *			3.array("id"=>"1","name"=>"sky")<br/>
	 * @return 对象总计数
	 */
	public static function countMultitable($object,$from,$filter=null)
	{
		return self::dao()->countMultitable(get_called_class(), $from, $filter);
	}

	/**
	 * 数据对象标识最大值
	 * @param string $column_name 列名，默认为数据对象标识
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * @return int 数据对象标识最大值<br/>
	 */
	public static function max($column_name=null,$filter=null)
	{
		return DataObjectFunc::max(get_called_class(),$column_name,$filter);
	}

	/**
	 * 数据对象指定列名最小值，如未指定列名，为标识最小值
	 * @param string $column_name 列名，默认为数据对象标识
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * @return int 数据对象列名最小值，如未指定列名，为标识最小值<br/>
	 */
	public static function min($column_name=null,$filter=null)
	{
		return DataObjectFunc::min(get_called_class(),$column_name,$filter);
	}

	/**
	 * 数据对象指定列名总数
	 * @param string $column_name 列名
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * @return int 数据对象列名总数<br/>
	 */
	public static function sum($column_name=null,$filter=null)
	{
		return DataObjectFunc::sum(get_called_class(),$column_name,$filter);
	}

	/**
	 * 对象分页
	 * @param int $startPoint  分页开始记录数
	 * @param int $endPoint	分页结束记录数
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件<br/>
	 * 默认为 id desc<br/>
	 * 示例如下：<br/>
	 *	  1.id asc;<br/>
	 *	  2.name desc;
	 * @return mixed 对象分页
	 */
	public static function queryPage($startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		if(($startPoint>$endPoint)||($endPoint==0))return null;
		return self::dao()->queryPage(get_called_class(),$startPoint,$endPoint,$filter,$sort);
	}

	/**
	 * 对象分页根据当前页数和每页显示记录数
	 * @param int $pageNo  当前页数
	 * @param int $pageSize 每页显示记录数
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件<br/>
	 * 默认为 id desc<br/>
	 * 示例如下：<br/>
	 *		1.id asc;<br/>
	 *		2.name desc;
	 * @return array
	 *		count	:符合条件的记录总计数
	 *		pageCount:符合条件的总页数
	 *		data	 :对象分页
	 */
	public static function queryPageByPageNo($pageNo,$filter=null,$pageSize=10,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		$count= self::dao()->count(get_called_class(), $filter);
		$data = array();
		$pageCount=0;
		if ($count>0){
			// 总页数
			$pageCount = floor(($count + $pageSize - 1) / $pageSize);
			if ($pageNo<=$pageCount){
				$startPoint=($pageNo-1)*$pageSize+1;
				if ($startPoint>$count) {
					$startPoint=0;
				}
				$endPoint=$pageNo*$pageSize;
				if ($endPoint>$count) {
					$endPoint=$count;
				}
				$data=self::dao()->queryPage(get_called_class(),$startPoint,$endPoint,$filter,$sort);
			}
		}
		return array(
			"count"	=>$count,
			"pageCount"=>$pageCount,
			"data"	=>$data
		);
	}

	/**
	 * 对象分页[多表关联查询]
	 * @param int $startPoint  分页开始记录数
	 * @param int $endPoint	分页结束记录数
	 * @param string|array $from 来自多张表或者多个类[必须是数据对象类名]，在from后的多张表名，表名之间以逗号[,]隔开
	 * 示例如下：<br/>
	 *		0."table1,table2"<br/>
	 *		1.array("table1","table2")<br/>
	 *		2."class1,class2"<br/>
	 *		3.array("class1","class2")<br/>
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *		0."id=1,name='sky'"<br/>
	 *		1.array("id=1","name='sky'")<br/>
	 *		2.array("id"=>"1","name"=>"sky")<br/>
	 *		3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件<br/>
	 * 默认为 id desc<br/>
	 * 示例如下：<br/>
	 *		1.id asc;<br/>
	 *		2.name desc;
	 * @return mixed 对象分页
	 */
	public static function queryPageMultitable($startPoint,$endPoint,$from,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		if(($startPoint>$endPoint)||($endPoint==0))return null;
		return self::dao()->queryPageMultitable(get_called_class(),$startPoint,$endPoint,$from,$filter,$sort);
	}
	//</editor-fold>

	//<editor-fold defaultstate="collapsed" desc="数据类型转换">
	/**
	 * 将数据对象转换成xml
	 * @param $filterArray 需要过滤不生成的对象的field<br/>
	 * 示例：$filterArray=array("id","commitTime");
	 * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
	 * @return xml内容
	 */
	public function toXml($isAll=true,$filterArray=null)
	{
		return UtilObject::object_to_xml($this,$filterArray,$isAll);
	}

	/**
	 * 将数据对象转换成Json类型格式
	 * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
	 * @return Json格式的数据格式的字符串。
	 */
	public function toJson($isAll=false)
	{
		return DataObjectFunc::toJson($this,$isAll);
	}

	/**
	 * 将数据对象转换成Array
	 * @param $isAll 是否对象所有的field都要生成，包括没有内容或者内容为空的field
	 * @return 数组
	 */
	public function toArray($isAll=true)
	{
		return UtilObject::object_to_array($this,$isAll);
	}
	//</editor-fold>

}
?>
