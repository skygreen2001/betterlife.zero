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

    /**
     * 数据库使用SqlServer，需使用字符转换:GBK->UTF8
     */
    public function getBlog_NameShow() {
        $name=UtilString::gbk2utf8($this->name);         
        return $name;   
    }
    
    /**
     * 数据库使用SqlServer，需使用字符转换:GBK->UTF8
     */
    public function getContentShow() {
        $content=UtilString::gbk2utf8($this->Blog_Content);         
        return $content;
    }    
                     
    /**
    * 当前登录用户是否可编辑该博客
    * @return bool true 可以
    */
    public function canEdit(){
        if (HttpSession::get("user_id")==$this->User_ID) {
            return true;
        }       
        return false;
    }
    
    /**
    * 当前登录用户是否可删除该博客
    * @return bool true 可以
    */
    public function canDelete(){
        if (HttpSession::get("user_id")==$this->User_ID) {
            return true;
        }       
        return false;
    }     
    
    /**
     * 返回计算当前博客的评论数
     * @param mixed $blog_id    
     */
    public function count_comments(){
        return Comment::count("Blog_ID=".$this->ID);     
    }    
    
    /**
     * 从属一对一关系
     */
    static $belong_has_one=array(
        "user"=>"User"
    );

    /**
     * 一对多关系
     */
    static $has_many=array(
        "comments"=>"Comment"
    );

}
?>