<?php
/**
 +---------------------------------------<br/>
 * 系统管理人员<br/>
 +---------------------------------------
 * @category betterlife
 * @package user
 * @author skygreen skygreen2001@gmail.com
 */
class Admin extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 
	 * @var int
	 * @access public
	 */
	public $admin_id;
	/**
	 * 用户名
	 * @var string
	 * @access public
	 */
	public $username;
	/**
	 * 真实姓名
	 * @var string
	 * @access public
	 */
	public $realname;
	/**
	 * 密码
	 * @var string
	 * @access public
	 */
	public $password;
	/**
	 * 系统管理员扮演角色。<br/>
	 * 0:超级管理员-superadmin<br/>
	 * 1:管理人员-manager<br/>
	 * 2:运维人员-normal<br/>
	 * 3:合作伙伴-partner<br/>
	 * 4:供应商-supplier<br/>
	 * 5:渠道商-channel<br/>
	 * <br/>
	 * 
	 * @var enum
	 * @access public
	 */
	public $roletype;    
	/**
	 * 角色标识<br/>
	 * 角色为渠道商或者供应商时为供应商标识。
	 * @var int
	 * @access public
	 */
	public $roleid; 
	/**
	 * 视野<br/>
	 * 0:只能查看自己的信息-self<br/>
	 * 1:查看所有的信息-all<br/>
	 * 
	 * @var enum
	 * @access public
	 */
	public $seescope;
	//</editor-fold>

	/** 
	 * 显示系统管理员扮演角色。<br/>
	 * 0:超级管理员-superadmin<br/>
	 * 1:管理人员-manager<br/>
	 * 2:运维人员-normal<br/>
	 * 3:合作伙伴-partner<br/>
	 * 4:供应商-supplier<br/>
	 * 5:渠道商-channel<br/>
	 * <br/>
	 * <br/>
	 */
	public function getRoletypeShow()
	{
		return self::roletypeShow($this->roletype);
	}

	/** 
	 * 显示视野<br/>
	 * 0:只能查看自己的信息-self<br/>
	 * 1:查看所有的信息-all<br/>
	 * <br/>
	 */
	public function getSeescopeShow()
	{
		return self::seescopeShow($this->seescope);
	}

	/** 
	 * 显示系统管理员扮演角色。<br/>
	 * 0:超级管理员-superadmin<br/>
	 * 1:管理人员-manager<br/>
	 * 2:运维人员-normal<br/>
	 * 3:合作伙伴-partner<br/>
	 * 4:供应商-supplier<br/>
	 * 5:渠道商-channel<br/>
	 * <br/>
	 * <br/>
	 */
	public static function roletypeShow($roletype)
	{
		return EnumRoletype::roletypeShow($roletype);
	}

	/** 
	 * 显示视野<br/>
	 * 0:只能查看自己的信息-self<br/>
	 * 1:查看所有的信息-all<br/>
	 * <br/>
	 */
	public static function seescopeShow($seescope)
	{
		return EnumSeescope::seescopeShow($seescope);
	}
}
?>