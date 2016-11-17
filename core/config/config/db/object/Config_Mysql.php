<?php
/**
 +---------------------------------<br/>
 * MYSQL的配置类<br/>
 * 应根据项目的需要修改相应的配置<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.config.db
 * @subpackage object
 * @author skygreen
 */
class Config_Mysql extends Config_Db {
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
