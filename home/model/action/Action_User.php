<?php
/**
 +---------------------------------------<br/>
 * 控制器:用户<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_User extends ActionModel
{
	/**
	 * 用户列表
	 */
	public function lists()
	{
		if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
			$nowpage=$this->data[UtilPage::$linkUrl_pageFlag];
		}else{
			$nowpage=1;
		}
		$count=User::count();
		$bb_page=UtilPage::init($nowpage,$count);
		$this->view->countUsers=$count;
		$users = User::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
		$this->view->set("users",$users);
	}
	/**
	 * 查看用户
	 */
	public function view()
	{
		$userId=$this->data["id"];
		$user = User::get_by_id($userId);
		$this->view->set("user",$user);
	}
	/**
	 * 编辑用户
	 */
	public function edit()
	{
		if (!empty($_POST)) {
			$user = $this->model->User;
			$id= $user->getId();
			$isRedirect=true;
			if (!empty($id)){
				$user->update();
			}else{
				$id=$user->save();
			}
			if ($isRedirect){
				$this->redirect("user","view","id=$id");
				exit;
			}
		}
		$userId=$this->data["id"];
		$user = User::get_by_id($userId);
		$this->view->set("user",$user);
	}
	/**
	 * 删除用户
	 */
	public function delete()
	{
		$userId=$this->data["id"];
		$isDelete = User::deleteByID($userId);
		$this->redirect("user","lists",$this->data);
	}
}

?>