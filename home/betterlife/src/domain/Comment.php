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

    /**
     * 从属一对一关系
     */
    static $belong_has_one=array(
        "user"=>"User",
        "blog"=>"Blog"
    );
        
    /**
    * 当前登录用户是否可编辑该评论
    * @return bool true 可以
    */
    public function canEdit(){
        if (HttpSession::get("user_id")==$this->User_ID) {
            return true;
        }       
        return false;
    }
    
    /**
    * 当前登录用户是否可删除该评论
    * @return bool true 可以
    */
    public function canDelete(){
        if (HttpSession::get("user_id")==$this->User_ID) {
            return true;
        }       
        return false;
    }   
    
}
?>