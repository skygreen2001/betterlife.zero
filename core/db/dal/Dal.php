<?php
/**
 +---------------------------------<br/>
 * 所有DAL(Data Access Layers)的父类<br/>
 +---------------------------------<br/>
 * @category betterlife
 * @package core.db
 * @subpackage dal
 * @author skygreen
 */
abstract class Dal {
    /**
     * @var string 对象类名
     */
    protected $classname;
    /**
     * @var mixed  数据库连接
     */
    protected $connection;
    /**
     * @var mixed 执行SQL的结果
     */
    protected $result;
    /**
     * @var mixed 预编译准备SQL表达式容器
     */
    protected $stmt;
    /**
     * 连接符
     */
    const EQUAL="=";
    /**
     * 构造器
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
     */
    public function __construct($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null) {
        $this->connect($host,$port,$username,$password,$dbname,$dbtype,$engine);
    }

    /**
     * 连接数据库
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
     * @return mixed 数据库连接
     */
    abstract protected function connect($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null);

    /**
     * 获取数据库连接
     * @param string $host
     * @param string $port
     * @param string $username
     * @param string $password
     * @param string $dbname 
     * @param mixed $dbtype 指定数据库类型。{该字段的值参考：EnumDbSource}
     * @param mixed $engine 指定操作数据库引擎。{该字段的值参考：EnumDbEngine}
     * @return mixed 数据库连接 
     */
    public function getConnection($host=null,$port=null,$username=null,$password=null,$dbname=null,$dbtype=null,$engine=null) {
        if ($this->connection==null) {
            $this->connect($host,$port,$username,$password,$dbname,$dbtype,$engine);
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
     * 将数据对象里的显示属性进行清除
     * 规范：数据对象里的显示属性以v_开始
     * @param array $saParams 预编译准备SQL参数
     */
    protected function filterViewProperties($saParams) 
    {
        if (isset($saParams)&&is_array($saParams)) {
            $keys=array_keys($saParams);
            foreach ($keys as $key) {
                if (strpos((substr($key,0,2)),"v_")!==false) {
                    unset($saParams[$key]);
                }
            }
        }
        return $saParams;
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
     * 设置Mysql数据库字符集
     * @param string $character_code 字符集
     */
    public function change_character_set($character_code=Config_C::CHARACTER_UTF8) {
        $sql = "SET NAMES ".$character_code;
        $this->connection->exec($sql);
    }
    
    /**
     * 获取插入或者更新的数据的类型。
     * @param string|class $object 需要生成注入的对象实体|类名称
     * @param array $saParams 对象field名称值键值对
     * @param array $typeOf <br/>
     *      0:通用的协议定义的类型标识，暂未实现。<br/>
     *      1:PHP定义的数据类型标识，暂未实现。<br/>
     *      2:Mdb2要求的类型标识。<br/>
     * @return array 获取插入或者更新的数据的field和field值类型键值对
     */
    public function getColumnTypes($object,$saParams,$typeOf=1){
        $type=array();
        foreach ($saParams  as $key => $value) {
            if ($typeOf==2){
                $type[$key]="text";
            }
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
