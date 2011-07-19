<?php
/**
 +---------------------------------<br/>
 * Description of Dao_Postgres<br/>
 * 使用PHP5的Postgres Extension:php_pgsql<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db.object
 * @subpackage postgres
 * @author skygreen
 */
class Dao_Postgres extends Dao implements IDaoNormal {
    /**
     * 连接数据库
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @return string 数据库连接
     */
    public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null) {
        if (!isset($host)){
            $host=Config_Postgres::$host;
        }
        if (!isset($port)){
            $port=Config_Postgres::$port;
        }
        if (!isset($username)){
            $username=Config_Postgres::$username;
        }
        if (!isset($password)){
            $password=Config_Postgres::$password;
        }
        if (!isset($dbname)){
            $dbname=Config_Postgres::$dbname;
        }
        
        if (Config_Postgres::$is_persistent) {
            $this->connection = pg_pconnect("host=".$host." port=".$port." dbname=".$dbname." user=".$username." password=".$password);
        }else {
            $this->connection = pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$username." password=".$password);
        }

        if (!$this->connection) {
            Exception_Db::log(Wl::ERROR_INFO_CONNECT_FAIL);
        }
    }

    /**
     * 执行预编译SQL语句
     * 无法防止SQL注入黑客技术
     */
    private function executeSQL() {
        $this->result=pg_query($this->connection,$this->sQuery);
        if (!$this->result) {
            Exception_Db::log(Wl::ERROR_INFO_DB_HANDLE);
        }
    }

    /**
     * 将查询结果转换成业务层所认知的对象
     * @param string $object 需要转换成的对象实体|类名称
     * @return 转换成的对象实体列表
     */
    private function getResultToObjects($object) {
        $result=array();
        while ($currentrow = pg_fetch_array($this->result,NULL,Config_Postgres::$fetchmode)) {
            if (!empty($object)) {
                if ($this->validParameter($object)) {
                    $c = UtilObject::array_to_object($currentrow, $this->classname);
                    $result[]=$c;
                }
            }else {
                $c=new stdClass();
                foreach($currentrow as $key => $val) {
                    $c->{$key} = $val;
                }
                $result[] = $c;
            }
        }
        if (count($result)==0) {
            $result=null;
        }
        $result=  $this->getValueIfOneValue($result);
        pg_free_result($this->result);
        return $result;
    }
    

    /**
     * 新建对象
     * @param Object $object
     * @return int 保存对象记录的ID标识号
     */
    public function save($object) {
        $autoId=-1;//新建对象插入数据库记录失败
        if (!$this->validObjectParameter($object)) {
            return $autoId;
        }
        try {
            $_SQL=new Crud_Sql_Insert();
            $_SQL->type_rep=1;
            $_SQL->isPreparedStatement=true;
            $object->setCommitTime(UtilDateTime::now());
            $this->saParams=UtilObject::object_to_array($object);
            $this->filterViewProperties($this->saParams);
            foreach ($this->saParams as $key=>&$value) {
                $value=$this->escape($value);
            }
            $this->sQuery=$_SQL->insert($this->classname)->values($this->saParams,1)->result();
            $this->result=pg_prepare($this->connection,"insert_query",$this->sQuery." RETURNING id");
            $this->result=pg_execute($this->connection,"insert_query",$this->saParams);
            $row = pg_fetch_row($this->result);
            if (!empty($row)&&is_array($row)) {
                $autoId=$row[0];
            }
            pg_free_result($this->result);
        } catch (Exception $exc) {
            Exception_Db::log($exc->getTraceAsString());
        }
        if (empty($object)&&is_object($object)){
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
    public function delete($object) {
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
                $result = pg_query($this->connection,$this->sQuery);
                pg_free_result($result);
                $result = true;
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
    public function update($object) {
        $result=false;
        if (!$this->validObjectParameter($object)) {
            return $result;
        }

        $id=$object->getId();
        if(!empty($id)) {
            try {
                $_SQL=new Crud_Sql_Update();
                $_SQL->type_rep=1;
                $object->setUpdateTime(UtilDateTime::now());
                $object->setId(null);
                $this->saParams=UtilObject::object_to_array($object,true);
                unset($this->saParams[DataObjectSpec::getRealIDColumnName($object)]);
                $this->filterViewProperties($this->saParams);
                $where=$this->sql_id($object).self::EQUAL.$id;
                foreach ($this->saParams as $key=>&$value) {
                    $value=$this->escape($value);
                }
                $this->sQuery=$_SQL->update($this->classname)->set($this->saParams)->where($where)->result();
                $this->result=pg_prepare($this->connection,"update_query",$this->sQuery);
                $this->result=pg_execute($this->connection,"update_query",$this->saParams);
                pg_free_result($this->result);
                $result =true;
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
     * @param string $filter 查询条件，在where后的条件
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
    public function get($object, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null) {
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
     * @return 单个对象实体
     */
    public function get_one($object, $filter=null) {
        $result=null;
        try {
            if (!$this->validParameter($object)) {
                return $result;
            }

            $_SQL=new Crud_Sql_Select();
            $_SQL->isPreparedStatement=true;     
            $this->saParams=$_SQL->parseValidInputParam($filter);
            $_SQL->isPreparedStatement=false;     
            $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID;            
            $realIdName=$this->sql_id($object);
            $sort=str_replace(Crud_SQL::SQL_FLAG_ID, $realIdName, $sort);
            $this->sQuery=$_SQL->select()->from($this->classname)->where($this->saParams)->order($sort)->result();
            $this->executeSQL();
            $result=$this->getResultToObjects($object);
            if (count($result)>=1) {
                $result=$result[0];
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
    public function get_by_id($object, $id) {
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
    public function sqlExecute($sql,$object=null) {
        $parts = split(" ",trim($sql));
        $type = strtolower($parts[0]);
        if((Crud_Sql_Update::SQL_KEYWORD_UPDATE==$type)||(Crud_Sql_Delete::SQL_KEYWORD_DELETE==$type)) {
            $this->result=pg_query($this->connection,$sql);
            pg_free_result($this->result);
            return true;
        }elseif (Crud_Sql_Insert::SQL_KEYWORD_INSERT==$type) {
            if (strpos($sql,"RETURNING")!==false) {
                $addfoot_sql="";
            }else {
                $addfoot_sql=" RETURNING id";
            }
            $this->result=pg_query($this->connection,$sql.$addfoot_sql);
            $row = pg_fetch_row($this->result);
            if (!empty($row)&&is_array($row)) {
                $autoId=$row[0];
            }
            pg_free_result($this->result);
            return $autoId;
        }
        $this->result = pg_query($this->connection,$sql);
        $result=$this->getResultToObjects($object);
        if (is_array($result)&&count($result)==1) {
            $result=$result[0];
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
    public function count($object, $filter=null) {
        $result=0;
        try {
            if (!$this->validParameter($object)) {
                return 0;
            }
            $_SQL=new Crud_Sql_Select();
            $_SQL->isPreparedStatement=true;
            $this->saParams=$_SQL->parseValidInputParam($filter);
            $_SQL->isPreparedStatement=false;
            $this->sQuery=$_SQL->select(Crud_Sql_Select::SQL_COUNT)->from($this->classname)->where($this->saParams)->result();
            $this->result = pg_query($this->connection,$this->sQuery);
            $row = pg_fetch_row($this->result);
            if (!empty($row)&&is_array($row)) {
                $result=$row[0];
                $result=intval($result);
            }
            pg_free_result($this->result);
            return $result;
        } catch (Exception $exc) {
            Exception_Db::record($exc->getTraceAsString());
        }
    }

    /**
     * 对象分页
     * @param string|class $object 需要查询的对象实体|类名称
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
    public function queryPage($object,$startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID) {
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
            $this->sQuery=$_SQL->select()->from($this->classname)->where($this->saParams)->order($sort)->limit($endPoint-$startPoint+1)->offset($startPoint)->result();
            $result=$this->sqlExecute($this->sQuery,$object);
            return $result;
        } catch (Exception $exc) {
            Exception_Db::record($exc->getTraceAsString());
        }
    }

    public function transBegin() {
        return @pg_exec($this->connection, "BEGIN");
        return TRUE;
    }
    public function transCommit() {
        return @pg_exec($this->connection, "COMMIT");
        return TRUE;
    }
    public function transRollback() {
        return @pg_exec($this->connection, "ROLLBACK");
        return TRUE;
    }

    public function escape($sql) {
        if (function_exists('pg_escape_string')) {
            return pg_escape_string( $sql);
        }
        else {
            return addslashes($sql);
        }
    }

}
?>
