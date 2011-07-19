<?php
/**
 +---------------------------------<br/>
 * Sqlite的配置类<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.config.db
 * @subpackage object
 * @author skygreen
 */
class Config_Sqlite  extends Config_Db{
    /**
     * Sqlite 2 抓取数据的模式<br/>
     * SQLITE_ASSOC will return only associative indices (named fields)<br/>
     * SQLITE_NUM will return only numerical indices (ordinal field numbers)<br/>
     * SQLITE_BOTH will return both associative and numerical indices.
     * @link http://php.net/manual/en/sqlite.constants.php
     * @var enum
     */
    public static $sqlite2_fetchmode= SQLITE_ASSOC;//SQLITE_NUM|SQLITE_BOTH
    /**
     * Sqlite 3 抓取数据的模式<br/>
     * @link http://php.net/manual/en/sqlite3.constants.php
     * @var enum 
     */
    public static $sqlite3_fetchmode= SQLITE3_ASSOC;
}
?>
