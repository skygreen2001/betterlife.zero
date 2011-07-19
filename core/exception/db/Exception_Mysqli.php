<?php
/**
 +----------------------------------------<br/>
 * MysqlI异常处理类<br/>
 +----------------------------------------<br/>
 * @category betterlife
 * @package core.exception.db
 * @author zhouyuepu
 */
class Exception_Mysqli extends Exception_Db {
    /**
     * MysqlI 异常记录：记录Myql的异常信息
     * @param string $extra  补充存在多余调试信息
     * @param string $category 异常分类
     */
    public static function record($extra=null,$category=null,$link=null) {
        if ($link==null) {
            $link=Manager_Db::newInstance()->currentdao()->getConnection();
        }
        if (mysqli_error($link)) {
            if (!isset ($category)) {
                $category=  Exception_Db::CATEGORY_MYSQL;
            }
            $errorinfo=mysqli_error($link);
            self::recordException($errorinfo, $category,mysqli_errno($link),$extra);
        }
    }

}
?>
