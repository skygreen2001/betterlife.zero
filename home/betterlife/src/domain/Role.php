<?php
/**
 +---------------------------------------<br/>
 * 角色<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
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
    public $ID;
    /**
     * 角色名称
     * @var string
     * @access public
     */
    public $Role_Name;
    //</editor-fold>

    /**
     * 从属于多对多关系
     */
    static $belongs_many_many=array(
        "users"=>"User"
    );

}
?>