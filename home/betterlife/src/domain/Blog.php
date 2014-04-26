<?php
/**
 +---------------------------------------<br/>
 * 博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Blog extends DataObject
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
     * 博客标题
     * @var string
     * @access public
     */
    public $Blog_Name;
    /**
     * 博客内容
     * @var string
     * @access public
     */
    public $Blog_Content;
    //</editor-fold>

}
?>