<?php
/**
 +---------------------------------------<br/>
 * 用户<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class User extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 用户标识
     * @var int
     * @access public
     */
    public $ID;
    /**
     * 用户名
     * @var string
     * @access public
     */
    public $Username;
    /**
     * 用户密码
     * @var string
     * @access public
     */
    public $Password;
    /**
     * 邮箱地址
     * @var string
     * @access public
     */
    public $Email;
    /**
     * 手机电话
     * @var string
     * @access public
     */
    public $Cellphone;
    /**
     * 访问次数
     * @var int
     * @access public
     */
    public $LoginTimes;
    //</editor-fold>

    /**
     * 一对一关系
     */
    static $has_one=array(
        "userDetail"=>"Userdetail"
    );

    /**
     * 一对多关系
     */
    static $has_many=array(
        "blogs"=>"Blog",
        "comment"=>"Comment"
    );

    /**
     * 多对多关系
     */
    static $many_many=array(
        "roles"=>"Role"
    );

}
?>