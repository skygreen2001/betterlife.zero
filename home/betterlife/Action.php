<?php
/**
 +----------------------------------------------<br/>
 * 所有控制器的父类<br/>
 * class_alias("Action","Controller");<br/>
 +----------------------------------------------
 * @category betterlife
 * @package core.model
 * @author skygreen
 */
class Action extends ActionBasic 
{
	/**
	 * 在Action所有的方法执行之前可以执行的方法
	 */
	public function beforeAction()
	{
		$this->keywords=Gc::$site_name;
		$this->description=Gc::$site_name;
		if (contain($this->data["go"],Gc::$appName)){
			if(($this->data["go"]!=Gc::$appName.".auth.register")&&($this->data["go"]!=Gc::$appName.".auth.login")&&!HttpSession::isHave('user_id')) {
				$this->redirect("auth","login");
			}
		}
	}
	
	/**
	 * 在Action所有的方法执行之后可以执行的方法 
	 */
	public function afterAction()
	{
		$this->view->set("keywords",$this->keywords);
		$this->view->set("description",$this->description);
	}
}

?>
