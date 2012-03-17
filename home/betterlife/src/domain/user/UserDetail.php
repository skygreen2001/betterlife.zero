<?php
/**
 +---------------------------------------<br/>
 * 用户详细信息<br/>
 +---------------------------------------
 * @category betterlife
 * @package user
 * @author skygreen skygreen2001@gmail.com
 */
class Userdetail extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $userdetail_id;
    /**
     * 用户标识
     * @var int
     * @access public
     */
    public $user_id;
    /**
     * 邮件地址
     * @var string
     * @access public
     */
    public $email;
    /**
     * 手机号码
     * @var string
     * @access public
     */
    public $cellphone;
    //</editor-fold>
}
?>