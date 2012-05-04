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
     * <br/>
     * <br/>
     * 
     * @var enum
     * @access public
     */
    public $roleid;
    //</editor-fold>

    /** 
     * 显示系统管理员扮演角色。<br/>
     * 0:超级管理员-superadmin<br/>
     * 1:管理人员-manager<br/>
     * 2:运维人员-normal<br/>
     * 3:合作伙伴-partner<br/>
     * <br/>
     * <br/>
     * <br/>
     */
    public function getRoleidShow()
    {
        return self::roleidShow($this->roleid);
    }

    /** 
     * 显示系统管理员扮演角色。<br/>
     * 0:超级管理员-superadmin<br/>
     * 1:管理人员-manager<br/>
     * 2:运维人员-normal<br/>
     * 3:合作伙伴-partner<br/>
     * <br/>
     * <br/>
     * <br/>
     */
    public static function roleidShow($roleid)
    {
        return EnumRoleid::roleidShow($roleid);
    }
}
?>