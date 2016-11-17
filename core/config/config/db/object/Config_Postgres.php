<?php
/**
 +---------------------------------<br/>
 * Postgres的配置类<br/>
 * @see http://blogs.techrepublic.com.com/howdoi/?p=110<br/>
 * @see http://neilconway.org/docs/sequences/<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.config.db
 * @subpackage object
 * @author skygreen
 */
class Config_Postgres extends Config_Db {   
    /**
     * @var type 获取数据的模式
     * PGSQL_ASSOC, PGSQL_NUM and PGSQL_BOTH
     */
    public static $fetchmode= PGSQL_ASSOC;

}
?>
