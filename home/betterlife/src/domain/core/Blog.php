<?php
/**
 +---------------------------------------<br/>
 * 博客<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.core
 * @author skygreen
 */
class Blog extends DataObject {
    
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 用户编号
     * @var int
     * @access private 
     */
    private $userId;

    /**
     * 博客名称
     * @var string
     * @access private 
     */
    private $name;

    /**
     * 博客内容
     * @var string
     * @access private 
     */
    private $content;
    //</editor-fold>
    
    static $has_many=array(
      "comments"=>"Comment"
    );

    static $belong_has_one=array(
      "user"=>"User"
    );
    
    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setUserId($userId){
        $this->userId=$userId;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setName($name){
        $this->name=$name;
    }

    public function getName(){
        return $this->name;
    }

    public function setContent($content){
        $this->content=$content;
    }

    public function getContent(){
        return $this->content;
    }
    //</editor-fold>
    
    public function getNameShow() {
        $name=UtilString::gbk2utf8($this->name);         
        return $name;   
    }
    
    public function getContentShow() {
        $content=UtilString::gbk2utf8($this->content);         
        return $content;
    }    
    
    public function getCommitTimeShow(){
        return date("Y-m-d H:i",strtotime($this->commitTime)); 
    }
    /**
    * 当前登录用户是否可编辑该博客
    * @return bool true 可以
    */
    public function canEdit(){
        if (HttpSession::get("userid")==$this->userId) {
            return true;
        }       
        return false;
    }
    
    /**
    * 当前登录用户是否可删除该博客
    * @return bool true 可以
    */
    public function canDelete(){
        if (HttpSession::get("userid")==$this->userId) {
            return true;
        }       
        return false;
    }
}
?>
