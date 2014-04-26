<?php
/**
 +---------------------------------------<br/>
 * 用户日志<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Loguser extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $ID;
    /**
     * 用户标识
     * @var int
     * @access public
     */
    public $User_ID;
    /**
     * 类型<br/>
     * 1:登录-LOGIN<br/>
     * 2:写日志-BLOG<br/>
     * 3:写评论-COMMENT
     * @var enum
     * @access public
     */
    public $UserType;
    /**
     * 日志详情<br/>
     * 一般日志类型决定了内容；这一栏一般没有内容
     * @var string
     * @access public
     */
    public $Log_Content;
    //</editor-fold>
    /**
     * 规格说明
     * 表中不存在的默认列定义:UpdateTime
     * @var mixed
     */
    public $field_spec=array(
        EnumDataSpec::REMOVE=>array(
            'UpdateTime'
        )
    );

    /** 
     * 显示类型<br/>
     * 1:登录-LOGIN<br/>
     * 2:写日志-BLOG<br/>
     * 3:写评论-COMMENT<br/>
     */
    public function getUserTypeShow()
    {
        return self::UserTypeShow($this->UserType);
    }

    /** 
     * 显示类型<br/>
     * 1:登录-LOGIN<br/>
     * 2:写日志-BLOG<br/>
     * 3:写评论-COMMENT<br/>
     */
    public static function UserTypeShow($UserType)
    {
        return EnumUserType::UserTypeShow($UserType);
    }

}
?>