<?php
/**
 +---------------------------------------<br/>
 * 通知<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.msg
 * @author skygreen
 */
class Notice extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 管理员编号
     * @var int
     * @access private 
     */
    private $senderId;

    /**
     * 分类
     * @var string
     * @access private 
     */
    private $group;

    /**
     * 标题
     * @var string
     * @access private 
     */
    private $title;

    /**
     * 通知内容
     * @var string
     * @access private 
     */
    private $content;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setSenderId($senderId){
        $this->senderId=$senderId;
    }

    public function getSenderId(){
        return $this->senderId;
    }

    public function setGroup($group){
        $this->group=$group;
    }

    public function getGroup(){
        return $this->group;
    }

    public function setTitle($title){
        $this->title=$title;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setContent($content){
        $this->content=$content;
    }

    public function getContent(){
        return $this->content;
    }
    //</editor-fold>
}
?>