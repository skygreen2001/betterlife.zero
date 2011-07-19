<?php
/**
 +--------------------------------------------------<br/>
 * 加载网站内的类【在系统里只需动态加载一次的对象】<br/>
 * 采用Singleton模式<br/>
 +--------------------------------------------------<br/>
 * @category betterlife
 * @package core.main
 * @author skygreen
 */
class Loader {
    const CLASS_CACHE="Cache";
    const CLASS_MODEL="Model";
    const CLASS_VIEW="View";
    private static $loaded = array();

    /**
     * @param Object $object
     * @param <type> 构造对象时传入的参数
     * @return Object
     */
    public static function load($object,$param1=null,$param2=null) {
        $valid = array(
                self::CLASS_CACHE,
                self::CLASS_MODEL,
                self::CLASS_VIEW
        );
        if (!in_array($object,$valid)) {
            if (Gc::$dev_debug_on) {
                ExceptionMe::backtrace();
            }
            e("Not a valid object '{$object}' to load",$this);
        }
        if (empty(self::$loaded[$object])) {
            if (empty($param1)) {
                self::$loaded[$object]= new $object();
            }else {
                self::$loaded[$object]= new $object($param1,$param2);
            }
        }
        return self::$loaded[$object];
    }

    /**
     * 获取网站物理路径
     * @return string 获取路径
     */
    public static function basePath() {
        return getcwd();
    }
}
?>
