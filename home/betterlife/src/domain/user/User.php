<?php
/**
 +---------------------------------------<br/>
 * 用户<br/>
 +---------------------------------------
 * @category betterlife
 * @package user
 * @author skygreen skygreen2001@gmail.com
 */
class User extends DataObject
{
	//<editor-fold defaultstate="collapsed" desc="定义部分">
	/**
	 * 标识
	 * @var int
	 * @access public
	 */
	public $user_id;
	/**
	 * 部门标识
	 * @var int
	 * @access public
	 */
	public $department_id;
	/**
	 * 用户名
	 * @var string
	 * @access public
	 */
	public $username;
	/**
	 * 用户密码
	 * @var string
	 * @access public
	 */
	public $password;
	//</editor-fold>
	
	/**
	 * 调用启动方法-》$this->userDetail();
	 * @var array
	 */
	static $has_one=array(    
		"userDetail"=> "Userdetail",            
	);

	static $belong_has_one=array(    
		"department"=> "Department",            
	);

	
	static $many_many=array(
	   "roles"=>"Role",
	);
	
	public function getNameShow() {
		$name=UtilString::gbk2utf8($this->name);    
		return $name;
	}
}
?>