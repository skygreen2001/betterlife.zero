<?php
/**
 +---------------------------------<br/>
 * 控制器:用户身份验证<br/>
 +---------------------------------
 * @category betterlife
 * @package  web.front
 * @subpackage auth
 * @author skygreen
 */
class Action_Auth extends Action
{
	/**
	 * 退出
	 */
	public function logout()
	{
		HttpSession::remove("user_id");
		if (Gc::$is_ucenter_integration)HttpSession::set("IsSyncLogout",true);
		$this->redirect("auth","login");
	}

	/**
	 * 登录
	 */
	public function login()
	{
		$this->view->set("message","");
		if(HttpSession::isHave('user_id')) {
			$this->redirect("blog","display");
		}else if (!empty($_POST)) {
			$user = $this->model->User;
			$userdata = User::get_one(array("username"=>$user->username,
					"password"=>md5($user->getPassword())));
			if (empty($userdata)) {
				$this->view->set("message","用户名或者密码错误");
			}else {
				$this->uc_login($user);
				HttpSession::set('user_id',$userdata->user_id);
				$this->redirect("blog","display");
			}
		}
		$this->uc_logout();
	}

	/**
	 * 注册
	 */
	public function register()
	{
		if(!empty($_POST)) {
			$user = $this->model->User;
			$userdata=User::get(array("username"=>$user->username));
			if (empty($userdata)) {
				$pass=$user->getPassword();
				$this->uc_register($user);
				$user->setPassword(md5($user->getPassword()));
				$user->loginTimes=0;
				$user->save();
				HttpSession::set('user_id',$user->id);
				$this->redirect("blog","display");
			}else{
				$this->view->color="red";
				$this->view->set("message","该用户名已有用户注册！");
			}
		}
	}

	/**
	 * Ucenter的注册
	 */
	private function uc_register($user)
	{
		$uid=0;
		if (Gc::$is_ucenter_integration){
			$uid=UtilUcenter::synRegister($user);
			if ($uid>0){
				$user_exists=User::get_by_id($newuid);
				if (!$user_exists)$user->setId($newuid);
				HttpSession::set("IsSyncUcenterOtherApp",true);
				HttpSession::set('uid',$uid);
			}
		}
		return $uid;
	}

	/**
	 * Ucenter的登录
	 */
	private function uc_login($user)
	{
		if (Gc::$is_ucenter_integration){
			$uid=UtilUcenter::login($user->username, $user->password);
			if ($uid>0){
				HttpSession::set("IsSyncUcenterOtherApp",true);
				HttpSession::set('uid',$uid);
			}
			return $uid;
		}
		return null;
	}

	/**
	 * Ucenter的登出
	 */
	private function uc_logout()
	{
		if (Gc::$is_ucenter_integration){
			if (HttpSession::isHave("IsSyncLogout")){
				if(empty($this->view->viewObject))$this->view->viewObject=new ViewObject();
				$syncLogoutJs = UtilUcenter::synLogout();
				UtilJavascript::loadJsContentReady($this->view->viewObject,$syncLogoutJs);
			}
			HttpSession::remove("IsSyncLogout");
		}
	}
}
?>