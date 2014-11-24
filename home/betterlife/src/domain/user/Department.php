<?php
/**
 +---------------------------------------<br/>
 * 用户所属部门<br/>
 +---------------------------------------
 * @category betterlife
 * @package user
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
	public $department_id;
	/**
	 * 部门名称
	 * @var string
	 * @access public
	 */
	public $department_name;
	/**
	 * 管理者
	 * @var string
	 * @access public
	 */
	public $manager;
	/**
	 * 预算
	 * @var int
	 * @access public
	 */
	public $budget;
	/**
	 * 实际开销
	 * @var int
	 * @access public
	 */
	public $actualexpenses;
	/**
	 * 预估平均工资<br/>
	 * 部门人员预估平均工资
	 * @var int
	 * @access public
	 */
	public $estsalary;
	/**
	 * 实际工资<br/>
	 * 部门人员实际平均工资
	 * @var int
	 * @access public
	 */
	public $actualsalary;
	//</editor-fold>

	/**
	 * 一对多关系
	 */
	static $has_many=array(
		"admins"=>"Admin"
	);

}
?>