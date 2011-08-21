<?php
/**
 +---------------------------------<br/>
 * PHP5自带的SQL数据库Sqlite<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db.object
 * @subpackage sqlite
 * @author skygreen
 */
class Dao_Sqlite2 extends Dao implements IDaoNormal {
    /**
     * 连接数据库
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @return mixed 数据库连接
     */
    public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null) {
        if (!isset($dbname)){
            $dbname=Config_Sqlite::$dbname;
        }
        
        if (Config_Sqlite::$is_persistent) {
            $this->connection =
                    sqlite_popen($dbname,0666,$errormessage);
        }else {
            $this->connection =
                    sqlite_open($dbname,0666,$errormessage);
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
        if (Config_Db::$debug_show_sql){
            echo "SQL:".$this->sQuery."<br />";  
        }             
        $this->stmt=sqlite_query($this->sQuery,$this->connection);
    }

    /**
     * 将查询结果转换成业务层所认知的对象
     * @param string $object 需要转换成的对象实体|类名称
     * @return 转换成的对象实体列表
     */
    private function getResultToObjects($object) {
        $result=null;        
        while ($currentrow = sqlite_fetch_array($this->stmt, Config_Sqlite::$sqlite2_fetchmode)) {
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
        $result=  $this->getValueIfOneValue($result);
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
            $object->setCommitTime(UtilDateTime::now(EnumDateTimeFormat::STRING));
            $this->saParams=UtilObject::object_to_array($object);
            $this->filterViewProperties($this->saParams);
            $this->sQuery=$_SQL->insert($this->classname)->values($this->saParams)->result();
            if (Config_Db::$debug_show_sql){
                echo "SQL:".$this->sQuery."<br />";  
            }                         
            $result = sqlite_exec($this->connection,  $this->sQuery, $error);
            if (!$result) {
                Exception_Db.log($error);
            }
            $autoId=@sqlite_last_insert_rowid($this->connection);
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
                if (Config_Db::$debug_show_sql){
                    echo "SQL:".$this->sQuery."<br />";  
                }                             
                $result = sqlite_exec($this->connection,  $this->sQuery, $error);
                if (!$result) {
                    Exception_Db.log($error);
                }
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
                $_SQL->isPreparedStatement=false;
                $object->setUpdateTime(UtilDateTime::now(EnumDateTimeFormat::STRING));
                $this->saParams=UtilObject::object_to_array($object);
                unset($this->saParams[DataObjectSpec::getRealIDColumnName($object)]);
                $this->filterViewProperties($this->saParams);
                $where=$this->sql_id($object).self::EQUAL.$id;
                $this->sQuery=$_SQL->update($this->classname)->set($this->saParams)->where($where)->result();
                if (Config_Db::$debug_show_sql){
                    echo "SQL:".$this->sQuery."<br />";  
                }                             
                $result = sqlite_exec($this->connection,  $this->sQuery, $error);
                if (!$result) {
                    Exception_Db.log($error);
                }
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
     * @param string $join  关联表:同Mysql join语法
     * @todo 将类名转换成表名
     * 示例如下：
     *    LEFT JOIN `Category` ON `Category`.ID = `Episode`.ID
     * @param string $limit 分页数目:同Mysql limit语法
     * 示例如下：
     *    0,10
     * @return 对象列表数组
     */
    public function  get($object, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID, $limit=null) {
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
    public function sqlExecute($sqlstring,$object=null) {
        $result=null;
        try {
            $this->sQuery=$sqlstring;
            if (Config_Db::$debug_show_sql){
                echo "SQL:".$this->sQuery."<br />";  
            }                         
            $this->executeSQL();

            $parts = split(" ",trim($sqlstring));
            $type = strtolower($parts[0]);
            if((Crud_Sql_Update::SQL_KEYWORD_UPDATE==$type)||(Crud_Sql_Delete::SQL_KEYWORD_DELETE==$type)) {
                return true;
            }elseif (Crud_Sql_Insert::SQL_KEYWORD_INSERT==$type) {
                $autoId=@sqlite_last_insert_rowid($this->connection);
                return $autoId;
            }
            $result=$this->getResultToObjects($object);
        } catch (Exception $exc) {
            Exception_Db::log($exc->getTraceAsString());
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
            $result=sqlite_fetch_single($this->stmt);
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
            $this->sQuery=$_SQL->select()->from($this->classname)->where($this->saParams)->order($sort)->limit($startPoint.",".($endPoint-$startPoint+1))->result();
            $result=$this->sqlExecute($this->sQuery,$object);
            return $result;
        } catch (Exception $exc) {
            Exception_Db::record($exc->getTraceAsString());
        }
    }

    public function escape($sql) {
        if (function_exists('sqlite_escape_string')) {
            return sqlite_escape_string($sql);
        }
        else {
            return addslashes($sql);
        }
    }

    public function transBegin() {
        $this->execute('BEGIN TRANSACTION');
    }

    public function transCommit() {
        $this->execute('COMMIT');
    }

    public function transRollback() {
        $this->execute('COMMIT');
    }
}
?>
