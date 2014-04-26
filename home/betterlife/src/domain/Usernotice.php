<?php
/**
 +---------------------------------------<br/>
 * 用户收到通知<br/>
 * 用户收到通知<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Usernotice extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $ID;
    /**
     * 用户编号
     * @var int
     * @access public
     */
    public $User_ID;
    /**
     * 通知编号
     * @var int
     * @access public
     */
    public $Notice_ID;
    //</editor-fold>

}
?>