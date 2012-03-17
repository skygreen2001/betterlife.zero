<?php
/**
 +---------------------------------------<br/>
 * 通知<br/>
 +---------------------------------------
 * @category betterlife
 * @package msg
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
    public $notice_id;
    /**
     * 管理员编号
     * @var int
     * @access public
     */
    public $user_id;
    /**
     * 分类
     * @var string
     * @access public
     */
    public $group;
    /**
     * 标题
     * @var string
     * @access public
     */
    public $title;
    /**
     * 通知内容
     * @var string
     * @access public
     */
    public $content;
    //</editor-fold>
}
?>