<?php
/**
 +---------------------------------------<br/>
 * 用户角色<br/>
 * 用户角色<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Userrole extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 标识
     * @var int
     * @access public
     */
    public $ID;
    /**
     * 用户标识
     * @var int
     * @access public
     */
    public $User_ID;
    /**
     * 角色标识
     * @var int
     * @access public
     */
    public $Role_ID;
    //</editor-fold>
    /**
     * 规格说明
     * 表中不存在的默认列定义:CommitTime,UpdateTime
     * @var mixed
     */
    public $field_spec=array(
        EnumDataSpec::REMOVE=>array(
            'CommitTime',
            'UpdateTime'
        )
    );

}
?>