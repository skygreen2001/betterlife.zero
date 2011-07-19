<?php
/**
 +-------------------------------------<br/>
 * 所有数据库，表，列信息的总接口<br/>
 +-------------------------------------
 * @category betterlife
 * @package core.db
 * @subpackage info
 * @author skygreen
 */
interface IDbInfo {
    /**
     * 获取数据库的版本信息
     * @return float
     */
    public function getVersion();

    /**
     * 返回所有的数据库列表
     */
    public function allDatabaseNames();

    /**
     * 返回数据库所有的表列表.
     * @return array
     */
    public function tableList();
    
    /**
     *返回数据库表信息列表
     * @return array 数据库表信息列表
     */
    public function tableInfoList();
    
    /**
     * 获取表所有的列信息
     * @param string $table
     */
    public function fieldInfoList($table);
    
    /**
     * 获取表所有的列定义
     * @param string $table
     */
    public function fieldDefineList($table);

    /**
     * 查看表在数据库里是否存在
     * NOTE: Experimental; introduced for db-abstraction and may changed before 2.4 is released.
     */
    public function hasTable($table);
    /**
     * 查看指定的数据库是否存在
     */
    public function hasDatabase($name);

    /**
     * 获取指定表的枚举类型的列的设定枚举值
     */
    public function enumValuesForField($tableName, $fieldName);

    /**
     * 获取数据库创建表的定义
     */
    public function getDbSqlDefinition($tableName);
}
?>
