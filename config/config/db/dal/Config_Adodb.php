<?php
/**
 +---------------------------------------------<br/>
 * Adodb的配置类<br/>
 * @link http://phplens.com/lens/adodb/docs-adodb.htm#intro
 * @link http://adodb.sourceforge.net/
 * @link http://www.tsingfeng.com/?p=256
 * 说明：<br/>
 *   经测试验证:<br/>
 *       Adodb当前版本需 Dsn less方式且只能对Sqlite 2进行操作<br/>
 *       Sql Server 支持UTF-8字符集：http://blog.chinaunix.net/u/12228/showart_2089621.html<br/>
 *                                   http://www.im502.com/2009/12/16/php-adodb-mssql-utf8-native/<br/>
 * 当发生异常的时候，可在底层【library/adodb5】库通过print_r( sqlsrv_errors());查看错误原因<br/>
 +---------------------------------------------
 * @category betterlife
 * @package core.config.db
 * @subpackage dal
 * @author skygreen
 */
class Config_Adodb extends Config_Db {
    /**
     * Ms SqlServer Utf8 驱动
     */
    const DRIVER_MSSQL_UTF8="mssqlnative";
    /**
     * @var int 数据库方言
     */
    public static $dialect=0;
    /**
     * 获取数据库驱动字符串
     * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
     * @return string 数据库驱动字符串 
     */
    public static function driver($dbtype=null) {
        switch ($dbtype) {
            case EnumDbSource::DB_MYSQL:
                return "mysql";
            case EnumDbSource::DB_PGSQL:
                return "postgres";
            case EnumDbSource::DB_FIREBIRD:
            case EnumDbSource::DB_INTERBASE:
                return "ibase";
            case EnumDbSource::DB_LDAP:
                $LDAP_CONNECT_OPTIONS = Array(
                        Array ("OPTION_NAME"=>LDAP_OPT_DEREF, "OPTION_VALUE"=>2),
                        Array ("OPTION_NAME"=>LDAP_OPT_SIZELIMIT,"OPTION_VALUE"=>100),
                        Array ("OPTION_NAME"=>LDAP_OPT_TIMELIMIT,"OPTION_VALUE"=>30),
                        Array ("OPTION_NAME"=>LDAP_OPT_PROTOCOL_VERSION,"OPTION_VALUE"=>3),
                        Array ("OPTION_NAME"=>LDAP_OPT_ERROR_NUMBER,"OPTION_VALUE"=>13),
                        Array ("OPTION_NAME"=>LDAP_OPT_REFERRALS,"OPTION_VALUE"=>FALSE),
                        Array ("OPTION_NAME"=>LDAP_OPT_RESTART,"OPTION_VALUE"=>FALSE)
                );
                return "ldap";
            case EnumDbSource::DB_SQLITE2:
            case EnumDbSource::DB_SQLITE3:              
                return "sqlite";
            case EnumDbSource::DB_MICROSOFT_ACCESS:
                return "access";
            case EnumDbSource::DB_SQLSERVER:
                if ((strtoupper(Gc::$encoding)==Config_C::CHARACTER_UTF8)||((strtoupper(Gc::$encoding)==Config_C::CHARACTER_UTF_8))){
                    return self::DRIVER_MSSQL_UTF8;              
                }else{
                    return "odbc_mssql";
                }
            case EnumDbSource::DB_DB2:
                return "db2";
            case EnumDbSource::DB_INFOMIX:
                return "informix";
            case EnumDbSource::DB_ORACLE:
                return "oci8";
            case EnumDbSource::DB_SYBASE:
                return "sybase";
        }
        return "mysql";
    }

    /**
     +---------------------------------<br/>
     * 返回ODBC所需的dsn_less字符串<br/>
     * 说明：<br/>
     *    $dsn可以直接在System DSN里配置；然后在配置里设置：Config_Db::$dbname
     +---------------------------------<br/>
     * @param string $host
     * @param string $port 
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param enum $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
     * @return string ODBC所需的dsn_less字符串
     */
    public static function dsn_less($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null) {
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
        if ($engine==  EnumDbEngine::ENGINE_DAL_ADODB_PDO) {
            $connecturl=self::driver($dbtype).":host=".$connecturl;
        }        
        switch ($dbtype) {
            case EnumDbSource::DB_SQLSERVER:
                if (!((strtoupper(Gc::$encoding)==Config_C::CHARACTER_UTF8)||(strtoupper(Gc::$encoding)==Config_C::CHARACTER_UTF_8))){
                    $dsn_less="Driver={SQL Server Native Client 10.0};Server=".$connecturl.";Database=".$dbname.";";
                }else{
                    $dsn_less=$connecturl;  
                }
                break;
            case EnumDbSource::DB_MICROSOFT_ACCESS:
                $dsn="Driver={Microsoft Access Driver (*.mdb)};Dbq=".$dbname.";Uid=".$username.";Pwd=".$password;
                break;
            case EnumDbSource::DB_DB2:
                $dsn="driver={IBM db2 odbc DRIVER};Database=".$dbname.";hostname=".$host.";port=".$port.";protocol=TCPIP;uid=".$username."; pwd=".$password;
                break;                
            case EnumDbSource::DB_FIREBIRD:
            case EnumDbSource::DB_INTERBASE:
                $dsn_less.=":".$dbname;
                break;
            case EnumDbSource::DB_SQLITE2:
            case EnumDbSource::DB_SQLITE3:
                $dsn_less=$dbname;  
                break;            
            default:
                $dsn_less=$connecturl;
                break;
        }
        return $dsn_less;
    }
    
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
        $dsn=self::driver($dbtype)."://";
        switch ($dbtype) {
            case EnumDbSource::DB_LDAP:
                $dsn.=$host."/".$dbname;
                break;
            case EnumDbSource::DB_SQLITE2:
            case EnumDbSource::DB_SQLITE3:             
                $path = urlencode($dbname);
                $dsn.= $path;
                if($engine==EnumDbEngine::ENGINE_DAL_ADODB_PDO) {
                    $dsn="pdo_".$dsn;
                }
                break;   
            default:
                $dsn.=$username.":".$password."@".$connecturl."/".$dbname;
                if($engine==  EnumDbEngine::ENGINE_DAL_ADODB_PDO) {
                    $dsn="pdo_".$dsn;
                }
                break;
        }
        return $dsn;
    }
}
?>
