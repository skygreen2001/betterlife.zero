<?php
/**
 +---------------------------------------<br/>
 * 地区<br/>
 +---------------------------------------
 * @category betterlife
 * @package dic
 * @author skygreen skygreen2001@gmail.com
 */
class Region extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 标识
	 * @var string
	 * @access public
	 */
	public $region_id;
	/**
	 * 父地区标识
	 * @var string
	 * @access public
	 */
	public $parent_id;
	/**
	 * 地区名称
	 * @var string
	 * @access public
	 */
	public $region_name;
	/**
	 * 地区类型<br/>
	 * 0:国家-country<br/>
	 * 1:省-province<br/>
	 * 2:市-city<br/>
	 * 3:区-region<br/>
	 *
	 * @var enum
	 * @access public
	 */
	public $region_type;
	/**
	 * 目录层级
	 * @var string
	 * @access public
	 */
	public $level;
	//</editor-fold>

	/**
	 * 从属一对一关系
	 */
	static $belong_has_one=array(
		"region_p"=>"Region"
	);

	/**
	 * 规格说明:外键声明
	 * @var array
	 */
	public $field_spec=array(
		EnumDataSpec::FOREIGN_ID=>array(
			"region_p"=>"parent_id"
		)
	);

	/**
	 * 显示地区类型<br/>
	 * 0:国家-country<br/>
	 * 1:省-province<br/>
	 * 2:市-city<br/>
	 * 3:区-region<br/>
	 * <br/>
	 */
	public function getRegion_typeShow()
	{
		return self::region_typeShow($this->region_type);
	}

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
		return EnumRegionType::region_typeShow($region_type);
	}

	/**
	 * 最高的层次，默认为3
	 */
	public static function maxlevel()
	{
		return Region::select("max(level)");//return 3;
	}

}
?>