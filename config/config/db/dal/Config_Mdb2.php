<?php
/**
 +---------------------------------<br/>
 * Mdb2的配置类<br/>
 * @link http://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php
 * @link http://pear.php.net/manual/en/package.database.mdb2.intro-connect.php
 +---------------------------------
 * @link http://pear.php.net/package/DB/docs
 * @category betterlife
 * @package core.config.db
 * @subpackage dal
 * @author skygreen
 */
class Config_Mdb2 extends Config_Db{
    /**
     * @var type 获取数据的模式
     * @link http://pear.php.net/manual/en/package.database.mdb2.intro-fetch.php
     * MDB2_FETCHMODE_ASSOC, MDB2_FETCHMODE_ORDERED and MDB2_FETCHMODE_OBJECT
     */
    public static $fetchmode= MDB2_FETCHMODE_OBJECT;
    
    /**
     * @var array 数据库连接参数
     */
    public static $options = array(
        'debug' => 2,
        'result_buffering' => false,
        'portability' => MDB2_PORTABILITY_ALL,
    );    

    /**
     * 返回ODBC所需的dsn字符串
     * @param string $host
     * @param string $port 
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param enum $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
     * @link http://pear.php.net/manual/en/package.database.mdb2.intro-connect.php
     * @return array ODBC所需的dsn
     */
    public static function dsn($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null) {      
        if (isset($host)){
            if (strlen($port)>0) {
                $connecturl= $host.":".$port;
            }else {
                $connecturl= $host;
            }
        }else{
            if (strlen(self::$port)>0) {
                $connecturl=self::$host.":".self::$port;
            }else {
                $connecturl=self::$host;
            }
        }
        $dsn=array(
          "hostspec" => $connecturl,
          "username" => $username,
          "password" => $password,
          "database" => $dbname
        );
        
        switch ($dbtype) {
            case EnumDbSource::DB_MYSQL:
                $dsn["phptype"]="mysqli";
                break;
            case EnumDbSource::DB_SQLITE2:
                $dsn["phptype"] ="sqlite";
                $dsn['mode']="0644";                
                break;
            case EnumDbSource::DB_SQLSERVER:
                $dsn["phptype"] ="mssql";
                break;
            case EnumDbSource::DB_PGSQL:
                $dsn["phptype"] ="pgsql";
                break;
            case EnumDbSource::DB_INTERBASE:   
            case EnumDbSource::DB_FIREBIRD:
                $dsn["phptype"] ="ibase";
                break;
            case EnumDbSource::DB_ORACLE:
                /**
                 * @link http://pear.php.net/bugs/bug.php?id=4854
                 */
                $dsn["phptype"]  = "oci8";
                break;
        }
        return $dsn;
    }
}

?>
