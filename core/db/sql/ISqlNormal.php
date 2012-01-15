<?php
/**
 +-----------------------------------<br/>
 * 接口：通过SQL查询的类<br/>
 +-----------------------------------
 * @category betterlife
 * @package core.db.sql
 * @author skygreen
 */
interface ISqlNormal {
    /**
     * 新增一条数据记录
     * @param array $data 数据数组
     * @return int 返回插入数据的ID编号
     * 示例：
     *     $db=new Sql_Mysql();
     *     $data = array("name"=>"skygreen","pass"=>md5("hello world"));
     *     $result = $this->Db->insertdata($data);
     *     其中 name,pass是表列名，"skygreen",md5("hello world"))是列值，与列名一一对应。
     */
    public function insertdata($tablename,$data);

    /**
     * 删除一条数据记录
     * @param int $id 需删除数据的ID编号
     * @return boolean:是否删除成功
     * 示例：
     *      $db=new Sql_Mysql();
     *      $result =$db ->deleteData(1);
     */
    public function deleteData($tablename,$id);

    /**
     * 修改一条数据记录
     * @param int $id 需修改数据的ID编号
     * @param array $data 数据数组
     * @return boolean:是否修改成功
     * 示例：
     *      $db=new Sql_Mysql();
     *      $data = array("name"=>"afif2","pass"=>md5("hello world"));
     *      $result = $db->updateData(1, $data);
     */
    public function updateData($tablename,$id, $data);

    /**
     * 直接执行SQL语句
     * @param string $sqlstring SQL语句
     */
    public function sqlExectue($sqlstring);
}
?>
