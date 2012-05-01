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
	  $this->redirect("auth","login");
	}

	/**
	* 登录
	*/
	public function login() {
		$this->view->set("message","");
		if(HttpSession::isHave('user_id')) {
			$this->redirect("blog","display");
		}else if (!empty($_POST)) {    
			$user = $this->model->User;
			$userdata = User::get(array("username"=>$user->username,  
					"password"=>md5($user->getPassword())));
			if (empty($userdata)) {
				$this->view->set("message","用户名或者密码错误");
			}else {
				HttpSession::set('user_id',$userdata[0]->user_id);
				$this->redirect("blog","display");
			}
		}
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
				$user->setPassword(md5($user->getPassword()));
				$this->uc_register($user);
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
		if (Gc::$is_ucenter_integration){
			$uc_client_path= Gc::$nav_root_path."data".DIRECTORY_SEPARATOR.'uc_client'.DIRECTORY_SEPARATOR.'client.php';     
			include_once Gc::$nav_root_path.'api'.DIRECTORY_SEPARATOR.'config.inc.php';
			include_once($uc_client_path);
			$newuid=uc_user_register($user->username, $user->password, $user->email);
			if($newuid <= 0) {
				if($newuid == -1) {
					LogMe::log('Ucenter[register]:user_name_is_not_legitimate');
				} elseif($newuid == -2) {
					LogMe::log('Ucenter[register]:include_not_registered_words');
				} elseif($newuid == -3) {
					LogMe::log('Ucenter[register]:user_name_already_exists');
				} elseif($newuid == -4) {
					LogMe::log('Ucenter[register]:email_format_is_wrong');
				} elseif($newuid == -5) {
					LogMe::log('Ucenter[register]:email_not_registered');
				} elseif($newuid == -6) {
					LogMe::log('Ucenter[register]:email_has_been_registered');
				} else {
					LogMe::log('Ucenter[register]:register_error');
				}
			}else{
				$user_exists=User::get_by_id($newuid);
				if (!$user_exists){
					$user->setId($newuid);
				}
			}
		}
	}

}
?>