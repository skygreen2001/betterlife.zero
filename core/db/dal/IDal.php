<?php
/**
 +---------------------------------<br/>
 * 所有DAL(Data Access Layers)的接口定义<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db
 * @subpackage dal
 * @author skygreen
 */
interface IDal {
    /**
     * 保存新建对象
     * @param Object $object
     * @return int 保存对象记录的ID标识号
     */
    public function save($object);

    /**
     * 删除对象
     * @param string $classname
     * @param int $id
     * @return Object
     */
    public function delete($object);

    /**
     * 更新对象
     * @param int $id
     * @param Object $object
     * @return Object
     */
    public function update($object);

    /**
     * 根据对象实体查询对象列表
     * @param string $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $sort 排序条件
     * 示例如下：
     *      1.id asc;
     *      2.name desc;
     * @param string $limit 分页数目:同Mysql limit语法
     * 示例如下：
     *    0,10
     * @return 对象列表数组
     */
    public function get($object, $filter=null, $sort=null, $limit=null);

    /**
     * 查询得到单个对象实体
     * @param string|class $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $sort 排序条件
     * 示例如下：
     *      1.id asc;
     *      2.name desc;
     * @return 单个对象实体
     */
    public function get_one($object, $filter=null, $sort=Crud_SQL::SQL_ORDER_DEFAULT_ID);

    /**
     * 根据表ID主键获取指定的对象[ID对应的表列]
     * @param string|class $object 需要查询的对象实体|类名称
     * @param string $id
     * @return 对象
     */
    public function get_by_id($object, $id);

    /**
     *  直接执行SQL语句
     *
     * @param mixed $sql SQL查询|更新|删除语句
     * @param string|class $object 需要生成注入的对象实体|类名称
     * @return array
     *  1.执行查询语句返回对象数组
     *  2.执行更新和删除SQL语句返回执行成功与否的true|null
     */
    public function sqlExecute($sql,$object=null);

    /**
     * 对象总计数
     * @param string|class $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @return 对象总计数
     */
    public function count($object, $filter=null);

    /**
     * 对象分页
     * @param string|class $object 需要查询的对象实体|类名称
     * @param object|string|array $filter 查询条件，在where后的条件
     * 示例如下：<br/>
     *      0."id=1,name='sky'"<br/>
     *      1.array("id=1","name='sky'")<br/>
     *      2.array("id"=>"1","name"=>"sky")<br/>
     *      3.允许对象如new User(id="1",name="green");<br/>
     * 默认:SQL Where条件子语句。如：(id=1 and name='sky') or (name like 'sky')<br/>
     * @param string $sort 排序条件
     * 默认为 id desc
     * 示例如下：
     *      1.id asc;
     *      2.name desc;
     */
    public function queryPage($object,$startPoint,$endPoint,$filter=null,$sort=Crud_SQL::SQL_ORDER_DEFAULT_ID);
}
?>
