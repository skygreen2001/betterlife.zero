<?php
/**
 +---------------------------------------<br/>
 * 消息<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
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
    public $ID;
    /**
     * 发送者<br/>
     * 发送者用户编号
     * @var int
     * @access public
     */
    public $SenderId;
    /**
     * 接收者<br/>
     * 接收者用户编号
     * @var int
     * @access public
     */
    public $ReceiverId;
    /**
     * 发送者名称
     * @var string
     * @access public
     */
    public $SenderName;
    /**
     * 接收者名称
     * @var string
     * @access public
     */
    public $ReceiverName;
    /**
     * 发送内容
     * @var string
     * @access public
     */
    public $Content;
    /**
     * 消息状态<br/>
     * 枚举类型。<br/>
     * 0:未读-unread<br/>
     * 1:已读-read
     * @var enum
     * @access public
     */
    public $Status;
    //</editor-fold>

    /** 
     * 显示消息状态<br/>
     * 枚举类型。<br/>
     * 0:未读-unread<br/>
     * 1:已读-read<br/>
     */
    public function getStatusShow()
    {
        return self::StatusShow($this->Status);
    }

    /** 
     * 显示消息状态<br/>
     * 枚举类型。<br/>
     * 0:未读-unread<br/>
     * 1:已读-read<br/>
     */
    public static function StatusShow($Status)
    {
        return EnumMsgStatus::StatusShow($Status);
    }

}
?>