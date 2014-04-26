<?php
/**
 +---------------------------------------<br/>
 * 通知<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Notice extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 编号
     * @var int
     * @access public
     */
    public $ID;
    /**
     * 通知分类
     * @var int
     * @access public
     */
    public $NoticeType;
    /**
     * 标题
     * @var string
     * @access public
     */
    public $Title;
    /**
     * 通知内容
     * @var string
     * @access public
     */
    public $Notice_Content;
    //</editor-fold>

}
?>