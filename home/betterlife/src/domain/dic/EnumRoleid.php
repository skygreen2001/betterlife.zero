<?php
/**
 *---------------------------------------<br/>
 * 枚举类型:系统管理员扮演角色。  <br/> 
 *---------------------------------------<br/>
 * @category betterlife
 * @package domain
 * @subpackage enum 
 * @author skygreen skygreen2001@gmail.com
 */
class EnumRoleid extends Enum
{
    /**
     * 系统管理员扮演角色。:超级管理员
     */
    const SUPERADMIN='0';
    /**
     * 系统管理员扮演角色。:管理人员
     */
    const MANAGER='1';
    /**
     * 系统管理员扮演角色。:运维人员
     */
    const NORMAL='2';
    /**
     * 系统管理员扮演角色。:合作伙伴
     */
    const PARTNER='3';

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
       switch($roleid){ 
            case self::SUPERADMIN:
                return "超级管理员"; 
            case self::MANAGER:
                return "管理人员"; 
            case self::NORMAL:
                return "运维人员"; 
            case self::PARTNER:
                return "合作伙伴"; 
       }
       return "未知";
    }

    /** 
     * 根据系统管理员扮演角色。显示文字获取系统管理员扮演角色。<br/>
     * @param mixed $roleidShow 系统管理员扮演角色。显示文字
     */
    public static function roleidByShow($roleidShow)
    {
       switch($roleidShow){ 
            case "超级管理员":
                return self::SUPERADMIN; 
            case "管理人员":
                return self::MANAGER; 
            case "运维人员":
                return self::NORMAL; 
            case "合作伙伴":
                return self::PARTNER; 
       }
       return self::SUPERADMIN;
    }

}
?>
