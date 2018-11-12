<?php
/**
 +---------------------------------<br/>
 * 实现mdb2通用的DAL访问方式
 * @see http://pear.php.net/package/MDB2
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db.dal
 * @subpackage mdb2
 * @author skygreen
 */
class Dal_Mdb2 extends Dal implements IDal
{
	/**
	 * @var enum 当前使用的数据类型
	 */
	private $dbtype;
	/**
	 * 连接数据库
	 * @global string $ADODB_FETCH_MODE
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
	 * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
	 * @return mixed 数据库连接
	 */
	public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null)
	{
		if (!isset($username)){
			$username=Config_Mdb2::$username;
		}
		if (!isset($password)){
			$password=Config_Mdb2::$password;
		}
		if (!isset($dbname)){
			$dbname=Config_Mdb2::$dbname;
		}
		if (!isset($dbtype)){
		   $dbtype=Config_Mdb2::$db;
		}
		$this->dbtype=$dbtype;
		try{
			$this->connection=&MDB2::connect(Config_Mdb2::dsn($host,$port,$username,$password,$dbname,$dbtype), Config_Mdb2::$options);
			if (PEAR::isError($this->connection)) {
				die($this->connection->getMessage());
			}

			if ($dbtype==EnumDbSource::DB_MYSQL) {
			   $this->change_character_set($character_code=Config_C::CHARACTER_UTF8);
			}
			if (!$this->connection) {
				Exception_Db::log(Wl::ERROR_INFO_CONNECT_FAIL);
			}
		}catch (Exception $e) {
			Exception_Db::log($e->getMessage());
		}
	}

	/**
	 * 执行预编译SQL语句
	 * 可以防止SQL注入黑客技术
	 */
	private function executeSQL()
	{
		try {
			if (Config_Db::$debug_show_sql){
				LogMe::log("SQL:".$this->sQuery);
			}
			$columnCount=0;
			$this->stmt = &$this->connection->query($this->sQuery);
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}
	}


	/**
	 * 将查询结果转换成业务层所认知的对象
	 * @param string $object 需要转换成的对象实体|类名称
	 * @return 转换成的对象实体列表
	 */
	private function getResultToObjects($object)
	{
		$result=null;
		$rows=$this->stmt->fetchAll(Config_Mdb2::$fetchmode);
		foreach ($rows as $row) {
			if (!empty($object)) {
				if ($this->validParameter($object)) {
					$c = UtilObject::array_to_object($row, $this->classname);
					$result[]=$c;
				}
			}else {
				if (count($row)==1){
					foreach($row as $key => $val) {
						$result[] = $val;
					}
				}else{
					$c=new stdClass();
					foreach($row as $key => $val) {
						$c->{$key} = $val;
					}
					$result[] = $c;
				}
			}
		}
		$result=$this->getValueIfOneValue($result);
		return $result;
	}

	/**
	 *  直接执行SQL语句
	 *
	 * @param mixed $sql SQL查询|更新|删除语句
	 * @param string|class $object 需要生成注入的对象实体|类名称
	 * @return array
	 *  1.执行查询语句返回对象数组
	 *  2.执行更新和删除SQL语句返回执行成功与否的true|null
	 */
	public function sqlExecute($sql,$object=null)
	{
		$result=null;
		try {
			$parts = explode(" ",trim($sql));
			$type = strtolower($parts[0]);

			if (Config_Db::$debug_show_sql){
				LogMe::log("SQL:".$sql);
			}
			if((Crud_Sql_Update::SQL_KEYWORD_UPDATE==$type)||(Crud_Sql_Delete::SQL_KEYWORD_DELETE==$type)) {
				$this->connection->exec($sql);
				return true;
			}elseif (Crud_Sql_Insert::SQL_KEYWORD_INSERT==$type) {
				$this->connection->exec($sql);
				$autoId=$this->connection->lastInsertId();
				return $autoId;
			}
			$this->stmt=$this->connection->query($sql);
			$result=$this->getResultToObjects($object);
			$sql_s=preg_replace("/\s/","",$sql);
			$sql_s=strtolower($sql_s);
			if ((!empty($result))&&(!is_array($result))){
				if (!(contains($sql_s,array("count(","sum(","max(","min(","sum(")))){
					$tmp=$result;
					$result=null;
					$result[]=$tmp;
				}
			}
			return $result;
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}

	}

	/**
	 * 新建对象
	 * @param Object $object
	 * @return Object
	 */
	public function save($object)
	{
		$autoId=-1;//新建对象插入数据库记录失败
		if (!$this->validObjectParameter($object)) {
			return $autoId;
		}
		try {
			$_SQL=new Crud_Sql_Insert();
			$_SQL->isPreparedStatement=true;
			$object->setCommitTime(UtilDateTime::now(EnumDateTimeFormat::TIMESTAMP));
			$this->saParams=UtilObject::object_to_array($object);
			$this->sQuery=$_SQL->insert($this->classname)->values($this->saParams)->result();
			 if (!empty($this->saParams)) {
				$type=array_values($this->getColumnTypes($object,$this->saParams,2));
				if (Config_Db::$debug_show_sql){
					LogMe::log("SQL:".$this->sQuery);
					if (!empty($this->saParams)) {
						LogMe::log("SQL PARAM:".var_export($this->saParams, true));
					}
				}
				$sth=$this->connection->prepare($this->sQuery, $type,MDB2_PREPARE_MANIP);
				$sth->execute(array_values($this->saParams));
			}
			$autoId=$this->connection->lastinsertid();
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}
		if (!empty($object)&&is_object($object)){
		  $object->setId($autoId);//当保存返回对象时使用
		}
		return $autoId;
	}


		/**
	 * 删除对象
	 * @param string $classname
	 * @param int $id
	 * @return Object
	 */
	public function delete($object)
	{
		$result=false;
		if (!$this->validObjectParameter($object)) {
			return $result;
		}

		$id=$object->getId();
		if (!empty($id)) {
			try {
				$_SQL=new Crud_Sql_Delete();
				$where=$this->sql_id($object).self::EQUAL.$id;
				$this->sQuery=$_SQL->deletefrom($this->classname)->where($where)->result();
				if (Config_Db::$debug_show_sql){
					LogMe::log("SQL:".$this->sQuery);
				}
				$this->connection->exec($this->sQuery);
				$result=true;
			} catch (Exception $exc) {
				Exception_Db::log($exc->getTraceAsString());
			}
		}
		return $result;
	}

	/**
	 * 更新对象
	 * @param int $id
	 * @param Object $object
	 * @return Object
	 */
	public function update($object)
	{
		$result=false;
		if (!$this->validObjectParameter($object)){
			return $result;
		}
		$id=$object->getId();
		if(!empty($id)) {
			try {
				$_SQL=new Crud_Sql_Update();
				$object->setUpdateTime(UtilDateTime::now(EnumDateTimeFormat::STRING));
				$this->saParams=UtilObject::object_to_array($object);
				unset($this->saParams[DataObjectSpec::getRealIDColumnName($object)]);
				$this->saParams=$this->filterViewProperties($this->saParams);
				$where=$this->sql_id($object).self::EQUAL.$id;
				$this->sQuery=$_SQL->update($this->classname)->set($this->saParams)->where($where)->result();
				if (Config_Db::$debug_show_sql){
					LogMe::log("SQL:".$this->sQuery);
					if (!empty($this->saParams)) {
						LogMe::log("SQL PARAM:".var_export($this->saParams, true));
					}
				}
				if (!empty($this->saParams)) {
					$type=array_values($this->getColumnTypes($object,$this->saParams,2));
					$sth=$this->connection->prepare($this->sQuery, $type,MDB2_PREPARE_MANIP);
					$sth->execute(array_values($this->saParams));
				}
				$result=true;
			} catch (Exception $exc) {
				Exception_Db::log($exc->getTraceAsString());
				$result=false;
			}
		}else {
			e(Wl::ERROR_INFO_UPDATE_ID,$this);
		}
		return $result;
	}

	/**
	 * 保存或更新当前对象
	 * @param Object $dataobject
	 * @return boolen|int 更新:是否更新成功；true为操作正常|保存:保存对象记录的ID标识号
	 */
	public function saveOrUpdate($dataobject)
	{
		$id=$dataobject->getId();
		if (isset($id)){
			$result=$this->update($dataobject);
		}else{
			$result=$this->save($dataobject);
		}
		return $result;
	}

	/**
	 * 根据对象实体查询对象列表
	 * @param string $object 需要查询的对象实体|类名称
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *      0."id=1,name='sky'"<br/>
	 *      1.array("id=1","name='sky'")<br/>
	 *      2.array("id"=>"1","name"=>"sky")<br/>
	 *      3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件
	 * 示例如下：
	 *      1.id asc;
	 *      2.name desc;
	 * @param string $limit 分页数目:同Mysql limit语法
	 * 示例如下：
	 *    0,10
	 * @return 对象列表数组
	 */
	public function get($object, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null)
	{
		$result=null;
		try {
			if (!$this->validParameter($object)) {
				return $result;
			}
			$_SQL=new Crud_Sql_Select();
			if ($sort==Crud_SQL::SQL_ORDER_DEFAULT_ID){
				$realIdName=$this->sql_id($object);
				$sort=str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sort);
			}
			$_SQL->isPreparedStatement=true;
			$this->saParams=$_SQL->parseValidInputParam($filter);
			$_SQL->isPreparedStatement=false;
			$this->sQuery=$_SQL->select()->from($this->classname)->where($this->saParams)->order($sort)->limit($limit)->result();
			$this->executeSQL();
			$result=$this->getResultToObjects($object);
			return $result;
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}
	}

	/**
	 * 查询得到单个对象实体
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *      0."id=1,name='sky'"<br/>
	 *      1.array("id=1","name='sky'")<br/>
	 *      2.array("id"=>"1","name"=>"sky")<br/>
	 *      3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件
	 * 示例如下：
	 *      1.id asc;
	 *      2.name desc;
	 * @return 单个对象实体
	 */
	public function get_one($object, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		$result=null;
		try {
			if (!$this->validParameter($object)) {
				return $result;
			}
			$_SQL=new Crud_Sql_Select();
			$_SQL->isPreparedStatement=true;
			$this->saParams=$_SQL->parseValidInputParam($filter);
			$_SQL->isPreparedStatement=false;
			if ($sort==Crud_SQL::SQL_ORDER_DEFAULT_ID){
				$realIdName=$this->sql_id($object);
				$sort=str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sort);
			}
			$this->sQuery=$_SQL->select()->from($this->classname)->where($this->saParams)->order($sort)->result();
			$this->executeSQL();
			$row=$this->stmt->fetchRow(Config_Mdb2::$fetchmode);
			if (isset($row)) {
				$result = UtilObject::array_to_object($row, $this->classname);
			}
			return $result;
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}
	}

	/**
	 * 根据表ID主键获取指定的对象[ID对应的表列]
	 * @param string $classname
	 * @param string $id
	 * @return 对象
	 */
	public function get_by_id($object, $id)
	{
		$result=null;
		try {
			if (!$this->validParameter($object)) {
				return $result;
			}

			if ($id!=null&&$id>0) {
				$_SQL=new Crud_Sql_Select();
				$where=$this->sql_id($object).self::EQUAL.$id;
				$this->sQuery=$_SQL->select()->from($this->classname)->where($where)->result();
				$this->executeSQL();
				$row=$this->stmt->fetchRow(Config_Mdb2::$fetchmode);
				if (isset($row)) {
					$result = UtilObject::array_to_object($row, $this->classname);
				}
			}
			return $result;
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}
	}

	/**
	 *  直接执行SQL语句
	 *
	 * @param mixed $sql SQL查询语句
	 * @param string|class $object 需要生成注入的对象实体|类名称
	 * @return array 返回数组
	 */
	public function sqlQuery($sql,$object)
	{

	}


	/**
	 * 对象总计数
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *      0."id=1,name='sky'"<br/>
	 *      1.array("id=1","name='sky'")<br/>
	 *      2.array("id"=>"1","name"=>"sky")<br/>
	 *      3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @return 对象总计数
	 */
	public function count($object, $filter=null)
	{
		$result=null;
		try {
			if (!$this->validParameter($object)) {
				return 0;
			}
			$_SQL=new Crud_Sql_Select();
			$_SQL->isPreparedStatement=true;
			$this->saParams=$_SQL->parseValidInputParam($filter);
			$_SQL->isPreparedStatement=false;
			$this->sQuery=$_SQL->select(Crud_Sql_Select::SQL_COUNT)->from($this->classname)->where($this->saParams)->result();
			if (Config_Db::$debug_show_sql){
				LogMe::log("SQL:".$this->sQuery);
				if (!empty($this->saParams)) {
					LogMe::log("SQL PARAM:".var_export($this->saParams, true));
				}
			}
			$this->stmt=&$this->connection->query($this->sQuery);
			$result=$this->stmt->fetchOne();
			return $result;
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}
	}

	/**
	 * 对象分页
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param int $startPoint  分页开始记录数
	 * @param int $endPoint    分页结束记录数
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *      0."id=1,name='sky'"<br/>
	 *      1.array("id=1","name='sky'")<br/>
	 *      2.array("id"=>"1","name"=>"sky")<br/>
	 *      3.允许对象如new User(id="1",name="green");<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件
	 * 默认为 id desc
	 * 示例如下：
	 *      1.id asc;
	 *      2.name desc;
	 */
	public function queryPage($object,$startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		try {
			if(($startPoint>$endPoint)||($endPoint==0))return null;
			if (!$this->validParameter($object))return null;

			$_SQL=new Crud_Sql_Select();
			$_SQL->isPreparedStatement=true;
			$this->saParams=$_SQL->parseValidInputParam($filter);
			$_SQL->isPreparedStatement=false;
			if ($sort==Crud_SQL::SQL_ORDER_DEFAULT_ID){
				$realIdName=$this->sql_id($object);
				$sort=str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sort);
			}
			if (Config_Db::$db==EnumDbSource::DB_MYSQL) {
				$this->sQuery=$_SQL->select()->from($this->classname)->where($this->saParams)->order($sort)->limit($startPoint.",".($endPoint-$startPoint+1))->result();
			}else if (Config_Db::$db==EnumDbSource::DB_MICROSOFT_ACCESS) {
				$whereclause=SqlServer_Crud_Sql_Select::pageSql($startPoint,$endPoint,$_SQL,$tablename,$this->saParams,$sort);
				$this->sQuery=$_SQL->select()->from($this->classname)->where($whereclause)->order($sort)->result();
			}else {
				$this->sQuery=$_SQL->select()->from($this->classname)->where($this->saParams)->order($sort)->limit($startPoint.",".($endPoint-$startPoint+1))->result();
			}
			$result=$this->sqlExecute($this->sQuery,$object);
			return $result;
		} catch (Exception $exc) {
			Exception_Db::log($exc->getTraceAsString());
		}
	}
}
?>
