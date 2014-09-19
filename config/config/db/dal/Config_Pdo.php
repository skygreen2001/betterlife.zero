<?php
/**
 +---------------------------------<br/>
 * Pdo的配置类<br/>
 * @see http://www.phpro.org/tutorials/Introduction-to-PHP-PDO.html<br/>
 +---------------------------------
 * @category betterlife
 * @package core.config.db
 * @subpackage dal
 * @author skygreen
 */
class Config_Pdo extends Config_Db {
    /**
     * @var enum 获取数据的模式
     * @link http://php.net/manual/en/pdo.constants.php
     * PDO::FETCH_ASSOC, PDO::FETCH_BOTH, PDO::FETCH_LAZY and PDO::FETCH_OBJ
     */
    public static $fetchmode= PDO::FETCH_ASSOC;
    
    /**
     * 返回ODBC所需的dsn字符串
     * @param string $host
     * @param string $port 
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param enum $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
     * @return string ODBC所需的dsn字符串
     */
    public static function dsn($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null) {
        if (!isset($dbname)){
            $dbname=self::$dbname;
        } 
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
        switch ($dbtype) {
            case EnumDbSource::DB_MYSQL:
                $dsn="mysql:dbname=".$dbname.";host=".$connecturl;//="mysql:dbname=test;host=localhost"
                break;
            case EnumDbSource::DB_SQLITE2:
                $dsn = 'sqlite:'.$dbname;
                break;
            case EnumDbSource::DB_SQLITE_MEMORY:
                $dsn='sqlite::memory:';
                break;
            case EnumDbSource::DB_SQLSERVER:
            case EnumDbSource::DB_SYBASE:
            case EnumDbSource::DB_FREETDS:
                $dsn='dblib:host='.$connecturl.";dbname=".$dbname.";charset=UTF-8";//DBLIB
//                new PDO ("dblib:host=$hostname:$port;dbname=$dbname","$username","$password");
                break;
            case EnumDbSource::DB_PGSQL:
                $dsn='pgsql:dbname='.$dbname.";host=".$connecturl;
                break;
            case EnumDbSource::DB_FIREBIRD:
                $dsn='firebird:dbname='.$dbname;//"firebird:dbname=localhost:C:\Programs\Firebird\DATABASE.FDB"
                //new PDO("firebird:dbname=localhost:C:\Programs\Firebird\DATABASE.FDB", "SYSDBA", "masterkey");
                break;
            case EnumDbSource::DB_INFOMIX:
                $dsn="informix:DSN=".$dbname;
                //connect to an informix database cataloged as InformixDB in odbc.ini: $dbh = new PDO("informix:DSN=InformixDB", "username", "password");
                break;
            case EnumDbSource::DB_ORACLE:
                $dsn="OCI:dbname=".$dbname.";charset=".Config_C::CHARACTER_UTF8;
                break;
            case EnumDbSource::DB_MICROSOFT_ACCESS:
                $dsn="odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=".$dbname.";Uid=".$username.";Pwd=".$password;
                //new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\accounts.mdb;Uid=Admin");
                break;
            case EnumDbSource::DB_DB2:
                $dsn="ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=".$dbname."; HOSTNAME=".$host.";PORT=".$port.";PROTOCOL=TCPIP;";
                //new PDO("ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=accounts; HOSTNAME=1.2.3,4;PORT=56789;PROTOCOL=TCPIP;", "username", "password");
                break;
        }
        return $dsn;
    }

}
?>
