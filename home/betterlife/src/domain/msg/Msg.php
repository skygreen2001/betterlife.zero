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
	 * 发送者<br/>
	 * 发送者用户编号
	 * @var int
	 * @access public
	 */
	private $senderId;

	/**
	 * 接收者<br/>
	 * 接收者用户编号
	 * @var int
	 * @access public
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
	 * 消息状态<br/>
	 * 枚举类型。<br/>
	 * 0:未读<br/>
	 * 1:已读
	 * @var enum
	 * @access public
	 */
	public $status;
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

	public function setStatus($status){
		$this->status=$status;
	}

	public function getStatus(){
		return $this->status;
	}
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