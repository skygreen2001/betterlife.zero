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
class DbInfo_Mysql extends  DbInfo implements IDbInfo 
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
    public $dbname_info="information_schema";
    /**
     * 是否使用获取数据库信息的数据库
     * @var bool 
     */
    public static $isUseDbInfoDatabase=false;   
    /**
     * 检查 操作Db的 Php Extensions驱动 是否已打开.   
     * @return TRUE/FALSE 是否已打开. 
     */
    public static function extension_is_available() 
    { return function_exists('mysql_connect'); }
        
    /**     
     * 在mysql数据库中执行SQL脚本   
     * @return TRUE/FALSE 是否正常运行
     */
    public static function run_script($db_config)
    {
        if (!self::extension_is_available())
        {
            LogMe::log('默认的PHP MySQL Extension没有打开.您需要打开对应的 php extensions');
            return FALSE;
        } 

        // Decode url-encoded information in the db connection string.
        $host    =$db_config["host"];
        $user    =$db_config["user"];
        
        $password=$db_config["password"];
        $dbname  =$db_config["dbname"];
        $script_filename=$db_config["script_filename"];  
        
        // Allow for non-standard MySQL port.
        if (isset($db_config["port"]) && !empty($db_config["port"]))
        {
            $host=$host . ':' . $db_config["port"];
        }

        // Test connecting to the database.
        $connection=@mysql_connect($host, $user, $password, TRUE, 2);

        if (!$connection)
        {
            LogMe::log(
                '连接到 MySQL 数据库失败. MySQL报告错误信息: ' . mysql_error()
                    . '.<ul><li>确认用户名和密码正确吗?</li><li>确认输入正确的数据库主机名?</li><li确认数据库服务器在运行?</li></ul>');
            return FALSE;
        }

        // Test selecting the database.
        if (!mysql_select_db($dbname))
        {
            if (mysql_query("CREATE DATABASE $dbname",$connection))
            {
                LogMe::log("指定数据库不存在，创建数据库$dbname成功！<br/>");                       
                if (!mysql_select_db($dbname))
                {                 
                   LogMe::log("无法指定数据库，数据库报告错误信息: " . mysql_error());                       
                   return FALSE;
                }
            }
            else
            {
              LogMe::log("指定数据库不存在，创建数据库失败错误信息: " . mysql_error());
              return FALSE;
            }                                               
        }     
        $isSetcharset=mysql_set_charset(Config_C::CHARACTER_UTF8, $connection);    
        if (!$isSetcharset){
            $error=mysql_error();
            LogMe::log('执行字符集操作命令发生错误脚本: ' . $v
                                   . '.<br/> MySQL报告错误信息:' . $error."<br/>");                
        }           
        if (file_exists($script_filename)){
            $query  =file($script_filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES|FILE_TEXT);
            $query=implode("\n",$query);    
            $query=str_replace("&nbsp;","--&nbsp--",$query);              
            $query_e=explode(';', $query);

            foreach ($query_e as $k => $v)
            {                       
                $v=str_replace("--&nbsp--","&nbsp;",$v);    
                if (!empty($v)&&($v!="\r")){
                    $result = mysql_query($v);                                                                                       
                    if (!$result)
                    {
                        $error=mysql_error();
                        LogMe::log('数据库服务器执行命令发生错误脚本: ' . $v
                                       . '.<br/> MySQL报告错误信息:' . $error);
                        return FALSE;
                    }
                }
            }                 
            LogMe::log("数据库操作成功，无异常！");   
        }else{
            LogMe::log('指定的脚本文件路径错误，请查看路径文件名: '. $script_filename); 
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
    public function connect($host=null,$port=null,$username=null,$password=null,$dbname=null,$engine=null) 
    {
        $this->connection = Manager_Db::newInstance()->object_mysql_php5($host,$port,$username,$password,$dbname)->getConnection();
    }

    /**
     * 设置数据库字符集
     * @param string $character_code 字符集
     */
    public function change_character_set($character_code='UTF8')
    {
       $sql = "set names ".$character_code;
       $result =  mysql_query($sql,$this->connection);
    }

    /**
     * 显示数据库的字符集
     */
    public function character_set() 
    {
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
    public function getVersion() 
    {
        if(!$this->mysqlVersion) {
            $this->mysqlVersion = (float)substr(trim(ereg_replace("([A-Za-z-])","",$this->query("SELECT VERSION()")->value())), 0, 3);
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
    public function tableInfoList()
    {
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
    public function fieldInfoList($table)
    {
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
    public function fieldDefineList($table) 
    {
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
     * 获取表所有的列名称定义映射数组                      
     * @param string $table 表名
     * @param bool $isCommentFull 列名称是否获取完整的表列自定义注释，默认获取注释第一列
     * @return 表所有的列名称定义映射数组
     * 示例如下:
     *     array('username'=>'用户名','password'=>'密码')
     */
    public function fieldMapNameList($table,$isCommentFull=false)
    {
        $fieldList=$this->fieldInfoList($table);
        $result=array();
        if (!empty($fieldList)){            
            foreach($fieldList as $field)
            {
                if ($isCommentFull){
                    $result[$field[Field]]= $field[Comment];          
                }else{
                    if (contain($field[Comment],"\n"))
                    {
                        $comment=explode("\n",$field[Comment]);
                        if (count($comment)>0){
                            $result[$field[Field]]= $comment[0];            
                        }                                                                                
                    }else{
                        $result[$field[Field]]= $field[Comment];       
                    }
                }
               
            }
        }
        return $result;    
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
        foreach($matches[0] as $value) {
            $classes[] = trim($value, "'");
        }
        return $classes;
    }

    /**
     * 获取数据库创建表的定义
     */
    public function getDbSqlDefinition($tableName) 
    {
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
    private function query($sql, $errorLevel = E_USER_ERROR,$showqueries=false) 
    {
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
