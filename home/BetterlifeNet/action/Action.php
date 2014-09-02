<?php
/**
 +----------------------------------------------<br/>
 * 所有控制器的父类<br/>
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
		parent::beforeAction();
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
		parent::afterAction();
	}
}

?>
