<?php
/**
 +---------------------------------------<br/>
 * 用户所属部门<br/>
 +---------------------------------------
 * @category betterlife
 * @package 
 * @author skygreen skygreen2001@gmail.com
 */
class Department extends DataObject
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 编号
     * @var int
     * @access public
     */
    public $ID;
    /**
     * 部门名称
     * @var string
     * @access public
     */
    public $Department_Name;
    /**
     * 管理者
     * @var string
     * @access public
     */
    public $Manager;
    /**
     * 预算
     * @var int
     * @access public
     */
    public $Budget;
    /**
     * 实际开销
     * @var int
     * @access public
     */
    public $Actualexpenses;
    /**
     * 预估平均工资<br/>
     * 部门人员预估平均工资
     * @var int
     * @access public
     */
    public $Estsalary;
    /**
     * 实际工资<br/>
     * 部门人员实际平均工资
     * @var int
     * @access public
     */
    public $Actualsalary;
    //</editor-fold>

    /**
     * 一对多关系
     */
    static $has_many=array(
        "users"=>"User"
    );

}
?>