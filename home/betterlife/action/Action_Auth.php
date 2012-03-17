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
class Action_Auth extends Action {
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
				$user->save();
				HttpSession::set('user_id',$user->id);  
				$this->redirect("blog","display");                                                               
			}else{
			   $this->view->color="red";
			   $this->view->set("message","该用户名已有用户注册！");  
			}
		}
	}

}
?>