<?php
/**
 +----------------------------------------<br/>
 * 所有数据库异常的父类<br/>
 +----------------------------------------<br/>
 * @category betterlife
 * @package core.exception.db
 * @author zhouyuepu
 */
class Exception_Db extends ExceptionMe {
    const CATEGORY_MYSQL="MySQL Error";
    const CATEGORY_PDO="Pdo Error";
    /**
     * 数据库异常记录：记录数据库的异常信息
     * @param string $extra  补充存在多余调试信息
     * @param string $category 异常分类
     */
    public static function record($extra=null,$category=null,$link=null) {

    }
    /**
     * PDO 异常记录：记录PDO的异常信息
     * @param string $category 异常分类
     */
    public static function log($errorInfo,$object=null,$code=0,$extra=null) {
        if (Gc::$dev_debug_on){
            parent::recordException($errorInfo, $object, $code, $extra);
        }
    }
}
?>
