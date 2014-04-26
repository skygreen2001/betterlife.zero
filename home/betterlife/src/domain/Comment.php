<?php
/**
 +---------------------------------------<br/>
 * 评论<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Comment extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $ID;
    /**
     * 评论者标识
     * @var int
     * @access public
     */
    public $User_ID;
    /**
     * 评论
     * @var string
     * @access public
     */
    public $Comment;
    /**
     * 博客标识
     * @var int
     * @access public
     */
    public $Blog_ID;
    //</editor-fold>

}
?>