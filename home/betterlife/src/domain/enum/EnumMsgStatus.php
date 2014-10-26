<?php
/**
 *---------------------------------------<br/>
 * 枚举类型:消息状态  <br/> 
 *---------------------------------------<br/>
 * @category betterlife
 * @package domain
 * @subpackage enum 
 * @author skygreen skygreen2001@gmail.com
 */
class EnumMsgStatus extends Enum
{
	/**
	 * 消息状态:未读
	 */
	const UNREAD='0';
	/**
	 * 消息状态:已读
	 */
	const READ='1';

	/** 
	 * 显示消息状态<br/>
	 * 枚举类型。<br/>
	 * 0:未读-unread<br/>
	 * 1:已读-read<br/>
	 */
	public static function statusShow($status)
	{
	   switch($status){ 
			case self::UNREAD:
				return "未读"; 
			case self::READ:
				return "已读"; 
	   }
	   return "未知";
	}

	/** 
	 * 根据消息状态显示文字获取消息状态<br/>
	 * @param mixed $statusShow 消息状态显示文字
	 */
	public static function statusByShow($statusShow)
	{
	   switch($statusShow){ 
			case "未读":
				return self::UNREAD; 
			case "已读":
				return self::READ; 
	   }
	   return self::UNREAD;
	}

}
?>
