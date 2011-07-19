<?php
/**
 +---------------------------------<br/>
 * 使用PHP5自带的MySQL Extension <br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db.sql
 * @subpackage mysql
 * @author skygreen
 */
class Sql_Mysql extends Sql implements ISqlNormal {
    /**
     * @var mixed 数据库连接 
     */
    private $connection;

    /**
     * 连接数据库
     * @param type $host
     * @param type $port
     * @param type $username
     * @param type $password
     * @param type $dbname 
     * @return mixed 数据库连接
     */
    public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null) {
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
        $this->connection = mysql_connect($connecturl,
                $username,$password);
        mysql_select_db($dbname,$this->connection);
    }

    /**
     * 新增一条数据记录
     * @param array $data 数据数组
     * @return int 返回插入数据的ID编号
     * 示例：
     *     $db=new Sql_Mysql();
     *     $data = array("name"=>"skygreen","pass"=>md5("hello world"));
     *     $result = $this->Db->insertData($data);
     *     其中 name,pass是表列名，"skygreen",md5("hello world"))是列值，与列名一一对应。
     */
    public function insertData($tablename,$data) {
        $fields = join(array_keys($data),",");
        $values = "'".join(array_values($data),",")."'";
        $query = Crud_SQL::SQL_INSERT.$tablename." ({$fields})".Crud_SQL::SQL_INSERT_VALUE." ({$values})";
        return mysql_query($query, $this->connection);
    }

    /**
     * 删除一条数据记录
     * @param int $sql_id 需删除数据的ID编号Sql语句<br/>     
     * 示例如下：<br/>
     *     $sql_id:<br/>
     *         user_id=1<br/>
     * @param string $tablename 表名称<br/>
     * 示例如下：<br/>
     *     $tablename:<br/>
     *         $db=new Sql_Mysql();<br/>
     *         $result =$db ->deleteData(1);<br/>
     * @return boolean:是否删除成功
     */
    public function deleteData($tablename,$sql_id) {
        $query = Crud_SQL::SQL_DELETE.Crud_SQL::SQL_FROM.$tablename.Crud_SQL::SQL_WHERE.$sql_id;
        return mysql_query($query, $this->connection);
    }

    /**
     * 修改一条数据记录
     * @param int $sql_id 需删除数据的ID编号Sql语句<br/>     
     * 示例如下：<br/>
     *     $sql_id:<br/>
     *         user_id=1<br/>
     * @param array $data 数据数组
     * @return boolean:是否修改成功
     * 示例：
     *      $db=new Sql_Mysql();
     *      $data = array("name"=>"afif2","pass"=>md5("hello world"));
     *      $result = $db->updateData(1, $data);
     */
    public function updateData($tablename,$sql_id, $data) {
        $queryparts = array();
        foreach ($data as $key=>$value) {
            $queryparts[] = "{$key} = '{$value}'";
        }
        $query =Crud_SQL::SQL_UPDATE.$tablename.Crud_SQL::SQL_SET .join($queryparts,",").
                Crud_SQL::SQL_WHERE.$sql_id;
        return mysql_query($query, $this->connection);
    }

    /**
     * 直接执行SQL语句
     * @param string $sqlstring SQL语句
     */
    public function sqlExectue($sqlstring) {
        return mysql_query($sqlstring, $this->connection);
    }
}
?>
