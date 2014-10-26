<?php
/**
 +---------------------------------------<br/>
 * 角色<br/>
 +---------------------------------------
 * @category betterlife
 * @package user
 * @author skygreen skygreen2001@gmail.com
 */
class Role extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 角色标识
	 * @var int
	 * @access public
	 */
	public $role_id;
	/**
	 * 角色名称
	 * @var string
	 * @access public
	 */
	public $role_name;
	//</editor-fold>

	/**
	 * 一对多关系
	 */
	static $has_many=array(
		"rolefunctionss"=>"Rolefunctions",
		"userroles"=>"Userrole"
	);

	/**
	 * 多对多关系
	 */
	static $many_many=array(
		"functionss"=>"Functions"
	);

	/**
	 * 从属于多对多关系
	 */
	static $belongs_many_many=array(
		"users"=>"User"
	);

}
?>