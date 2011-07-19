<?php
/**
 +---------------------------------------<br/>
 * 评论<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.core
 * @author skygreen
 */
class Comment extends DataObject {
    
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 评论者编号
     * @var int
     * @access private 
     */
    private $userId;

    /**
     * 评论
     * @var string
     * @access private 
     */
    private $comment;

    /**
     * 博客编号
     * @var int
     * @access private 
     */
    private $blogId;
    //</editor-fold>


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

    public function setComment($comment){
        $this->comment=$comment;
    }

    public function getComment(){
        return $this->comment;
    }

    public function setBlogId($blogId){
        $this->blogId=$blogId;
    }

    public function getBlogId(){
        return $this->blogId;
    }
    //</editor-fold>

    public function getCommitTime() {
        return date("Y-m-d H:i",strtotime($this->commitTime));
    }    

}
?>
