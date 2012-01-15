<?php
/**
 +---------------------------------<br/>
 * 所有数据库访问对象的父类<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db.object
 * @author skygreen
 */
abstract class Dao {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * @var string 对象类名
     */
    protected $classname;
    /**
     * @var string 数据库连接
     */
    protected $connection;
    /**
     * @var string SQL语句
     */
    protected $sQuery;
    /**
     * @var array 预编译准备SQL参数
     */
    protected $saParams;
    /**
     * @var mixed 预编译准备SQL表达式容器
     */
    protected $stmt;
    /**
     * @var mixed 执行SQL的结果
     */
    protected $result;
    /**
     * Sql 连接符
     */
    const EQUAL="=";
    //</editor-fold>
    
    /**
     * 构造器
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param enum $dbtype 指定数据库类型。{使用Dao_ODBC引擎，需要定义该字段,该字段的值参考：EnumDbSource}
     *                      需要在实现里重载 setdbType方法以传入数据库类型参数
     */
    public function __construct($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null) {
        if (isset($dbtype)){
            $this->setdbType($dbtype);
        }    
        $this->connect($host,$port,$username,$password,$dbname);
    }
    
    /**
     * 指定数据库类型
     * @param enum $dbtype 指定数据库类型。{使用Dao_ODBC引擎，需要定义该字段,该字段的值参考：EnumDbSource}
     */
    protected function setdbType($dbtype){
        
    }

    /**
     * 连接数据库
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @return mixed 数据库连接
     */
    abstract protected function connect($host=null,$port=null,$username=null,$password=null,$dbname=null);

    /**
     * 获取数据库连接
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @return mixed 数据库连接 
     */
    public function getConnection($host=null,$port=null,$username=null,$password=null,$dbname=null) {
        if ($this->connection==null) {
            $this->connect($host,$port,$username,$password,$dbname);
        }
        return $this->connection;
    }

    /**
     * 返回基于主键查询的sql语句
     * @param mixed $object 对象实体|对象名称
     * @return string 基于主键的sql语句,如主键列名为user_id,则返回"user_id"
     */
    protected function sql_id($object){  
        if (is_string($object)) {
            if (class_exists($object)) {
                $object=new $object();
            }
        }
        if ($object instanceof DataObject){
            return DataObjectSpec::getRealIDColumnName($object);
        }
        e(Wl::ERROR_INFO_EXTENDS_CLASS);
    }
    
    /**
     * 察看传入参数是否合法
     * @param string|class $object 需要更新的对象实体|对象名称【Id是已经存在的】
     * @param boolean
     */
    protected function validParameter($object) {
        if (is_string($object)) {
            if (class_exists($object)) {
                if ((new $object()) instanceof DataObject) {
                    $this->classname=$object;
                    return true;
                }else {
                    e(Wl::ERROR_INFO_EXTENDS_CLASS,$this);
                    return false;
                }
            }
        }else {
            return $this->validObjectParameter($object);
        }
    }
    
    /**
     * 将数据对象里的显示属性进行清除
     * 规范：数据对象里的显示属性以v_开始
     * @param array $saParams 预编译准备SQL参数
     */
    protected function filterViewProperties(&$saParams) {
        if (isset($saParams)&&is_array($saParams)) {
            $keys=array_keys($saParams);
            foreach ($keys as $key){
                if (strpos((substr($key,0,2)),"v_")!==false){
                    unset($saParams[$key]);
                }
            }
        }
    }

    /**
     * 察看传入参数是否合法
     * @param class $object 需要更新的对象实体|对象名称【Id是已经存在的】
     * @param boolean
     */
    protected function validObjectParameter($object) {
        if (is_object($object)) {
            if ($object instanceof DataObject) {
                $this->classname=$object->classname();
            }else {
                e(Wl::ERROR_INFO_EXTENDS_CLASS,$this);
                return false;
            }
        }else {
            e(Wl::ERROR_INFO_NEED_OBJECT_CLASSNAME,$this);
            return false;
        }
        return true;
    }
    
    /**
     * 获取插入或者更新的数据的类型。
     * @param string|class $object 需要生成注入的对象实体|类名称
     * @param array $saParams 对象field名称值键值对
     * @param array $typeOf <br/>
     *      0:通用的协议定义的类型标识，暂未实现。<br/>
     *      1:PHP定义的数据类型标识，暂未实现。<br/>
     * @return array 获取插入或者更新的数据的field和field值类型键值对
     */
    public function getColumnTypes($object,$saParams,$typeOf=1){
        $type=array();
        foreach ($saParams  as $key => $value) {
          $type[$key]="s";
        }
        return $type;
    }    
    
    /**
     * 当查询结果集只有一个值的时候，直接返回该值
     * @param stdClass $result 结果集
     * @return 值
     */
    protected function getValueIfOneValue($result){
        if (($result!=null)&&(count($result)==1)){                   
            if($result[0] instanceof stdClass){
                $tmp=UtilObject::object_to_array($result[0]);
                if (count($tmp)==1){
                   $tmp_values= array_values($tmp);
                   $result=$tmp_values[0];
                }
            }
        }
        return $result;
    }
}
?>
