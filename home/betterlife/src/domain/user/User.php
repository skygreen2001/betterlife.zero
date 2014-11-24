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
	 * 用户标识
	 * @var int
	 * @access public
	 */
	public $user_id;
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
	/**
	 * 邮箱地址
	 * @var string
	 * @access public
	 */
	public $email;
	/**
	 * 手机电话
	 * @var string
	 * @access public
	 */
	public $cellphone;
	/**
	 * 访问次数
	 * @var int
	 * @access public
	 */
	public $loginTimes;
	//</editor-fold>

	/**
	 * 一对一关系
	 */
	static $has_one=array(
		"userDetail"=>"Userdetail"
	);

	/**
	 * 一对多关系
	 */
	static $has_many=array(
		"blogs"=>"Blog",
		"comment"=>"Comment"
	);

	/**
	 * 多对多关系
	 */
	static $many_many=array(
		"roles"=>"Role"
	);

	public function getUsernameShow() {
		$username=UtilString::gbk2utf8($this->username);
		return $username;
	}
}

?>