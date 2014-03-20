<?php
/**
 +---------------------------------<br/>
 * 工具类： Ucenter<br/>
 * 负责与Ucenter用户中心的整合
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilUcenter extends Util
{
	/**
	 * 初始化
	 */
	public static function init()
	{
		$uc_client_path= Gc::$nav_root_path."data".DIRECTORY_SEPARATOR.'uc_client'.DIRECTORY_SEPARATOR.'client.php';
		include_once Gc::$nav_root_path.'api'.DIRECTORY_SEPARATOR.'config.inc.php';
		include_once($uc_client_path);
	}

	/**
	 * Ucenter用户登录
	 * @param string $username 用户名
	 * @param string $password 密码
	 * @return int 用户在Ucenter里的唯一标识
	 */
	public static function login($username,$password)
	{
		self::init();
		list($uid, $username, $password, $email) = uc_user_login($username,$password);
		return $uid;
	}

	/**
	 * 返回同步登录的js
	 * @param int $uid 用户在Ucenter里的唯一标识
	 * @return string 同步登录的js
	 */
	public static function synLogin($uid)
	{
		self::init();
		if($uid > 0) {
			$ucsynlogin = uc_user_synlogin($uid);//生成同步登录的JS代码
			return $ucsynlogin;
		} else{
			return false;
		}
	}

	/**
	 * 重命名用户名
	 * skygreen:新增ucenter底层函数定制:renameuser
	 * @param string $oldusername 原用户名
	 * @param string $newusername 新用户名
	 * @return bool 是否操作成功
	 */
	public static function renameuser($oldusername,$newusername)
	{
		self::init();
		if(!empty($oldusername)&&!empty($newusername)) {
			$ucrenameuser=uc_user_renameuser($newusername,$oldusername);
			return $ucrenameuser;
		}else{
			return false;
		}
	}

	/**
	 * 返回Ucenter用户的登出JS代码
	 * @return string 同步登出JS代码
	 */
	public static function synLogout()
	{
		self::init();
		$ucsynlogout = uc_user_synlogout();
		return $ucsynlogout;
	}

	/**
	 * Ucenter的注册
	 * @param User $user 用户实体数据对象
	 * @return int 用户在Ucenter里的唯一标识
	 */
	public static function synRegister($user)
	{
		self::init();
		$newuid=uc_user_register($user->username, $user->password, $user->email);
		if($newuid <= 0)
		{
			if($newuid == -1) {
				LogMe::log('用户名不合法:Ucenter[register]:user_name_is_not_legitimate');
			} elseif($newuid == -2) {
				LogMe::log('包含不允许注册的词语:Ucenter[register]:include_not_registered_words');
			} elseif($newuid == -3) {
				LogMe::log('用户名已经存在:Ucenter[register]:user_name_already_exists');
			} elseif($newuid == -4) {
				LogMe::log('Email格式有误:Ucenter[register]:email_format_is_wrong');
			} elseif($newuid == -5) {
				LogMe::log('Email不允许注册:Ucenter[register]:email_not_registered');
			} elseif($newuid == -6) {
				LogMe::log('该Email已经被注册:[register]:email_has_been_registered');
			} else {
				LogMe::log('未知的错误:Ucenter[register]:register_error');
			}
		}
		return $newuid;
	}

	/**
	 * 更改用户信息:如密码 email
	 * @param string $username 用户名
	 * @param string $oldpassword 原密码
	 * @param string $newpassword 新密码
	 * @param string $emailnew 新邮箱地址
	 * @return string 回馈信息
	 */
	public static function synEdit($username ,$oldpassword ,$newpassword ,$emailnew)
	{
		self::init();
		$ucresult = uc_user_edit($username ,$oldpassword ,$newpassword ,$emailnew,true);

		if ($ucresult == 1) {
			return 'ok';
		} elseif($ucresult == -1) {
			return '旧密码不正确';
		} elseif($ucresult == -4) {
			return 'Email 格式有误';
		} elseif($ucresult == -5) {
			return 'Email 不允许注册';
		} elseif($ucresult == -6) {
			return '该 Email 已经被注册';
		}else{
			return $ucresult;
		}
	}

	/**
	 * 删除用户
	 * @param string $username 用户名
	 * @return bool 是否操作成功
	 */
	public static function synDelUser($username)
	{
		self::init();
		$data = uc_get_user($username);
		if($data) {
			list($uid, $username, $email) = $data;
		} else {
			return false;
		}

		if (uc_user_delete($uid) == 1) {
			return false;
		}
		return true;
	}
}
?>
