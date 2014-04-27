<?php
/**
 +---------------------------------<br/>
 * 使用PHP5的MySQLi Extension<br/>
 * 前提条件：<br/>
 *     PHP 5<br/>
 *     Mysql 4.1.3以上版本<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db.object
 * @subpackage mysql
 * @author skygreen
 */
class Dao_MysqlI5 extends Dao implements IDaoNormal
{
	/**
	 * 连接数据库
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname
	 * @return mixed 数据库连接
	 */
	public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null)
	{
		$connecturl=Config_Mysql::connctionurl($host,$port);

		if (!isset($username)){
			$username=Config_Mysql::$username;
		}
		if (!isset($password)){
			$password=Config_Mysql::$password;
		}
		if (!isset($dbname)){
			$dbname=Config_Mysql::$dbname;
		}
		$this->connection = new mysqli($connecturl,
				$username,$password,$dbname);

		if (mysqli_connect_errno()) {
			Exception_Mysqli::record();
		}

		if (strpos($this->character_set(),Config_C::CHARACTER_LATIN1)!==false||strpos($this->character_set(),Config_C::CHARACTER_GBK)!==false) {
			$this->change_character_set($character_code=Config_C::CHARACTER_UTF8);
		}
	}

	/**
	 * 执行预编译SQL语句<br/>
	 * 可以防止SQL注入黑客技术
	 */
	private function executeSQL()
	{
		try {
			if (Config_Db::$debug_show_sql){
				LogMe::log("SQL:".$this->sQuery);
				if (!empty($this->saParams)) {
					LogMe::log("SQL PARAM:".var_export($this->saParams, true));
				}
			}
			if (!empty($this->saParams)&&is_array($this->saParams)) {
				$this->sQuery=self::preparse_prepared($this->sQuery, $this->saParams);
			}

			$this->stmt  =$this->connection->prepare($this->sQuery);
			Exception_Mysqli::record();
			if (!empty($this->saParams)&&is_array($this->saParams)) {
				/*****************************************************************************
				 * START:执行预编译生成SQL语句
				 * 说明：
				 * 1.call_user_func_array需要传入的参数为Reference，而不是值；因此有下面一段特殊的代码
				 * 2.采用 call_user_func_array('mysqli_stmt_bind_param', $bind_params);
				 *   而不是$stmt->bind_param($bind_params[0],$bind_params[1]...), 是因为无法将数组分解
				 *   开来写；虽然可以通过函数知道传入参数数组的个数；但是无法通过循环语句调用该语句
				 * ***************************************************************************
				 */
				$i = 0;
				if (contain($this->sQuery,"?")){
					if (count($this->saParams)>0) {
						foreach ($this->saParams as $param) {
							$bind_name = 'bind' . $i++;
							$$bind_name = $param;
							$bind_params[] = &$$bind_name;
						}
						array_unshift($bind_params, self::getPreparedTypeString($this->saParams));
						array_unshift($bind_params, $this->stmt);
						if (is_object($bind_params[0])){
							call_user_func_array('mysqli_stmt_bind_param', $bind_params);
						}else{
							Exception_Mysqli::record(Wl::ERROR_INFO_DB_HANDLE);
						}
					}
				}
			}
			if ($this->stmt){
				$this->stmt->execute ();
			}
			Exception_Mysqli::record();
			/*END:执行预编译生成SQL语句******************************************************/
		} catch (Exception $ex) {
			Exception_Mysqli::record($ex->getTraceAsString());
		}
	}

	/**
	 *  直接执行SQL语句
	 * @param mixed $sql SQL查询|更新|删除语句
	 * @param string|class $object 需要生成注入的对象实体|类名称
	 * @return array
	 *  1.执行查询语句返回对象数组<br/>
	 *  2.执行更新和删除SQL语句返回执行成功与否的true|null
	 */
	public function sqlExecute($sqlstring,$object=null)
	{
		$result=null;
		try {
			if (Config_Db::$debug_show_sql){
				LogMe::log("SQL:".$sqlstring);
			}
			$this->stmt=$this->connection->prepare($sqlstring);
			Exception_Mysqli::record();
			if ($this->stmt){
				$this->stmt->execute();
				Exception_Mysqli::record();

				$parts = split(" ",trim($sqlstring));
				$type = strtolower($parts[0]);
				if((Crud_Sql_Update::SQL_KEYWORD_UPDATE==$type)||(Crud_Sql_Delete::SQL_KEYWORD_DELETE==$type)) {
					$this->stmt->free_result();
					$this->stmt->close();
					return true;
				}elseif (Crud_Sql_Insert::SQL_KEYWORD_INSERT==$type) {
					$autoId=$this->stmt->insert_id;
					$this->stmt->free_result();
					$this->stmt->close();
					return $autoId;
				}
				$result=$this->getResultToObjects($object);
				$sql_s=preg_replace("/\s/","",$sqlstring);
				$sql_s=strtolower($sql_s);
				if ((!empty($result))&&(!is_array($result))){
					if (!(contains($sql_s,array("count(","sum(","max(","min(","sum(")))){
						$tmp=$result;
						$result=null;
						$result[]=$tmp;
					}
				}
			}else{
			   Exception_Mysqli::record(Wl::ERROR_INFO_DB_HANDLE);
			}
		} catch (Exception $exc) {
			Exception_Mysqli::record($exc->getTraceAsString());
		}
		return $result;
	}

	/**
	 * 对象总计数
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param object|string|array $filter
	 *      $filter 格式示例如下：<br/>
	 *          0.允许对象如new User(id="1",name="green");<br/>
	 *          1."id=1","name='sky'"<br/>
	 *          2.array("id=1","name='sky'")<br/>
	 *          3.array("id"=>"1","name"=>"sky")<br/>
	 * @return 对象总计数
	 */
	public function count($object, $filter=null)
	{
		if (!$this->validParameter($object)) {
			return 0;
		}
		return $this->countMultitable($object,$this->classname,$filter);
	}

	/**
	 * 对象总计数[多表关联查询]
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param string|array $from 来自多张表或者多个类[必须是数据对象类名]，在from后的多张表名，表名之间以逗号[,]隔开
	 * 示例如下：<br/>
	 *      0."table1,table2"<br/>
	 *      1.array("table1","table2")<br/>
	 *      2."class1,class2"<br/>
	 *      3.array("class1","class2")<br/>
	 * @param object|string|array $filter
	 *      $filter 格式示例如下：<br/>
	 *          0.允许对象如new User(id="1",name="green");<br/>
	 *          1."id=1","name='sky'"<br/>
	 *          2.array("id=1","name='sky'")<br/>
	 *          3.array("id"=>"1","name"=>"sky")<br/>
	 * @return 对象总计数
	 */
	public function countMultitable($object,$from,$filter=null)
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
			$this->sQuery=$_SQL->select(Crud_Sql_Select::SQL_COUNT)->from($from)->where($this->saParams)->result();
			if (Config_Db::$debug_show_sql){
				LogMe::log("SQL:".$this->sQuery);
				if (!empty($this->saParams)) {
					LogMe::log("SQL PARAM:".var_export($this->saParams, true));
				}
			}
			$object_arr=$this->connection->query($this->sQuery);
			if ($object_arr){
				$row = $object_arr->fetch_row();
				$result=$row[0];
			}
			return $result;
		} catch (Exception $exc) {
			Exception_Mysqli::record($exc->getTraceAsString());
			return 0;
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
	 * 默认为 id desc<br/>
	 * 示例如下：<br/>
	 *      1.id asc;<br/>
	 *      2.name desc;<br/>
	 */
	public function queryPage($object,$startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		if (!$this->validParameter($object)) {
			return null;
		}
		return $this->queryPageMultitable($object,$startPoint,$endPoint,$this->classname,$filter,$sort);
	}

	/**
	 * 对象分页[多表关联查询]
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param int $startPoint  分页开始记录数
	 * @param int $endPoint    分页结束记录数
	 * @param object|string|array $filter 查询条件，在where后的条件
	 * 示例如下：<br/>
	 *      0."id=1,name='sky'"<br/>
	 *      1.array("id=1","name='sky'")<br/>
	 *      2.array("id"=>"1","name"=>"sky")<br/>
	 *      3.允许对象如new User(id="1",name="green");<br/>
	 * @param string|array $from 来自多张表或者多个类[必须是数据对象类名]，在from后的多张表名，表名之间以逗号[,]隔开
	 * 示例如下：<br/>
	 *      0."table1,table2"<br/>
	 *      1.array("table1","table2")<br/>
	 *      2."class1,class2"<br/>
	 *      3.array("class1","class2")<br/>
	 * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
	 * @param string $sort 排序条件
	 * 默认为 id desc<br/>
	 * 示例如下：<br/>
	 *      1.id asc;<br/>
	 *      2.name desc;<br/>
	 */
	public function queryPageMultitable($object,$startPoint,$endPoint,$from,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID)
	{
		try {
			if (!$this->validParameter($object)) {
				return null;
			}
			$_SQL=new Crud_Sql_Select();
			$_SQL->isPreparedStatement=true;
			$this->saParams=$_SQL->parseValidInputParam($filter);
			$_SQL->isPreparedStatement=false;
			if ($sort==Crud_SQL::SQL_ORDER_DEFAULT_ID){
				$realIdName=$this->sql_id($object);
				$sort=str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sort);
			}
			$this->sQuery=$_SQL->select()->from($from)->where($this->saParams)->order($sort)->limit($startPoint.",".($endPoint-$startPoint+1))->result();
			$result=$this->sqlExecute($this->sQuery,$object);
			return $result;
		} catch (Exception $exc) {
			Exception_Mysqli::record($exc->getTraceAsString());
			return null;
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
		if (is_object($this->stmt)){
			$this->stmt->store_result();
			if ($this->stmt->num_rows>0) {
				/* get resultset for metadata */
				$meta = $this->stmt->result_metadata();
				while ($field = $meta->fetch_field()) {
					$params[] = &$row[$field->name];
				}
				call_user_func_array(array($this->stmt, 'bind_result'), $params);
				$result=array();
				while ($this->stmt->fetch()) {
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
				$result=  $this->getValueIfOneValue($result);
			}
			$this->stmt->free_result();
			$this->stmt->close();
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
	 * 默认为 id desc<br/>
	 * 示例如下：<br/>
	 *      1.id asc;<br/>
	 *      2.name desc;<br/>
	 * @param string $limit 分页数目:同Mysql limit语法
	 * 示例如下：<br/>
	 *    0,10<br/>
	 * @return 列表:查询被列表的对象
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
			$filter_arr=$_SQL->parseValidInputParam($filter);
			if (is_array($filter_arr)&&count($filter_arr)>0){
				$this->saParams=$filter_arr;
			}else{
				$_SQL->isPreparedStatement=false;
			}
			$this->sQuery=$_SQL->select()->from($this->classname)->where($filter_arr)->order($sort)->limit($limit)->result();
			$this->executeSQL();
			$result=$this->getResultToObjects($object);
			return $result;
		} catch (Exception $exc) {
			Exception_Mysqli::record($exc->getTraceAsString());
		}
	}

	/**
	 * 查询得到单个对象实体
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param object|string|array $filter 过滤条件
	 *      $filter 格式示例如下：<br/>
	 *          0.允许对象如new User(id="1",name="green");<br/>
	 *          1."id=1","name='sky'"<br/>
	 *          2.array("id=1","name='sky'")<br/>
	 *          3.array("id"=>"1","name"=>"sky")<br/>
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
			$filter_arr=$_SQL->parseValidInputParam($filter);
			if (is_array($filter_arr)&&count($filter_arr)>0){
				$this->saParams=$filter_arr;
			}else{
				$_SQL->isPreparedStatement=false;
			}
			if ($sort==Crud_SQL::SQL_ORDER_DEFAULT_ID){
				$realIdName=$this->sql_id($object);
				$sort=str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sort);
			}
			$this->sQuery=$_SQL->select()->from($this->classname)->where($filter_arr)->order($sort)->limit("0,1")->result();
			$this->executeSQL();
			$result=$this->getResultToObjects($object);
			if (count($result)>=1) {
				$result=$result[0];
			}
			return $result;
		} catch (Exception $exc) {
			Exception_Mysqli::record($exc->getTraceAsString());
		}
	}

	/**
	 * 根据表ID主键获取指定的对象[ID对应的表列]
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param string $id 数据对象的唯一标识
	 * @return Object 对象
	 */
	public function get_by_id($object, $id)
	{
		$result=null;
		try {
			if (!$this->validParameter($object)) {
				return $result;
			}

			if (!empty($id)&&is_scalar($id)) {
				$_SQL=new Crud_Sql_Select();
				$where=$this->sql_id($object).self::EQUAL.$id;
				$this->saParams=null;
				$this->sQuery=$_SQL->select()->from($this->classname)->where($where)->result();
				$this->executeSQL();
				$result=$this->getResultToObjects($object);
				if (count($result)==1) {
					$result=$result[0];
				}
				return $result;
			}
		} catch (Exception $exc) {
			Exception_Mysqli::record($exc->getTraceAsString());
		}
	}

	/**
	 * 新建对象
	 * @param string|class $object 需要添加对象实体|对象名称【允许设置自定义ID】
	 * @return int 保存对象记录的ID标识号
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
			$object->setCommitTime(UtilDateTime::now());
			$this->saParams=UtilObject::object_to_array($object);
			//$this->saParams=$this->filterViewProperties($this->saParams);
			$this->sQuery=$_SQL->insert($this->classname)->values($this->saParams)->result();
			$this->executeSQL();
			if ($this->stmt){
				$autoId=$this->stmt->insert_id;
				$object->setId($autoId);
				$this->stmt->free_result ();
				$this->stmt->close();
			}else{
				 Exception_Mysqli::record(Wl::ERROR_INFO_DB_HANDLE);
			}
		} catch (Exception $exc) {
			Exception_Mysqli::record($exc->getTraceAsString());
		}
		return $autoId;
	}

	/**
	 * 删除对象
	 * @param string|class $object 需要删除对象实体|对象名称【对象内的属性即存在的条件】
	 * @return boolen 是否删除成功；true为操作正常
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
				$this->stmt=mysqli_prepare($this->connection,$this->sQuery);
				$this->stmt->execute ();
				$this->stmt->free_result ();
				$this->stmt->close();
				$result=true;
			} catch (Exception $exc) {
				Exception_Mysqli::record($exc->getTraceAsString());
			}
		}
		return $result;
	}

	/**
	 * 更新对象
	 * @param string|class $object 需要更新的对象实体|对象名称【Id是已经存在的】
	 * @return boolen 是否更新成功；true为操作正常
	 */
	public function update($object)
	{
		$result=false;
		if (!$this->validObjectParameter($object)) {
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
				$this->executeSQL();
				if ($this->stmt){
					$this->stmt->free_result ();
					$this->stmt->close();
					$result=true;
				}else{
					$result=false;
				}
			} catch (Exception $exc) {
				Exception_Mysqli::record($exc->getTraceAsString());
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
	 * 处理当传入参数为NULL的情况
	 * @param string $sQuery SQL
	 * @param array $saParams
	 * @return string
	 */
	private static function preparse_prepared($sQuery, &$saParams)
	{
		$nPos =0;
		$sRetval=$sQuery;
		foreach ($saParams as $x_Key =>$Param) {
			//if we find no more ?'s we're done then
			if (($nPos=strpos($sRetval, '?', $nPos + 1)) === false) {
				break;
			}

			//this test must be done second, because we need to increment offsets of $nPos for each ?.
			//we have no need to parse anything that isn't NULL.
			if (!is_null($Param)) {
				continue;
			}

			//null value, replace this ? with NULL.
			$sRetval=substr_replace($sRetval, 'NULL', $nPos, 1);
			//unset this element now
			unset($saParams[$x_Key]);
		}

		return $sRetval;
	}

	/**
	 * 获取mysqli_stmt_bind_param所需的参数类型<br/>
	 * 格式如下：<br/>
	 * 1.i corresponding variable has type integer<br/>
	 * 2.d corresponding variable has type double<br/>
	 * 3.s corresponding variable has type string<br/>
	 * 4.b corresponding variable is a blob and will be sent in packets<br/>
	 * @todo第四种情况b;大多数情况下不需要；需要再进行特定的编码<br/>
	 * 参数类型参考Mysql 5:mysqli_bind_param<br/>
	 * @param pointer $saParams
	 * @return string
	 */
	private static function getPreparedTypeString(&$saParams)
	{
		$sRetval='';
		//if not an array, or empty.. return empty string
		if (!is_array($saParams) || !count($saParams)) {
			return $sRetval;
		}
		//iterate the elements and figure out what they are, and append to result
		foreach ($saParams as $Param) {
			if (is_int($Param)) {
				$sRetval.='i';
			}
			else if (is_double($Param)) {
				$sRetval.='d';
			}
			else if (is_string($Param)) {
				$sRetval.='s';
			}
			else {
				$sRetval.='s';
			}
		}
		return $sRetval;
	}

	/**
	 * 设置数据库字符集
	 * @param string $character_code 字符集
	 */
	public function change_character_set($character_code=Config_C::CHARACTER_UTF8)
	{
		$sql = "set names ".$character_code;
		$this->connection->query($sql);
	}

	/**
	 * 显示数据库的字符集
	 */
	public function character_set()
	{
		$charset = $this->connection->character_set_name();
		return $charset;
//        echo Wl::INFO_DB_CHARACTER." {$charset}<br/>";
	}
}
?>
