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
class DbInfo_Mysqli extends  DbInfo implements IDbInfo
{
    /**
    * Mysql的版本号
    *
    * @var mixed
    */
    private $mysqlVersion;
    /**
     * @var string 获取数据库信息的数据库名称
     */
    public $dbname_info = "information_schema";
    /**
     * 是否使用获取数据库信息的数据库
     * @var bool
     */
    public static $isUseDbInfoDatabase = false;

    private static $showtables;

    /**
     * @var mixed 预编译准备SQL表达式容器
     */
    protected $stmt;

    /**
     * 检查 操作Db的 Php Extensions驱动 是否已打开.
     * @return TRUE/FALSE 是否已打开.
     */
    public static function extension_is_available()
    { return function_exists('mysqli_prepare'); }

    /**
     * 在mysql数据库中执行SQL脚本
     * @return TRUE/FALSE 是否正常运行
     */
    public static function run_script($db_config)
    {
        if ( !self::extension_is_available() )
        {
            LogMe::log('默认的PHP MySQL Extension没有打开.您需要打开对应的 php extensions');
            return FALSE;
        }

        // Decode url-encoded information in the db connection string.
        $host     = $db_config["host"];
        $user     = $db_config["user"];
        $password = $db_config["password"];
        $dbname   = $db_config["dbname"];
        $script_filename = $db_config["script_filename"];

        // Allow for non-standard MySQL port.
        if ( isset($db_config["port"]) && !empty($db_config["port"]) )
        {
            $host = $host . ':' . $db_config["port"];
        }

        // Test connecting to the database.
        $connecturl = Config_Mysql::connctionurl( $host, $port );

        if ( !isset($username) ) {
            $username = Config_Mysql::$username;
        }
        if ( !isset($password) ) {
            $password = Config_Mysql::$password;
        }
        if ( !isset($dbname) ) {
            $dbname   = Config_Mysql::$dbname;
        }
        $dbinfo = new DbInfo_Mysqli();
        $connection = new mysqli($connecturl, $username, $password, $dbname);
        if ( !$connection )
        {
            LogMe::log(
                '连接到 MySQL 数据库失败. MySQL报告错误信息: ' . mysqli_error($connection)
                    . '.<ul><li>确认用户名和密码正确吗?</li><li>确认输入正确的数据库主机名?</li><li确认数据库服务器在运行?</li></ul>' );
            return false;
        }

        // Test selecting the database.
        if ( mysqli_connect_errno() ) {
            Exception_Mysqli::record();
            return false;
        }

        $connection->query("SET NAMES " . Config_Db::$character);

        if ( file_exists($script_filename) ) {
            $query = file($script_filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES|FILE_TEXT);
            $query = implode("\n", $query);
            $query = str_replace("&nbsp;", "--&nbsp--", $query);
            $query_e = explode(';', $query);

            foreach ($query_e as $k => $v)
            {
                $v = str_replace("--&nbsp--", "&nbsp;", $v);
                if ( !empty($v) && ( $v != "\r") ) {
                    $stmt = $connection->prepare($v);
                    if ( $stmt ) {
                        $stmt->execute();
                    }

                    if ( mysqli_connect_errno() ) {
                        LogMe::log( '数据库服务器执行命令发生错误脚本: ' . $v . '.<br/> MySQL报告错误信息:' . $error );
                        Exception_Mysqli::record();
                        return false;
                    }
                }
            }
            if ( $stmt ) {
                $stmt->free_result();
                $stmt->close();
            }
            LogMe::log( "数据库操作成功，无异常！" );
        } else {
            LogMe::log( '指定的脚本文件路径错误，请查看路径文件名: '. $script_filename );
        }
    }

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
    public function connect($host = null, $port = null, $username = null, $password = null, $dbname = null, $engine = null)
    {
        $this->connection = Manager_Db::newInstance()->object_mysql_mysqli($host, $port, $username, $password, $dbname)->getConnection();
    }

    /**
     * 设置数据库字符集
     * @param string $character_code 字符集
     */
    public function change_character_set($character_code = "utf8mb4")
    {
        $sql = "SET NAMES " . Config_C::CHARACTER_UTF ;
        $this->connection->query($sql);
    }

    /**
     * 显示数据库的字符集
     */
    public function character_set()
    {
        $sql    = "SHOW VARIABLES LIKE '%character%'";
        $result = $this->connection->query($sql);
        if ( !$result ) {
            echo "ERROR : " . mysqli_error($this->connection) . "<br>";
            return;
        } else {
            UtilCss::report_info();
            echo "SQL> {$sql}; <br>";
            echo "<table class='" . UtilCss::CSS_REPORT_TABLE . "' border=1><thead><tr><th> Variable_name</th>" . "<th> Value</th></tr></thead>";
            while ($row = fetch_row($result)) {
                echo "<tr><td>{$row['Variable_name']}</td><td>{$row['Value']}</td></tr>";
            }
            echo "</table><br>";
        }
    }

    /**
     * 获取数据库的版本信息
     * @return float
     */
    public function getVersion()
    {
        if ( !$this->mysqlVersion ) {
            $this->mysqlVersion = (float)substr(trim(preg_replace('/([A-Za-z-])/', '', $this->query("SELECT VERSION()")->value())), 0, 3);
        }
        return $this->mysqlVersion;
    }

    /**
     * 返回所有的数据库列表
     */
    public function allDatabaseNames()
    {
        return $this->query("SHOW DATABASES")->column();
    }

    /**
     * 返回数据库所有的表列表.
     * @return array
     */
    public function tableList()
    {
        if ( !self::$showtables ) {
            self::$showtables = $this->query("SHOW TABLES");
        }
        $tables = array();
        foreach(self::$showtables as $record) {
            if ( $record ) $table = @reset($record);
            if ( empty($table) ) $table = $record;
            $tables[strtolower($table)] = $table;
        }
        return $tables;
    }

    /**
     *返回数据库表信息列表
     * @return array 数据库表信息列表
     */
    public function tableInfoList()
    {
        $tableInfos = $this->query("show table status");
        if ( $tableInfos ) {
            foreach ($tableInfos as $tableInfo) {
                $tableInfo = UtilObject::object_to_array($tableInfo);
                $tableInfoList[$tableInfo['Name']] = $tableInfo;
            }
        }
        if ( isset($tableInfoList) ) return $tableInfoList;
        return null;
    }

    private static $_cache_collation_info = array();

    /**
     * 获取表所有的列信息
     * @param string $table 表名
     */
    public function fieldInfoList($table)
    {
        //$fields = $this->query("select * from columns where table_name='$table'");//需要从数据库information_schema中获取。
        $fields = $this->query("SHOW FULL FIELDS IN $table");

        foreach ($fields as $field) {
            $field = UtilObject::object_to_array($field);
            $fieldList[$field['Field']] = $field;
        }
        if (isset ($fieldList)) return $fieldList;
        return null;
    }

    /**
     * 获取表所有的列定义
     * @param string $table 表名
     */
    public function fieldDefineList($table)
    {
        $fields = $this->query("SHOW FULL FIELDS IN $table");

        foreach ($fields as $field) {
            $fieldSpec = $field['Type'];
            if ( !$field['Null'] || $field['Null'] == 'NO' ) {
                $fieldSpec .= ' not null';
            }
            if ( $field['Collation'] && $field['Collation'] != 'NULL' ) {
                // Cache collation info to cut down on database traffic
                if ( !isset(self::$_cache_collation_info[$field['Collation']]) ) {
                    self::$_cache_collation_info[$field['Collation']] = $this->query("SHOW COLLATION LIKE '$field[Collation]'")->record();
                }
                $collInfo   = self::$_cache_collation_info[$field['Collation']];
                $fieldSpec .= " character set $collInfo[Charset] collate $field[Collation]";
            }

            if ( $field['Default'] || $field['Default'] === "0" ) {
                if ( is_numeric($field['Default']) )
                    $fieldSpec .= " default " . addslashes($field['Default']);
                else
                    $fieldSpec .= " default '" . addslashes($field['Default']) . "'";
            }
            if ( $field['Extra'] ) $fieldSpec .= " $field[Extra]";
            $fieldList[$field['Field']] = $fieldSpec;
        }
        if ( isset($fieldList) ) return $fieldList;
        return null;
    }

    /**
     * 获取表所有的列名称定义映射数组
     * @param string $table 表名
     * @param bool $isCommentFull 列名称是否获取完整的表列自定义注释，默认获取注释第一列
     * @return 表所有的列名称定义映射数组
     * 示例如下:
     *     array('username'=>'用户名','password'=>'密码')
     */
    public function fieldMapNameList($table, $isCommentFull = false)
    {
        $fieldList = $this->fieldInfoList( $table );
        $result    = array();
        if ( !empty($fieldList) ) {
            foreach ($fieldList as $field)
            {
                if ( $isCommentFull ) {
                    $result[$field["Field"]] = $field["Comment"];
                } else {
                    if ( contain($field["Comment"], "\n" ) )
                    {
                        $comment = explode("\n", $field["Comment"]);
                        if ( count($comment) > 0 ) {
                            $result[$field["Field"]] = $comment[0];
                        }
                    } else {
                        $result[$field["Field"]] = $field["Comment"];
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param string|array  查看Column_name的Unique在表里是否存在
     */
    public function hasUnique($table, $Column_names)
    {
        if ( is_array($Column_names) ) {
             $conditions = array();
             foreach ($Column_names as $Column_name) {
                $conditions[] = "Column_name='$Column_name'";
             }
             $condition = implode(" or ", $conditions);
        } else {
             $condition = "Column_name='$Column_names'";
        }
        $sqlUnique = "show index from $table where Key_name!='PRIMARY' and Non_unique=0 and ($condition);";
        LogMe::log( $sqlUnique );
        return (bool)($this->query( $sqlUnique )->value());
    }

    /**
     * 查看表在数据库里是否存在
     * NOTE: Experimental; introduced for db-abstraction and may changed before 2.4 is released.
     */
    public function hasTable($table)
    {
        return (bool)($this->query("SHOW TABLES LIKE '$table'")->value());
    }

    /**
     * 查看指定的数据库是否存在
     */
    public function hasDatabase($name)
    {
        return $this->query("SHOW DATABASES LIKE '$name'")->value() ? true : false;
    }

    /**
     * 获取指定表的枚举类型的列的设定枚举值
     */
    public function enumValuesForField($tableName, $fieldName)
    {
        // Get the enum of all page types from the SiteTree table
        $classnameinfo = $this->query("DESCRIBE $tableName \"$fieldName\"")->first();
        preg_match_all("/'[^,]+'/", $classnameinfo["Type"], $matches);
        $classes = array();
        foreach ($matches[0] as $value) {
            $classes[] = trim($value, "'");
        }
        return $classes;
    }

    /**
     * 获取数据库创建表的定义
     */
    public function getDbSqlDefinition($tableName)
    {
        $dbDefine = $this->query("SHOW CREATE TABLE $tableName");
        $dbDefine = $dbDefine->next();
        return $dbDefine["Create Table"];
    }

    /**
     * 查询sql效果。
     * @param stirng $sql 查询语句
     * @param enum $errorLevel 错误等级
     * @param bool $showqueries 是否显示profile信息
     * @return Query_Mysql
     */
    private function query($sqlstring, $errorLevel = E_USER_ERROR, $showqueries=false)
    {
        if ( isset($_REQUEST['showqueries']) ) {
            $starttime = microtime(true);
        }
        if ( $this->connection ) {
            $this->stmt = $this->connection->prepare($sqlstring);
            if ( $this->stmt ) {
                $this->stmt->execute();
                $result = $this->getQueryResult();
                Exception_Mysqli::record();

            }
        }

        if( isset($_REQUEST['showqueries']) ) {
            $endtime = microtime(true);
            echo "\n$sql\n开始:{$starttime}-结束:{$endtime}ms\n";
        }
        if ( !$this->stmt && $errorLevel && $this->connection ) e( "无法运行查询语句: $sql | " . mysqli_error($this->connection), $this );
        if ( $this->stmt ) return $result;
        return null;
    }


    /**
     * 获取查询结果
     * @return 查询结果
     */
    private function getQueryResult()
    {
        $result = null;
        if ( is_object($this->stmt) ) {
            $this->stmt->store_result();
            if ( $this->stmt->num_rows>0 ) {
                /* get resultset for metadata */
                $meta = $this->stmt->result_metadata();
                while ($field = $meta->fetch_field()) {
                    $params[] = &$row[$field->name];
                }
                call_user_func_array(array($this->stmt, 'bind_result'), $params);
                $result=array();
                while ($this->stmt->fetch()) {
                    if ( count($row) == 1 ) {
                        foreach($row as $key => $val) {
                            $result[] = $val;
                        }
                    }else{
                        $c = new stdClass();
                        foreach ($row as $key => $val) {
                            $c->{$key} = $val;
                        }
                        $result[] = $c;
                    }
                }
            }
            $this->stmt->free_result();
            $this->stmt->close();
        }
        return $result;
    }


}

?>
