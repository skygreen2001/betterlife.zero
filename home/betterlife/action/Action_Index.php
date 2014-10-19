<?php
/**
 +---------------------------------<br/>
 * 控制器:首页<br/>
 +---------------------------------
 * @category betterlife
 * @package  web.front
 * @subpackage auth
 * @author skygreen <skygreen2001@gmail.com>
 */
class Action_Index extends Action
{
	/**
	 * 首页
	 */
	public function index()
	{
		//$this->redirect_url(Gc::$url_base."welcome.php");
		$this->redirect("auth","login");
	}
}
?>
