<?php
/**
 *---------------------------------------<br/>
 * 枚举类型:类型  <br/>
 *---------------------------------------<br/>
 * @category betterlife
 * @package domain
 * @subpackage enum
 * @author skygreen skygreen2001@gmail.com
 */
class EnumUserType extends Enum
{
    /**
     * 类型:登录
     */
    const LOGIN='1';
    /**
     * 类型:写日志
     */
    const BLOG='2';
    /**
     * 类型:写评论
     */
    const COMMENT='3';

    /**
     * 显示类型<br/>
     * 1:登录-LOGIN<br/>
     * 2:写日志-BLOG<br/>
     * 3:写评论-COMMENT<br/>
     */
    public static function userTypeShow($userType)
    {
        switch($userType){
            case self::LOGIN:
                return "登录";
            case self::BLOG:
                return "写日志";
            case self::COMMENT:
                return "写评论";
        }
        return "未知";
    }

    /**
     * 根据类型显示文字获取类型<br/>
     * @param mixed $userTypeShow 类型显示文字
     */
    public static function userTypeByShow($userTypeShow)
    {
        switch($userTypeShow){
            case "登录":
                return self::LOGIN;
            case "写日志":
                return self::BLOG;
            case "写评论":
                return self::COMMENT;
        }
        return self::LOGIN;
    }

    /**
     * 通过枚举值获取枚举键定义<br/>
     */
    public static function userTypeEnumKey($userType)
    {
        switch($userType){
            case '1':
                return "LOGIN";
            case '2':
                return "BLOG";
            case '3':
                return "COMMENT";
        }
        return "LOGIN";
    }

}
?>
