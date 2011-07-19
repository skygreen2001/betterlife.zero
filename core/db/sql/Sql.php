<?php
/**
 +----------------------------<br/>
 *所有SQL查询的父类<br/>
 +---------------------------
 * @category betterlife
 * @package core.db.sql
 * @author skygreen
 */
abstract class Sql {
    public function __construct($host=null,$port=null,$username=null,$password=null,$dbname=null) {
        $this->connect($host,$port,$username,$password,$dbname);
    }
    
    /**
     * 连接数据库
     */
    abstract  protected function connect($host=null,$port=null,$username=null,$password=null,$dbname=null);


}
?>
