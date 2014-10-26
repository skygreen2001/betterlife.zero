<?php
/**
 *---------------------------------------<br/>
 * 枚举类型:会员性别  <br/> 
 *---------------------------------------<br/>
 * @category betterlife
 * @package domain
 * @subpackage enum 
 * @author skygreen skygreen2001@gmail.com
 */
class EnumSex extends Enum
{
	/**
	 * 会员性别:女
	 */
	const FEMALE='0';
	/**
	 * 会员性别:男
	 */
	const MALE='1';
	/**
	 * 会员性别:待确认
	 */
	const UNKNOWN='-1';

	/** 
	 * 显示会员性别<br/>
	 * 0：女-female<br/>
	 * 1：男-male<br/>
	 * -1：待确认-unknown<br/>
	 * 默认男<br/>
	 */
	public static function sexShow($sex)
	{
	   switch($sex){ 
			case self::FEMALE:
				return "女"; 
			case self::MALE:
				return "男"; 
			case self::UNKNOWN:
				return "待确认"; 
	   }
	   return "未知";
	}

	/** 
	 * 根据会员性别显示文字获取会员性别<br/>
	 * @param mixed $sexShow 会员性别显示文字
	 */
	public static function sexByShow($sexShow)
	{
	   switch($sexShow){ 
			case "女":
				return self::FEMALE; 
			case "男":
				return self::MALE; 
			case "待确认":
				return self::UNKNOWN; 
	   }
	   return self::FEMALE;
	}

}
?>
