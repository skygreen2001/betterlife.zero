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
    public function logout() {
      HttpSession::remove("userid");
      $this->redirect("auth","login");
    }

    /**
    * 登录
    */
    public function login() {
        $this->loadCss();
        $this->view->set("message","");
        if(HttpSession::isHave('userid')) {
            $this->redirect("blog","display");
        }else if (!empty($_POST)) {    
            $user = $this->model->User;
            $userdata = User::get(array("name"=>$user->getName(),  
                    "password"=>md5($user->getPassword())));
            if (empty($userdata)) {
                $this->view->set("message","用户名或者密码错误");
            }else {
                HttpSession::set('userid',$userdata[0]->id);
                $this->redirect("blog","display");
            }
        }
    }

    /**
    * 注册
    */
    public function register() {
        $this->loadCss();
        if(!empty($_POST)) {
            $user = $this->model->User;
            $userdata=User::get(array("name"=>$user->getName()));
            if (empty($userdata)) {
                $pass=$user->getPassword();
                $user->setPassword(md5($user->getPassword()));
                $user->save();
                HttpSession::set('userid',$user->id);  
                $this->redirect("blog","display");                                                               
            }else{
               $this->view->color="green";
               $this->view->set("message","该用户名已有用户注册！");  
            }
        }
    }

}
?>