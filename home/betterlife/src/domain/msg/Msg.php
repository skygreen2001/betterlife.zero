<?php
/**
 +---------------------------------------<br/>
 * 消息<br/>
 +---------------------------------------
 * @category betterlife
 * @package domain.msg
 * @author skygreen
 */
class Msg extends DataObject {
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 发送者用户编号
     * @var int
     * @access private 
     */
    private $senderId;

    /**
     * 接收者用户编号
     * @var int
     * @access private 
     */
    private $receiverId;

    /**
     * 发送者名称
     * @var string
     * @access private 
     */
    private $senderName;

    /**
     * 接收者名称
     * @var string
     * @access private 
     */
    private $receiverName;

    /**
     * 发送内容
     * @var string
     * @access private 
     */
    private $content;

    /**
     * 枚举类型。
     * 0:未读
     * 1:已读
     * @var enum
     * @access private 
     */
    private $state;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="setter和getter">
    public function setSenderId($senderId){
        $this->senderId=$senderId;
    }

    public function getSenderId(){
        return $this->senderId;
    }

    public function setReceiverId($receiverId){
        $this->receiverId=$receiverId;
    }

    public function getReceiverId(){
        return $this->receiverId;
    }

    public function setSenderName($senderName){
        $this->senderName=$senderName;
    }

    public function getSenderName(){
        return $this->senderName;
    }

    public function setReceiverName($receiverName){
        $this->receiverName=$receiverName;
    }

    public function getReceiverName(){
        return $this->receiverName;
    }

    public function setContent($content){
        $this->content=$content;
    }

    public function getContent(){
        return $this->content;
    }

    public function setState($state){
        $this->state=$state;
    }

    public function getState(){
        return $this->state;
    }
    //</editor-fold>
}
?>