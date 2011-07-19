<?php
/**
 +-------------------------------------<br/>
 * 获取Mysql数据库信息。
 +-------------------------------------<br/>
 * @category betterlife
 * @package core.db.info
 * @subpackage mysql
 * @author skygreen
 */
class DbInfo_Mysql extends  DbInfo implements IDbInfo {
    private $mysqlVersion;
    /**
     * @var string 获取数据库信息的数据库名称
     */
    public $dbname_info="information_schema";
    /**
     * 是否使用获取数据库信息的数据库
     * @var bool 
     */
    public static $isUseDbInfoDatabase=false;
    /**
     * 连接数据库
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
     * @return mixed 数据库连接
     */
    public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null,$engine=null) {
        $this->connection = Manager_Db::newInstance()->object_mysql_php5($host,$port,$username,$password,$dbname)->getConnection();
    }

    /**
     * 设置数据库字符集
     * @param string $character_code 字符集
     */
    public function change_character_set($character_code='UTF8'){
       $sql = "set names ".$character_code;
       $result =  mysql_query($sql,$this->connection);
    }

    /**
     * 显示数据库的字符集
     */
    public function character_set() {
        $sql = "SHOW VARIABLES LIKE '%character%'";
        $result =  mysql_query($sql,$this->connection);
        if(!$result) {
            echo "ERROR : ".mysql_error($this->connection)."<br>";
            return;
        }else {
            UtilCss::report_info();
            echo "SQL> {$sql}; <br>";
            echo "<table class='".UtilCss::CSS_REPORT_TABLE."' border=1><thead><tr><th> Variable_name</th>"
                    ."<th> Value</th></tr></thead>";
            while ($row = mysql_fetch_assoc($result)) {
                echo "<tr><td>{$row['Variable_name']}</td><td>{$row['Value']}</td></tr>";
            }
            echo "</table><br>";
        }
    }

    /**
     * 获取数据库的版本信息
     * @return float
     */
    public function getVersion() {
        if(!$this->mysqlVersion) {
            $this->mysqlVersion = (float)substr(trim(ereg_replace("([A-Za-z-])","",$this->query("SELECT VERSION()")->value())), 0, 3);
        }
        return $this->mysqlVersion;
    }

    /**
     * 返回所有的数据库列表
     */
    public function allDatabaseNames() {
        return $this->query("SHOW DATABASES")->column();
    }

    /**
     * 返回数据库所有的表列表.
     * @return array
     */
    public function tableList() {
        $tables = array();
        foreach($this->query("SHOW TABLES") as $record) {
            $table = reset($record);
            $tables[strtolower($table)] = $table;
        }
        return $tables;
    }

    /**
     *返回数据库表信息列表
     * @return array 数据库表信息列表
     */
    public function tableInfoList(){
        $tableInfos = $this->query("show table status");
        
        foreach($tableInfos as $tableInfo) {
            $tableInfoList[$tableInfo['Name']] = $tableInfo;
        }
        if(isset ($tableInfoList)) return $tableInfoList;
        return null;
    }
    
    private static $_cache_collation_info = array();
    
    /**
     * 获取表所有的列信息
     * @param string $table 表名
     */
    public function fieldInfoList($table){
        //$fields = $this->query("select * from columns where table_name='$table'");//需要从数据库information_schema中获取。
        $fields = $this->query("SHOW FULL FIELDS IN $table");
        
        foreach($fields as $field) {
            $fieldList[$field['Field']] = $field;
        }
        if(isset ($fieldList)) return $fieldList;
        return null;
    }
    
    /**
     * 获取表所有的列定义
     * @param string $table 表名
     */
    public function fieldDefineList($table) {
        $fields = $this->query("SHOW FULL FIELDS IN $table");

        foreach($fields as $field) {
            $fieldSpec = $field['Type'];
            if(!$field['Null'] || $field['Null'] == 'NO') {
                $fieldSpec .= ' not null';
            }
            if($field['Collation'] && $field['Collation'] != 'NULL') {
                // Cache collation info to cut down on database traffic
                if(!isset(self::$_cache_collation_info[$field['Collation']])) {
                    self::$_cache_collation_info[$field['Collation']] = $this->query("SHOW COLLATION LIKE '$field[Collation]'")->record();
                }
                $collInfo = self::$_cache_collation_info[$field['Collation']];
                $fieldSpec .= " character set $collInfo[Charset] collate $field[Collation]";
            }

            if($field['Default'] || $field['Default'] === "0") {
                if(is_numeric($field['Default']))
                    $fieldSpec .= " default " . addslashes($field['Default']);
                else
                    $fieldSpec .= " default '" . addslashes($field['Default']) . "'";
            }
            if($field['Extra']) $fieldSpec .= " $field[Extra]";
            $fieldList[$field['Field']] = $fieldSpec;
        }
        if(isset ($fieldList)) return $fieldList;
        return null;
    }

    /**
     * 查看表在数据库里是否存在
     * NOTE: Experimental; introduced for db-abstraction and may changed before 2.4 is released.
     */
    public function hasTable($table) {
        return (bool)($this->query("SHOW TABLES LIKE '$table'")->value());
    }

    /**
     * 查看指定的数据库是否存在
     */
    public function hasDatabase($name) {
        return $this->query("SHOW DATABASES LIKE '$name'")->value() ? true : false;
    }

    /**
     * 获取指定表的枚举类型的列的设定枚举值
     */
    public function enumValuesForField($tableName, $fieldName) {
        // Get the enum of all page types from the SiteTree table
        $classnameinfo = $this->query("DESCRIBE $tableName \"$fieldName\"")->first();
        preg_match_all("/'[^,]+'/", $classnameinfo["Type"], $matches);
        $classes = array();
        foreach($matches[0] as $value) {
            $classes[] = trim($value, "'");
        }
        return $classes;
    }

    /**
     * 获取数据库创建表的定义
     */
    public function getDbSqlDefinition($tableName) {
        $dbDefine= $this->query("SHOW CREATE TABLE $tableName");
        $dbDefine=$dbDefine->next();
        return $dbDefine["Create Table"];
    }

    /**
     * 查询sql效果。
     * @param stirng $sql 查询语句
     * @param enum $errorLevel 错误等级
     * @param bool $showqueries 是否显示profile信息
     * @return Query_Mysql 
     */
    private function query($sql, $errorLevel = E_USER_ERROR,$showqueries=false) {
        if(isset($_REQUEST['showqueries'])) {
            $starttime = microtime(true);
        }

        $handle = mysql_query($sql,$this->connection);

        if(isset($_REQUEST['showqueries'])) {
            $endtime = microtime(true);
            echo "\n$sql\n开始:{$starttime}-结束:{$endtime}ms\n";
        }

        if(!$handle && $errorLevel) e("无法运行查询语句: $sql | " . mysql_error($this->connection),$this);        
        return new Query_Mysql($handle);
    }

}

?>
