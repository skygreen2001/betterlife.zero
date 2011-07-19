<?php
/**
 +---------------------------------------<br/>
 * 用户收到通知关系表<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.msg.relation
 * @author skygreen
 */
class Usernotice extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 用户编号
     * @var int
     * @access private 
     */
    private $userId;

    /**
     * 通知编号
     * @var int
     * @access private 
     */
    private $noticeId;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setUserId($userId){
        $this->userId=$userId;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setNoticeId($noticeId){
        $this->noticeId=$noticeId;
    }

    public function getNoticeId(){
        return $this->noticeId;
    }
    //</editor-fold>
}
?>