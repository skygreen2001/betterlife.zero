<?php
/**
 +---------------------------------<br/>
 * Ms SQL Server的配置类<br/>
 * 应根据项目的需要修改相应的配置<br/>
 * 说明：<br/>
 * 1.它能和FreeTDS配合支持UTF-8字符集，FreeTDS本身支持Unix和Linux，但也有支持Windows的组件；详情参考如下：<br/>
 * @see http://www.sunboyu.cn/2008/07/22/%E5%9C%A8windows%E5%9C%A8%E5%AE%89%E8%A3%85freetds%EF%BC%8C%E8%AE%A9mssql%E6%94%AF%E6%8C%81utf-8.shtml<br/>
 * @see http://docs.moodle.org/en/Installing_MSSQL_for_PHP [官方文档，支持FreeTDS的组件也在这里] 需要PHP5.2~5.3<br/>
 * 2.时间在表里设置为datetime;在页面上显示的时间格式不太适合中文阅读<br/>
 * 解决办法如下：<br/>
 *  a.修改php.ini文件，找到php.ini文件，将mssql.datetimeconvert 设为OFF，并去掉行首的‘；’<br/>
 *  b.如果没办法修改php.ini文件，可以在你的php配置（比喻数据库连接文件）文件里加上一句：<br/>
 *    ini_set ("mssql.datetimeconvert","0"); //设置数据库格式.<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.config.db
 * @subpackage object
 * @author skygreen
 */
class Config_Mssql extends Config_Db {
    /**
     * 返回数据库连接地址
     * @param string $host
     * @param string $port
     * @return string 数据库连接地址
     */
    final public static function connctionurl($host=null,$port=null) {
        if (isset($host)){
            if (strlen($port)>0) {
                return $host.":".$port;
            }else {
                return $host;
            }
        }else{  
            if (strlen(self::$port)>0) {
                return self::$host.":".self::$port;
            }else {
                return self::$host;
            }
        }
    }

}
?>
