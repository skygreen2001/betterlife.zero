<?php
/**
 +---------------------------------<br/>
 * 使用PHP5的Ms sql Extension:php_mssql<br/>
 * 主要操作Microsoft的Sql Server 2000数据库<br/>
 * 定义如下：<br/>
 * 通用SQL的执行<br/>
 * 单个对象【单张表】的增删改查<br/>
 * 列表对象的查询<br/>
 * 统计函数的执行<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db.object
 * @subpackage sqlserver
 * @author skygreen
 */
class Dao_Mssql extends Dao implements IDaoNormal{
	public static $fetchmode= MSSQL_ASSOC;  
	//MSSQL_ASSOC, MSSQL_NUM, and the default value of MSSQL_BOTH. 
	
	/**
	 * 连接数据库
	 * 说明：$dsn可以直接在System DSN里配置；然后在配置里设置：Config_Db::$dbname
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $password
	 * @param string $dbname 
	 * @return mixed 数据库连接
	 */
	public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null) { 
		$connecturl=Config_Mssql::connctionurl($host,$port);
	   
		if (!isset($username)){
			$username=Config_Mssql::$username;
		}
		if (!isset($password)){
			$password=Config_Mssql::$password;
		}
		if (!isset($dbname)){
			$dbname=Config_Mssql::$dbname;
		}        
		if (Config_Odbc::$is_persistent) {
			$this->connection =mssql_pconnect($connecturl, $username,$password);
		}else {
			$this->connection =mssql_connect($connecturl, $username,$password);
		}

		if (!$this->connection) {
			$errorinfo="错误原因:".mssql_get_last_message();
			Exception_Db::log(Wl::ERROR_INFO_CONNECT_FAIL.$errorinfo);
		}
		mssql_select_db($dbname,$this->connection);
	}     
	
	/**
	 * 执行预编译SQL语句      
	 */
	private function executeSQL() {
		if (!empty($this->sQuery)){         
			if (Config_Db::$debug_show_sql){                                    
				LogMe::log("SQL:".$this->sQuery);  
			}                        
			$this->result=mssql_query($this->sQuery);
			if (!$this->result) {
				$errorinfo="错误原因:".mssql_get_last_message();
				Exception_Db::log(Wl::ERROR_INFO_DB_HANDLE.$errorinfo);
			}
		}
	}    
	
	/**
	 * 将查询结果转换成业务层所认知的对象
	 * @param string $object 需要转换成的对象实体|类名称
	 * @return 转换成的对象实体列表
	 */
	private function getResultToObjects($object) {
		$result=array();
		if(!mssql_num_rows($this->result)){
		   return null;
		}

		while($row = mssql_fetch_array($this->result, self::$fetchmode)){    
			if (!empty($object)) {
				if ($this->validParameter($object)) {
					$c = UtilObject::array_to_object($row, $this->classname);
					$result[]=$c;
				}
			}else {
				$c=new stdClass();
				foreach($row as $key => $val) {
					$c->{$key} = $val;
				}
				$result[] = $c;
			}
		}
		$result=  $this->getValueIfOneValue($result);
		mssql_free_result($this->result);
		return $result;
	}
	
	/**
	 * 保存新建对象
	 * @param Object $object
	 * @return int 保存对象记录的ID标识号
	 */
	public function save($object){
		$autoId=-1;//新建对象插入数据库记录失败
		if (!$this->validObjectParameter($object)) {
			return $autoId;
		}
		try {
			$_SQL=new Crud_Sql_Insert();
			$object->setCommitTime(UtilDateTime::now(EnumDateTimeFormat::TIMESTAMP));
			if (Config_Db::$db==EnumDbSource::DB_SQLSERVER&&
					((trim(strtoupper(Gc::$encoding))==Config_C::CHARACTER_UTF_8)||(trim(strtolower(Gc::$encoding))==Config_C::CHARACTER_UTF8))) {
				$this->saParams=UtilObject::object_to_array($object,false,array(Config_C::CHARACTER_UTF_8=>Config_C::CHARACTER_GBK));
			}else {
				$this->saParams=UtilObject::object_to_array($object);
			}
			$this->sQuery=$_SQL->insert($this->classname)->values($this->saParams)->result();
			$this->executeSQL();
			$this->sQuery=Crud_SQL::SQL_SELECT." @@IDENTITY as id";
			$this->executeSQL();                   
			if($row=mssql_fetch_array($this->result,$this->fetchmode)){
				$autoId= $row["id"];
			}
		} catch (Exception $exc) {
			Exception_Db::log($exc->getMessage()."<br/>".$exc->getTraceAsString());
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
	public function delete($object){
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
				$this->executeSQL();
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
	public function update($object){
		$result=false;
		if (!$this->validObjectParameter($object)) {
			return $result;
		}

		$id=$object->getId();
		if(!empty($id)) {
			try {
				$_SQL=new Crud_Sql_Update();
				$_SQL->isPreparedStatement=false;
				$object->setUpdateTime(UtilDateTime::now(EnumDateTimeFormat::STRING));
				$object->setId(null);   
				if (Config_Db::$db==EnumDbSource::DB_SQLSERVER&&
						((trim(strtoupper(Gc::$encoding))==Config_C::CHARACTER_UTF_8)||(trim(strtolower(Gc::$encoding))==Config_C::CHARACTER_UTF8))) {
					$this->saParams=UtilObject::object_to_array($object,false,array(Config_C::CHARACTER_UTF_8=>Config_C::CHARACTER_GBK));
				}else {
					$this->saParams=UtilObject::object_to_array($object);
				}
				unset($this->saParams[DataObjectSpec::getRealIDColumnName($object)]);
				$this->filterViewProperties($this->saParams);
				$where=$this->sql_id($object).self::EQUAL.$id;
				$this->sQuery=$_SQL->update($this->classname)->set($this->saParams)->where($where)->result();
				$this->executeSQL();
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
	public function get($object, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null){
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
			$_SQL->isPreparedStatement=false;
			$this->sQuery=$_SQL->select()->from($this->classname)->where($filter_arr)->order($sort)->limit($limit)->result();
			$this->executeSQL();
			$result=$this->getResultToObjects($object);
			return $result;
		} catch (Exception $exc) {
			Exception_Db::record($exc->getTraceAsString());
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
	public function get_one($object, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID){
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
			$result=$this->getResultToObjects($object);
			if (count($result)>=1) {
				$result=$result[0];
			}
			return $result;
		} catch (Exception $exc) {
			Exception_Db::record($exc->getTraceAsString());
		}
		
	}

	/**
	 * 根据表ID主键获取指定的对象[ID对应的表列]
	 * @param string|class $object 需要查询的对象实体|类名称
	 * @param string $id
	 * @return 对象
	 */
	public function get_by_id($object, $id){
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
			Exception_Db::record($exc->getTraceAsString());
		}        
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
	public function sqlExecute($sqlstring,$object=null){
		$result=null;
		try {
			if (Config_Db::$db==EnumDbSource::DB_SQLSERVER&&((trim(strtoupper(Gc::$encoding))==Config_C::CHARACTER_UTF_8)||(trim(strtolower(Gc::$encoding))==Config_C::CHARACTER_UTF8))) {
			  if (UtilString::is_utf8($sqlstring)) { 
				 $sqlstring=UtilString::utf82gbk($sqlstring); 
			  }   
			}
			$this->sQuery=$sqlstring;            
			$this->executeSQL();
			$parts = split(" ",trim($sqlstring));
			$type = strtolower($parts[0]);
			if((Crud_Sql_Update::SQL_KEYWORD_UPDATE==$type)||(Crud_Sql_Delete::SQL_KEYWORD_DELETE==$type)) {
				return true;
			}elseif (Crud_Sql_Insert::SQL_KEYWORD_INSERT==$type) {
				$tablename=Crud_Sql_Insert::tablename($sqlstring);
				if (isset($tablename)){
					$object=Config_Db::tom($tablename);
					$realIdName=DataObjectSpec::getRealIDColumnName(new $object());
					$sql_maxid=Crud_SQL::SQL_MAXID;
					$sql_maxid=str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sql_maxid);               
					$this->sQuery = Crud_SQL::SQL_SELECT.$sql_maxid.Crud_SQL::SQL_FROM.$tablename;
					$this->executeSQL();                   
					$row=mssql_fetch_array($this->result,$this->fetchmode);
					if (isset($row)&&array_key_exists($realIdName, $row)){
					   $autoId= $row[$realIdName];
					   if (!empty($object)&&is_object($object)){ 
							$object->setId($autoId);//当保存返回对象时使用   
					   }  
					}  else {
					   $autoId=-1;
					}
				}else{
					$autoId=-1;
				}
				return $autoId;
			}
			$result=$this->getResultToObjects($object);
		} catch (Exception $exc) {
			Exception_Db::record($exc->getTraceAsString());
		}
		return $result;        
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
	public function count($object, $filter=null){
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
			$this->executeSQL();
			$row=mssql_fetch_array($this->result,MSSQL_NUM);
			if($row){
				$result =$row[0];
			}
			if (empty($result)){
				 return 0;
			}
			return $result;
		} catch (Exception $exc) {
			Exception_Db::record($exc->getTraceAsString());
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
	public function queryPage($object,$startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID){
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
			$tablename =Config_Mssql::orm($this->classname);            
			$whereclause=SqlServer_Crud_Sql_Select::pageSql($startPoint,$endPoint,$_SQL,$tablename,$this->saParams,$sort);
			$this->sQuery=$_SQL->from($this->classname)->where($whereclause)->order($sort)->result();
			$result=$this->sqlExecute($this->sQuery,$object);
			return $result;
		} catch (Exception $exc) {
			Exception_Db::record($exc->getTraceAsString());
		}
		
	}
}
?>
