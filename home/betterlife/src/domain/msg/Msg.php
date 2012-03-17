<?php
/**
 +---------------------------------------<br/>
 * 消息<br/>
 +---------------------------------------
 * @category betterlife
 * @package msg
 * @author skygreen skygreen2001@gmail.com
 */
class Msg extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识<br/>
     * 消息编号
     * @var int
     * @access public
     */
    public $msg_id;
    /**
     * 发送者<br/>
     * 发送者用户编号
     * @var int
     * @access public
     */
    public $senderId;
    /**
     * 接收者<br/>
     * 接收者用户编号
     * @var int
     * @access public
     */
    public $receiverId;
    /**
     * 发送者名称
     * @var string
     * @access public
     */
    public $senderName;
    /**
     * 接收者名称
     * @var string
     * @access public
     */
    public $receiverName;
    /**
     * 发送内容
     * @var string
     * @access public
     */
    public $content;
    /**
     * 消息状态<br/>
     * 枚举类型。<br/>
     * 0:未读-unread<br/>
     * 1:已读-read
     * @var enum
     * @access public
     */
    public $status;
    //</editor-fold>

    /** 
     * 显示消息状态<br/>
     * 枚举类型。<br/>
     * 0:未读-unread<br/>
     * 1:已读-read<br/>
     */
    public function getStatusShow()
    {
        return self::statusShow($this->status);
    }

    /** 
     * 显示消息状态<br/>
     * 枚举类型。<br/>
     * 0:未读-unread<br/>
     * 1:已读-read<br/>
     */
    public static function statusShow($status)
    {
        return EnumMsgStatus::statusShow($status);
    }
}
?>