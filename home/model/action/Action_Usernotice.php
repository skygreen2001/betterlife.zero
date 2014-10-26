<?php
/**
 +---------------------------------------<br/>
 * 控制器:用户收到通知<br/>
 +---------------------------------------
 * @category betterlife
 * @package web.model.action
 * @author skygreen skygreen2001@gmail.com
 */
class Action_Usernotice extends ActionModel
{
	/**
	 * 用户收到通知列表
	 */
	public function lists()
	{
		if ($this->isDataHave(UtilPage::$linkUrl_pageFlag)){
			$nowpage=$this->data[UtilPage::$linkUrl_pageFlag];
		}else{
			$nowpage=1;
		}
		$count=Usernotice::count();
		$bb_page=UtilPage::init($nowpage,$count);
		$this->view->countUsernotices=$count;
		$usernotices = Usernotice::queryPage($bb_page->getStartPoint(),$bb_page->getEndPoint());
		$this->view->set("usernotices",$usernotices);
	}
	/**
	 * 查看用户收到通知
	 */
	public function view()
	{
		$usernoticeId=$this->data["id"];
		$usernotice = Usernotice::get_by_id($usernoticeId);
		$this->view->set("usernotice",$usernotice);
	}
	/**
	 * 编辑用户收到通知
	 */
	public function edit()
	{
		if (!empty($_POST)) {
			$usernotice = $this->model->Usernotice;
			$id= $usernotice->getId();
			$isRedirect=true;
			if (!empty($id)){
				$usernotice->update();
			}else{
				$id=$usernotice->save();
			}
			if ($isRedirect){
				$this->redirect("usernotice","view","id=$id");
				exit;
			}
		}
		$usernoticeId=$this->data["id"];
		$usernotice = Usernotice::get_by_id($usernoticeId);
		$this->view->set("usernotice",$usernotice);
	}
	/**
	 * 删除用户收到通知
	 */
	public function delete()
	{
		$usernoticeId=$this->data["id"];
		$isDelete = Usernotice::deleteByID($usernoticeId);
		$this->redirect("usernotice","lists",$this->data);
	}
}

?>