<?php
/**
 *---------------------------------------<br/>
 * 枚举类型:地区类型  <br/>
 *---------------------------------------<br/>
 * @category betterlife
 * @package domain
 * @subpackage enum
 * @author skygreen skygreen2001@gmail.com
 */
class EnumRegionType extends Enum
{
	/**
	 * 地区类型:国家
	 */
	const COUNTRY='0';
	/**
	 * 地区类型:省
	 */
	const PROVINCE='1';
	/**
	 * 地区类型:市
	 */
	const CITY='2';
	/**
	 * 地区类型:区
	 */
	const REGION='3';

	/**
	 * 显示地区类型<br/>
	 * 0:国家-country<br/>
	 * 1:省-province<br/>
	 * 2:市-city<br/>
	 * 3:区-region<br/>
	 * <br/>
	 */
	public static function region_typeShow($region_type)
	{
		switch($region_type){
			case self::COUNTRY:
				return "国家";
			case self::PROVINCE:
				return "省";
			case self::CITY:
				return "市";
			case self::REGION:
				return "区";
		}
		return "未知";
	}

	/**
	 * 根据地区类型显示文字获取地区类型<br/>
	 * @param mixed $region_typeShow 地区类型显示文字
	 */
	public static function region_typeByShow($region_typeShow)
	{
		switch($region_typeShow){
			case "国家":
				return self::COUNTRY;
			case "省":
				return self::PROVINCE;
			case "市":
				return self::CITY;
			case "区":
				return self::REGION;
		}
		return self::COUNTRY;
	}

	/**
	 * 通过枚举值获取枚举键定义<br/>
	 */
	public static function region_typeEnumKey($region_type)
	{
		switch($region_type){
			case '0':
				return "COUNTRY";
			case '1':
				return "PROVINCE";
			case '2':
				return "CITY";
			case '3':
				return "REGION";
		}
		return "COUNTRY";
	}

}
?>
